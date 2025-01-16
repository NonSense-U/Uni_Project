<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Method to handle user authentication and token generation
    public function generateToken(Request $request)
    {

        $request->validate([
            'email' => ['required_without:phoneNumber', 'string', 'email'],
            'phoneNumber'=>['required_without:email','string'], //! Update Later
            'password' => ['required', 'string'],
        ]);
        $identifier = null;

        if($request->email!=null)
        {
            $identifier = 'email';
        }
        else
        {
            $identifier = 'phoneNumber';
        }

        $user = User::where($identifier, $request->$identifier)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                $identifier => ['The provided credentials are incorrect.'],
            ]);
        }
        Auth::login($user);

        $token = $user->createToken('my-app-token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    // Method to handle user logout and token revocation
    public function logout(Request $request)
    {
		// Revoke all tokens...
		$request->user()->tokens()->delete();

		// Revoke the current token
		$request->user()->currentAccessToken()->delete();

		return response()->json(['message' => 'You have been successfully logged out.'], 200);
    }
}
