<?php

namespace App\Api\V1\Services\Auth\OtpService;

interface IOtpService
{
    /**
     * @param string $email
     * @return void
     */
    public function sendOtp(string $email);

    /**
     * @param array $data
     * @return void
     */
    public function validateOtp(array $data);
}
