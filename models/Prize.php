<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\Lottery;

class Prize extends activeRecord
{
	public $count;
	public $type;
	public $settings;

	/* Update field amount in db (return object) */
	public function updateAmount($amountNew = false)
	{
		if ($this->getAttribute('is_limit')) {
			if (!$amountNew) $amountNew = intval($this->amount) - intval($this->count);			
			$this->amount = $amountNew;
			$this->save();
		}

		return $this;
	}

	/* Calculate params on settings (return object) */
	public function calcCount()
	{
		$category = $this->type;
		$params = $this->settings;
		
		switch (true) {
			case ($params[$category . '_min'] && $params[$category . '_max']):
				$this->count = Lottery::getSecureRand( $params[$category . '_max'], $params[$category . '_min'] );
				break;

			default:
				$this->count = 1;
				break;
		}

		return $this;
	}

	/* Get all rows from table (return array) */
	public static function getAll()
	{
		return self::find()->asArray()->all();
	}

	/* Get random prize (return array) */
	public static function getRandomByCategory($idCategory = 1)
	{
		$randNumPhp = Lottery::getSecureRand( self::getCountByCategory($idCategory) );
		$randNumPhp--;

		$query = "SELECT * FROM `prize`
			WHERE `order` = ( 
				SELECT `order` FROM `prize` 
				WHERE `id_category` = $idCategory 
					AND ( 
					(`amount` > 0 AND `is_limit` > 0)
					OR (`amount` = 0 AND `is_limit` = 0)
				)
				LIMIT 1
			) + $randNumPhp
			AND ( 
				(`amount` > 0 AND `is_limit` > 0)
				OR (`amount` = 0 AND `is_limit` = 0)
			)
			ORDER BY `id`
			LIMIT 1";
		
		return self::findBySql($query)->one();
	}

	/* Get count rows in table Prize (return int) */
	public static function getCountByCategory($idCategory)
	{
		return self::find()
			->where("`id_category` = $idCategory 
			AND ( 
				(`amount` > 0 AND `is_limit` > 0) 
				OR (`amount` = 0 AND `is_limit` = 0) 
			)")
			->count();
	}
}
