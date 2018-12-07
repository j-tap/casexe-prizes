<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends activeRecord implements IdentityInterface
{
    public function setPassword($password)
    {
        $this->password = sha1($password);
    }

    public function validatePassword($password)
    {
        return $this->password === sha1($password);
    }

	public function genSalt()
	{
		return substr(sha1(uniqid()), -5);
	}

	public function genKey ($salt) 
	{
		return sha1($salt);
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
