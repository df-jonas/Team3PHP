<?php

namespace App\Http\Controllers;

use App\Address;
use App\Traits\AddressTrait;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    use AddressTrait;

    public function byID($id)
    {
        $address = Address::find($id);
        if (!empty($address))
            return response()->json($address);

        return Response('Not Found', 404);
    }

    public function create(Request $request)
    {
        $createAddressResponse = $this->createNewAdress($request);

        switch($createAddressResponse)
        {
            case 200:
                return Response('Address successfully created', 200);
                break;
            case 406:
                return Response('Not Acceptable', 406);
                break;
            default:
                return Response('Bad Request', 400);
                break;
        }
    }

    public function update(Request $request, $id)
    {
        $address = Address::find($id);
        if (!empty($address)) {
            if ($request->Street)
                $address->Street = $request->AddressID;
            if ($request->Number)
                $address->Number = $request->StationID;
            if ($request->City)
                $address->City = $request->FirstName;
            if ($request->ZipCode)
                $address->ZipCode = $request->LastName;
            if ($request->Coordinates)
                $address->Coordinates = $request->UserName;

            if ($address->save())
                return Response('Address successfully updated', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }

    public function delete($id)
    {
        $address = Address::find($id);
        if (!empty($address)) {
            if ($address->delete())
                return Response('Address with id ' . $id . ' has successfully been deleted', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }
}
