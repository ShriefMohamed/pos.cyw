<?php


namespace Framework\lib;


use League\OAuth2\Client\Provider\GenericProvider;

class XeroModel
{
    public $sync_types = array('customers', 'accounts', 'invoices', 'items');

    public function Provider(): GenericProvider
    {
        return $provider = new GenericProvider([
            'clientId'                => XERO_KEY,
            'clientSecret'            => XERO_SEC,
            'redirectUri'             => XERO_REDIRECT_URI,
            'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
            'urlAccessToken'          => 'https://identity.xero.com/connect/token',
            'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
        ]);
    }

    public function RefreshToken($storage, $xeroTenantId)
    {
        $provider = $this->Provider();

        $newAccessToken = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $storage->getRefreshToken()
        ]);

        // Save my token, expiration and refresh token
        $storage->setToken(
            $newAccessToken->getToken(),
            $newAccessToken->getExpires(),
            $xeroTenantId,
            $newAccessToken->getRefreshToken(),
            $newAccessToken->getValues()["id_token"] );
    }
}