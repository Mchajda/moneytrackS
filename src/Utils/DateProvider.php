<?php

namespace App\Utils;

class DateProvider
{
    public function getTodayDate(): array
    {
        return [
            'year' => date('Y'),
            'month' => date('m'),
            'day' => date('d'),
        ];
    }

    public function getLastUsedDate(string $date): array
    {
        return [
            'year' => explode('-', $date)[0],
            'month' => explode('-', $date)[1],
            'day' => explode('-', $date)[2],
        ];
    }
}