<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
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

        return Response('Not Found', 404);
    }

    public function create(Request $request)
    {
        if ( $request->RouteID
            && $request->Date
            && $request->Price
            && $request->ValidFrom
            && $request->ValidUntil
            && $request->ComfortClass
        ) {
            $ticket = new Ticket();
            $ticket->RouteID = $request->RouteID;
            $ticket->Date = $request->Date;
            $ticket->Price = $request->Price;
            $ticket->ValidFrom = $request->ValidFrom;
            $ticket->ValidUntil = $request->ValidUntil;
            $ticket->ComfortClass = $request->ComfortClass;

            if ($ticket->save())
                return Response('Ticket successfully created', 200);

            return Response('Not Acceptable', 406);
        }
        return Response('Bad Request', 400);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        if (!empty($ticket)) {
            if ($request->RouteID)
                $ticket->RouteID = $request->RouteID;
            if ($request->Date)
                $ticket->Date = $request->Date;
            if ($request->Price)
                $ticket->Price = $request->Price;
            if ($request->ValidFrom)
                $ticket->ValidFrom = $request->ValidFrom;
            if ($request->ValidUntil)
                $ticket->ValidUntil = $request->ValidUntil;
            if ($request->ComfortClass)
                $ticket->ComfortClass = $request->ComfortClass;


            if ($ticket->save())
                return Response('Ticket successfully updated', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }

    public function delete($id)
    {
        $ticket = Ticket::find($id);
        if (!empty($ticket)) {
            if ($ticket->delete())
                return Response('Ticket with id ' . $id . ' has successfully been deleted', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }
}
