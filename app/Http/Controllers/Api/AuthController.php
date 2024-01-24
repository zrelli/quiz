<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\StoreUserRequest;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    use ApiRequestValidationTrait;
    public function register(Request $request)
    {
        $validationRequest = isMemberApiRoute() ? StoreUserRequest::class :  StoreMemberRequest::class;
        $input = $this->processRequest($request, $validationRequest);
        $input['password'] = Hash::make($request->password);
        $user = authModel()::create($input); //user or member model
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
    public function login(Request $request)
    {
        if (!currentAuthApiGuard()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized',
                'success' => false
            ], 401);
        }
        $user = authModel()::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success',
            'success' => true
        ]);
    }
}
