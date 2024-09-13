<?php

namespace App\GraphQL\Mutations;

use App\Models\Income as Incomes;
use App\Models\Expense as Expenses;
use Carbon\Carbon;

final class Transaction
{
    /**
     * ShowDataTransactions
     * @param  null  $_
     * @param  array{}  $args
     */

    public function ShowDataTransactions($root, array $args)
    {
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
