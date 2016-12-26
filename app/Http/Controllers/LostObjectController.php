<?php

namespace App\Http\Controllers;

use App\LostObject;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LostObjectController extends Controller
{
    use ReturnTrait;

    protected $className = 'LostObject';

    public function index()
    {
        $lostObject = LostObject::all();
        return response()->json($lostObject);
    }

    public function byID($id)
    {
        $lostObject = LostObject::find($id);
        if (!empty($lostObject))
            return response()->json($lostObject);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->objectID
            && $request->stationID
            && $request->description
            && $request->date
            && $request->trainID
            && $request->lastUpdated
        ) {
            $lostObject = new LostObject();
            $lostObject->ObjectID = $request->objectID;
            $lostObject->StationID = $request->stationID;
            $lostObject->Description = $request->description;
            $lostObject->Date = $request->date;
            $lostObject->TrainID = $request->trainID;
            $lostObject->Found = $request->found;
            $lostObject->LastUpdated = $request->lastUpdated;

            if ($lostObject->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'LostObjectID' => $lostObject->LostObjectID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $lostObject = LostObject::find($id);
        if (!empty($lostObject)) {
            if ($request->stationID)
                $lostObject->StationID = $request->stationID;
            if ($request->description)
                $lostObject->Description = $request->description;
            if ($request->date)
                $lostObject->Date = $request->date;
            if ($request->trainID)
                $lostObject->TrainID = $request->trainID;
            if ($request->found)
                $lostObject->Found = $request->found;
            if ($request->lastUpdated)
                $lostObject->LastUpdated = $request->lastUpdated;
            else
                $lostObject->LastUpdated = time();

            if ($lostObject->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT ObjectID) as Count, MAX(LastUpdated) as LastUpdated FROM LostObject');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->ObjectList)) {

            $lostObjectList = $request->ObjectList;

            try
            {
                foreach ($lostObjectList as $Object)
                {
                    $myObject = LostObject::find($Object['objectID']);

                    if (empty($myObject))
                        $myObject = New LostObject();

                    $myObject->ObjectID = $Object['objectID'];
                    $myObject->StationID = $Object['stationID'];
                    $myObject->Description = $Object['description'];
                    $myObject->Date = $Object['date'];
                    $myObject->TrainID = $Object['trainID'];
                    $myObject->Found = $Object['found'];
                    $myObject->LastUpdated = $Object['lastUpdated'];

                    if (!$myObject->save())
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
        $lostObject = LostObject::find($id);
        if (!empty($lostObject)) {
            if ($lostObject->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
