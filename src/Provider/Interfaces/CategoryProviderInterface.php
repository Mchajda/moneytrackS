<?php


namespace App\Provider\Interfaces;


use App\Entity\Category;

interface CategoryProviderInterface
{
    public function getAllCategories();

    public function getAllCategoriesNames();

    public function getCategoriesColors();

    public function getAllParentCategories(): array;

    public function getOneByName($category_name): ?Category;
}