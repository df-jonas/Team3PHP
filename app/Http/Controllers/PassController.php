<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ReturnTrait;
use App\Pass;
use Illuminate\Support\Facades\DB;

class PassController extends Controller
{
    use ReturnTrait;

    /**
     * ClassName to return on BeautifulRespons (Response Trait)
     * @var string
     */
    protected $className = 'Pass';


    public function index()
    {
        $pass = Pass::all();
        return response()->json($pass);
    }

    public function byID($id)
    {
        $pass = Pass::find($id);
        if (!empty($pass))
            return response()->json($pass);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->passID
            && $request->typePassID
            && $request->date
            && $request->startDate
            && $request->comfortClass
            && $request->lastUpdated
        ) {
            $pass = new Pass();
            $pass->PassID = $request->passID;
            $pass->TypePassID = $request->typePassID;
            $pass->Date = $request->date;
            $pass->StartDate = $request->startDate;
            $pass->ComfortClass = $request->comfortClass;
            $pass->LastUpdated = $request->lastUpdated;

            if ($pass->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'SubscriptionID' => $pass->PassID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $pass = Pass::find($id);
        if (!empty($pass)) {
            if ($request->typePassID)
                $pass->TypePassID = $request->typePassID;
            if ($request->date)
                $pass->Date = $request->date;
            if ($request->startDate)
                $pass->StartDate = $request->startDate;
            if ($request->comfortClass)
                $pass->ComfortClass = $request->comfortClass;
            if ($request->lastUpdated)
                $pass->LastUpdated = $request->lastUpdated;
            else
                $pass->LastUpdated = time();

            if ($pass->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT PassID) as Count, MAX(LastUpdated) as LastUpdated FROM Pass');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->passList)) {

            $passList = $request->passList;

            try
            {
                foreach ($passList as $pass)
                {
                    $myPass = Pass::find($pass['passID']);

                    if (empty($myPass))
                        $myPass = New Pass();

                    $myPass->PassID = $pass['passID'];
                    $myPass->TypePassID = $pass['typePassID'];
                    $myPass->Date = $pass['date'];
                    $myPass->StartDate = $pass['startDate'];
                    $myPass->ComfortClass = $pass['comfortClass'];
                    $myPass->LastUpdated = $pass['lastUpdated'];

                    if (!$myPass->save())
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
        $pass = Pass::find($id);
        if (!empty($pass)) {
            if ($pass->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
