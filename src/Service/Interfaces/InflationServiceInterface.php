<?php


namespace App\Service\Interfaces;


interface InflationServiceInterface
{
    public function budgetOnInflation($inflation_values, $budget);
}