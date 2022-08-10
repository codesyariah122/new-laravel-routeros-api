<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use libphonenumber\PhoneNumberType;
use Propaganistas\LaravelPhone\Rules\Phone as Rule;
use Propaganistas\LaravelPhone\Exceptions\InvalidParameterException;
use App\Events\NotificationEvent;
use App\Models\ContactMessage;
use App\Models\Newsletter;

class TokoKelontongController extends Controller
{
    public function test_api()
    {
        try{
            return response()->json([
                'message' => 'Fetch api toko kelontong'
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => "Error fetch api : $e->getMessage()"
            ]);
        }
    }

    public function contact_message(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => ['field' => 'phone:mobile,ID'],
                'email' => 'required|email|max:255',
                'message' => 'required'
            ]);

            if($validator->fails()) return response()->json($validator->errors(), 404);

            $contact_message = new ContactMessage;
            $contact_message->name = $request->name;
            $contact_message->phone = $request->phone;
            $contact_message->email = $request->email;
            $contact_message->message = $request->message;
            $contact_message->save();

            $data_event = [
                'notif' => "$request->name mengirim pesan",
                'data' => $contact_message
            ];

            $event = broadcast(new NotificationEvent($data_event));

            return response()->json([
                'message' => "Hai, $request->name. Pesan anda telah di proses system kami.",
                'data' => $contact_message
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'message' => "Error fetch contact message : $e->getMessage()"
            ], 401);
        }
    }

    public function newsletter(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255'
            ]);

            if($validator->fails()) return response()->json($validator->errors(), 404);

            $news_letter = new Newsletter;
            $news_letter->email = $request->email;
            $news_letter->save();

            $data_event = [
                'notif' => "$request->email baru saja berlangganan",
                'data' => $news_letter
            ];

            $event = broadcast(new NotificationEvent($data_event));

            return response()->json([
                'message' => "Terima kasih, $request->email, telah berlangganan.",
                'data' => $news_letter
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'message' => "Error fetch contact message : $e->getMessage()"
            ], 401);
        }
    }
}
