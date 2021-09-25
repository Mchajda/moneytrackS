<?php


namespace App\Provider;


use App\Entity\Category;
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
        $i = 0;
        $categories = $this->repository->findAll();

        foreach ($categories as $cat) {
            $categories_names[$i] = $cat->getCategoryName();
            $i++;
        }

        return $categories_names;
    }

    public function getCategoriesColors()
    {
        $categories_colors = [];
        $i = 0;
        $categories = $this->repository->findAll();

        foreach ($categories as $cat) {
            $categories_colors[$i] = $cat->getCategoryColor();
            $i++;
        }

        return $categories_colors;
    }

    public function getAllParentCategories(): array
    {
        return $this->repository->findBy(['parent_category' => null], ['sort_order' => 'ASC']);
    }

    public function getAllParentCategoriesNames(): array
    {
        $categories = $this->getAllParentCategories();
        $return = [];

        foreach ($categories as $cat) {
            $return[] = $cat->getCategoryName();
        }

        return $return;
    }

    public function getOneByName($category_name): ?Category
    {
        return $this->repository->findOneBy(['category_name' => $category_name]);
    }
}