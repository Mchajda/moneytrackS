<?php


namespace App\Service\Interfaces;


interface ExportServiceInterface
{
    public function exportToJson($transactions);
}