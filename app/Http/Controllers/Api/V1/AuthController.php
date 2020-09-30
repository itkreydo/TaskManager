<?php


namespace App\Http\Controllers\Api\V1;


use App\Exceptions\BadApiRequestException;
use App\Services\KeyService\KeyStore;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class AuthController
{
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->with('avatar')->with('post')->with('settings')->first();
        if (!$user || !Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            throw ValidationException::withMessages([
                'email' => ['Указан неверный логин или пароль'],
            ]);
        }

        $result['two_fa_auth'] = true;
        if (!($user->settings->secret_two_fa ?? false)) {
            $result = $user;
            $result['two_fa_auth'] = false;
            $user['token'] = $user->createToken("go")->plainTextToken;
        } else {
            $payload = [
                'access_type' => 'two-fa',
                'user_id' => $user->id,
            ];
            $signedToken = JWT::encode($payload, KeyStore::getKeys()->getPrivateKey(), 'RS256');
            $result['token'] = $signedToken;
        }

        return $result;

    }

    public function checkTwoFaCode(Request $request) {
        $code = $request->get('code');
        $token = $request->get('token');

        $data = JWT::decode($token, KeyStore::getKeys()->getPublicKey(), array('RS256'));
        $user = User::with('avatar')->with('post')->with('settings')->find($data->user_id);

        $googleAuth = new GoogleAuthenticator();

        if (!$code || !$googleAuth->checkCode($user->settings->secret_two_fa, $code)) {
            throw new BadApiRequestException('Неверный код подтверждения');
        }

        $result = $user;
        $result['two_fa_auth'] = true;
        $result['token'] = $user->createToken("go")->plainTextToken;
        return response()->json($result);


    }

    public function logout(Request $request) {
        Auth::guard('web')->logout();
        return response('');
    }
}
