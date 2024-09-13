<?php

namespace App\GraphQL\Mutations;

use App\Models\Income as Incomes;
use App\Models\Expense as Expenses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

final class Transaction
{
    /**
     * ShowDataTransactions
     * @param  null  $_
     * @param  array{}  $args
     */

    public function ShowDataTransactions($root, array $args)
    {
        $validator = Validator::make($args, [
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|digits:4',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }


        $month = $args['month'];
        $year = $args['year'];


        $incomes = Incomes::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('del_flag', false)
            ->get();
        $expenses = Expenses::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('del_flag', false)
            ->get();


        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        $transactionsByDay = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day)->toDateString();

            $dayIncomes = $incomes->where('date', $date)->values();
            $dayExpenses = $expenses->where('date', $date)->values();

            $transactionsByDay[] = [
                'date' => $date,
                'incomes' => $dayIncomes,
                'expenses' => $dayExpenses,
            ];
        }

        return $transactionsByDay;
    }
}
