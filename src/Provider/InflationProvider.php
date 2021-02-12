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
}