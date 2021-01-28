<?php


namespace App\Provider;


use App\Provider\Interfaces\CategoryProviderInterface;
use App\Repository\CategoryRepository;

class CategoryProvider implements CategoryProviderInterface
{
    private $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCategories()
    {
        return $this->repository->findAll();
    }
}