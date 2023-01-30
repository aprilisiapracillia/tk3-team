<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    protected $validation = [
        'nama' => 'required',
        'id_role' => 'required',
        'tgl_lahir' => 'nullable|date',
        'username' => 'required',
        'password' => 'required',
    ];

    public function get() 
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function getById($id){
        $data = User::find($id);
        return response()->json($data, 200);
    }

    public function save(Request $request, $id = null) 
    {
        $isFileUploaded = false;
        $dataExist = User::find($id);
        if(!isNull($request["file_ktp"])) {
            if(!is_string($request["file_ktp"])) {
                $fileName = $request->file_ktp->hashName();
                $filePath = $fileName;
                
                $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->file_ktp));
                if(!empty($id)) Storage::delete('public/'.$dataExist->file_ktp);
            }
        } else {
            $isFileUploaded = true;
        }

        if ($isFileUploaded) {
            $data['nama'] = $request['nama'];
            $data['role'] = $request['role'];
            $data['jenis_kelamin'] = $request['jenis_kelamin'];
            $data['alamat'] = $request['alamat'];
            $data['file_ktp'] = $filePath ?? $dataExist->file_ktp ?? "";
            $data['tempat_lahir'] = $request['tempat_lahir'];
            $data['tgl_lahir'] = $request['tgl_lahir'];
            $data['username'] = $request['username'];
            $data['password'] = Hash::make($request['password']);
            $data['created_at'] = new DateTime();

            $res = false;
            if(!empty($id)) {
                $res = $dataExist->update($data);
            } else {
                $res = User::create($data);
            }

            if($res) {
                return response()->json([
                    'message' => "Successfully created",
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'message' => "Failed created",
                    'success' => true
                ], 500);
            }
        }
    }

    public function delete($id){
        $res = User::find($id)->delete();
        if($res) {
            return response()->json([
                'message' => "Successfully deleted",
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'message' => "Failed deleted",
                'success' => true
            ], 500);
        }
    }

}
