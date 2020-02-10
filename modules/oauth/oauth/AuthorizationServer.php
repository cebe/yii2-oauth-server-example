<?php

namespace app\modules\oauth\oauth;

use app\components\psr7\Request;
use app\components\psr7\Response;
use League\OAuth2\Server\AuthorizationServer as LeagueOauth2AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\modules\oauth\oauth\repositories\ClientRepository;
use app\modules\oauth\oauth\repositories\ScopeRepository;
use app\modules\oauth\oauth\repositories\AccessTokenRepository;
use app\modules\oauth\oauth\repositories\AuthCodeRepository;
use app\modules\oauth\oauth\repositories\RefreshTokenRepository;

class AuthorizationServer extends LeagueOauth2AuthorizationServer
{
    public static function getInstance(): self
    {
        // Init our repositories
        $clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
        // $clientObj = $clientRepository->getClientEntity(1);

        $scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
        // $basicScope = $scopeRepository->getScopeEntityByIdentifier('basic');

        // $ate = new AccessTokenEntity();

        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
        // $ate = $accessTokenRepository->getNewToken($clientObj, [$basicScope], 2);
        // print_r($accessTokenRepository->persistNewAccessToken($ate)); die;

        $authCodeRepository = new AuthCodeRepository(); // instance of AuthCodeRepositoryInterface
        $refreshTokenRepository = new RefreshTokenRepository(); // instance of RefreshTokenRepositoryInterface

        $privateKey = Yii::getAlias('@app').DIRECTORY_SEPARATOR.'private.key';
        //$privateKey = new CryptKey('file://path/to/private.key', 'passphrase'); // if private key has a pass phrase
        $encryptionKey = Yii::$app->params['encryption_key']; // generate using base64_encode(random_bytes(32))

        // Setup the authorization server
        $server = new static(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            $encryptionKey
        );

        $grant = new \League\OAuth2\Server\Grant\AuthCodeGrant(
             $authCodeRepository,
             $refreshTokenRepository,
             new \DateInterval('PT10M') // authorization codes will expire after 10 minutes
        );

        // $grant->setDefaultScope('email'); // TODO - bug? - not working
        // var_dump($grant->getDefaultScope()); die;
        $grant->disableRequireCodeChallengeForPublicClients();
        $grant->setRefreshTokenTTL(new \DateInterval('P1M')); // refresh tokens will expire after 1 month

        // Enable the authentication code grant on the server

        $server->setDefaultScope('email'); // this also doesn't work
        $server->enableGrantType(
            $grant,
            new \DateInterval('PT1H') // access tokens will expire after 1 hour
        );

        return $server;
    }
}
