<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use app\models\User;

class Signup extends Model 
{
	public $email;
	public $password;
	public $key;

	public function Rules()
	{
		return [
			[['email','password'], 'required'],
			['email','email'],
			['email','unique','targetClass'=>'app\models\User'],
			['password','string','min'=>2,'max'=>30],
		];
	}

	/* Register new user (return bool) */
	public function signup()
	{
		$user = new User();
		$user->key = $this->key;
		$user->email = $this->email;
		$user->setPassword($this->password);
		return $user->save();
	}

	/* Send to user email mail with activation key */
	public function sendMail($attrs)
	{
		$site = Url::home(true);
		$aSiteUrl = parse_url($site);
		$domain = $aSiteUrl['host'];
		$sitename = Yii::$app->name;
		$activate = $site . 'activate?key=' . $attrs['key'];

		$fromEmail = 'noreply@' . $domain;
		$subject = 'Регистрация на сайте '. $sitename;

		Yii::$app->mailer->compose('signup', compact('activate','sitename'))
			->setFrom([$fromEmail => $sitename])
			->setTo($attrs['email'])
			->setSubject($subject)
			->send();
	}
}
