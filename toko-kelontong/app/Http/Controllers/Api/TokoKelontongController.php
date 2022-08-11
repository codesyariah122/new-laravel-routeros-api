<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use libphonenumber\PhoneNumberType;
use Propaganistas\LaravelPhone\Rules\Phone as Rule;
use Propaganistas\LaravelPhone\Exceptions\InvalidParameterException;
use App\Events\NotificationEvent;
use App\Models\ContactMessage;
use App\Models\Newsletter;
use App\Models\Visitor;

class TokoKelontongController extends Controller
{
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
                'message' => "Error fetch contact message : {$e->getMessage()}"
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
                'message' => "Error fetch contact message : {$e->getMessage()}"
            ], 401);
        }
    }

    public function location()
    {
        try{
            $ip_find_key = env('API_KEY_IP_FIND');
            $big_data_key = env('API_KEY_BIG_DATA');

            $ip = Http::get('https://api.ipify.org/?format=json');
            // $location = Http::get("https://ipapi.co/{$ip->json()['ip']}/json");
            $location = Http::get("https://api.ipfind.com?ip={$ip->json()['ip']}&auth={$ip_find_key}");

            $locator = Http::get("https://api.bigdatacloud.net/data/ip-geolocation?ip={$location->json()['ip_address']}&localityLanguage={$location->json()['languages'][0]}&key={$big_data_key}");
            
            // var_dump($locator->json()['location']['timeZone']['localTime']); die;

            $visitor_data = [
                'ip_address' => $location->json()['ip_address'],
                'longitude' => $location->json()['longitude'],
                'latitude' => $location->json()['latitude'],
                'country_emoji' => $locator->json()['country']['countryFlagEmoji'],
                'city' => $locator->json()['location']['city'],
                'province' => $locator->json()['location']['principalSubdivision'],
                'locality_name' => $locator->json()['location']['localityName'],
                'local_time' => $locator->json()['location']['timeZone']['localTime'],
                'providers' => $locator->json()['network']['carriers'][0]['organisation']
            ];

            return $this->save_visitor($visitor_data);

        }catch(Exception $e){
            return response()->json([
                'message' => "Error fetch contact message : {$e->getMessage()}"
            ], 401);
        }
    }

    public function save_visitor($data)
    {
        $save_visitor = new Visitor;
        $save_visitor->ip_address = $data['ip_address'];
        $save_visitor->longitude = $data['longitude'];
        $save_visitor->latitude = $data['latitude'];
        $save_visitor->country_emoji = $data['country_emoji'];
        $save_visitor->city = $data['city'];
        $save_visitor->province = $data['province'];
        $save_visitor->locality_name = $data['locality_name'];
        $save_visitor->local_time = $data['local_time'];
        $save_visitor->providers = $data['providers'];

        $save_visitor->save();

        return response()->json([
            'message' => "Fetch location",
            'locator' => $save_visitor
        ], 401);
    }
}
