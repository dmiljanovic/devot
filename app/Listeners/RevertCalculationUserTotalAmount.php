<?php

namespace App\Listeners;

use App\Events\BillDeleted;
use App\Repositories\UserRepository;

class RevertCalculationUserTotalAmount
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
     * @param  \App\Events\BillDeleted  $event
     * @return void
     */
    public function handle(BillDeleted $event): void
    {
        $this->userRepository->increaseTotalAmount($event->bill);
    }
}
