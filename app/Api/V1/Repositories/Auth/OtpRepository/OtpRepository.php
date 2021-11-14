<?php

namespace App\Api\V1\Repositories\Auth\OtpRepository;

use App\Api\V1\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use App\Models\Otp;

class OtpRepository extends BaseRepository implements IOtpRepository
{
    /**
     * OtpRepository constructor.
     *
     * @param Otp $model
     */
    public function __construct(Otp $model)
    {
        parent::__construct($model);
    }

    public function getLastOtp($email): ?Model
    {
        return $this->model::where('email', $email)->latest('generated_at')->first();
    }

    public function sinceHourAgo(): Collection
    {
        return $this->model::where('generated_at', '>=', Carbon::now()->subHour())->get();
    }

    public function isGeneratedTwentyFourHourAgo($otp): bool
    {
        return $this->model::where('id', '!=', $otp->id)
            ->where('pin', $otp->pin)
            ->where('generated_at', '>=', Carbon::now()->subHours(24))->count();
    }
}
