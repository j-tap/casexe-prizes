<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class CategoryPrize extends activeRecord
{
	/* Key - name in table, value - title glyphicon */
	private static $_icons = [
		'money' => 'usd',
		'score' => 'thumbs-up',
		'gift' => 'apple',
	];

	/* Get all rows from table and add field icon (return array) */
	public static function getAll()
	{
		$categories = self::find()->asArray()->all();
		foreach ($categories as $k => $category) {
			$categories[$k]['icon'] = self::getIconName($category['name']);
		}
		return $categories;
	}

	/* Get title icon from $_icons by $name (return string) */
	public static function getIconName($name)
	{
		return self::$_icons[$name];
	}

	/* Get random row from table (return array) */
	public static function getRandom()
	{
		$query = "SELECT * FROM `category_prize` AS `t1` 
			JOIN (
				SELECT ( RAND() * (SELECT MAX(`id`) FROM `category_prize`) ) AS `id`
			) 
			AS `t2`
			WHERE `t1`.`id` >= `t2`.`id`
			ORDER BY `t1`.`id` ASC
			LIMIT 1";

		$result = self::findBySql($query)->asArray()->one();
		return $result;
	}

	/* Get count rows in table (return int) */
	public static function getCount()
	{
		return self::find()->count();
	}
}
