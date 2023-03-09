<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::all();

        if(count($produks) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $produks
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama' => 'required',
            'stok' => 'required',
            'jenis' => 'required',
            'harga' => 'required',
            'gambar' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $uploadFolder = 'produks';
        $gambar = $request->file('gambar');
        $image_uploaded_path = $gambar->store($uploadFolder, 'public');

        $storeData['gambar'] = basename($image_uploaded_path);
            
        $produk = Produk::create($storeData);
        return response([
            'message' => 'Add produk Success',
            'data' => $produk
        ], 200);
    }

    public function show($id)
    {
        $produk = Produk::find($id);

        if(!is_null($produk)) {
            return response([
                'message' => 'Retrieve produk Success',
                'data' => $produk
            ], 200);
        }else

        return response([
            'message' => 'Produk Not Found',
            'data' => null
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);
        if(is_null($produk)) {
            return response([
                'message' => 'Produk Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'stok' => 'required',
            'jenis' => 'required',
            'harga' => 'required',
            'gambar' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);


        $uploadFolder = 'produks';
        $gambar = $request->file('gambar');
        $image_uploaded_path = $gambar->store($uploadFolder, 'public');

        $produk->nama = $updateData['nama'];
        $produk->stok = $updateData['stok'];
        $produk->jenis = $updateData['jenis'];
        $produk->harga = $updateData['harga'];
        $produk->gambar = basename($image_uploaded_path);

        if($produk->save()) {
                return response([
                'message' => 'Update produk Success',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'Update Produk Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $produk = Produk::find($id);

        if(is_null($produk)) {
            return response([
                'message' => 'Produk Not Found',
                'data' => null
            ], 404);
        }

        if($produk->delete()) {
            return response([
                'message' => 'Delete Produk Success',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'Delete Produk Failed',
            'data' => null
        ], 400);
    }
}