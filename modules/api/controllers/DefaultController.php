<?php

namespace app\modules\api\controllers;

use yii\web\Controller;
use Yii;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCustomData()
    {
        $request = Yii::$app->request->getPsr7Request();
        $response = new \Laminas\Diactoros\Response();

        $middleware = $this->withMiddleware();

        return $middleware($request, $response, function($request, $response) {
            return [
                'custom-data' => 'custom-data-value', // some custom data
            ];
        });
    }

    public function actionUser()
    {
        // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // // return ['sss'];
        // // Init our repositories
        // $accessTokenRepository = new \app\modules\oauth\oauth\repositories\AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
        // // Path to authorization server's public key
        // // $publicKeyPath = 'file://path/to/public.key';
        // $publicKeyPath = Yii::getAlias('@app').DIRECTORY_SEPARATOR.'public.key';

        // // Setup the authorization server
        // $server = new \League\OAuth2\Server\ResourceServer(
        //     $accessTokenRepository,
        //     $publicKeyPath
        // );

        // $request = Yii::$app->request->getPsr7Request();
        // $response = new \Laminas\Diactoros\Response();

        // $middleware = new \League\OAuth2\Server\Middleware\ResourceServerMiddleware($server);

        $request = Yii::$app->request->getPsr7Request();
        $response = new \Laminas\Diactoros\Response();

        $middleware = $this->withMiddleware();

        return $middleware($request, $response, function($request, $response) {
            // echo '<pre>66666666'; print_r($request); die;
            $user = \Da\User\Model\User::findOne($request->getAttribute('oauth_user_id'));
            return [
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
                'a5' => '566', // some custom data
            ];
        });

        // $request2 = Yii::$app->request->getPsr7Request();
        // echo '<pre>'; print_r($request2); die;
        // // return $request;

        // if (!$request->getAttribute('oauth_user_id')) {
        //     throw new \Exception("oauth_user_id not set in request. User auth failed", 1);
        // }

        // $user = Da\User\Model\User::findOne($request->getAttribute('oauth_user_id'));

        // return [
        //     // 'email' => 'email data here',
        //     'some' => 'data here',
        //     'req' => (array) $request,
        //     'id' => $user->id,
        //     'email' => $user->email,
        //     'username' => $user->username,

        // ];
    }

    protected function withMiddleware()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // return ['sss'];
        // Init our repositories
        $accessTokenRepository = new \app\modules\oauth\oauth\repositories\AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
        // Path to authorization server's public key
        // $publicKeyPath = 'file://path/to/public.key';
        $publicKeyPath = Yii::getAlias('@app').DIRECTORY_SEPARATOR.'public.key';

        // Setup the authorization server
        $server = new \League\OAuth2\Server\ResourceServer(
            $accessTokenRepository,
            $publicKeyPath
        );

        return new \League\OAuth2\Server\Middleware\ResourceServerMiddleware($server);
    }
}
