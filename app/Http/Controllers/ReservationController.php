<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ReturnTrait;
use App\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    use ReturnTrait;

    /**
     * ClassName to return on BeautifulRespons (Response Trait)
     * @var string
     */
    protected $className = 'Reservation';


    public function index()
    {
        $reservation = Reservation::all();
        return response()->json($reservation);
    }

    public function byID($id)
    {
        $reservation = Reservation::find($id);
        if (!empty($reservation))
            return response()->json($reservation);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->reservationID
            && $request->passengerCount
            && $request->trainID
            && $request->price
            && $request->reservationDate
            && $request->routeID
            && $request->lastUpdated
        ) {
            $reservation = new Reservation();
            $reservation->ReservationID = $request->reservationID;
            $reservation->PassengerCount = $request->passengerCount;
            $reservation->TrainID = $request->trainID;
            $reservation->Price = $request->price;
            $reservation->RouteID = $request->routeID;
            $reservation->ReservationDate = $request->reservationDate;
            $reservation->LastUpdated = $request->lastUpdated;

            if ($reservation->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'SubscriptionID' => $reservation->ReservationID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        if (!empty($reservation)) {
            if ($request->passengerCount)
                $reservation->PassengerCount = $request->passengerCount;
            if ($request->trainID)
                $reservation->TrainID = $request->trainID;
            if ($request->price)
                $reservation->Price = $request->price;
            if ($request->reservationDate)
                $reservation->ReservationDate = $request->reservationDate;
            if ($request->routeID)
                $reservation->RouteID = $request->routeID;
            if ($request->lastUpdated)
                $reservation->LastUpdated = $request->lastUpdated;
            else
                $reservation->LastUpdated = time();

            if ($reservation->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT ReservationID) as Count, MAX(LastUpdated) as LastUpdated FROM Reservation');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->reservationList)) {

            $reservationList = $request->reservationList;

            try
            {
                foreach ($reservationList as $reservation)
                {
                    $myReservation = Customer::find($reservation['reservationID']);

                    if (empty($myReservation))
                        $myReservation = New Customer();

                    $myReservation->ReservationID = $reservation['reservationID'];
                    $myReservation->PassengerCount = $reservation['passengerCount'];
                    $myReservation->TrainID = $reservation['trainID'];
                    $myReservation->Price = $reservation['price'];
                    $myReservation->ReservationDate = $reservation['reservationDate'];
                    $myReservation->RouteID = $reservation['routeID'];
                    $myReservation->LastUpdated = $reservation['lastUpdated'];

                    if (!$myReservation->save())
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
        $reservation = Reservation::find($id);
        if (!empty($reservation)) {
            if ($reservation->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
