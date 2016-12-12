<?php
/**
 * Created by PhpStorm.
 * User: Ludo
 * Date: 12/12/2016
 * Time: 11:17
 */

use App\Http\Controllers\StationController;
use App\StationWithAddress;
use App\Log;

class CronJobs
{
    function __construct()
    {
        $stations = StationWithAddress::all();
        $path = "documents/stations.xml";
        
        $log = new Log();
        $log->CreatedAt = \Carbon\Carbon::now()->timestamp;
        $log->LogOrigin = "Station index to xml";

        try
        {


            $xml = new XMLWriter();
            $xml->openUri($path);
            $xml->startDocument('1.0');
            $xml->startElement('stations');

            foreach ($stations as $station) {


                $xml->startElement('station');

                $xml->writeElement('StationID', $station->StationID);

                $xml->startElement('Address');

                $xml->writeElement('AddressID', $station->Address->AddressID);
                $xml->writeElement('AddressID', $station->Address->Street);
                $xml->writeElement('AddressID', $station->Address->Number);
                $xml->writeElement('AddressID', $station->Address->City);
                $xml->writeElement('AddressID', $station->Address->ZipCode);
                $xml->writeElement('AddressID', $station->Address->Coordinates);

                $xml->writeElement('Name', $station->Name);

                $xml->endElement();
            }

            $xml->endElement();
            $xml->endDocument();

            $xml->flush();

            $log->LogMessage = "Stations succesfully indexed to \"" . $path . "\".";
        }
        catch(Exception $e)
        {
            $log->LogMessage = "Stations NOT succesfully indexed to \"" . $path . "\". \n" . $e->getMessage();
        }
        finally
        {
            $log->save();
        }
    }


    private function indexStations()
    {
        print_r("oaoaoaoao");
        StationController::indexToXML();
    }

}