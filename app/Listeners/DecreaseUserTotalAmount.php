<?php

namespace App\Listeners;

use App\Events\ExpenseCreated;
use App\Repositories\BillRepository;
use App\Repositories\UserRepository;

class DecreaseUserTotalAmount
{
    private UserRepository $userRepository;
    private BillRepository $billRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, BillRepository $billRepository)
    {
        $this->userRepository = $userRepository;
        $this->billRepository = $billRepository;
    }

    /**
     * Handle the event.
     *
     * @param ExpenseCreated $event
     * @return void
     */
    public function handle(ExpenseCreated $event): void
    {
        $this->userRepository->decreaseTotalAmount($event->expense);
        $this->billRepository->increaseAmount($event->expense);
    }
}
