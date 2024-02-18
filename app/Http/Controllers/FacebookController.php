<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class FacebookController extends Controller
{

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $checkIfExist = User::where('facebookId', $user->id)->first();

            if ($checkIfExist) {
                dd('User exist');
            } else {
                $createUser = User::updateOrCreate(
                    [
                        'email' => $user->email
                    ],
                    [
                        'name' => $user->name,
                        'password' => encrypt('123456789'),
                        'facebookId' => $user->id
                    ]
                );

                return response()->json([
                    "data" => $createUser,
                    "message" => "Successfully logged in with facebook!"
                ], 200);
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}
