<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AccountWidget extends Widget
{
    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'admin.widgets.account-widget';
}
