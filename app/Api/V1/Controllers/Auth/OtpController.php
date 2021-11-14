<?php

namespace App\Api\V1\Controllers\Auth;

use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use App\Api\V1\Services\Auth\OtpService\IOtpService;
use App\Api\V1\Services\Auth\OtpService\OtpService;
use App\Api\V1\Requests\Auth\Otp\RequestOtp;
use App\Api\V1\Requests\Auth\Otp\ValidateOtp;

class OtpController extends Controller
{
    /**
     * @var OtpService
     */
    private $otpService;

    /**
     * OtpController constructor
     *
     * @param IOtpService $otpService
     */
    public function __construct(IOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     *  @OA\Post(
     *      path="/auth/otp/request",
     *      operationId="requestOtp",
     *      tags={"Authentication"},
     *      summary="Request OTP.",
     *      description="Sends an OTP to the user's email address.",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="email"
     *                  ),
     *                  example={"email": "gladwell_n@live.com"}
     *              )
     *          )
     *      ),
     *      @OA\Response(response=201, description="OTP sent"),
     *      @OA\Response(response=403, description="Maximum OTP requests reached."),
     *      @OA\Response(response=422, description="Invalid email address"),
     *      @OA\Response(response=429, description="Too Many Requests"),
     *  )
     *
     *  Sends an OTP to user's email address
     *
     *  @param RequestOtp $request
     *
     *  @return Response
     */
    public function requestOtp(RequestOtp $request): Response
    {
        $this->otpService->sendOtp($request->email);

        return $this->response->noContent();
    }

    /**
     *  @OA\Post(
     *      path="/auth/otp/validate",
     *      operationId="validateOtp",
     *      tags={"Authentication"},
     *      summary="Validate OTP.",
     *      description="Validates the OTP provided by the user.",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="email"
     *                  ),
     *                  @OA\Property(
     *                      property="pin",
     *                      type="integer"
     *                  ),
     *                  example={"email": "gladwell_n@live.com", "pin": 123456 }
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="OTP valid"),
     *      @OA\Response(response=403, description="Invalid OTP"),
     *      @OA\Response(response=400, description="No active OTP"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=429, description="Too Many Requests"),
     *  )
     *
     *  Validates OTP
     *
     *  @param ValidateOtp $request
     *  @return Response
     */
    public function validateOtp(ValidateOtp $request): Response
    {
        $this->otpService->validateOtp($request->only('email', 'pin'));

        return $this->response->noContent();
    }
}
