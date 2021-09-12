<?php


namespace App\Provider\Interfaces;


interface CategoryProviderInterface
{
    public function getAllCategories();
    public function getAllCategoriesNames();
    public function getCategoriesColors();
}