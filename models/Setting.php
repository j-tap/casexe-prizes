<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Setting extends activeRecord
{
	/* Get all rows from table and redefined key (return array) */
	public static function getAll()
	{
		$settings = self::find()->asArray()->all();
		$result = array();

		foreach ($settings as $set) {
			$result[$set['name']] = $set['value'];
		}

		return $result;
	}
}
