<?php

namespace App\Http\Controllers;

use App\Station;
use App\StationWithAddress;

use App\Traits\ExceptionTrait;
use App\Traits\ReturnTrait;
use App\Traits\AddressTrait;
use Illuminate\Http\Request;

class StationController extends Controller
{
    use ExceptionTrait;
    use AddressTrait;
    use ReturnTrait;

    protected $className = 'Station';

    public function index()
    {
        $station = Station::all();
        return response()->json($station);
    }

    public function byID($id)
    {
        $station = StationWithAddress::find($id);
        if (!empty($station))
            return response()->json($station);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->StationID
            && $request->AddressID
            && $request->Name
            && $request->CoX
            && $request->CoY
            && $request->LastUpdated
        ) {
            $station = new Station();
            $station->StationID = $request->StationID;
            $station->AddressID = $request->AddressID;
            $station->Name = $request->Name;
            $station->CoX = $request->CoX;
            $station->CoY = $request->CoY;
            $station->LastUpdated = $request->LastUpdated;

            try {
                if ($station->save())
                    return $this->beautifyReturn(200, ['Extra' => 'Created', 'StationID' => $station->StationID]);

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
        $station = Station::find($id);
        if (!empty($station)) {
            if ($request->AddressID)
                $station->AddressID = $request->AddressID;
            if ($request->CoX)
                $station->CoX = $request->CoX;
            if ($request->CoY)
                $station->CoY = $request->CoY;
            if ($request->Name)
                $station->Name = $request->Name;
            if ($request->LastUpdated)
                $station->LastUpdated = $request->LastUpdated;
            else
                $station->LastUpdated = time();

            if ($station->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->StationList)) {

            $stationList = $request->StationList;

            try
            {
                foreach ($stationList as $station)
                {
                    $myStation = Station::find($station['StationID']);

                    if (empty($myStation))
                        $myStation = New Station();

                    $myStation->StationID = $station['StationID'];
                    $myStation->AddressID = $station['AddressID'];
                    $myStation->Name = $station['Name'];
                    $myStation->CoX = $station['Name'];
                    $myStation->CoY = $station['Name'];
                    $myStation->LastUpdated = $station['LastUpdated'];

                    if (!$myStation->save())
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
        $station = Station::find($id);
        if (!empty($station)) {
            if ($station->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function stationsAutocomplete()
    {
        $stationNames = [];
        $path = storage_path('xmls/stations.xml');

        $stationsXMLElement = simplexml_load_file($path);

        foreach ($stationsXMLElement->Station as $station) {
            $stationNames[] = (string)$station->Name;
        }

        return $stationNames;
    }
}
