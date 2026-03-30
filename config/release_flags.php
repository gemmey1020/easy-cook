<?php

return [
    'invitation_public_enabled' => filter_var(
        env('INVITATION_PUBLIC_ENABLED', in_array(env('APP_ENV'), ['local', 'testing'], true)),
        FILTER_VALIDATE_BOOL
    ),
    'coupon_public_enabled' => filter_var(
        env('COUPON_PUBLIC_ENABLED', in_array(env('APP_ENV'), ['local', 'testing'], true)),
        FILTER_VALIDATE_BOOL
    ),
];
