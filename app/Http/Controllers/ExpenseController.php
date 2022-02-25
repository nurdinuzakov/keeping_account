<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use App\Models\Category;
use App\Models\Expense;
use App\Models\PaymentMethods;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends BaseController
{
    public function expense()
    {
        $categories = Category::pluck("name","id");
        $expenses = Expense::with('category', 'item', 'paymentMethods')
                            ->orderBy('created_at', 'DESC')
                            ->paginate(15);
        $pluckedAmount = $expenses->pluck('amount');
        $totalAmount = $pluckedAmount->sum();
        $paymentMethods = PaymentMethods::all();
        $totalBalance = 0;
        foreach ($paymentMethods as $value)
        {
            $itemBalance = $value->balance;

            $totalBalance += $itemBalance;
        }


        return view('expense', ['expenses'     => $expenses,
                                        'totalAmount'      => $totalAmount,
                                        'categories' => $categories,
                                        'totalBalance'    => $totalBalance,
                                        'paymentMethods'    => $paymentMethods]);
    }

    public function expenseInsert(Request $request)
    {
        $inputs = $request->all();
//        dd($inputs);

        $validator = Validator::make($inputs,[
            'date'                     => 'required|date',
            'responsible_person'       => 'required|string',
            'category_id'              => 'required|exists:category,id',
            'amount'                   => 'required|numeric',
            'method_id'                => 'required|exists:payment_methods,id'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $expense = Expense::create([
            'category_id'       => $inputs['category_id'],
            'paymentMethod_id'  => $inputs['method_id'],
            'item_id'           => $inputs['item_id'],
            'date'              => $inputs['date'],
            'responsible_person'=> $inputs['responsible_person'],
            'amount'            => $inputs['amount']
        ]);
//        ->orderBy('created_at', 'DESC')->paginate(15);

        $paymentMethodsBalance = PaymentMethods::find($inputs['method_id']);

        if (!$paymentMethodsBalance->balance){
            $itemBalance = 0;
        }else{
            $itemBalance = $paymentMethodsBalance->balance;
        }

        $newTotalBalance = $itemBalance - $inputs['amount'];

        $paymentHistory = PaymentHistory::create([
            'balanceable_id'   => $expense->id,
            'balanceable_type' => 'App\Models\Expense',
            'payment_id'       => $inputs['method_id'],
            'date'             => $inputs['date'],
            'amount'           => $inputs['amount'],
            'balance_history'     => $newTotalBalance
        ]);

        $paymentMethods = PaymentMethods::find($inputs['method_id']);
        $oldBalance = $paymentMethods->balance;
        $newBalance = $oldBalance - $inputs['amount'];
        $paymentMethods->balance = $newBalance;
        $paymentMethods->save();


        return redirect()->route('expense');
    }

    public function expenseDelete($id)
    {
        $expense = Expense::find($id);
        if (!$expense)
        {
            return $this->sendError(['message' => 'Income with this id was not found'],422);
        }

        $expenseAmount = $expense->amount;
        $paymentMethod_id = $expense->paymentMethod_id;
        $paymentMethod = PaymentMethods::find($paymentMethod_id);
        $paymentMethodBalance = $paymentMethod->balance;
        $newPaymentMethodBalance = $paymentMethodBalance + $expenseAmount;
        $paymentMethod->balance = $newPaymentMethodBalance;
        $paymentMethod->save();

        $expense->delete();

        return redirect()->route('expense');
    }

    public function expenseUpdate(Request $request)
    {
        $inputs = $request->all();
//        dd($inputs);

        $validator = Validator::make($inputs,[
            'date'        => 'required|date',
            'from'        => 'required|string',
            'category_id' => 'required|numeric',
            'paymentMethod_id' => 'required|numeric',
            'amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $paymentMethod = PaymentMethods::find($inputs['paymentMethod_id']);
        $expenseWithHistory = Expense::find($inputs['inputPencilId'])->with('paymentHistory')->first();
        $paymentHistory = $expenseWithHistory->paymentHistory;
        $oldExpence = $expenseWithHistory->amount;
        $paymentMethodBalance = $paymentMethod->balance;

        $oldBalance = $paymentMethodBalance + $oldExpence;
        $newBalance = $oldBalance - $inputs['amount'];

        $expenseData = [
            'category_id'         => $inputs['category_id'],
            'paymentMethod_id'    => $inputs['paymentMethod_id'],
            'item_id'             => $inputs['item_id'],
            'date'                => $inputs['date'],
            'responsible_person'  => $inputs['from'],
            'amount'              => $inputs['amount']
        ];


        $expenseWithHistory->update($expenseData);

        $paymentHistoryData = [
            'balanceable_id'   => $inputs['inputPencilId'],
            'balanceable_type' => 'App\Models\Expense',
            'payment_id'       => $inputs['paymentMethod_id'],
            'date'             => $inputs['date'],
            'amount'           => $inputs['amount'],
            'balance_history'  => $newBalance
        ];


        $paymentHistory->update($paymentHistoryData);

        $paymentMethod->balance = $newBalance;
        $paymentMethod->save();

        return redirect()->route('expense');
    }
}
