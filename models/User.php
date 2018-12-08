<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends activeRecord implements IdentityInterface
{
	/* Set generated hash password in this object */
	public function setPassword($password)
	{
		$this->password = Yii::$app->security->generatePasswordHash($password);
	}

	/* Compare password entering and in database (return bool) */
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->password);
	}

	/* Update activate for current user (return bool) */
	public function activate($key)
	{
		if ($this->checkKey($key)) {
			$this->is_activate = 1;
			return $this->save();
		}
		return false;
	}

	/* Compare activation key (return bool) */
	public function checkKey ($key) 
	{
		return ($this->key === $key);
	}

	/* Generate activation key (return string 40) */
	public static function genActivateKey ($string) 
	{
		return sha1($string . time());
	}

	/* Delete all inactivated user from table */
	public static function deleteNotActivate()
	{
		$now = time();
		$days = 5;
		$old = $now - (3600 * 24 * $days);

		self::deleteAll([
			'AND',
			'is_activate' => '0',
			['<', 'date', $old],
		]);
	}

	/* Interface */

	public static function findIdentity($id)
	{
		return self::findOne($id);
	}
	public function getId()
	{
		return $this->id;
	}
	public static function findIdentityByAccessToken($token, $type = null)
	{
		
	}
	public function getAuthKey()
	{
		
	}
	public function validateAuthKey($authKey)
	{
		
	}

}
