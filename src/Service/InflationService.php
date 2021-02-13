<?php


namespace App\Service;


use App\Service\Interfaces\InflationServiceInterface;

class InflationService implements InflationServiceInterface
{
    public function budgetOnInflation($inflation_values, $budget)
    {
        $budget_values = []; $i=0;

        foreach($inflation_values as $inflation_value)
        {
            $budget_values[$i] = ($inflation_value * $budget) / $inflation_values[count($inflation_values)-1];
            $i++;
        }

        return $budget_values;
    }
}