<?php
/**
 * Created by PhpStorm.
 * User: Shahriar Ahmmed
 * Date: 20-Nov-18
 * Time: 12:02 AM
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{
    public $successStatus = 200;

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {

            $user = Auth::user();

            $success['token'] = $user->createToken('MyApp')->accessToken;

            return response()->json(['success' => $success], $this->successStatus);

        } else {

            return response()->json(['error' => 'Unauthorised'], 401);

        }
    }
}