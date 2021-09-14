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

    public function getAllCategoriesNames()
    {
        $categories_names = [];
        $i=0;
        $categories = $this->repository->findAll();

        foreach($categories as $cat)
        {
            $categories_names[$i] = $cat->getCategoryName();
            $i++;
        }

        return $categories_names;
    }

    public function getCategoriesColors()
    {
        $categories_colors = [];
        $i=0;
        $categories = $this->repository->findAll();

        foreach($categories as $cat)
        {
            $categories_colors[$i] = $cat->getCategoryColor();
            $i++;
        }

        return $categories_colors;
    }

    public function getAllParentCategories(): array
    {
        $categories = $this->repository->findAll();
        $parents = [];

        foreach($categories as $cat)
        {
            if ($cat->getParentCategory() == null) {
                $parents[] = $cat;
            }
        }

        return $parents;
    }
}