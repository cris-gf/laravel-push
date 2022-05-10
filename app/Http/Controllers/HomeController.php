<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    public $serverKey = null;
    public $fcmURL = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->serverKey = env('SERVER_KEY', '');
        $this->fcmURL    = env('FCM_URL', '');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    //Actualizar el token
    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token' => $request->token]);

        return response()->json(['El token se ha actualizado correctamente.']);
    }

    //Envio de notificacion
    public function sendNotification(Request $request)
    {
        $tokens = User::whereNotNull('device_token')->pluck('device_token')->all();

        $data = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $request->title,
                "body"  => $request->body,  
            ]
        ];

        $headers = [
            'Authorization: key='.$this->serverKey,
            'Content-Type: application/json',
        ];

        $response = $this->cURL($headers, $data);
        $message = json_decode($response)->success ? 'Notificación enviada correctamente.' : 'Ocurrió un error al enviar la notificación.';

        return back()->with('status', $message);
    }

    //Consumo de FCM enviar Push
    public function cURL($headers, $data) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->fcmURL);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        return curl_exec($curl);
    }
}