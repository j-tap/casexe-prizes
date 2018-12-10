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

    /* Get last date row with status > -1 by user id */
	public static function getLastByUser($id)
	{
		return self::find(['id_user' => $id])
			->andWhere(['>', 'status', -1])
			->orderBy('date DESC')->one();
	}
}
