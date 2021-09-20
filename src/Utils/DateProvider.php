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
}