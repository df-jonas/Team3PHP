<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function getDienstRegeling(Request $request)
    {

        //https://api.irail.be/connections/?to={station1}&from={station2} (OPTIONAL:) &date={dmy}&time=2359&timeSel=arrive or depart

        $url = sprintf('https://api.irail.be/connections/?format=json&to=%s&from=%s&date=%s&time=%s', $request->To, $request->From, $request->Date, $request->Time);
//        $url = sprintf('https://api.irail.be/connections/?to=%s&from=%s&date=%s&time=%s', 'Bruxelles-Midi', 'Bruxelles-Nord', '181116', '2000');
//        $url = "https://api.irail.be/connections/?to=Bruxelles-Midi&from=Bruxelles-Nord&date=181116&time=2000&format=json";

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);

        if($res->getStatusCode() != 200)
            return view('pages.index', array('dienstRegeling' => 'error'));


            $body = json_decode($res->getBody());
            $dienstRegeling = $body->connection;

            return respons()->json($request);

            return view('pages.index', array('dienstRegelingen' => $dienstRegeling));



//
//        echo $res->getStatusCode();
//// 200
//        echo $res->getHeaderLine('content-type');
//// 'application/json; charset=utf8'
//        echo $res->getBody();
//// '{"id": 1420053, "name": "guzzle", ...}'


        return response()->json($request);
    }

    public function show()
    {
        return view('pages.index');
    }
}
