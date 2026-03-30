<?php

return [
    'admin_enabled' => env('COUPON_V2_ADMIN_ENABLED', in_array(env('APP_ENV'), ['local', 'testing'], true)),
    'auto_code_length' => (int) env('COUPON_V2_AUTO_CODE_LENGTH', 6),
];
