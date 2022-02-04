<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function income()
    {
        $incomes = Income::where('from', 'admin')->get();
//        dd($incomes);
        return view('income', ['incomes' => $incomes]);
    }

    public function incomeInsert(Request $request)
    {
        $d = $_POST['income-insert'];
        dd($d);
    }
}
