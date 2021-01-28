<?php


namespace App\Service\Interfaces;


use Doctrine\ORM\EntityManagerInterface;

interface EntityServiceInterface
{
    /**
     * EntityServiceInterface constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function createEntity(&$entityManager): void;

    public function save(): void;
}