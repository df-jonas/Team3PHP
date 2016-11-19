<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function getDienstRegeling(Request $request)
    {

        //https://api.irail.be/connections/?to={station1}&from={station2} (OPTIONAL:) &date={dmy}&time=2359&timeSel=arrive or depart

        $dateArray = explode('-', $request->Date);
        $date = $dateArray[2] . $dateArray[1] . substr($dateArray[0], 2);
        $time = str_replace(':', '', $request->Time);


        $url = sprintf('https://api.irail.be/connections/?format=json&to=%s&from=%s&date=%s&time=%s', $request->To, $request->From, $date, $time);

        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);

        if($res->getStatusCode() != 200)
            return view('pages.index', array('dienstRegeling' => 'error'));


            $body = json_decode($res->getBody());
            $dienstRegeling = $body->connection;

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
