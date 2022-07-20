<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\RouterOs;
use App\MyHelper\RouterosAPI;

class RouterosController extends Controller
{
    public $API=[], $routeros_data=[], $connection;

    public function test_api()
    {
        try{
            return response()->json([
                'success' => true,
                'message' => 'Welcome in Routeros API'
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error fetch data Routeros API'
            ]);
        }
    }

    public function store_routeros($data)
    {
        $API = new RouterosAPI;
        $connection = $API->connect($data['ip_address'], $data['login'], $data['password']);

        if(!$connection) return response()->json(['error' => true, 'message' => 'Routeros not connected ...'], 404);

        $store_routeros_data = [
            'identity' => $API->comm('/system/identity/print')[0]['name'],
            'ip_address' => $data['ip_address'],
            'login' => $data['login'],
            'password' => $data['password'],
            'connect' => $connection
        ];

        $store_routeros = new RouterOs;
        $store_routeros->identity = $store_routeros_data['identity'];
        $store_routeros->ip_address = $store_routeros_data['ip_address'];
        $store_routeros->login = $store_routeros_data['login'];
        $store_routeros->password = $store_routeros_data['password'];
        $store_routeros->connect = $store_routeros_data['connect'];
        $store_routeros->save();

        return response()->json([
            'success' => true,
            'message' => 'Routeros data has been saved to database laravel',
            'routeros_data' => $store_routeros
        ]);

    }

    public function routeros_connection(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'ip_address' => 'required',
                'login' => 'required',
                'password' => 'required'
            ]);

            if($validator->fails()) return response()->json($validator->errors(), 404);

            $req_data = [
                'ip_address' => $request->ip_address,
                'login' => $request->login,
                'password' => $request->password
            ];

            $routeros_db = RouterOs::where('ip_address', $req_data['ip_address'])->get();

            if(count($routeros_db) > 0){
                if($this->check_routeros_connection($request->all())):
                    return response()->json([
                        'connect' => true,
                        'message' => 'Routeros have a connection from database',
                        'routeros_data' => $this->routeros_data
                    ]);

                else:
                    return response()->json([
                        'error' => true,
                        'message' => 'Routeros not connected, check administrator login !'
                    ]);
                endif;
            }else{
                return $this->store_routeros($request->all());
            }

        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error fetch data Routeros API'
            ]);
        }
    }

    public function check_routeros_connection($data)
    {
        $routeros_db = RouterOs::where('ip_address', $data['ip_address'])->get();

        if(count($routeros_db) > 0):
            $API = new RouterosAPI;
            $connection = $API->connect($routeros_db[0]['ip_address'], $routeros_db[0]['login'], $routeros_db[0]['password']);
            if(!$connection) return false;

            $this->API = $API;
            $this->connection = $connection;
            $this->routeros_data = [
                'identity' => $this->API->comm('/system/identity/print')[0]['name'],
                'ip_address' => $routeros_db[0]['ip_address'],
                'login' => $routeros_db[0]['login'],
                'connect' => $connection
            ];
            return true;

        else:
            echo "Routeros not available at database, please create routeros connection again";
        endif;

    }

    public function set_interface(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'ip_address' => 'required',
                'numbers' => 'required',
                'interface' => 'required',
                'name' => 'required'
            ]);
            if($validator->fails()) return response()->json($validator->errors(), 404);

            if($this->check_routeros_connection($request->all())):
                $interface_list = $this->API->comm('/interface/print');

                $find_interface = array_search($request->name, array_column($interface_list, 'name'));

                if(!$find_interface):
                    $set_interface = $this->API->comm('/interface/set', [
                        '.id' => '*'.$request->numbers,
                        'name' => $request->name
                    ]);
                    return response()->json([
                        'success' => true,
                        'message' => 'Successfully set interface from : '.$request->interface.' to : '.$request->name,
                        'interface_list' => $this->API->comm('/interface/print')
                    ]);
                else:
                    return response()->json([
                        'success' => false,
                        'message' => "Interface name : $request->name with .id : *$request->numbers has already been taken from routeros.",
                        'interface_list' => $this->API->comm('/interface/print')
                    ]);
                endif;
            endif;

        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error fetch data Routeros API'
            ]);
        }
    }
}
