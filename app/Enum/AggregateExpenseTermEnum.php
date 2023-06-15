<?php

namespace App\Enum;

enum AggregateExpenseTermEnum: string
{
    case LAST_MONTH = 'last_month';
    case LAST_QUARTER = 'last_quarter';
    case LAST_YEAR = 'last_year';
    case THIS_YEAR = 'this_year';
}
