<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethods;
use Illuminate\Support\Facades\Validator;

class PaymentMethodsController extends BaseController
{
    public function createPaymentMethods(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'     => 'required|unique:payment_methods|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $paymentMethods = PaymentMethods::create([
            'name' => $request->get('name')
        ]);

        return redirect()->route('payment-methods', ['payment_methods' => $paymentMethods]);
    }

    public function paymentMethods()
    {
        $paymentMethods = PaymentMethods::all();

        return view('paymentMethods', ['paymentMethods' => $paymentMethods]);
    }

    public function deletePaymentMethod(PaymentMethods $method)
    {
        $method->delete();

        return redirect()->route('payment-methods');
    }

    public function updatePaymentMethod(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'method_id' => 'required|integer',
            'name'     => 'required|unique:payment_methods|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $method = PaymentMethods::find($request->get('method_id'));

        $method->name = $request->get('name');
        $method->save();

        return redirect()->route('payment-methods');
    }
}
