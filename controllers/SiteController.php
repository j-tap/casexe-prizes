<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Signup;
use app\models\Signin;
use app\models\CategoryPrize;
use app\models\User;
use app\models\Lottery;

class SiteController extends Controller
{
	/* Action Index page */
	public function actionIndex()
	{
		/* Guest go to regiter, inactive go to activate, ajax - process */
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['signup']);

		} elseif (!Yii::$app->user->identity->is_activate) {
			return $this->redirect(['activate']);

		} elseif (Yii::$app->request->isAjax) {
			$request = Yii::$app->request;

			switch (true) {
				case $request->post('getPrize'):
					$response = Lottery::getPrize();
					return json_encode($response);
					break;
				
				case $request->post('dismissPrize'):
					$response = Lottery::dismissPrize($request->post('key'));
					return json_encode($response);
					break;

				case $request->post('acceptPrize'):
					$response = Lottery::acceptPrize($request->post('key'));
					return json_encode($response);
					break;

				case $request->post('convertMoney'):
					$response = Lottery::convertMoney($request->post('key'));
					return json_encode($response);
					break;

				case $request->post('moneySend'):
					$response = Lottery::moneySend($request->post('key'), $request->post('cart'));
					return json_encode($response);
					break;

				case $request->post('giftSend'):
					$response = Lottery::giftSend($request->post('key'), $request->post('address'));
					return json_encode($response);
					break;
			}
		}

		/* Get all possible categories */
		$categories = CategoryPrize::getAll();

		return $this->render('index', compact('categories','user'));
	}

	/* Action Logout page */
	public function actionLogout()
	{
		if (!Yii::$app->user->isGuest) {
			Yii::$app->user->logout();
			return $this->redirect(['signin']);
		}
	}

	/* Action Signup page */
	public function actionSignup()
	{
		/* Authorized users go to index page */
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new Signup();
		$request = Yii::$app->request;

		if ($request->post('Signup')) {
			$attrs = $request->post('Signup');
			$model->attributes = $attrs;
			$model->key = User::genActivateKey($attrs['email']);

			if ($model->validate()) {

				$model->signup();
				
				User::deleteNotActivate(); // Modify: Need execute async or used crone
				$model->sendMail($model->attributes); // Modify: Need execute async

				/* Login */
				$modelSignin = new Signin();
				$modelSignin->attributes = $attrs;
				Yii::$app->user->login($modelSignin->getCurrent());

				return $this->goHome();
			}
		}

		return $this->render('signup', compact('model'));
	}

	/* Action Signin page */
	public function actionSignin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new Signin();
		$request = Yii::$app->request;

		if ($request->post('Signin')) {

			$model->attributes = $request->post('Signin');

			if ($model->validate()) {
				Yii::$app->user->login($model->getCurrent());
				return $this->goHome();
			}
		}

		return $this->render('signin', compact('model'));
	}

	/* Action Activate page */
	public function actionActivate()
	{
		/* Guests and activate authorized users go to index page */
		if (Yii::$app->user->isGuest || Yii::$app->user->identity->is_activate) {
			return $this->goHome();
		} else {
			$request = Yii::$app->request;

			$key = $request->get('key');
			$email = Yii::$app->user->identity->email;

			/* If exist key in GET */
			if ($key) {
				$user = User::findOne(Yii::$app->user->identity->id);
				$isActivate = $user->activate($key);
				if ($isActivate) return $this->goHome();
			}

			return $this->render('activate', compact(['email']));
		}
	}
	
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}
}
