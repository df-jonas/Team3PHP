<?php

namespace App\Http\Controllers;


use App\User;
use App\Traits\AddressTrait;
use App\Traits\ExceptionTrait;
use App\Traits\ReturnTrait;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    use AddressTrait;
    use ExceptionTrait;
    use ReturnTrait;

    protected $className = 'Staff Member';

    public function login(Request $request)
    {

        if (Auth::once(['username' => $request->username, 'password' => $request->password]))
        {
            $result = [
                'StatusCode' => 200,
                'Api_token' => Auth::user()->Api_token,
                'StaffID' => Auth::user()->StaffID,
                'Rights' => Auth::user()->Rights,
            ];

            return response()->json($result);
        }

        return $this->beautifyReturnMessage(400, 'wrong username or password');
    }

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

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->staffID
            && $request->addressID
            && $request->stationID
            && $request->firstName
            && $request->lastName
            && $request->userName
            && $request->password
            && isset($request->rihts)
            && $request->birthDate
            && $request->email
            && $request->apiToken
            && $request->lastUpdated
        ) {
            $user = new User();
            $user->StaffID = $request->staffID;
            $user->AddressID = $request->addressID;
            $user->StationID = $request->stationID;
            $user->FirstName = $request->firstName;
            $user->LastName = $request->lastName;
            $user->UserName = $request->userName;

            $user->Rights = $request->rights;
            $user->BirthDate = $request->birthDate;
            $user->Email = $request->email;
            $user->LastUpdated = $request->lastUpdated;

            $hashPatern = '/^(\$2y\$10\$)/';

            // Hash password if not already hashed
            if (preg_match($hashPatern, $request->password))
                $user->Password = $request->password;
            else
                $user->Password = Hash::make($request->password);

            // Hash api_token if not already hashed
            if (preg_match($hashPatern, $request->apiToken))
                $user->Api_token = $request->api_token;
            else
                $user->Api_token = Hash::make(uniqid($user->UserName, true));


            try {
                if ($user->save())
                    return $this->beautifyReturn(200, ['Extra' => 'Created', 'Staff MemberID' => $user->StaffID]);

                return $this->beautifyReturn(406);
            } catch (\Exception $e) {
                return $this->beautifyReturn(406, ['Error' => $this->beautifyException($e)]);
            }

        }
        return $this->beautifyReturn(400);
    }

    public function createWithAddress(Request $request)
    {
        $createAddressResponse = $this->createNewAddress($request);

        if (is_numeric($createAddressResponse)) {
            $request->request->add(['AddressID' => $createAddressResponse]);
            return $this->create($request);
        } else {
            return $createAddressResponse;
        }
    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);
        if (!empty($user)) {
            $hashPatern = '/^(\$2y\$10\$)/';

            if ($request->addressID)
                $user->AddressID = $request->addressID;
            if ($request->stationID)
                $user->StationID = $request->stationID;
            if ($request->firstName)
                $user->FirstName = $request->firstName;
            if ($request->lastName)
                $user->LastName = $request->lastName;
            if ($request->userName)
                $user->UserName = $request->userName;
            if (isset($request->rights))
                $user->Rights = $request->rights;
            if ($request->birthDate)
                $user->BirthDate = $request->birthDate;
            if ($request->email)
                $user->Email = $request->email;
            if ($request->apiToken)
                $user->Api_token = $request->apiToken;
            if ($request->lastUpdated)
                $user->LastUpdated = $request->lastUpdated;
            else
                $user->LastUpdated = time();

            if ($request->password)
            {
                if (preg_match($hashPatern, $request->password))
                    $user->Password = $request->password;
                else
                    $user->Password = Hash::make($request->password);
            }

            if ($user->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);

    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT StaffID) as Count, MAX(LastUpdated) as LastUpdated FROM Staff');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->staffList)) {

            $staffList = $request->staffList;

            try
            {
                foreach ($staffList as $staff)
                {
                    $myStaff = User::find($staff['staffID']);

                    if (empty($myStaff))
                        $myStaff = New User();

                    $myStaff->StaffID = $staff['staffID'];
                    $myStaff->AddressID = $staff['addressID'];
                    $myStaff->StationID = $staff['stationID'];
                    $myStaff->FirstName = $staff['firstName'];
                    $myStaff->LastName = $staff['lastName'];
                    $myStaff->UserName = $staff['userName'];
                    $myStaff->Password = $staff['password'];
                    $myStaff->Rights = $staff['rights'];
                    $myStaff->BirthDate = $staff['birthDate'];
                    $myStaff->Email = $staff['email'];
                    $myStaff->Api_token = $staff['api_token'];
                    $myStaff->LastUpdated = $staff['lastUpdated'];

                    if (!$myStaff->save())
                        return $this->beautifyReturn(460, ['Extra' => 'MassUpdate']);

                }
                return $this->beautifyReturn(200, ['Extra' => 'MassUpdated']);
            }
            catch (\Exception $e)
            {
                return $this->beautifyReturn(444, ['Error' => $this->beautifyException($e)]);
            }
        }

        return $this->beautifyReturn(400);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!empty($user)) {
            if ($user->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
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
