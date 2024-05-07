<?php

return [
    'login' => env('ROBOKASSA_LOGIN', 'test_login'),
    'pass_1' => env('ROBOKASSA_PASS_1', 'test_pass1'),
    'pass_2' => env('ROBOKASSA_PASS_2', 'test_pass2'),
    'test_pass_1' => env('ROBOKASSA_TEST_PASS_1', 'test_pass1'),
    'test_pass_2' => env('ROBOKASSA_TEST_PASS_2', 'test_pass2'),
    'is_test' => env('ROBOKASSA_TEST', true),

    'log_driver' => 'stack',

    'log_during_testing' => false,
];