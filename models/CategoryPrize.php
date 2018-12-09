<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\Lottery;

class CategoryPrize extends activeRecord
{
	/* Key - name in table, value - title glyphicon */
	private static $_icons = [
		'default' => 'cog',
		'money' => 'usd',
		'score' => 'thumbs-up',
		'gift' => 'apple',
	];

	/* Get all rows from table and add field icon (return array) */
	public static function getAll()
	{
		$categories = self::find()->asArray()->all(); // Добавить проверку если в prize is_limit = 0 and amount = 0
		foreach ($categories as $k => $category) {
			$categories[$k]['icon'] = self::getIconName($category['name']);
		}
		return $categories;
	}

	/* Get title icon from $_icons by $name (return string) */
	public static function getIconName($name)
	{
		$icon = self::$_icons[$name];
		if (!$icon) {
			$icon = self::$_icons['default'];
		}
		return $icon;
	}

	/* Get random row from table (return array) */
	public static function getRandom()
	{
		$randNumPhp = Lottery::getSecureRand( self::getCount() );
		//$randNumSql = '( SELECT FLOOR( MAX(`id`) * RAND() ) FROM `category_prize` LIMIT 1 )';
		
		$query = "SELECT `id`, `name` FROM `category_prize`
			WHERE `id` >= $randNumPhp
			ORDER BY `id`
			LIMIT 1";

		return self::findBySql($query)->asArray()->one();
	}

	/* Get count rows in table (return int) */
	public static function getCount()
	{
		return self::find()->count();
	}
}
