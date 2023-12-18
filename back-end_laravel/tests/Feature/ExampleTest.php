<?php

namespace Tests\Feature;

use function Pest\Laravel\get;

it(
    $description . 'has a welcom page',
    function () {
        get(uri: '/')->assertOk();
    }
);
