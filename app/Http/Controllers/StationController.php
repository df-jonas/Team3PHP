<?php

namespace App\Http\Controllers;

use App\Station;
use App\StationWithAddress;
use App\Log;

use App\Traits\ExceptionTrait;
use App\Traits\ReturnTrait;
use App\Traits\AddressTrait;
use Carbon\Carbon;
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
        if ($request->AddressID
            && $request->Name
        ) {
            $station = new Station();
            $station->AddressID = $request->AddressID;
            $station->Name = $request->Name;

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
        $createAddressResponse = $this->createNewAdress($request);

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
            if ($request->Name)
                $station->Name = $request->Name;

            if ($station->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
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

    public static function indexToXML()
    {
        $stations = StationWithAddress::all();
        $path = "documents/stations.xml";

        $log = new Log();
        $log->CreatedAt = \Carbon\Carbon::now()->timestamp;
        $log->LogOrigin = "Station index to xml";

        try
        {
            $xml = new XMLWriter();
            $xml->openUri($path);
            $xml->startDocument('1.0');
            $xml->startElement('stations');

            foreach ($stations as $station) {


                $xml->startElement('station');

                $xml->writeElement('StationID', $station->StationID);

                $xml->startElement('Address');

                $xml->writeElement('AddressID', $station->Address->AddressID);
                $xml->writeElement('AddressID', $station->Address->Street);
                $xml->writeElement('AddressID', $station->Address->Number);
                $xml->writeElement('AddressID', $station->Address->City);
                $xml->writeElement('AddressID', $station->Address->ZipCode);
                $xml->writeElement('AddressID', $station->Address->Coordinates);

                $xml->writeElement('Name', $station->Name);

                $xml->endElement();
            }

            $xml->endElement();
            $xml->endDocument();

            $xml->flush();

            $log->LogMessage = "Stations succesfully indexed to \"" . $path . "\".";
        }
        catch(Exception $e)
        {
            $log->LogMessage = "Stations NOT succesfully indexed to \"" . $path . "\". \n" . $e->getMessage();
        }
        finally
        {
            $log->save();
        }
    }
}
