<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Prize extends activeRecord
{
	/* Get all rows from table (return array) */
	public static function getAll()
	{
		return self::find()->asArray()->all();
	}

	/* Get random prize (return array) */
	public static function getRandom()
	{
		
	}
}
