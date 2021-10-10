<?php


namespace App\RequestProcessor;


use App\Entity\Expense;
use App\Factory\ExpenseFactory;
use App\Provider\Interfaces\CategoryProviderInterface;
use App\RequestProcessor\Interfaces\ExpenseRequestProcessorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ExpenseRequestProcessor implements ExpenseRequestProcessorInterface
{
    private CategoryProviderInterface $categoryProvider;

    /**
     * @param CategoryProviderInterface $categoryProvider
     */
    public function __construct(CategoryProviderInterface $categoryProvider)
    {
        $this->categoryProvider = $categoryProvider;
    }

    public function create(array $params, UserInterface $user): Expense
    {
        $category = $this->categoryProvider->getOneByName($params['category']);

        $user_id = $user->getId();
        $title = $params['title'];
        $date = $params['date'];
        $recipient = $params['recipient'];
        $amount = $params['amount'];
        $amIPayer = $params['payer'];

        return ExpenseFactory::create(
            $user_id,
            $title,
            $date,
            $category,
            $recipient,
            $amount,
            $amIPayer
        );
    }
}