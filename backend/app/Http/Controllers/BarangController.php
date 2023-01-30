<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use DateTime;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response as FacadesResponse;

use function PHPUnit\Framework\isNull;

class BarangController extends Controller
{
    public function get() 
    {
        $users = Barang::all();
        return response()->json($users, 200);
    }

    public function getById($id){
        $data = Barang::find($id);
        return response()->json($data, 200);
    }

    public function save(Request $request, $id = null) 
    {
        $isFileUploaded = false;
        $dataExist = Barang::find($id);
        if(!isNull($request["gambar"])) {
            if(!is_string($request["gambar"])) {
                $fileName = $request->gambar->hashName();
                $filePath = $fileName;
                
                $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->gambar));
                if(!empty($id)) Storage::delete('public/'.$dataExist->gambar);
            }
        } else {
            $isFileUploaded = true;
        }
        
        if ($isFileUploaded) {
            $data['nama'] = $request['nama'];
            $data['deskripsi'] = $request['deskripsi'];
            $data['jenis'] = $request['jenis'];
            $data['stok'] = $request['stok'];
            $data['harga_beli'] = $request['harga_beli'];
            $data['harga_jual'] = $request['harga_jual'];
            $data['gambar'] = $filePath ?? $dataExist->gambar ?? "";
            $data['created_by'] = $request['created_by'];
            
            $res = false;
            if(!empty($id)) {
                $res = $dataExist->update($data);
            } else {
                $res = Barang::create($data);
            }

            if($res) {
                return response()->json([
                    'message' => "Success",
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'message' => "Failed",
                    'success' => true
                ], 500);
            }
        }
    }

    public function delete($id){
        $data = Barang::find($id);
        $isFileDelete = Storage::disk('public')->delete($data->gambar);
        if($isFileDelete) {
            $res = Barang::find($id)->delete();
            if($res) {
                return response()->json([
                    'message' => "Successfully deleted",
                    'success' => true
                ], 200);
            }
        }
        return response()->json([
            'message' => "Failed deleted",
            'success' => true
        ], 500);
    }
}
