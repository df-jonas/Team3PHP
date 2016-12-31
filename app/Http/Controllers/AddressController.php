<?php

namespace App\Http\Controllers;

use App\Address;
use App\Traits\AddressTrait;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    use AddressTrait;
    use ReturnTrait;

    protected $className = 'Address';

    public function index()
    {
        $address = Address::all();

        return response()->json($address);
    }

    public function byID($id)
    {
        $address = Address::find($id);
        if (!empty($address))
            return response()->json($address);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        $createAddressResponse = $this->createNewAddress($request);

        if (is_numeric($createAddressResponse)) {
            return $this->beautifyReturn(200, ['Extra' => 'Created', 'AddressID' => $createAddressResponse]);
        } else {
            return $createAddressResponse;
        }
    }

    public function update(Request $request, $id)
    {
        $address = Address::find($id);
        if (!empty($address)) {
            if ($request->street)
                $address->Street = $request->street;
            if ($request->number)
                $address->Number = $request->number;
            if ($request->city)
                $address->City = $request->city;
            if ($request->zipCode)
                $address->ZipCode = $request->zipCode;
            if ($request->coordinates)
                $address->Coordinates = $request->coordinates;
            if ($request->lastUpdated)
                $address->LastUpdated = $request->lastUpdated;
            else
                $address->LastUpdated = time();

            if ($address->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT AddressID) as Count, MAX(LastUpdated) as LastUpdated FROM Address');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->addressList)) {

            $addressList = $request->addressList;

            try
            {
                foreach ($addressList as $address)
                {
                    $myAddress = Address::find($address['addressID']);

                    if (empty($myAddress))
                        $myAddress = New Address();

                    $myAddress->AddressID = $address['addressID'];
                    $myAddress->Street = $address['street'];
                    $myAddress->Number = $address['number'];
                    $myAddress->City = $address['city'];
                    $myAddress->ZipCode = $address['zipCode'];
                    $myAddress->Coordinates = $address['coordinates'];
                    $myAddress->LastUpdated = $address['lastUpdated'];

                    if (!$myAddress->save())
                        return $this->beautifyReturn(460, ['Extra' => 'MassUpdate']);

                }
                return $this->beautifyReturn(200, ['Extra' => 'MassUpdated']);
            }
            catch (\Exception $e)
            {
                print_r($e->getLine());
                return $this->beautifyReturn(444, ['Error' => $this->beautifyException($e)]);
            }
        }
        return $this->beautifyReturn(400);
    }

    public function delete($id)
    {
        $address = Address::find($id);
        if (!empty($address)) {
            if ($address->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
