<?php

namespace App\Listeners;

use App\Events\ExpenseDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RevertCalculationUserTotalAmount
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ExpenseDeleted  $event
     * @return void
     */
    public function handle(ExpenseDeleted $event)
    {
        //
    }
}
