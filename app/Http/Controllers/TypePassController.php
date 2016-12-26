<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ReturnTrait;
use App\TypePass;
use Illuminate\Support\Facades\DB;

class TypePassController extends Controller
{
    use ReturnTrait;

    /**
     * ClassName to return on BeautifulRespons (Response Trait)
     * @var string
     */
    protected $className = 'TypePass';


    public function index()
    {
        $typePass = TypePass::all();
        return response()->json($typePass);
    }

    public function byID($id)
    {
        $typePass = TypePass::find($id);
        if (!empty($typePass))
            return response()->json($typePass);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->typePassID
            && $request->name
            && $request->price
            && $request->lastUpdated
        ) {
            $typePass = new TypePass();
            $typePass->TypePassID = $request->typePassID;
            $typePass->Name = $request->name;
            $typePass->Price = $request->price;
            $typePass->LastUpdated = $request->lastUpdated;

            if ($typePass->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'TypePassID' => $typePass->TypePassID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $typePass = TypePass::find($id);
        if (!empty($typePass)) {
            if ($request->name)
                $typePass->Name = $request->name;
            if ($request->price)
                $typePass->Price = $request->price;
            if ($request->lastUpdated)
                $typePass->LastUpdated = $request->lastUpdated;
            else
                $typePass->LastUpdated = time();

            if ($typePass->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT TypePassID) as Count, MAX(LastUpdated) as LastUpdated FROM TypePass');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->typePassList)) {

            $typePassList = $request->typePassList;

            try
            {
                foreach ($typePassList as $typePass)
                {
                    $myTypePass = TypePass::find($typePass['typePassID']);

                    if (empty($myTypePass))
                        $myTypePass = New TypePass();

                    $myTypePass->TypePassID = $typePass['typePassID'];
                    $myTypePass->Name = $typePass['name'];
                    $myTypePass->Price = $typePass['price'];
                    $myTypePass->LastUpdated = $typePass['lastUpdated'];

                    if (!$myTypePass->save())
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
        $typePass = TypePass::find($id);
        if (!empty($typePass)) {
            if ($typePass->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
