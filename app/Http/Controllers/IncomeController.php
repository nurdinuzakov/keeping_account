<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use App\Models\Expense;
use App\Models\PaymentMethods;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncomeController extends BaseController
{
    public function income()
    {
        $income = Income::with('paymentMethods');
        $income = $income->orderBy('created_at', 'DESC');
        $pluckedAmount = $income->pluck('amount');
        $totalAmount = $pluckedAmount->sum();
        $paymentMethods = PaymentMethods::all();

        $totalBalance = 0;
        foreach ($paymentMethods as $value)
        {
            $itemBalance = $value->balance;

            $totalBalance += $itemBalance;
        }

        $incomes = $income->paginate(15);

        return view('income', ['incomes' => $incomes, 'totalBalance' => $totalBalance,
                                    'paymentMethods' => $paymentMethods, 'totalAmount' => $totalAmount]);
    }

    public function incomeInsert(Request $request)
    {
        $inputs = $request->all();
//        dd($inputs);

        $validator = Validator::make($inputs,[
            'date'        => 'required|date',
            'from'        => 'required|string',
            'method_id'   => 'required|exists:payment_methods,id',
            'amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $income = Income::create([
            'paymentMethod_id' => $inputs['method_id'],
            'date' => $inputs['date'],
            'from' => $inputs['from'],
            'amount' => $inputs['amount']
        ]);

        $paymentMethodsBalance = PaymentMethods::find($inputs['method_id']);

        if (!$paymentMethodsBalance->balance){
            $itemBalance = 0;
        }else{
            $itemBalance = $paymentMethodsBalance->balance;
        }

        $newTotalBalance = $itemBalance + $inputs['amount'];

        $paymentHistory = PaymentHistory::create([
            'balanceable_id'   => $income->id,
            'balanceable_type' => 'App\Models\Income',
            'payment_id'       => $inputs['method_id'],
            'date'             => $inputs['date'],
            'amount'           => $inputs['amount'],
            'balance_history'     => $newTotalBalance
        ]);

        $paymentMethods = PaymentMethods::find($inputs['method_id']);
        $oldBalance = $paymentMethods->balance;
        $newBalance = $oldBalance + $inputs['amount'];
        $paymentMethods->balance = $newBalance;
        $paymentMethods->save();


        return redirect()->route('income');
    }

    public function incomeDelete($id)
    {
        $income = Income::find($id);
        if (!$income)
        {
            return $this->sendError(['message' => 'Income with this id was not found'],422);
        }

        $incomeAmount = $income->amount;
        $paymentMethod_id = $income->paymentMethod_id;
        $paymentMethod = PaymentMethods::find($paymentMethod_id);
        $paymentMethodBalance = $paymentMethod->balance;
        $newPaymentMethodBalance = $paymentMethodBalance - $incomeAmount;
        $paymentMethod->balance = $newPaymentMethodBalance;
        $paymentMethod->save();

        $income->delete();

        return redirect()->route('income');
    }

    public function incomeUpdate(Request $request)
    {
        $inputs = $request->all();
//        dd($inputs);

        $validator = Validator::make($inputs,[
            'date'        => 'required|date',
            'from'        => 'required|string',
            'method_id'     => 'required|exists:payment_methods,id',
            'amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $incomeWithRelations = PaymentMethods::where('id', $inputs['method_id'])
                                                ->with(['incomes' => function($query) use ($inputs){
                                                    return $query->where('id', $inputs['inputPencilId'])
                                                        ->with('paymentHistory');
                                                }])->first();
        dd($incomeWithRelations);

        $paymentMethodBalance = $incomeWithRelations->balance;
        $income = $incomeWithRelations->incomes[0];


        $incomeAmount = $income->amount;

        $incomeData = [
            'id' => $inputs['inputPencilId'],
            'paymentMethod_id' => $inputs['method_id'],
            'date'             => $inputs['date'],
            'from'             => $inputs['from'],
            'amount'           => $inputs['amount']
        ];

        $income->update($incomeData);
        $income->save();

        $paymentHistory = $income->paymentHistory;

        $oldBalance = $paymentMethodBalance-$incomeAmount;
        $newBalance = $oldBalance + $inputs['amount'];

        $paymentHistoryData = [
            'balanceable_id'   => $inputs['inputPencilId'],
            'balanceable_type' => 'App\Models\Income',
            'payment_id'       => $inputs['method_id'],
            'date'             => $inputs['date'],
            'amount'           => $inputs['amount'],
            'balance_history'  => $newBalance
        ];

        $paymentHistory->update($paymentHistoryData);
        $paymentHistory->save();

        $paymentMethod = PaymentMethods::find($inputs['method_id']);

        $paymentMethod->balance = $newBalance;
        $paymentMethod->save();


        return redirect()->route('income');
    }
}
