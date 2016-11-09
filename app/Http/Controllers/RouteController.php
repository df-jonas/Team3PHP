<?php

namespace App\Http\Controllers;

use App\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $route = Route::all();
        return response()->json($route);
    }

    public function byID($id)
    {
        $route = Route::find($id);
        if (!empty($route))
            return response()->json($route);

        return Response('Not Found', 404);
    }

    public function create(Request $request)
    {
        if ( $request->DepartureStationID
            && $request->ArrivalStationID
        ) {
            $route = new Route();
            $route->DepartureStationID = $request->DepartureStationID;
            $route->ArrivalStationID = $request->ArrivalStationID;

            if ($route->save())
                return Response('Route successfully created', 200);

            return Response('Not Acceptable', 406);
        }
        return Response('Bad Request', 400);
    }

    public function update(Request $request, $id)
    {
        $route = Route::find($id);
        if (!empty($route)) {
            if ($request->DepartureStationID)
                $route->DepartureStationID = $request->DepartureStationID;
            if ($request->ArrivalStationID)
                $route->ArrivalStationID = $request->ArrivalStationID;


            if ($route->save())
                return Response('Route successfully updated', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }

    public function delete($id)
    {
        $route = Route::find($id);
        if (!empty($route)) {
            if ($route->delete())
                return Response('Route with id ' . $id . ' has successfully been deleted', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }
}
