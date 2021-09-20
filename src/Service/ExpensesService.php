<?php


namespace App\Service;


use App\Entity\Expense;
use App\Service\Interfaces\ExpensesServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class ExpensesService extends EntityService implements ExpensesServiceInterface
{
    public function __construct(EntityManagerInterface $entity_manager)
    {
        parent::__construct($entity_manager, Expense::class);
    }

    public function create(Expense &$expense): void
    {
        $this->entity_manager->persist($expense);
        $this->entity_manager->flush();
    }
}