<?php

namespace App\Api\V1\Services\Auth\OtpService;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Api\V1\Services\Auth\OtpService\IOtpService;
use App\Api\V1\Repositories\Auth\OtpRepository\IOtpRepository;
use App\Api\V1\Repositories\Auth\OtpRepository\OtpRepository;
use Log;

class OtpService implements IOtpService
{
    /**
     * @var OtpRepository
     */
    private $otpRepo;

    /**
     * OtpService constructor
     *
     * @param IOtpRepository $otpRepository
     */
    public function __construct(
        IOtpRepository $otpRepository
    ) {
        $this->otpRepo = $otpRepository;
    }

    /**
     * @param string $email
     *
     * @return void
     */
    public function sendOtp($email): void
    {
        $otp = $this->otpRepo->getLastOtp($email);
        if (!$otp) {
            $otp = $this->createOtpRecord($email);

            Log::info('no otp found: generate a new one');
        } else {
            // A user cannot request more than X OTPs per hour. X is a config variable set to 3 for now, we should be able to change this easily.
            $maxReqReached = $this->otpRepo->sinceHourAgo()->count() > env('OTP_MAX_REQ_PER_HR');
            if ($maxReqReached) {
                Log::info('Maximum (' . env('OTP_MAX_REQ_PER_HR') . ') OTP requests reached.');

                abort(403, 'Maximum OTP requests reached. Please try again later.');
            }

            // If a user requests the OTP to be resent within X minutes, it will resend the original OTP (and
            // update the expiry) instead of generating a new one. X is a config variable set to 5 for now.
            Log::debug('Created mim ago: ' . $otp->generated_at->diffInMinutes(Carbon::now()));
            Log::debug('OTP_RESEND_MIN_INTERVAL: ' . env('OTP_RESEND_MIN_INTERVAL'));

            if ($otp->generated_at->diffInMinutes(Carbon::now()) < env('OTP_RESEND_MIN_INTERVAL')) {
                $otp->expires_at = Carbon::now()->addSeconds(env('OTP_EXPIRE_SEC'));
                $otp->save();

                Log::info('OTP resent within ' . env('OTP_RESEND_MIN_INTERVAL') . ' minutes, update expires_at');
            }

            // An OTP cannot be resent more than X times. X is a config variable set to 3 for now.
            if ($otp->resent_count >= env('OTP_MAX_RESEND')) {
                $otp = $this->createOtpRecord($email);

                Log::info('Maximum (' . env('OTP_MAX_RESEND') . ') OTP resend exceeded, generate a new one');
            } else {
                $otp->resent_count += 1;
                $otp->save();
                Log::info('Update resend count');
            }

            // A user cannot receive the same OTP number if it's within a 24 hour period. If that happens, a
            // new one should be generated and the user should not be made aware that the same one was
            // randomly generated.
            do {
                $isGenerated = $this->otpRepo->isGeneratedTwentyFourHourAgo($otp);
                if ($isGenerated) {
                    Log::info('PIN (' . $otp->pin . ') was generated 24 hours ago, generate a new one.');
                    $otp = $this->createOtpRecord($email);
                }
            } while ($isGenerated);

            Log::info('sending PIN ' . $otp->pin);
            // TODO - Send email notification
        }
    }

    /**
     * @param string $email
     * @return Model
     */
    private function createOtpRecord($email): Model
    {
        return $this->otpRepo->create([
            'email' => $email,
            'pin' => $this->generatePin(),
            'generated_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addSeconds(env('OTP_EXPIRE_SEC')),
        ]);
    }

    private function generatePin(): int
    {
        // TODO - Make sure it can start with 0
        return random_int(100000, 999999);
    }
}
