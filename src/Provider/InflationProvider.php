<?php


namespace App\Provider;


use App\Provider\Interfaces\InflationProviderInterface;
use App\Repository\InflationRepository;

class InflationProvider implements InflationProviderInterface
{
    private $repository;

    public function __construct(InflationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }

    public function getAllValues()
    {
        $values = [];
        $i=0;

        foreach($this->getAll() as $inflation)
        {
            $values[$i] = $inflation->getValue();
            $i++;
        }

        return $values;
    }

    public function getAllYears()
    {
        $years = [];
        $i=0;

        foreach($this->getAll() as $inflation)
        {
            $years[$i] = $inflation->getYear();
            $i++;
        }

        return $years;
    }
}