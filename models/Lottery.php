<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\CategoryPrize;
use app\models\Prize;
use app\models\Setting;

class Lottery extends Model 
{
	public static $category;
	public static $setting;

	public $count;
	public $prize;
	public $type;

	/* Union multiple methods for get prize (return object) */
	public static function getPrize()
	{
		self::$setting = Setting::getAll();
		$lottery = new Lottery();
		$user = User::findOne(Yii::$app->user->identity->id);

		$lastPrize = $user->getLastPrize();
		$timeout = ( strtotime($lastPrize['date']) + intval(self::$setting['interval_get_prize']) ) - time();

		if ($timeout > 0) {
			return [
				'title' => 'Вы уже играли, вернитесь через ' . $timeout . ' секунд',
				'prize' => false,
				'timeout' => $timeout,
			];
		}

		self::$category = CategoryPrize::getRandom();

		$prize = Prize::getRandomByCategory(self::$category['id']);

		if ($prize) {
			$prize->updateAmount();

			$lottery->count = Prize::$count;
			$lottery->type = self::$category['name'];
			$lottery->prize = $prize;

			$user->managePrize($lottery);

			$prizeString = $lottery->prize['title'];
			switch ($lottery->type) {
				case 'score':
					$prizeString .= " $lottery->count";
					break;

				case 'money':
					$prizeString .= " в размере $lottery->count руб.";
					break;
			}

			return [
				'title' => 'Поздравляем, вы получили: ' . $prizeString . '!',
				'prize' => [
					'id' => $lottery->prize['id'],
					'name' => $lottery->prize['title'],
					'type' => $lottery->type,
					'count' => $lottery->count,
				]
			];
		}

		return [
			'title' => 'Извините, призы закончились',
			'prize' => false,
		];
	}

	/* Get random number in range $min and $max (return int) */
	public static function getSecureRand($max, $min = 1, $isStrict = true)
	{
		$diff = intval($max) - $min;
		if ($diff <= 0) return intval($min);
		$range = $diff + 1;
		$bits = ceil( log( ($range), 2) );
		$bytes = ceil($bits / 8.0);
		$bitsMax = 1 << $bits;
		$num = 0;
		do {
			$num = hexdec( bin2hex( openssl_random_pseudo_bytes($bytes) ) ) % $bitsMax;
			if ($num >= $range) {
				if ($isStrict) continue;
				$num = $num % $range;
			}
			break;
		} while (true);
		return $num + $min;
	}
}
