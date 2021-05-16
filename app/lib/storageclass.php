<?php


namespace Framework\lib;


class storageclass
{
    public function getSession()
    {
        return Session::Get('oauth2');
    }

    public function setToken($token, $expires, $tenantId, $refreshToken, $idToken)
    {
        Session::Set('oauth2', [
            'token' => $token,
            'expires' => $expires,
            'tenant_id' => $tenantId,
            'refresh_token' => $refreshToken,
            'id_token' => $idToken
        ]);
    }

    public function getToken()
    {
        //If it doesn't exist or is expired, return null
        if (!empty($this->getSession()) ||
            (Session::Get('oauth2')['expires'] !== null && Session::Get('oauth2')['expires'] <= time())
        ) {
            return null;
        }
        return $this->getSession();
    }

    public function getAccessToken()
    {
        return Session::Get('oauth2')['token'];
    }

    public function getRefreshToken()
    {
        return Session::Get('oauth2')['refresh_token'];
    }

    public function getExpires()
    {
        return Session::Get('oauth2')['expires'];
    }

    public function getXeroTenantId()
    {
        return Session::Get('oauth2')['tenant_id'];
    }

    public function getIdToken()
    {
        return Session::Get('oauth2')['id_token'];
    }

    public function getHasExpired()
    {
        if (!empty($this->getSession())) {
            if (time() > $this->getExpires()) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
