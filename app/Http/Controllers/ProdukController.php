<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $produks = Produk::join('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
            ->orderByRaw('CAST(kode_produk AS UNSIGNED) ASC') // urut sesuai angka kode
            ->select('produks.*', 'nama_kategori')
            ->when($search, function ($q, $search) {
                return $q->where('kode_produk', 'like', "%{$search}%")
                    ->orWhere('nama_produk', 'like', "%{$search}%");
            })
            ->paginate();

        if ($search) {
            $produks->appends(['search' => $search]);
        }

        return view('produk.index', [
            'produks' => $produks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dataKategori = Kategori::orderBy('nama_kategori')->get();

        $kategoris = [
            ['', 'Pilih Kategori:']
        ];

        foreach ($dataKategori as $kategori) {
            $kategoris[] = [$kategori->id, $kategori->nama_kategori];
        }

        return view('produk.create', [
            'kategoris' => $kategoris
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => ['nullable', 'max:250', 'unique:produks'],
            'nama_produk' => ['required', 'max:150'],
            'harga' => ['required', 'numeric', 'min:0'], // Harga beli/modal
            'harga_jual' => ['required', 'numeric', 'min:0'], // Harga jual
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'diskon' => ['required', 'between:0,100'],
        ]);

         // Hitung harga final setelah diskon dari harga jual
    $harga_final = $request->harga_jual - ($request->harga_jual * $request->diskon / 100);

    // Simpan data ke database
    Produk::create([
        'kode_produk' => $request->kode_produk,
        'nama_produk' => $request->nama_produk,
        'harga_produk' => $request->harga, // Harga modal/beli
        'harga_jual' => $request->harga_jual, // Harga jual sebelum diskon
        'harga' => $harga_final, // Harga final setelah diskon (untuk transaksi)
        'diskon' => $request->diskon,
        'kategori_id' => $request->kategori_id,
    ]);

        // kalau kode_produk tidak diisi, buat otomatis
        if (!$request->filled('kode_produk')) {
            $request->merge([
                'kode_produk' => $this->generateKodeUrut()
            ]);
        }

        return redirect()->route('produk.index')->with('store', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        // tidak digunakan, langsung abort
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        $dataKategori = Kategori::orderBy('nama_kategori')->get();

        $kategoris = [
            ['', 'Pilih Kategori:']
        ];

        foreach ($dataKategori as $kategori) {
            $kategoris[] = [$kategori->id, $kategori->nama_kategori];
        }

        return view('produk.edit', [
            'produk' => $produk,
            'kategoris' => $kategoris,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kode_produk' => ['required', 'max:250', 'unique:produks,kode_produk,' . $produk->id],
            'nama_produk' => ['required', 'max:150'],
            'harga' => ['required', 'numeric'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'diskon' => ['required', 'between:0,100'],
        ]);

       // Hitung harga final setelah diskon dari harga jual
        $harga_final = $request->harga_jual - ($request->harga_jual * $request->diskon / 100);

        // Update produk
        $produk->update([
            'kode_produk' => $request->kode_produk,
            'nama_produk' => $request->nama_produk,
            'harga_produk' => $request->harga, // Harga modal/beli
            'harga_jual' => $request->harga_jual, // Harga jual sebelum diskon
            'harga' => $harga_final, // Harga final setelah diskon (untuk transaksi)
            'diskon' => $request->diskon,
            'kategori_id' => $request->kategori_id,
        ]);


        return redirect()->route('produk.index')->with('update', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();
        return back()->with('destroy', 'success');
    }

    /**
     * Generate kode produk urut tanpa loncat
     * - Mulai dari 1001
     * - Isi nomor yang kosong dulu kalau ada
     */
    private function generateKodeUrut()
    {
        $allCodes = Produk::orderByRaw('CAST(kode_produk AS UNSIGNED) ASC')->pluck('kode_produk')->toArray();
        $next = 1001;

        foreach ($allCodes as $code) {
            if ((int)$code != $next) {
                // kalau ada bolong, pakai yang bolong
                return str_pad($next, 4, '0', STR_PAD_LEFT);
            }
            $next++;
        }

        // kalau tidak ada bolong, lanjut nomor berikutnya
        return str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
