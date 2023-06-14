<?php

namespace App\Listeners;

use App\Events\ExpenseCreated;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CalculateUserTotalAmount
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
     * @param  \App\Events\ExpenseCreated  $event
     * @return void
     */
    public function handle(ExpenseCreated $event)
    {
        $this->userRepository->updateAmount($event->expense);
    }
}
