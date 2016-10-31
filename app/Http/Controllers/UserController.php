<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function login()
    {
        return response()->json('login_func');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();

        return response()->json($user);
    }

    public function byID($id)
    {
        $user = Staff::find($id);
        if (!empty($user))
            return response()->json($user);

        return Response('Not Found', 404);
    }

    /**
     * Created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (Input::has('AddressID')
            && Input::has('StationID')
            && Input::has('FirstName')
            && Input::has('LastName')
            && Input::has('UserName')
            && Input::has('Password')
            && Input::has('Rights')
            && Input::has('BirthDate')
            && Input::has('Email')
        ) {

            $user = new Staff();
            $user->AddressID = Input::get('AddressID');
            $user->StationID = Input::get('StationID');
            $user->FirstName = Input::get('FirstName');
            $user->LastName = Input::get('LastName');
            $user->UserName = Input::get('UserName');
            $user->Password = Input::get('Password');
            $user->Rights = Input::get('Rights');
            $user->BirthDate = Input::get('BirthDate');
            $user->Email = Input::get('Email');

            if ($user->save())
                return Response('Staff member successfully created', 200);

            return Response('Not Acceptable', 406);
        }
        return Response('Bad Request', 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user = Staff::find($id);
        if (!empty($user)) {
            if (Input::has('AddressID'))
                $user->AddressID = Input::get('AddressID');
            if (Input::has('StationID'))
                $user->StationID = Input::get('StationID');
            if (Input::has('FirstName'))
                $user->FirstName = Input::get('FirstName');
            if (Input::has('LastName'))
                $user->LastName = Input::get('LastName');
            if (Input::has('UserName'))
                $user->UserName = Input::get('UserName');
            if (Input::has('Password'))
                $user->Password = Input::get('Password');
            if (Input::has('Rights'))
                $user->Rights = Input::get('Rights');
            if (Input::has('BirthDate'))
                $user->BirthDate = Input::get('BirthDate');
            if (Input::has('Email'))
                $user->Email = Input::get('Email');


            if ($user->save())
                return Response('Staff member successfully created', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user = User::find($id);
        if (!empty($user)) {
            if ($user->delete())
                return Response('Staff member with id ' . $id . ' has successfully been deleted', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }
}
