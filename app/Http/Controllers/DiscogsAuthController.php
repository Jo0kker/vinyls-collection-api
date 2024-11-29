<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Passport\Database\Factories\ClientFactory;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Services\DiscogsService;

class DiscogsAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }

    public function redirect(Request $request)
    {
        try {
            if (!$request->hasValidSignature()) {
                throw new Exception('Invalid signature');
            }

            $userId = $request->get('user_id');
            $user = User::findOrFail($userId);

            $uniqid = $request->get('nonce');
            $discogs = new DiscogsService();
            $result = $discogs->getRequestToken($uniqid);

            Redis::setex(
                "discogs_auth:{$uniqid}",
                1800,
                json_encode([
                    'oauth_token_secret' => $result['oauth_token_secret'],
                    'user_id' => $user->id
                ])
            );

            return redirect("https://discogs.com/oauth/authorize?oauth_token={$result['oauth_token']}");
        } catch (Exception $e) {
            Log::error('Discogs auth error:', ['error' => $e->getMessage()]);
            return redirect(config('app.frontend_url') . '/profile?error=' . urlencode($e->getMessage()));
        }
    }

    public function callback(Request $request)
    {
        try {
            $nonce = $request->get('nonce');

            $authData = Redis::get("discogs_auth:{$nonce}");

            $authData = json_decode($authData, true);
            $user = User::findOrFail($authData['user_id']);
            $discogs = new DiscogsService();

            $accessTokenData = $discogs->getAccessToken(
                $request->oauth_token,
                $authData['oauth_token_secret'],
                $request->oauth_verifier
            );

            $identity = $discogs->getIdentityWithOAuth(
                $accessTokenData['oauth_token'],
                $accessTokenData['oauth_token_secret']
            );

            $userData = $discogs->getUserData(
                $accessTokenData['oauth_token'],
                $accessTokenData['oauth_token_secret'],
                $identity['username']
            );

            $user->update([
                'discogs_id' => $identity['id'],
                'discogs_username' => $identity['username'],
                'discogs_token' => $accessTokenData['oauth_token'],
                'discogs_token_secret' => $accessTokenData['oauth_token_secret'],
                'discogs_avatar' => $userData['avatar_url'] ?? null,
                'discogs_data' => json_encode($userData)
            ]);

            $user->refresh();

            if (!$user->discogs_id || !$user->discogs_token) {
                throw new Exception('User data not properly saved');
            }

            Redis::del("discogs_auth:{$nonce}");

            return redirect(config('app.frontend_url') . '/settings?link=true');
        } catch (Exception $e) {
            Log::error('Discogs callback error:', ['error' => $e->getMessage()]);
            return redirect(config('app.frontend_url') . '/settings?' . http_build_query([
                'error' => $e->getMessage()
            ]));
        }
    }
}
