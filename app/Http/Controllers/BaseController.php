<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var User $user */
    protected $user;


    public function sendSuccess($message, $data): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function sendError($message, $code): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => false,
            'code' => $code,
            'error_message' => $message,
        ],$code);
    }

    public function get_token($userId): string
    {
        return  md5(time() . $userId);
    }

    /**
     * Sends sms to user using Twilio's programmable sms client
     * @param String $message Body of sms
     * @param Number $recipients string or array of phone number of recepient
     */
    public function sendMessage($message, $recipients)
    {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($recipients,
            ['from' => $twilio_number, 'body' => $message] );
    }

    protected function responseUser(User $user)
    {
        return [
            'id' => $user->id,
            'user_name' => $user->name,
            'full_name' => $user->last_name,
            'photo' => $user->photo ? asset('storage/'.$user->photo) : null,
            'thumb_photo' => $user->thumb_photo ? asset('storage/'.$user->thumb_photo) : null,
            'email' => $user->email,
            'phone' => $user->phone_number ? $user->phone_number : null,
        ];
    }
}
