<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

use App\Http\Resources\UserResource;

use App\Traits\VerificationTrait;

class VerificationController extends Controller
{
    use VerificationTrait;
    
    public function checkifverified(Request $request){
        if ($request->user()->hasVerifiedEmail()) {
            return response(["isVerified" => true]);
        }
        return response(["isVerified" => false]);
    }

    public function verify($user_id, Request $request) {
        if ($this->signatureExpired($request) || !$this->isValidRoute($request, $id)) {
            return response(["message" => "Invalid/Expired url."], 401);
        }

        $user = User::byHashOrFail($id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        $this->logInfo('User verified email using verification link.', 'Account Verification', $this->ACTION_UPDATE, new UserResource($user));

        return response(['message' => 'Email Verification successful!']);
    }

    public function resend(Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return response([
                "message" => "Email already verified.",
                "isVerified" => true
            ]);
        }

        $request->user()->sendEmailVerification();

        $user = auth()->user();

        $this->logInfo('User requested new email verification link', 'Account Verification', $this->ACTION_SEND_EMAIL, new UserResource($user));

        return response([
            "message" => "Email verification link sent to your email.",
        ]);
    }
}
