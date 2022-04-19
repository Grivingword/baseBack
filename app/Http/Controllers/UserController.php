<?php

namespace App\Http\Controllers;

use App\Helper\ConnectionHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Psr\Log\LoggerInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    protected $queryLibrary;
    protected $default;
    protected $log;

    public function __construct(
        QueryController $queryLibrary,
        LoggerInterface $logger
    )
    {
        $this->default = env('DB_DATABASE');
        ConnectionHelper::createDataBaseConnection($this->default);
        $this->queryLibrary = $queryLibrary;
        $this->log = $logger;
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only("email", "password");
            $validator = Validator::make($credentials, [
                'email' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response' => false,
                    'errors' => $validator->errors(),
                    'code' => 422
                ], 422);
            }
            $token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password]);

            if (!$token) {
                return response()->json(['status' => false, 'message' => 'Unauthorized', 'code' => 401], 401);
            }

            $data = JWTAuth::user();
            $userData = User::where('id', $data->id)->first();

            return response()->json([
                'status' => true,
                'message' => 'Bienvenido',
                'userData' => $userData,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL()

            ]);
        } catch (\Throwable $e) {
            logger('Auth exception: ' . $e);
            return response()->json(['status' => false, 'message' => 'Internal server error', 'code' => 500], 500);
        }
    }

    public function logout(Request $request)
    {
        try {

            JWTAuth::invalidate($request->token);

            return response()->json([
                'status' => true,
                'message' => 'Logout',
            ]);

        } catch (\Throwable $e) {
            logger('Auth exception: ' . $e);
            return response()->json(['status' => false, 'message' => 'Internal server error', 'code' => 500], 500);
        }
    }

}
