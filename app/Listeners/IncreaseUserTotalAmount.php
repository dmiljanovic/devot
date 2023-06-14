<?php

namespace App\Listeners;

use App\Events\ExpenseDeleted;
use App\Repositories\UserRepository;

class IncreaseUserTotalAmount
{
    private UserRepository $userRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     *
     * @param ExpenseDeleted $event
     * @return void
     */
    public function handle(ExpenseDeleted $event): void
    {
        $this->userRepository->increaseTotalAmount($event->expense);
    }
}
