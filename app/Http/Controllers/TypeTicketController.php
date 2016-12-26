<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ReturnTrait;
use App\TypeTicket;
use Illuminate\Support\Facades\DB;

class TypeTicketController extends Controller
{
    use ReturnTrait;

    protected $className = 'TypeTicket';

    public function index()
    {
        $typeTicket = TypeTicket::all();
        return response()->json($typeTicket);
    }

    public function byID($id)
    {
        $typeTicket = TypeTicket::find($id);
        if (!empty($typeTicket))
            return response()->json($typeTicket);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ( $request->typeTicketID
            && $request->name
            && $request->price
            && $request->comfortClass
            && $request->lastUpdated
        ) {
            $typeTicket = new TypeTicket();
            $typeTicket->TypeTicketID = $request->typeTicketID;
            $typeTicket->Name = $request->name;
            $typeTicket->Price = $request->price;
            $typeTicket->ComfortClass = $request->comfortClass;
            $typeTicket->LastUpdated = $request->lastUpdated;

            if ($typeTicket->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'TicketID' => $typeTicket->TypeTicketID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $typeTicket = TypeTicket::find($id);
        if (!empty($typeTicket)) {
            if ($request->name)
                $typeTicket->Name = $request->name;
            if ($request->price)
                $typeTicket->Price = $request->Price;
            if ($request->comfortClass)
                $typeTicket->ComfortClass = $request->comfortClass;
            if ($request->lastUpdated)
                $typeTicket->LastUpdated = $request->lastUpdated;
            else
                $typeTicket->LastUpdated = time();


            if ($typeTicket->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT TypeTicketID) as Count, MAX(LastUpdated) as LastUpdated FROM TypeTicket');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->typeTicketList)) {

            $typeTicketList = $request->typeTicketList;

            try
            {
                foreach ($typeTicketList as $typeTicket)
                {
                    $myTypeTicket = TypeTicket::find($typeTicket['typeTicketID']);

                    if (empty($myTypeTicket))
                        $myTypeTicket = New TypeTicket();

                    $myTypeTicket->TypeTicketID = $typeTicket['typeTicketID'];
                    $myTypeTicket->Name = $typeTicket['name'];
                    $myTypeTicket->Price = $typeTicket['price'];
                    $myTypeTicket->ComfortClass = $typeTicket['comfortClass'];
                    $myTypeTicket->LastUpdated = $typeTicket['lastUpdated'];

                    if (!$myTypeTicket->save())
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
        $typeTicket = TypeTicket::find($id);
        if (!empty($typeTicket)) {
            if ($typeTicket->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
