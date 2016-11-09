<?php

namespace App\Http\Controllers;


use App\User;
use App\Traits\AddressTrait;
use App\Traits\ExceptionTrait;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    use AddressTrait;
    use ExceptionTrait;

    public function login(Request $request)
    {
        if (Auth::once(['username' => $request->get('username'), 'password' => $request->get('password')])) {
            $result = [
                'api_token' => Auth::user()->Api_token,
            ];

            return response()->json($result);
        }

        return Response('Bad Request', 400);
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
        $user = User::find($id);
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

        if ($request->AddressID
            && $request->StationID
            && $request->FirstName
            && $request->LastName
            && $request->UserName
            && $request->Password
            && isset($request->Rights)
            && $request->BirthDate
            && $request->Email
        ) {
            $user = new User();
            $user->AddressID = $request->AddressID;
            $user->StationID = $request->StationID;
            $user->FirstName = $request->FirstName;
            $user->LastName = $request->LastName;
            $user->UserName = $request->UserName;
            $user->Password = $request->Password;
            $user->Rights = $request->Rights;
            $user->BirthDate = $request->BirthDate;
            $user->Email = $request->Email;
            $user->Api_token = md5(uniqid($user->UserName, true));

            try {
                if ($user->save())
                    return Response('User member successfully created', 200);

                return Response('User member Not Acceptable', 406);
            } catch (\Exception $e) {

                return Response( $this->beautifyException($e), 406);
            }

        }
        return Response('User member Bad Request', 400);
    }

    public function createWithAddress(Request $request)
    {
        $createAddressResponse = $this->createNewAdress($request);

        switch ($createAddressResponse) {
            case 400:
                return Response('Address Bad Request', 400);
                break;
            case 406:
                return Response('Address Not Acceptable', 406);
                break;
            default:
                $request->request->add(['AddressID' => $createAddressResponse]);
                return $this->create($request);

                break;
        }

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

        $user = User::find($id);
        if (!empty($user)) {
            if ($request->AddressID)
                $user->AddressID = $request->AddressID;
            if ($request->StationID)
                $user->StationID = $request->StationID;
            if ($request->FirstName)
                $user->FirstName = $request->FirstName;
            if ($request->LastName)
                $user->LastName = $request->LastName;
            if ($request->UserName)
                $user->UserName = $request->UserName;
            if ($request->Password)
                $user->Password = $request->Password;
            if ($request->Rights)
                $user->Rights = $request->Rights;
            if ($request->BirthDate)
                $user->BirthDate = $request->BirthDate;
            if ($request->Email)
                $user->Email = $request->Email;


            if ($user->save())
                return Response('User member successfully updated', 200);
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
                return Response('User member with id ' . $id . ' has successfully been deleted', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'UserName';
    }
}
