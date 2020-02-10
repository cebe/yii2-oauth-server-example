<?php

namespace app\modules\oauth\controllers;

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
                        'actions' => ['authorize', /*'token-check',*/ 'my-access-token'],
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
        // TODO add exception handling (try catch)
        $server = AppAuthorizationServer::getInstance();
        $request = Yii::$app->request->getPsr7Request();
        $response = new \Laminas\Diactoros\Response();
        $authRequest = Yii::$app->getSession()->get('auth_req');
        parse_str($request->getUri()->getQuery(), $queryParams);

        if ($authRequest instanceof AuthorizationRequest &&
            !empty($queryParams['is_logged_in']) &&
            $queryParams['is_logged_in'] === 'yes') {

            if (Yii::$app->user->isGuest) { // double check // user MUST be logged in here as per the flow
                die('// fatal error. internal server error// problem in user auth // 500'); // TODO
            }

            $authRequest->setUser(UserEntity::findIdentity(Yii::$app->user->identity->id));
            // Yii::$app->getSession()->set('auth_req', null);
            Yii::$app->getSession()->set('auth_req', $authRequest);
            return $this->redirect(['allow-deny-access']);

        } elseif ($authRequest instanceof AuthorizationRequest &&
            !empty($queryParams['result'])) {
            if ($queryParams['result'] === 'yes') { // 1
                // echo "successfull login, redirect back to client or client callback with auth_code";
                $authRequest->setAuthorizationApproved(true);
            } else { // 0
                // echo "redirect back to client redirect uri saying you denied";
                $authRequest->setAuthorizationApproved(false);
            }
            // Yii::$app->getSession()->set('auth_req', null);
            Yii::$app->getSession()->set('auth_req', $authRequest);
            // return $server->completeAuthorizationRequest($authRequest, $response);
            return \Yii::$app->response->mergeWithPsr7Response(
                $server->completeAuthorizationRequest($authRequest, $response)
            );
        } else {
            $authRequest = $server->validateAuthorizationRequest($request);
            // Yii::$app->getSession()->set('auth_req', null);
            Yii::$app->getSession()->set('auth_req', $authRequest);
            return $this->redirect(['/user/security/login']);
            // redirect to login page
            // in login action method on successful login check if session is set then redirect back to here with query string param is_login_success=yes after successful login
        }
    }

    public function actionMyAccessToken()
    {
        // $this->enableCsrfValidation = false;
        $server = AppAuthorizationServer::getInstance();
        $request = Yii::$app->request->getPsr7Request();
        $response = new \Laminas\Diactoros\Response();

        try {
            // Try to respond to the request
            $response = $server->respondToAccessTokenRequest($request, $response);
            // echo "<pre>"; print_r($response); die;
            // return \Yii::$app->response->mergeWithPsr7Response(
            //     $server->respondToAccessTokenRequest($request, $response)
            // );
            $yiiResponse = Yii::$app->response;
            $yiiResponse->format = \yii\web\Response::FORMAT_JSON;
            $yiiResponse->content = $response->getBody()->__toString();
            return $yiiResponse;

        } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
            echo '<pre>'; print_r($exception); die;
            // All instances of OAuthServerException can be formatted into a HTTP response
            return $exception->generateHttpResponse($response);

        } catch (\Exception $exception) {
            echo '<pre>'; print_r($exception); die;
            // Unknown exception
            $body = new \GuzzleHttp\Psr7\Stream(fopen('php://temp', 'r+'));
            $body->write($exception->getMessage());
            return $response->withStatus(500)->withBody($body);
        }
    }

    // TODO implement Action for /access_token

    // public function FunctionName($value='')
    // {

    //     // echo "<pre>";
    //     // Yii::$app->getSession()->set('popol', \app\modules\oauth\oauth\entities\ClientEntity::findOne(1));
    //     // print_r(Yii::$app->getSession()->get('popol'));
    //     // die;

    //     $server = AppAuthorizationServer::getInstance();
    //     // die('2555');
    //     /* @var \League\OAuth2\Server\AuthorizationServer $server */
    //     // $server = Yii::$container->get(AuthorizationServer::class);

    //     /** @var ServerRequestInterface $request */
    //     $request = Yii::$app->request->getPsr7Request();
    //     $response = new \Laminas\Diactoros\Response();

    //     try {
    //         // Validate the HTTP request and return an AuthorizationRequest object.
    //         $authRequest = $server->validateAuthorizationRequest($request);
    //         Yii::$app->getSession()->set('auth_req', $authRequest);
    //         // die('------------');
    //         // store session here.
    //         // redirect to login
    //         // on successful login, redirect to here
    //         // check session
    //         // redirect to auuthorze deny page
    //         // submit its form to here
    //         // on authorize send token/redirect
    //         return $this->redirect(['/user/security/login']);

    //         // The auth request object can be serialized and saved into a user's session.
    //         // You will probably want to redirect the user at this point to a login endpoint.

    //         // Once the user has logged in set the user on the AuthorizationRequest
    //         $authRequest->setUser(new UserEntity()); // TODO an instance of UserEntityInterface

    //         // At this point you should redirect the user to an authorization page.
    //         // This form will ask the user to approve the client and the scopes requested.

    //         // Once the user has approved or denied the client update the status
    //         // (true = approved, false = denied)
    //         $authRequest->setAuthorizationApproved(true);

    //         // Return the HTTP redirect response
    //         return \Yii::$app->response->mergeWithPsr7Response(
    //             $server->completeAuthorizationRequest($authRequest, $response)
    //         );

    //     } catch (OAuthServerException $exception) {
    //         // echo "string------------";
    //         // print_r($exception);
    //         // die('-554-------');
    //         // All instances of OAuthServerException can be formatted into a HTTP response
    //         return \Yii::$app->response->mergeWithPsr7Response(
    //             $exception->generateHttpResponse($response)
    //         ); // TODO there is problem with mergeWithPsr7Response()

    //     }
    // }

    public function actionAllowDenyAccess()
    {
        $authRequest = Yii::$app->getSession()->get('auth_req');
        if (!$authRequest instanceof AuthorizationRequest) {
            // throw new NotFoundException("Error Processing Request", 1); // TODO
        }
        $sr = new ScopeRepository;
        // print_r($authRequest->getScopes()); die;
        $scopeDesc = 'scope desc'; // TODO might be there is a bug in league, default scope is not set
        if (!empty($authRequest->getScopes()[0])) {
            $scopeDesc = $sr->allScopes[$authRequest->getScopes()[0]->getIdentifier()]['description'];
        }

        return $this->render('allow-deny-access', ['scopeDesc' => $scopeDesc]);
    }

    // public function actionTokenCheck()
    // {
    // }
}
