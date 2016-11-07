<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
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

        return Response('Not Found', 404);
    }

    public function create(Request $request)
    {
        if ($request->RailCardID
            && $request->RouteID
            && $request->DiscountID
            && $request->ValidFrom
            && $request->ValidUntil
        ) {
            $subscription = new Subscription();
            $subscription->RailCardID = $request->RailCardID;
            $subscription->RouteID = $request->RouteID;
            $subscription->DiscountID = $request->DiscountID;
            $subscription->ValidFrom = $request->ValidFrom;
            $subscription->ValidUntil = $request->ValidUntil;

            if ($subscription->save())
                return Response('Subscription successfully created', 200);

            return Response('Not Acceptable', 406);
        }
        return Response('Bad Request', 400);
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::find($id);
        if (!empty($subscription)) {
            if ($request->RailCardID)
                $subscription->RailCardID = $request->RailCardID;
            if ($request->RouteID)
                $subscription->RouteID = $request->RouteID;
            if ($request->DiscountID)
                $subscription->DiscountID = $request->DiscountID;
            if ($request->ValidFrom)
                $subscription->ValidFrom = $request->ValidFrom;
            if ($request->ValidUntil)
                $subscription->ValidUntil = $request->ValidUntil;

            if ($subscription->save())
                return Response('Subscription successfully updated', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }

    public function delete($id)
    {
        $subscription = Subscription::find($id);
        if (!empty($subscription)) {
            if ($subscription->delete())
                return Response('Subscription with id ' . $id . ' has successfully been deleted', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }
}
