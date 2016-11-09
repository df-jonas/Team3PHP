<?php

namespace App\Http\Controllers;

use App\Line;
use Illuminate\Http\Request;

class LineController extends Controller
{
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

        return Response('Not Found', 404);
    }

    public function create(Request $request)
    {
        if ( $request->RouteID
            && $request->TrainType
        ) {
            $line = new Line();
            $line->RouteID = $request->RouteID;
            $line->TrainType = $request->TrainType;

            if ($line->save())
                return Response('Line successfully created', 200);

            return Response('Not Acceptable', 406);
        }
        return Response('Bad Request', 400);
    }

    public function update(Request $request, $id)
    {
        $line = Line::find($id);
        if (!empty($line)) {
            if ($request->RouteID)
                $line->RouteID = $request->RouteID;
            if ($request->TrainType)
                $line->TrainType = $request->TrainType;


            if ($line->save())
                return Response('Line successfully updated', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }

    public function delete($id)
    {
        $line = Line::find($id);
        if (!empty($line)) {
            if ($line->delete())
                return Response('Line with id ' . $id . ' has successfully been deleted', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }
}
