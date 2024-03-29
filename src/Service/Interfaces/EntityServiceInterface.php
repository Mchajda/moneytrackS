<?php


namespace App\Service\Interfaces;


use Doctrine\ORM\EntityManagerInterface;

interface EntityServiceInterface
{
    /**
     * EntityServiceInterface constructor.
     * @param EntityManagerInterface $entity
     */
    public function createEntity(&$entity): void;

    public function save(): void;
}