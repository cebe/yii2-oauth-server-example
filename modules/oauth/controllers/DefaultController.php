<?php

namespace app\modules\oauth\controllers;

use GuzzleHttp\Psr7\Stream;
use app\modules\oauth\oauth\entities\AccessTokenEntity;
use yii\helpers\Json;
use app\modules\oauth\oauth\repositories\ScopeRepository;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use app\modules\oauth\oauth\entities\UserEntity;
use app\modules\oauth\oauth\AuthorizationServer as AppAuthorizationServer;
use app\components\psr7\Request;
use app\components\psr7\Response;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class DefaultController extends Controller
{
    public $enableCsrfValidation = false; // TODO only do this where needed, not in all actions

    public function behaviors()
    {
        return [
            'accessControl' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['authorize', 'my-access-token'],
                        'allow' => true,
                        'roles' => ['?'],
                    ]
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Example implementation of Auth Grant
     * @see https://oauth2.thephpleague.com/authorization-server/auth-code-grant/ headline "Implementation" adjusted for yii
     *
     * /authorize
     */
    public function actionAuthorize()
    {
        $server = AppAuthorizationServer::getInstance();
        $request = Yii::$app->request->getPsr7Request();
        $response = new \Laminas\Diactoros\Response();
        $authRequest = Yii::$app->getSession()->get('auth_req');
        parse_str($request->getUri()->getQuery(), $queryParams);

        try {
            // when user just logged in from login form
            if ($authRequest instanceof AuthorizationRequest &&
                !empty($queryParams['is_logged_in']) &&
                $queryParams['is_logged_in'] === 'yes' &&
                !Yii::$app->user->isGuest
            ) {
                $authRequest->setUser(UserEntity::findIdentity(Yii::$app->user->identity->id));
                // if the user once approved (allowed) the server and any one access token is not revoked nor expired, then now no need to redirect user to allow-deny page
                if (AccessTokenEntity::checkToken(Yii::$app->user->identity->id, $authRequest->getClient(), $authRequest->getScopes())) {
                    // $authRequest->setAuthorizationApproved(true);
                    // Yii::$app->getSession()->set('auth_req', $authRequest);
                    // return \Yii::$app->response->mergeWithPsr7Response(
                    //     $server->completeAuthorizationRequest($authRequest, $response)
                    // );
                    return $this->handleApproved($authRequest, $server, $response);
                }

                Yii::$app->getSession()->set('auth_req', $authRequest);
                return $this->redirect(['allow-deny-access']);

                // when user clicked 'Allow'
            } elseif ($authRequest instanceof AuthorizationRequest &&
                !empty($queryParams['result']) && !Yii::$app->user->isGuest) {
                $authRequest->setUser(UserEntity::findIdentity(Yii::$app->user->identity->id));
                if ($queryParams['result'] === 'yes') { // 1
                    $authRequest->setAuthorizationApproved(true);
                } else { // 0
                    $authRequest->setAuthorizationApproved(false);
                }
                Yii::$app->getSession()->set('auth_req', $authRequest);
                return \Yii::$app->response->mergeWithPsr7Response(
                    $server->completeAuthorizationRequest($authRequest, $response)
                );

                // when user is already logged in on server when redirected by client to server to authenticate
            } elseif ($authRequest instanceof AuthorizationRequest &&
                !Yii::$app->user->isGuest
            ) {
                // $authRequest = $server->validateAuthorizationRequest($request);

                // // when scope is present in request query string but empty, whitespace, 0 or having similar values, set default to email
                // // if (!$authRequest->getScopes()) {
                // //     $authRequest->setScopes([(new ScopeRepository())->getScopeEntityByIdentifier('email')]);
                // // }
                // $this->handleDefaultScope($authRequest);
                $authRequest = $this->initialStep($server, $request);
                if (AccessTokenEntity::checkToken(Yii::$app->user->identity->id, $authRequest->getClient(), $authRequest->getScopes())) {
                    $authRequest->setUser(UserEntity::findIdentity(Yii::$app->user->identity->id));
                    // $authRequest->setAuthorizationApproved(true);
                    // Yii::$app->getSession()->set('auth_req', $authRequest);
                    // return \Yii::$app->response->mergeWithPsr7Response(
                    //     $server->completeAuthorizationRequest($authRequest, $response)
                    // );
                    return $this->handleApproved($authRequest, $server, $response);
                }
                Yii::$app->getSession()->set('auth_req', $authRequest);
                return $this->redirect(['allow-deny-access']);
                // The very first step
            }
            // $authRequest = $server->validateAuthorizationRequest($request);
            // // when scope is present in request query string but empty, whitespace, 0 or having similar values, set default to email
            // // if (!$authRequest->getScopes()) {
            // //     $authRequest->setScopes([(new ScopeRepository())->getScopeEntityByIdentifier('email')]);
            // // }
            // $this->handleDefaultScope($authRequest);

            $authRequest = $this->initialStep($server, $request);

            Yii::$app->getSession()->set('auth_req', $authRequest);
            return $this->redirect(['/user/security/login']);

        } catch (OAuthServerException $exception) {
            return \Yii::$app->response->mergeWithPsr7Response($exception->generateHttpResponse($response));
        } catch (\Exception $exception) {
            $body = new Stream(fopen('php://temp', 'r+'));
            $body->write($exception->getMessage());
            Yii::$app->response->statusCode = 500;
            return $response->withBody($body)->getBody()->__toString();
        }
    }

    public function actionMyAccessToken()
    {
        $server = AppAuthorizationServer::getInstance();
        $request = Yii::$app->request->getPsr7Request();
        $response = new \Laminas\Diactoros\Response();
        $yiiResponse = Yii::$app->response;
        $yiiResponse->format = \yii\web\Response::FORMAT_JSON;

        try {
            $response = $server->respondToAccessTokenRequest($request, $response);
            $yiiResponse->content = $response->getBody()->__toString();
            return $yiiResponse;
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
            $yiiResponse->statusCode = 500;
            $yiiResponse->content = $exception->generateHttpResponse($response)->getBody()->__toString();
            return $yiiResponse;

        } catch (\Exception $exception) {
            $body = new Stream(fopen('php://temp', 'r+'));
            $body->write($exception->getMessage());
            $yiiResponse->statusCode = 500;
            $yiiResponse->content = Json::encode(['error' => $response->withBody($body)->getBody()->__toString()]);
            return $yiiResponse;
        }
    }

    public function actionAllowDenyAccess()
    {
        $authRequest = Yii::$app->getSession()->get('auth_req');
        if (!$authRequest instanceof AuthorizationRequest) {
            throw new Exception("Auth Request is not set in session");
        }

        $sr = new ScopeRepository;

        $scopeDesc = [];

        if (!empty($authRequest->getScopes()[0])) {
            foreach ($authRequest->getScopes() as $key => $value) {
                $scopeDesc[] = $sr->allScopes[$authRequest->getScopes()[$key]->getIdentifier()]['description'];
            }
        }

        return $this->render('allow-deny-access', ['scopeDesc' => $scopeDesc]);
    }

    protected function handleDefaultScope($authRequest)
    {
        if (!$authRequest->getScopes()) {
            $authRequest->setScopes([(new ScopeRepository())->getScopeEntityByIdentifier('email')]);
        }
    }

    protected function handleApproved($authRequest, $server, $response)
    {
        $authRequest->setAuthorizationApproved(true);
        Yii::$app->getSession()->set('auth_req', $authRequest);
        return \Yii::$app->response->mergeWithPsr7Response(
            $server->completeAuthorizationRequest($authRequest, $response)
        );
    }

    protected function initialStep($server, $request)
    {
        $newAuthRequest = $server->validateAuthorizationRequest($request);

        // when scope is present in request query string but empty, whitespace, 0 or having similar values, set default to email
        // if (!$authRequest->getScopes()) {
        //     $authRequest->setScopes([(new ScopeRepository())->getScopeEntityByIdentifier('email')]);
        // }
        $this->handleDefaultScope($newAuthRequest);
        return $newAuthRequest;
    }
}
