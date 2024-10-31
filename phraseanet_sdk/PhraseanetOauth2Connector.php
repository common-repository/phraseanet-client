<?php


use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Http\GuzzleAdapter;
use PhraseanetSDK\OAuth2Connector;

/**
 * @since 1.0.0
 */
class PhraseanetOauth2Connector extends OAuth2Connector
{
    public const TOKEN_ENDPOINT = 'oauthv2/token';

    /**
     * @var GuzzleAdapter
     */
    private $adapter;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $secret;

    /**
     * PhraseanetOauth2Connector constructor.
     * @param \PhraseanetSDK\Http\GuzzleAdapter $adapter
     * @since 1.0.0
     */
    public function __construct(GuzzleAdapter $adapter, $clientId, $secret)
    {
        $this->adapter = $adapter;
        $this->clientId = $clientId;
        $this->secret = $secret;
    }

    public function getUrl()
    {
        $baseUrl = $this->adapter->getBaseUrl();

        return $baseUrl;
    }

    /**
     * Retrieves access token from Phraseanet with given username and password
     *
     * @param $username
     * @param $password
     * @return mixed
     * @throws \PhraseanetSDK\Exception\AuthenticationException
     * @since 1.0.0
     */
    public function retrieveAccessTokenByPassword($username, $password)
    {
        $postFields = array(
      'grant_type' => 'password',
      'client_id' => $this->clientId,
      'username' => $username,
      'password' => $password
    );

        try {
            $responseContent = $this->adapter->call(
                'POST',
                $this->getUrl() . static::TOKEN_ENDPOINT,
                array(),
                $postFields
            );
            $data = json_decode($responseContent, true);
            $token = $data["access_token"];
        } catch (BadResponseException $e) {
            $response = json_decode($e->getResponseBody(), true);
            $msg = isset($response['error']) ? $response['error'] : (isset($response['msg']) ? $response['msg'] : '');

            return false;
            //throw new AuthenticationException($msg);
        }

        return $token;
    }
}
