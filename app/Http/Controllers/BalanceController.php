<?php

namespace App\Http\Controllers;

use App\Models\PaymentHistory;
use App\Models\PaymentMethods;
use Illuminate\Http\Request;

class BalanceController extends BaseController
{
    public function balance()
    {
        $paymentHistory = PaymentHistory::with('balanceable.paymentMethods')->orderBy('created_at', 'DESC')->paginate(15);

        $paymentMethods = PaymentMethods::all();
        $totalBalance = 0;
        foreach ($paymentMethods as $value)
        {
            $itemBalance = $value->balance;

            $totalBalance += $itemBalance;
        }

        return view('balance', ['paymentHistory' => $paymentHistory, 'totalBalance'    => $totalBalance, 'paymentMethods'    => $paymentMethods]);
    }
}
