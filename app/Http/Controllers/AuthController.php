<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * User Login
     */
    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email',
                'password' => 'required',
            ];

            $this->validateRequestData($request->all(), $rules);
            $user = User::where('email', $request->email)->first();
            $userAuth = $user ? Hash::check($request->password, $user->password) : false;

            if ($userAuth) {
                Auth::login($user);
                $token = $user->createToken('auth_token')->plainTextToken;

                // response
                $response = [
                    'success' => true,
                    'message' => 'User login successfully',
                    'token' => [
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                    ],
                    'user' => $user,
                ];

                return response()->json($response, 200);
            } else {

                $response = [
                    'success' => false,
                    'message' => 'The email address or password is not valid',
                ];

                return response()->json($response, 404);
            }
        } catch (\Throwable $th) {
            logger($th);

            $response = [
                'success' => false,
                'error' => $th->getMessage(),
            ];

            return response()->json($response, 500);
        }
    }

    /**
     * Forgot password
     */
    public function forgotPassword(Request $request): JsonResponse
    {

        $rules = [
            'email' => 'required|email|exists:users,email',
        ];

        $this->validateRequestData($request->all(), $rules);

        $token = $this->createResetToken($request->email);

        $name = User::where('email', $request->email)->first()->name;

        $this->sendResetLink($name, $request->email, $token);

        $response = [
            'success' => true,
            'message' => 'A password reset link has been sent to your email',
        ];

        return response()->json($response, 200);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $rules = [
            'token' => 'required',
            'email' => 'required|email|exists:users,email|exists:password_reset_tokens,email',
            'password' => [
                'required',
                'string',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
            'password_confirmation' => 'required|string|confirmed',
        ];

        $data = (object) $this->validateRequestData($request->all(), $rules);

        $user = User::where('email', $data->email);

        $token = PasswordResetToken::where([
            ['email', $data->email],
            ['created_at', '>', now()->subHours(24)],
        ]);
        if (! $token->exists()) {

            $response = [
                'success' => false,
                'message' => 'The password reset link has expired',
            ];
            $status_code = 422;
        } else {
            $token = $token->first()->id;
            $user->password = Hash::make($data->password);
            $user->save();

            PasswordResetToken::destroy($token);
            $response = [
                'success' => true,
                'message' => 'Password reset successfully',
            ];
            $status_code = 200;
        }

        return response()->json($response, $status_code);
    }

    /**
     * User registration function
     */
    public function register(Request $request): JsonResponse
    {
        DB::beginTransaction();

        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ];

        $data = $this->validateRequestData($request->all(), $rules);

        $data['password'] = Hash::make($request->password);

        try {

            /**
             * @var User $user
             */
            $user = User::create($data);

            if ($user) {
                $data = [
                    'success' => true,
                    'message' => 'User account created succesfully, please check your email to activate your account',
                ];

                DB::commit();

                return response()->json($data, 201);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * Logout a user
     */
    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        $response = [
            'success' => true,
            'message' => 'Successfully logged out user',
        ];

        return response()->json($response, 200);
    }

    /**
     * Create password reset token
     */
    private function createResetToken(string $email): string
    {
        $token = bin2hex(random_bytes(45));

        PasswordResetToken::create([
            'email' => $email,
            'token' => $token,
        ]);

        return $token;
    }

    /**
     * Send password reset link email
     *
     * @return void
     */
    private function sendResetLink(string $name, string $email, string $token)
    {
        Mail::to($email)->send(new PasswordResetMail($name, $token));
    }
}
