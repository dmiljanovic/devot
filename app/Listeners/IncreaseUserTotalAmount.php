<?php

namespace App\Listeners;

use App\Events\ExpenseCreated;
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
     * @param ExpenseCreated $event
     * @return void
     */
    public function handle(ExpenseCreated $event): void
    {
        $this->userRepository->increaseTotalAmount($event->expense);
    }
}
