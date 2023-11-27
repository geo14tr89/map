<?php

namespace frontend\controllers;

use common\components\Config;
use common\models\LoginForm;
use common\models\User;
use frontend\components\FrontendController;
use frontend\models\SignupForm;
use Yii;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Cookie;
use yii\web\Response;

class AuthController extends FrontendController
{

    /**
     * @return array|false|null[]
     * @throws Exception
     */
    public function actionLogin()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new LoginForm();

        if (Yii::$app->request->isPost) {
            $model->username = Yii::$app->request->post()['username'];
            $model->password = Yii::$app->request->post()['password'];
            $model->rememberMe = Yii::$app->request->post()['rememberMe'];

            if ($model->login() === true) {
                $user = User::findOne(['username' => $model->username]);
                $token = substr(Yii::$app->request->getCsrfToken(), 0, 20);
                $user->token = $token;
                if ($user->save(false) === false) {
                    throw new Exception('Error per saving token');
                }

                return [
                    'token' => $token ?? null
                ];
            }

            return $model->getFirstErrors();
        }

        return false;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionLogout(): string
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $token = substr(Yii::$app->request->headers->get('Authorization'), 7);
        $user = User::findOne(['token' => $token]);
        $user->token = '';
        if ($user->save(false) === false) {
            throw new Exception('Error per saving token');
        }

        return 'You successfully logged out';
    }

    /**
     * @return false|Response
     */
    public function actionRegister()
    {
        $model = new SignupForm();

        if (Yii::$app->request->isPost) {
            $data = Json::decode(Yii::$app->request->getRawBody());

            $model->username = $data['username'];
            $model->password = $data['password'];
            $model->email = $data['email'];
            $model->signup();

            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');

            return $this->goHome();
        }

        return false;
    }

    public function actionGoogle()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $parameters = [
            'client_id' => Config::GOOGLE_CLIENT_ID,
            'client_secret' => Config::GOOGLE_CLIENT_SECRET,
            'redirect_uri' => Config::GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code',
            'code' => $_GET['code'],
        ];

        $client = new \GuzzleHttp\Client();

        $response = $client->post(Config::GOOGLE_TOKEN_URI, ['form_params' => $parameters]);
        $data = Json::decode($response->getBody()->getContents());
        $token = $data['access_token'];

        $response = $client->get(Config::GOOGLE_USER_INFO_URI, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $userData = Json::decode($response->getBody()->getContents());

        if ($userData !== null) {
            $user = User::findOne(['email' => $userData['email']]);
            if ($user === null) {
                $signupForm = new SignupForm();
                $signupForm->username = $userData['name'];
                $signupForm->password = Yii::$app->security->generateRandomString();
                $signupForm->email = $userData['email'];
                $signupForm->signup();

                $userInfo = User::findOne(['email' => $userData['email']]);

//                return [
//                    'token' => $userInfo->token ?? null
//                ];

                $url = 'https://archimapa.transsearch.net/setToken.html?token=' . $userInfo->token;
                return $this->redirect($url);
            }


            $loginForm = new LoginForm();
            $loginForm->username = $user->username;
            if ($loginForm->login()) {
                $user = User::findOne(['username' => $loginForm->username]);
                $token = substr(Yii::$app->request->getCsrfToken(), 0, 20);
                $user->token = $token;
                if ($user->save(false) === false) {
                    throw new Exception('Error per saving token');
                }

//                return [
//                    'token' => $token ?? null
//                ];

                $url = 'https://archimapa.transsearch.net/setToken.html?token=' . $token;
                return $this->redirect($url);
            }
        }

        return false;
    }
}
