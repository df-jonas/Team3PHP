<?php

namespace App\Http\Controllers;

use App\Station;

use App\Traits\ExceptionTrait;
use App\Traits\ReturnTrait;
use App\Traits\AddressTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $station = Station::find($id);
        if (!empty($station))
            return response()->json($station);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->stationID
            && $request->name
            && $request->coX
            && $request->coY
            && $request->lastUpdated
        ) {
            $station = new Station();
            $station->StationID = $request->stationID;
            $station->Name = $request->name;
            $station->CoX = $request->coX;
            $station->CoY = $request->coY;
            $station->LastUpdated = $request->lastUpdated;

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

    public function update(Request $request, $id)
    {
        $station = Station::find($id);
        if (!empty($station)) {
            if ($request->name)
                $station->Name = $request->name;
            if ($request->coX)
                $station->CoX = $request->coX;
            if ($request->coY)
                $station->CoY = $request->coY;
            if ($request->lastUpdated)
                $station->LastUpdated = $request->lastUpdated;
            else
                $station->LastUpdated = time();

            if ($station->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT StationID) as Count, MAX(LastUpdated) as LastUpdated FROM Station');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->stationList)) {

            $stationList = $request->stationList;

            try
            {
                foreach ($stationList as $station)
                {
                    $myStation = Station::find($station['stationID']);

                    if (empty($myStation))
                        $myStation = New Station();

                    $myStation->StationID = $station['stationID'];
                    $myStation->Name = $station['name'];
                    $myStation->CoX = $station['name'];
                    $myStation->CoY = $station['name'];
                    $myStation->LastUpdated = $station['lastUpdated'];

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
