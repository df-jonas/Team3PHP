<?php

namespace App\Http\Controllers;

use App\LostObject;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;

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
        if ($request->LostObjectID
            && $request->StationID
            && $request->Description
            && $request->Date
            && $request->TrainID
            && $request->LastUpdated
        ) {
            $lostObject = new LostObject();
            $lostObject->LostObjectID = $request->LostObjectID;
            $lostObject->StationID = $request->StationID;
            $lostObject->Description = $request->Description;
            $lostObject->Date = $request->Date;
            $lostObject->TrainID = $request->TrainID;
            $lostObject->LastUpdated = $request->LastUpdated;

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
            if ($request->StationID)
                $lostObject->StationID = $request->StationID;
            if ($request->Description)
                $lostObject->Description = $request->Description;
            if ($request->Date)
                $lostObject->Date = $request->Date;
            if ($request->TrainID)
                $lostObject->TrainID = $request->TrainID;
            if ($request->LastUpdated)
                $lostObject->LastUpdated = $request->LastUpdated;
            else
                $lostObject->LastUpdated = time();

            if ($lostObject->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
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
