<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user-registered', function () {
    return true;
});
