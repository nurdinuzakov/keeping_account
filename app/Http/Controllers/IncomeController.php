<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncomeController extends BaseController
{
    public function income()
    {
        $income = Income::orderBy('created_at', 'DESC');
        $total = $income->pluck('amount');
        $incomes = $income->paginate(15);
        $totalSum = $total->sum();
        return view('income', ['incomes' => $incomes, 'total' => $totalSum]);
    }

    public function incomeInsert(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'date'        => 'required|date',
            'from'        => 'required|string',
            'description' => 'required|string',
            'amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $incomes = Income::create($inputs)->orderBy('created_at', 'DESC')->paginate(15);

        return redirect()->route('income', ['incomes' => $incomes]);
    }

    public function incomeDelete($id)
    {
        $income = Income::find($id);
        if (!$income)
        {
            return $this->sendError(['message' => 'Income with this id was not found'],422);
        }
        $income->delete();
        $income->get();

        return redirect()->route('income', ['incomes' => $income]);
    }

    public function incomeUpdate(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'date'        => 'required|date',
            'from'        => 'required|string',
            'description' => 'required|string',
            'amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $incomes = Income::all();
        $income = $incomes->find($inputs['inputPencilId']);

        $income->date = $inputs['date'];
        $income->from = $inputs['from'];
        $income->description = $inputs['description'];
        $income->amount = $inputs['amount'];
        $income->save();

        $income->get();
        return redirect()->route('income', ['incomes' => $incomes]);
    }
}
