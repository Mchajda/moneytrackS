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

    public function countPreviousMonth($year, $month): array
    {
        $this_year = $year;
        $this_month = $month;

        if ($this_month == "01") //zabezpieczenie w przypadku previous month przypadku grudzien-styczen
        {
            $previous_month = "12";
            $previous_year = strval($this_year - 1);
        } else {
            $previous_month = date('m', strtotime("1-".($this_month-1)."-1"));
            $previous_year = $this_year;
        }

        return [
            'this_month' => [
                'year' => $this_year,
                'month' => $this_month,
            ],
            'previous_month' => [
                'year' => $previous_year,
                'month' => $previous_month
            ]
        ];
    }
}