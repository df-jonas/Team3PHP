<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    use ReturnTrait;

    /**
     * ClassName to return on BeautifulRespons (Response Trait)
     * @var string
     */
    protected $className = 'Subscription';


    public function index()
    {
        $subscription = Subscription::all();
        return response()->json($subscription);
    }

    public function byID($id)
    {
        $subscription = Subscription::find($id);
        if (!empty($subscription))
            return response()->json($subscription);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->subscriptionID
            && $request->railCardID
            && $request->routeID
            && $request->discountID
            && $request->validFrom
            && $request->validUntil
            && $request->lastUpdated
        ) {
            $subscription = new Subscription();
            $subscription->SubscriptionID = $request->subscriptionID;
            $subscription->RailCardID = $request->railCardID;
            $subscription->RouteID = $request->routeID;
            $subscription->DiscountID = $request->discountID;
            $subscription->ValidFrom = $request->validFrom;
            $subscription->ValidUntil = $request->validUntil;
            $subscription->LastUpdated = $request->lastUpdated;

            if ($subscription->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'SubscriptionID' => $subscription->SubscriptionID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::find($id);
        if (!empty($subscription)) {
            if ($request->railCardID)
                $subscription->RailCardID = $request->railCardID;
            if ($request->routeID)
                $subscription->RouteID = $request->routeID;
            if ($request->discountID)
                $subscription->DiscountID = $request->discountID;
            if ($request->validFrom)
                $subscription->ValidFrom = $request->validFrom;
            if ($request->validUntil)
                $subscription->ValidUntil = $request->validUntil;
            if ($request->lastUpdated)
                $subscription->LastUpdated = $request->lastUpdated;
            else
                $subscription->LastUpdated = time();

            if ($subscription->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT SubscriptionID) as Count, MAX(LastUpdated) as LastUpdated FROM Subscription');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->subscriptionList)) {

            $subscriptionList = $request->subscriptionList;

            try
            {
                foreach ($subscriptionList as $subscription)
                {
                    $mySubscription = Subscription::find($subscription['subscriptionID']);

                    if (empty($mySubscription))
                        $mySubscription = New Subscription();

                    $mySubscription->SubscriptionID = $subscription['subscriptionID'];
                    $mySubscription->RailCardID = $subscription['railCardID'];
                    $mySubscription->RouteID = $subscription['routeID'];
                    $mySubscription->DiscountID = $subscription['discountID'];
                    $mySubscription->ValidFrom = $subscription['validFrom'];
                    $mySubscription->ValidUntil = $subscription['validUntil'];
                    $mySubscription->LastUpdated = $subscription['lastUpdated'];

                    if (!$mySubscription->save())
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
        $subscription = Subscription::find($id);
        if (!empty($subscription)) {
            if ($subscription->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
