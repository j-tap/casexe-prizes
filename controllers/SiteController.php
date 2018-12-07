<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Signup;
use app\models\Signin;

class SiteController extends Controller
{
	public function actionIndex()
	{
		if (Yii::$app->user->isGuest) {
			return $this->redirect(['signup']);
		}
		return $this->render('index');
	}

	public function actionLogout()
	{
		if (!Yii::$app->user->isGuest) {
			Yii::$app->user->logout();
			return $this->redirect(['signin']);
		}
	}

	public function actionSignup()
	{
		$model = new Signup();
		$request = Yii::$app->request;

		if ($request->post('Signup')) {

			$model->attributes = $request->post('Signup');

			if ($model->validate()) {
				$model->signup();
				$model->sendMail($request->post('Signup')['email']);

				$modelSignin = new Signin();
				$modelSignin->attributes = $request->post('Signup');
				Yii::$app->user->login($modelSignin->getUser());

				return $this->goHome();
			}
		}

		return $this->render('signup', compact('model'));
	}

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
				Yii::$app->user->login($model->getUser());
				return $this->goHome();
			}
		}

		return $this->render('signin', compact('model'));
	}
	
}
