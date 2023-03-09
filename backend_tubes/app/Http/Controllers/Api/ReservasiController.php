<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservasi;
use Validator;

class ReservasiController extends Controller
{
    public function index(){
 
        $reservasi = Reservasi::with('layanans')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Reservasi',
            'data' => $reservasi
        ], 200);

    }

    public function show($id){
   
        $reservasi = Reservasi::with('layanans')->where('id', $id)->get();
       

        if($reservasi){
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Reservasi',
                'data' => $reservasi
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Reservasi Tidak Ditemukan',
                'data' => ''
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        
        $validate = Validator::make($storeData, [
            'tanggal' => 'required',
            'sesi' => 'required',
            'id_layanan' => 'required',
            'nama_customer' => 'required',
            
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
    

        $reservasi = Reservasi::create($storeData);

        return response()->json([
            'message' => 'Successfully created reservasi!'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $reservasi = Reservasi::find($id);
        if(is_null($reservasi)){
            return response()->json([
                'message' => 'Reservasi Not Found'
            ], 404);
        }

        $validate = Validator::make($request->all(), [
            'tanggal' => 'required',
            'sesi' => 'required',
            'id_layanan' => 'required',
            'nama_customer' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $reservasi->tanggal = $request->tanggal;
        $reservasi->sesi = $request->sesi;
        $reservasi->id_layanan = $request->id_layanan;
        $reservasi->nama_customer = $request->nama_customer;


        if($reservasi->save()){
            return response([
                'message' => 'Update reservasi Success',
                'data' => $reservasi
            ], 200);
        }

        return response([
            'message' => 'Update Reservasi Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $reservasi = Reservasi::find($id);
        if(is_null($reservasi)){
            return response()->json([
                'message' => 'Reservasi Not Found'
            ], 404);
        }

        $reservasi->delete();
        return response([
            'message' => 'Delete Reservasi Success',
            'data' => $reservasi
        ], 200);
    }

    // public function changeStatus($id)
    // {
    //     $reservasi = Reservasi::find($id);

    //     $reservasi->status = 1;
    //     $reservasi->save();

    //     return response([
    //         'message' => 'Change Status Reservasi Success',
    //         'data' => $reservasi
    //     ], 200);
    // }



}