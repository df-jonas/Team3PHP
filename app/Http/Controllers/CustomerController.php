<?php

namespace App\Http\Controllers;

use App\Customer;
use App\RailCard;
use App\Traits\AddressTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    use AddressTrait;

    protected $className = 'Customer';

    public function index()
    {
        $customer = Customer::all();

        return response()->json($customer);
    }

    public function byID($id)
    {
        $customer = Customer::find($id);
        if (!empty($customer))
            return response()->json($customer);

        return $this->beautifyReturn(404);
    }

    /**
     * Created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if ($request->custommerID
            && $request->cardID
            && $request->addressID
            && $request->firstName
            && $request->lastName
            && $request->birthDate
            && $request->email
            && $request->lastUpdated
        ) {
            $customer = new Customer();

//            $railcard = new RailCard();
//            $railcard->RailcardID = $request->customerID;
//            $railcard->LastUpdated = $request->lastUpdated;
//
//            if (!$railcard->save())
//                return $this->beautifyReturnMessage('400', 'Railcard could not be saved');

            $customer->CustommerID = $request->custommerID;
            $customer->RailCardID = $request->cardID;
            $customer->AddressID = $request->addressID;
            $customer->FirstName = $request->firstName;
            $customer->LastName = $request->lastName;
            $customer->BirthDate = $request->birthDate;
            $customer->Email = $request->email;
            $customer->LastUpdated = $request->lastUpdated;

            if ($customer->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'CustomerID' => $customer->CustomerID]);

            return $this->beautifyReturn(406);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $customer = Customer::find($id);
        if (!empty($customer)) {
            if ($request->addressID)
                $customer->AddressID = $request->addressID;
            if ($request->firstName)
                $customer->FirstName = $request->firstName;
            if ($request->lastName)
                $customer->LastName = $request->lastName;
            if ($request->birthDate)
                $customer->BirthDate = $request->birthDate;
            if ($request->email)
                $customer->Email = $request->email;
            if ($request->lastUpdated)
                $customer->LastUpdated = $request->lastUpdated;
            else
                $customer->LastUpdated = time();


            if ($customer->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);

    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT CustomerID) as Count, MAX(LastUpdated) as LastUpdated FROM Customer');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {
        if (!empty($request->customerList)) {

            $customerList = $request->customerList;

            try
            {
                foreach ($customerList as $customer)
                {
                    $myCustomer = Customer::find($customer['custommerID']);

                    if (empty($myCustomer))
                        $myCustomer = New Customer();

                    $myCustomer->CustommerID = $customer['custommerID'];
                    $myCustomer->RailCardID = $customer['railCardID'];
                    $myCustomer->AddressID = $customer['addressID'];
                    $myCustomer->FirstName = $customer['firstName'];
                    $myCustomer->LastName = $customer['lastName'];
                    $myCustomer->BirthDate = $customer['birthDate'];
                    $myCustomer->Email = $customer['email'];
                    $myCustomer->LastUpdated = $customer['lastUpdated'];

                    if (!$myCustomer->save())
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
        $customer = Customer::find($id);
        if (!empty($customer)) {
            if ($customer->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
