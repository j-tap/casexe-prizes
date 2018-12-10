<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class User2prize extends activeRecord
{
    /*  */
	public static function getByKey($key)
	{
		return self::findOne(['key' => $key]);
	}

	/*  */
	public static function getByUser($id)
	{
		return self::find(['id_user' => $id])->all();
	}

    /*  */
	public static function getLastByUser($id)
	{
		return self::find(['id_user' => $id])->orderBy('date DESC')->one();
	}
}
