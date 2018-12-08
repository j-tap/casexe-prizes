<?php

namespace app\models;

use yii\base\Model;
use app\models\CategoryPrize;
use app\models\Prize;

class Lottery extends Model 
{
	/*  */
	public function run()
	{
		//$prize = Prize::getRandom();
		//$number = Lottery::secureRand(self::getCount());
		$category = CategoryPrize::getRandom();
		return $category['title'];
	}

	/* Get random number in range $min and $max (return int) */
	public static function secureRand($max, $min = 1, $isStrict = true)
	{
		$diff = $max - $min;
		if ($diff <= 0) return $min;
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
