<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FundsFlowType;
use Illuminate\Support\Facades\Validator;

class FundsFlowTypeController extends BaseController
{
    public function createFundsFlowType(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'     => 'required|unique:funds_flow_types|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $flow = FundsFlowType::create([
            'name' => $request->get('name')
        ]);

        return redirect()->route('flow-type', ['flow' => $flow]);
    }

    public function openFundsFlowType()
    {
        $flows = FundsFlowType::all();

        return view('fundsFlowType', ['flows' => $flows]);
    }

    public function deleteFundsFlowType(FundsFlowType $flow)
    {
       $flow->delete();

        return redirect()->route('flow-type');
    }

    public function updateFundsFlowType(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'flow_id' => 'required|integer',
            'name'     => 'required|unique:funds_flow_types|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $flow = FundsFlowType::find($request->get('flow_id'));

        $flow->name = $request->get('name');
        $flow->save();

        return redirect()->route('flow-type', ['flow' => $flow]);
    }
}
