<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\RouterOs;
use App\MyMethod\RouterosAPI;

class RouterosController extends Controller
{
    public $API=[], $routeros_data=[], $connection;

    public function test_api()
    {
        try{
            return response()->json([
                'success' => true,
                'message' => 'Welcome in my routeros API',
            ]);
        }catch(Exception $e){
            return response()->json([
                'error' => true,
                'message' => 'Error fetch data to routeros API'
            ], 404);
        }
    }

    public function store_routeros($data)
    {
        $API = new RouterosAPI;
        $connection = $API->connect($data['ip_address'], $data['login'], $data['password']);

        if(!$connection) return response()->json(['error' => true, 'message' => 'Routeros not connected !!']);

        $store_data = [
            'identity' => $API->comm('/system/identity/print')[0]['name'],
            'ip_address' => $data['ip_address'],
            'login' => $data['login'],
            'password' => $data['password'],
            'connect' => $connection
        ];

        $store_routeros = new RouterOs;
        $store_routeros->identity = $store_data['identity'];
        $store_routeros->ip_address = $store_data['ip_address'];
        $store_routeros->login = $store_data['login'];
        $store_routeros->password = $store_data['password'];
        $store_routeros->connect = $store_data['connect'];
        $store_routeros->save();

        return response()->json([
            'success' => true,
            'message' => 'Routeros data has been save to database',
            'store' => $store_routeros
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

            if(count($routeros_db) > 0):
                $API = new RouterosAPI;
                $connect = $API->connect($routeros_db[0]['ip_address'], $routeros_db[0]['login'], $routeros_db[0]['password']);

                if($connect):
                    $routerosdb_data = [
                        'identity' => $API->comm('/system/identity/print')[0]['name'],
                        'ip_address' => $routeros_db[0]['ip_address'],
                        'login' => $routeros_db[0]['login'],
                        'connect' => $routeros_db[0]['connect']
                    ];
                    return response()->json([
                        'success' => true,
                        'message' => 'Routeros has been connected !',
                        'data' => $routerosdb_data
                    ]);
                else:
                    return response()->json(['connect' => false, 'message' => 'Routeros is not connected'], 404);
                endif;
            else:
                return $this->store_routeros($request->all());
            endif;

        }catch(Exception $e){
           return response()->json([
            'error' => true,
            'message' => 'Error fetch data to routeros API'
        ], 404);
       }
   }

   public function check_routeros_connection($data)
   {
        $routeros_db = RouterOs::where('ip_address', $data['ip_address'])->get();
        if(count($routeros_db) > 0):
            $API = new RouterosAPI;
            $connection = $API->connect($routeros_db[0]['ip_address'], $routeros_db[0]['login'], $routeros_db[0]['password']);
            $this->API = $API;
            $this->connection = $connection;
            $this->routeros_data = $routeros_db;
            return true;
        else:
            return false;
        endif;
   }

   public function add_new_address(Request $request)
   {
        $validator = Validator::make($request->all(), [
            'ip_address' => 'required',
            'address' => 'required',
            'interface' => 'required'
        ]);

        if($validator->fails()) return response()->json($validator->errors(), 404);

        if($this->check_routeros_connection($request->all())):

            $add_address = $this->API->comm('/ip/address/add', [
                'address' => $request->address,
                'interface' => $request->interface
            ]);
            

            $list_address = $this->API->comm('/ip/address/print');

            if(array_search($add_address, array_column($list_address, '.id'))):
            
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully added new address from interface : '.$request->interface,
                    'address_list' => $list_address
                ]);
            else:
                return response()->json([
                    'success' => false,
                    'message' => $add_address["!trap"][0]["message"],
                    'data' => $this->API->comm('/ip/address/print')
                ], 404);
            endif;
        else:
            return response()->json([
                'empty' => true,
                'message' => 'Routeros data is not available on a database, please restore routeros data to database.'
            ]);
        endif;
   }

   public function add_dns_servers(Request $request)
   {
        
   }
}
