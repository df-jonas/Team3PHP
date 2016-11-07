<?php

namespace App\Http\Controllers;

use App\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discount = Discount::all();
        return response()->json($discount);
    }

    public function byID($id)
    {
        $discount = Discount::find($id);
        if (!empty($discount))
            return response()->json($discount);

        return Response('Not Found', 404);
    }

    public function create(Request $request)
    {
        if ($request->Name
            && $request->Amount
        ) {
            $discount = new Discount();
            $discount->Name = $request->Name;
            $discount->Amount = $request->Amount;

            if ($discount->save())
                return Response('Discount successfully created', 200);

            return Response('Not Acceptable', 406);
        }
        return Response('Bad Request', 400);
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::find($id);
        if (!empty($discount)) {
            if ($request->Name)
                $discount->Name = $request->Name;
            if ($request->Amount)
                $discount->Amount = $request->Amount;

            if ($discount->save())
                return Response('Discount successfully updated', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }

    public function delete($id)
    {
        $discount = Discount::find($id);
        if (!empty($discount)) {
            if ($discount->delete())
                return Response('Discount with id ' . $id . ' has successfully been deleted', 200);
        } else {
            return Response('Not Found', 404);
        }
        return Response('Bad Request', 400);
    }
}
