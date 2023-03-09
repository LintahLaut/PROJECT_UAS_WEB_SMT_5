<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Layanan;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::all();

        if(count($layanans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $layanans
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
            'harga' => 'required',
            'deskripsi' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
            
        $layanan = Layanan::create($storeData);
        return response([
            'message' => 'Add layanan Success',
            'data' => $layanan
        ], 200);
    }

    public function show($id)
    {
        $layanan = Layanan::find($id);

        if(!is_null($layanan)) {
            return response([
                'message' => 'Retrieve layanan Success',
                'data' => $layanan
            ], 200);
        }

        return response([
            'message' => 'Layanan Not Found',
            'data' => null
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $layanan = Layanan::find($id);
        if(is_null($layanan)) {
            return response([
                'message' => 'Layanan Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $layanan->nama = $updateData['nama'];
        $layanan->harga = $updateData['harga'];
        $layanan->deskripsi = $updateData['deskripsi'];

        if($layanan->save()) {
                return response([
                'message' => 'Update layanan Success',
                'data' => $layanan
            ], 200);
        }

        return response([
            'message' => 'Update Layanan Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $layanan = Layanan::find($id);

        if(is_null($layanan)) {
            return response([
                'message' => 'Layanan Not Found',
                'data' => null
            ], 404);
        }

        if($layanan->delete()) {
            return response([
                'message' => 'Delete Layanan Success',
                'data' => $layanan
            ], 200);
        }

        return response([
            'message' => 'Delete Layanan Failed',
            'data' => null
        ], 400);
    }
}