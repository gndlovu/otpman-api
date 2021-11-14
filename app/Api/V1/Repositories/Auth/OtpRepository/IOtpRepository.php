<?php

namespace App\Api\V1\Repositories\Auth\OtpRepository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface OtpRepository
 *
 * @package App\Api\V1\Repositories\Auth\OtpRepository
 */
interface IOtpRepository
{
    /**
     * @param string $email
     * @return Model|null
     */
    public function getLastOtp(string $email): ?Model;

    /**
     * @return Collection
     */
    public function sinceHourAgo(): Collection;

    /**
     *
     * @param Model $otp
     * @return bool
     */
    public function isGeneratedTwentyFourHourAgo(Model $otp): bool;
}
