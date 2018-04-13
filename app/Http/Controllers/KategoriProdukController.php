<?php

namespace App\Http\Controllers;

use App\KategoriProduk;
use App\Produk;
use Auth;
use Illuminate\Http\Request;

class KategoriProdukController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'nama_kategori_produk'   => 'required|unique:kategori_produks,nama_kategori_produk,NULL,id,toko_id,' . Auth::user()->toko_id,
            'urutan_kategori_produk' => 'required|unique:kategori_produks,urutan_kategori_produk,NULL,id,toko_id,' . Auth::user()->toko_id,
        ]);
        KategoriProduk::create([
            'nama_kategori_produk'   => $request->nama_kategori_produk,
            'urutan_kategori_produk' => $request->urutan_kategori_produk,
            'toko_id'                => Auth::user()->toko_id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return KategoriProduk::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_kategori_produk'   => 'required|unique:kategori_produks,nama_kategori_produk,' . $id . ',id,toko_id,' . Auth::user()->toko_id,
            'urutan_kategori_produk' => 'required|unique:kategori_produks,urutan_kategori_produk,' . $id . ',id,toko_id,' . Auth::user()->toko_id,
        ]);
        KategoriProduk::find($id)->update([
            'nama_kategori_produk'   => $request->nama_kategori_produk,
            'urutan_kategori_produk' => $request->urutan_kategori_produk,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = Produk::select('kategori_produks_id')->get();

        foreach ($produk as $kategori_produks_id) {
            if ($kategori_produks_id->kategori_produks_id == $id) {
                return response()->json(['error' => 1]);
            }

        }
        $hapus = KategoriProduk::destroy($id);
        return $hapus;
    }
    public function view()
    {
        return KategoriProduk::where('toko_id', Auth::user()->toko_id)->orderBy('urutan_kategori_produk', 'asc')->paginate(10);
    }

    public function search(Request $request)
    {
        $nama_kategori_produk = KategoriProduk::where('nama_kategori_produk', 'LIKE', "%$request->pencarian%")
            ->orWhere('urutan_kategori_produk', 'LIKE', "%$request->pencarian%")
            ->paginate(10);
        return response()->json($nama_kategori_produk);
    }
}
