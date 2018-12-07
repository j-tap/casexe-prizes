<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

class Signup extends Model 
{
	public $email;
	public $password;

	public function Rules()
	{
		return [
			[['email','password'], 'required'],
			['email','email'],
			['email','unique','targetClass'=>'app\models\User'],
			['password','string','min'=>2,'max'=>30]
		];
	}

	public function signup()
	{
		$user = new User();
		$user->email = $this->email;
		$user->setPassword($this->password);
		return $user->save();
	}

	public function sendMail($toEmail)
	{
		$aSiteUrl = parse_url(Url::home(true));
		$domain = $aSiteUrl['host'];
		$sitename = Yii::$app->name;
		$fromEmail = 'noreply@' . $domain;
		$subject = 'Регистрация на сайте '. $site;

		Yii::$app->mailer->compose('signup', compact('domain','sitename'))
			->setFrom([$fromEmail => $sitename])
			->setTo($toEmail)
			->setSubject($subject)
			->send();
	}
}
