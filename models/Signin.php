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

    /* Custom validate password */
    public function validatePassword($attribute,$params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getCurrent();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute,'Проверьте правильность введённых данных');
            }
        }
    }

    /* Get current user by email (return array) */
    public function getCurrent()
    {
        return User::findOne(['email'=>$this->email]);
    }
}
