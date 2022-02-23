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

        dd($paymentHistory);



//        $expenseAmount = $expense->pluck('expense_amount');
//        $expenseTotal = $expenseAmount->sum();
//
//        $income = Income::pluck('amount');
//        $incomeTotal = $income->sum();
//
//        $balance = $incomeTotal-$expenseTotal;
//
//
//        $recordBalance = PaymentHistory::create(['expense_id' => $expense[0]->id, 'date' => $inputs['date'], 'balance' => $balance]);

        return redirect()->route('expense', ['expense' => $expense, 'balance' => $recordBalance]);
    }

    public function expenseDelete($id)
    {
        $expense = Expense::find($id);
        if (!$expense)
        {
            return $this->sendError(['message' => 'Expense with this id was not found'],422);
        }
        $balances = PaymentHistory::get();
        $latestBalance = $balances->last();
        $balance = $latestBalance->balance + $expense->expense_amount;
        $expense->delete();
        $expense->get();
        $date = new \DateTime();
        $newBalance = PaymentHistory::create(['date' => $date, 'balance' => $balance]);

        return redirect()->route('expense', ['expense' => $expense, 'balance' => $newBalance]);
    }

    public function expenseUpdate(Request $request)
    {
        $inputs = $request->all();
//        dd($inputs);

        $validator = Validator::make($inputs,[
            'date'        => 'required|date',
            'from'        => 'required|string',
            'category_id' => 'required|numeric',
            'flow_id'        => 'required|numeric',
            'amount'      => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $expenses = Expense::with('balance');
        $expense = $expenses->find($inputs['inputPencilId']);

        $oldBalance = $expense->balance->balance;
        $oldExpence = $expense->expense_amount;
        $originalBalance = $oldBalance + $oldExpence;
        $newBalance = $originalBalance - $inputs['amount'];
        $balance = PaymentHistory::where('expense_id', $inputs['inputPencilId'])->first();
        $balance->balance=$newBalance;
        $balance->save();
        $dataSend = $balance->balance;


        $expense->category_id = $inputs['category_id'];
        $expense->item_id = $inputs['item_id'];
        $expense->date = $inputs['date'];
        $expense->responsible_person = $inputs['from'];
        $expense->expense_amount = $inputs['amount'];
        $expense->flow_id = $inputs['flow_id'];
        $expense->save();

        $expense->get();
        return redirect()->route('expense', ['expense' => $expense, 'balance' => $dataSend]);
    }
}
