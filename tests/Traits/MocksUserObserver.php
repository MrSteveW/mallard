<?php

namespace Tests\Traits;

use App\Observers\UserObserver;

trait MocksUserObserver
{
    public function setUpMocksUserObserver(): void
    {
        $this->mock(UserObserver::class)->shouldIgnoreMissing();
    }
}
