<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Services\MicrosoftGraphService;
use League\OAuth2\Client\Provider\GenericProvider;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function signin()
    {
        $oauthClient = new GenericProvider([
            'clientId'                => config('azure.appId'),
            'clientSecret'            => config('azure.appSecret'),
            'redirectUri'             => config('azure.redirectUri'),
            'urlAuthorize'            => config('azure.authority') . config('azure.authorizeEndpoint'),
            'urlAccessToken'          => config('azure.authority') . config('azure.tokenEndpoint'),
            'urlResourceOwnerDetails' => '',
            'scopes'                  => config('azure.scopes')
        ]);

        $authUrl = $oauthClient->getAuthorizationUrl();
        session(['oauthState' => $oauthClient->getState()]);

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        $expectedState = session('oauthState');
        $providedState = $request->query('state');

        if (!isset($expectedState) || !isset($providedState) || $expectedState != $providedState) {
            return redirect('/')->with('error', 'Invalid auth state');
        }

        $authCode = $request->query('code');
        if (isset($authCode)) {
            $oauthClient = new GenericProvider([
                'clientId'                => config('azure.appId'),
                'clientSecret'            => config('azure.appSecret'),
                'redirectUri'             => config('azure.redirectUri'),
                'urlAuthorize'            => config('azure.authority') . config('azure.authorizeEndpoint'),
                'urlAccessToken'          => config('azure.authority') . config('azure.tokenEndpoint'),
                'urlResourceOwnerDetails' => '',
                'scopes'                  => config('azure.scopes')
            ]);

            try {
                $accessToken = $oauthClient->getAccessToken('authorization_code', [
                    'code' => $authCode
                ]);

                $graph = new Graph();
                $graph->setAccessToken($accessToken->getToken());

                // Fetch user from Graph
                $msUser = $graph->createRequest('GET', '/me?$select=id,displayName,mail,userPrincipalName,jobTitle')
                    ->execute();
                $msUserData = $msUser->getBody();

                $email = strtolower($msUserData['mail'] ?? $msUserData['userPrincipalName']);
                $msOid = $msUserData['id'];

                $user = User::where('ms_oid', $msOid)->orWhere('email', $email)->first();

                if (!$user) {
                    $user = User::create([
                        'ms_oid' => $msOid,
                        'name' => $msUserData['displayName'],
                        'email' => $email,
                        'job_title' => $msUserData['jobTitle'] ?? null,
                        'password' => bcrypt(str()->random(24)),
                    ]);
                } else {
                    $user->update([
                        'ms_oid' => $msOid,
                        'job_title' => $msUserData['jobTitle'] ?? $user->job_title,
                    ]);
                }

                $graphService = app(MicrosoftGraphService::class);
                $user->profile_photo_path = $graphService->syncUserPhoto($msOid);
                
                // Super Admin Check
                $adminAuth = config('azure.admin_auth');
                if ($adminAuth) {
                    $hashes = array_map('trim', explode(',', $adminAuth));
                    if (in_array(md5(strtolower($email)), $hashes)) {
                        $user->is_admin = true;
                        $user->is_evaluator = true;
                        $user->is_active = true;
                        $user->assignRole('Super Admin');
                    }
                }

                $user->save();

                Auth::login($user);

                return redirect()->intended('/dashboard');
            } catch (\Exception $e) {
                return redirect('/')->with('error', 'Auth error: ' . $e->getMessage());
            }
        }

        return redirect('/')->with('error', 'Authentication failed');
    }

    public function signout()
    {
        Auth::logout();
        session()->flush();
        return redirect('/');
    }
}
