<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function get() 
    {
        $users = Pembelian::select("pembelian.id", "pembelian.id_barang", "pembelian.total", "barang.nama AS nama_barang", "pembelian.is_validate")
                            ->join('barang', 'pembelian.id_barang', '=', 'barang.id')
                            ->get();
        return response()->json($users, 200);
    }

    public function getById($id){
        $data = Pembelian::find($id);
        return response()->json($data, 200);
    }

    public function save(Request $request, $id = null) 
    {
        $dataExist = Pembelian::find($id);

        $data['id_barang'] = $request['barang'];
        $data['total'] = $request['total'];
        $data['is_validate'] = $request['is_validasi'] ?? 0;
        $data['created_by'] = $request['created_by'];
        
        $res = false;
        if(!empty($id)) {
            $res = $dataExist->update($id, $data);
        } else {
            $res = Pembelian::create($data);
        }

        if($res) {
            if($request->role == "Staff") {
                $dataBarang = Barang::find($request['id_barang']);
                $dataBarang->stok = $dataBarang->stok - 1;
                $dataBarang->save();
                if($dataBarang) {
                    return response()->json([
                        'message' => "Success",
                        'success' => true
                    ], 200);
                }
            } else {
                return response()->json([
                    'message' => "Success",
                    'success' => true
                ], 200);
            }
        }

        return response()->json([
            'message' => "Failed",
            'success' => true
        ], 500);
    }

    public function validasi($id) 
    {
        $dataExist = Pembelian::find($id);

        $data['is_validate'] = 1;
        
        $res = $dataExist->update($data);

        if($res) {
            // if($request->role == "Staff") {
                $dataBarang = Barang::find($dataExist['id_barang']);
                $dataBarang->stok = $dataBarang->stok - $dataExist["total"];
                $dataBarang->save();
                if($dataBarang) {
                    return response()->json([
                        'message' => "Success",
                        'success' => true,
                        'status' => 200
                    ], 200);
                }
            // } else {
            //     return response()->json([
            //         'message' => "Success",
            //         'success' => true
            //     ], 200);
            // }
        }

        return response()->json([
            'message' => "Failed",
            'success' => true
        ], 500);
    }

    public function delete($id){
        $res = Pembelian::find($id)->delete();
        if($res) {
            return response()->json([
                'message' => "Successfully deleted",
                'success' => true
            ], 200);
        }
        
        return response()->json([
            'message' => "Failed deleted",
            'success' => true
        ], 500);
    }
}
