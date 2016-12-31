<?php
/**
 * Created by PhpStorm.
 * User: Ludovic
 * Date: 09-11-16
 * Time: 18:02
 */

namespace App\Traits;

use App\Address;
use GuzzleHttp\Message\Response;
use Illuminate\Http\Request;

trait AddressTrait
{
    public function createNewAddress(Request $request)
    {
        if ($request->addressID
            && $request->street
            && $request->number
            && $request->city
            && $request->zipCode
            && isset($request->coordinates)
            && $request->lastUpdated
        ) {
            $address = new Address();
            $address->AddressID = $request->addressID;
            $address->Street = $request->street;
            $address->Number = $request->number;
            $address->City = $request->city;
            $address->ZipCode = $request->zipCode;
            $address->Coordinates = $request->coordinates;
            $address->LastUpdated = $request->lastUpdated;

            if ($address->save())
                return $address->AddressID;

            return $this->beautifyReturnMessage(406, 'Address Not Acceptable');
        }
        return $this->beautifyReturnMessage(400, 'Address Bad Request');
    }
}