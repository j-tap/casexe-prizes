<?php

namespace app\models;

use yii\base\Model;

class Signin extends Model 
{
    public $email;
    public $password;

    public function Rules()
    {
        return [
            [['email','password'], 'required'],
            ['email','email'],
            ['password','validatePassword']
        ];
    }

    public function validatePassword($attribute,$params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute,'Проверьте правильность введённых данных');
            }
        }
    }

    public function getUser()
    {
        return User::findOne(['email'=>$this->email]);
    }
}
