<?php
/**
 * Created by PhpStorm.
 * User: Ludo
 * Date: 24/10/2016
 * Time: 10:19
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

namespace App\Http\Controllers;


class UserController extends Controller
{

    public function login($credentials)
    {
        if(Input::post('username', 'password'))
        {
            $username = Input::post('username');
            $password = Input::post('password');

            if(Auth::attempt(['username' => $username, 'password' => $password]))
            {
                return response(true, 200);
            }

            return response(true, 404);
        }

        return response(false, 400);
    }

}