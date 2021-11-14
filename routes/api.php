<?php

use App\Api\V1\Controllers\Auth\OtpController;

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['middleware' => 'api'], function ($api) {
    $api->group(['prefix' => 'auth'], function ($api) {
        $api->group(['prefix' => 'otp'], function ($api) {
            $api->post('request', [OtpController::class, 'requestOtp'])->name('otp.request');
            $api->post('validate', [OtpController::class, 'validateOtp'])->name('otp.validate');
        });
    });

    $api->get('/hello', function () {
        return [
            'gladwell' => 'A Full Stack Developer (\' \',)?'
        ];
    });
});
