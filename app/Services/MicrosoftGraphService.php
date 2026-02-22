<?php

namespace App\Services;

use Microsoft\Graph\Graph;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MicrosoftGraphService
{
    protected $accessToken;

    public function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $client = new Client();
        $tenantId = str_replace(['https://login.microsoftonline.com/', '/oauth2/v2.0/authorize'], '', config('azure.authority'));
        $tenantId = trim($tenantId, '/');

        $url = "https://login.microsoftonline.com/" . $tenantId . "/oauth2/v2.0/token";

        try {
            $response = $client->post($url, [
                'form_params' => [
                    'client_id' => config('azure.appId'),
                    'client_secret' => config('azure.appSecret'),
                    'scope' => 'https://graph.microsoft.com/.default',
                    'grant_type' => 'client_credentials',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $this->accessToken = $data['access_token'];
            return $this->accessToken;
        } catch (\Exception $e) {
            Log::error('Failed to get Microsoft Graph Access Token: ' . $e->getMessage());
            return null;
        }
    }

    public function syncUserPhoto($userIdOrEmail)
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        $graph = new Graph();
        $graph->setAccessToken($token);

        try {
            $photoResponse = $graph->createRequest('GET', "/users/" . urlencode($userIdOrEmail) . "/photo/\$value")
                ->execute();

            $photoData = $photoResponse->getRawBody();
            
            $filename = md5(strtolower($userIdOrEmail)) . '.png';
            $path = 'profile_pics/' . $filename;
            
            if (!Storage::disk('public')->exists('profile_pics')) {
                Storage::disk('public')->makeDirectory('profile_pics');
            }
            
            Storage::disk('public')->put($path, $photoData);
            
            return Storage::disk('public')->url($path);
        } catch (\Exception $e) {
            return null;
        }
    }
}
