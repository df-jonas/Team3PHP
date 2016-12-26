<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    use ReturnTrait;

    protected $className = 'Ticket';

    public function index()
    {
        $ticket = Ticket::all();
        return response()->json($ticket);
    }

    public function byID($id)
    {
        $ticket = Ticket::find($id);
        if (!empty($ticket))
            return response()->json($ticket);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ( $request->ticketID
            && $request->routeID
            && $request->typeTicketID
            && $request->date
            && $request->validFrom
            && $request->validUntil
            && $request->lastUpdated
        ) {
            $ticket = new Ticket();
            $ticket->TicketID = $request->ticketID;
            $ticket->RouteID = $request->routeID;
            $ticket->TypeTicketID = $request->rypeTicketID;
            $ticket->Date = $request->date;
            $ticket->ValidFrom = $request->validFrom;
            $ticket->ValidUntil = $request->validUntil;
            $ticket->LastUpdated = $request->lastUpdated;

            if ($ticket->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'TicketID' => $ticket->TicketID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        if (!empty($ticket)) {
            if ($request->routeID)
                $ticket->RouteID = $request->routeID;
            if ($request->typeTicketID)
                $ticket->TypeTicketID = $request->typeTicketID;
            if ($request->date)
                $ticket->Date = $request->date;
            if ($request->validFrom)
                $ticket->ValidFrom = $request->validFrom;
            if ($request->validUntil)
                $ticket->ValidUntil = $request->validUntil;
            if ($request->lastUpdated)
                $ticket->LastUpdated = $request->lastUpdated;
            else
                $ticket->LastUpdated = time();


            if ($ticket->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT TicketID) as Count, MAX(LastUpdated) as LastUpdated FROM Ticket');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->ticketList)) {

            $ticketList = $request->ticketList;

            try
            {
                foreach ($ticketList as $ticket)
                {
                    $myTicket = Ticket::find($ticket['ticketID']);

                    if (empty($myTicket))
                        $myTicket = New Ticket();

                    $myTicket->TicketID = $ticket['ticketID'];
                    $myTicket->RouteID = $ticket['routeID'];
                    $myTicket->TypeTicketID = $ticket['typeTicketID'];
                    $myTicket->Date = $ticket['date'];
                    $myTicket->ValidFrom = $ticket['validFrom'];
                    $myTicket->ValidUntil = $ticket['validUntil'];
                    $myTicket->LastUpdated = $ticket['lastUpdated'];

                    if (!$myTicket->save())
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
        $ticket = Ticket::find($id);
        if (!empty($ticket)) {
            if ($ticket->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
