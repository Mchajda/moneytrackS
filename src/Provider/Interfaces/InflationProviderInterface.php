<?php


namespace App\Provider\Interfaces;


interface InflationProviderInterface
{
    public function getAll();
    public function getAllValues();
    public function getAllYears();
}