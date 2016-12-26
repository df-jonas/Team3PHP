<?php

namespace App\Http\Controllers;

use App\Line;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LineController extends Controller
{
    use ReturnTrait;

    protected $className = 'Line';

    public function index()
    {
        $line = Line::all();
        return response()->json($line);
    }

    public function byID($id)
    {
        $line = Line::find($id);
        if (!empty($line))
            return response()->json($line);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->lineID
            && $request->routeID
            && $request->trainType
            && $request->lastUpdated
        ) {
            $line = new Line();
            $line->LineID = $request->lineID;
            $line->RouteID = $request->routeID;
            $line->TrainType = $request->trainType;
            $line->LastUpdated = $request->lastUpdated;

            if ($line->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'LineID' => $line->LineID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $line = Line::find($id);
        if (!empty($line)) {
            if ($request->routeID)
                $line->RouteID = $request->routeID;
            if ($request->trainType)
                $line->TrainType = $request->trainType;
            if ($request->lastUpdated)
                $line->LastUpdated = $request->lastUpdated;
            else
                $line->LastUpdated = time();


            if ($line->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT LineID) as Count, MAX(LastUpdated) as LastUpdated FROM Line');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->lineList)) {

            $lineList = $request->lineList;

            try
            {
                foreach ($lineList as $line)
                {
                    $myLine = Line::find($line['lineID']);

                    if (empty($myLine))
                        $myLine = New Line();

                    $myLine->LineID = $line['lineID'];
                    $myLine->RouteID = $line['routeID'];
                    $myLine->TrainType = $line['trainType'];
                    $myLine->LastUpdated = $line['lastUpdated'];

                    if (!$myLine->save())
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
        $line = Line::find($id);
        if (!empty($line)) {
            if ($line->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
