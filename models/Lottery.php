<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\CategoryPrize;
use app\models\Prize;
use app\models\Setting;
use app\models\User2prize;
use yii\httpclient\Client;

class Lottery extends Model 
{
	public static $category;
	public static $setting;

	public $count;
	public $prize;
	public $type;
	public $key;

	/* Union multiple methods for get prize (return array) */
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
			$prize->type = self::$category['name'];
			$prize->settings = self::$setting;
			$prize->calcCount();
			$prize->updateAmount();

			$lottery->key = sha1(Yii::$app->user->identity->id . $prize['id'] . time());
			$lottery->count = $prize->count;
			$lottery->type = self::$category['name'];
			$lottery->prize = $prize;
			
			$lottery->addUser2prize();

			$prizeTitle = $lottery->prize['title'];
			switch ($lottery->type) {
				case 'score':
					$prizeTitle .= " $lottery->count";
					break;

				case 'money':
					$prizeTitle .= " в размере $lottery->count руб.";
					break;
			}

			return [
				'title' => 'Поздравляем, вы получили:',
				'subtitle' => $prizeTitle,
				'prize' => [
					'key' => $lottery->key,
					'name' => $lottery->prize['title'],
					'type' => $lottery->type,
					'icon' => CategoryPrize::getIconName($lottery->type),
					'count' => $lottery->count,
				]
			];
		}

		return [
			'title' => 'Извините, призы закончились',
			'prize' => false,
		];
	}

	/* if user dismiss prize */
	public static function dismissPrize($key)
	{
		$user2prize = User2prize::findOne(['key' => $key]);
		$idPrize = $user2prize->id_prize;
		$count = intval($user2prize->count);
		$user2prize->status = -1;
		$user2prize->save();

		$prize = Prize::findOne($idPrize);
		$prize->updateAmount( $count + intval($prize->amount) );
		
		return [];
	}

	/* if user accept prize */
	public static function acceptPrize($key)
	{
		$user2prize = User2prize::findOne(['key' => $key]);
		$score = intval($user2prize->count);
		$idPrize = $user2prize->id_prize;
		$user2prize->status = 2;
		$user2prize->save();

		switch (CategoryPrize::getNameByPrizeId($idPrize)) {
			case 'score':
				$user = User::findOne(Yii::$app->user->identity->id);
				$user->score = intval($user->score) + $score;
				$user->update();
				break;

			case 'money':
				break;

			case 'gift':
				break;
		}

		return [];
	}

	public function convertMoney($key)
	{
		$user2prize = User2prize::findOne(['key' => $key, 'status' => 2]);
		$user = User::findOne($user2prize->id_user);
		$setting = Setting::getAll();

		$money = intval($user2prize->count);
		$k = $setting['score_ratio'];

		if ($money >= $k) {
			$score = floor($money / $k);
			$newScore = intval($user->score) + $score;

			$user->score = $newScore;
			$user2prize->status = 10;

			$user->update();
			$user2prize->update();

			return [
				'is' => true,
				'score' => $newScore
			];
		} else {
			return [
				'is' => false,
				'title' => 'Недостаточно средств для конвертации'
			];
		}
	}

	public function moneySend($key, $cart)
	{
		$user2prize = User2prize::findOne(['key' => $key]);
		$user = User::findOne($user2prize->id_user);

		if (true) { // нужна валидация
			$user->cart = $cart;
			$user->update();

			$client = new Client();

			$response = $client->createRequest()
				->setMethod('POST')
				->setUrl('https://sheetsu.com/apis/v1.0su/2ec458232deb') // for test
				->setData([
					'cart' => $user->cart, 
					'money' => $user2prize->count, 
					'email' => $user->email
				])
				->send();

			if (!$response) {
				return [
					'title' => 'Ошибка отправки'
				];
			}

			$user2prize->status = 10;
			$user2prize->update();

			return [
				'title' => 'Денежный приз отправлен вам',
				'response' => $response
			];
		}
		return [
			'title' => 'Ошибка валидации'
		];
	}

	public function giftSend($key, $address)
	{
		$user2prize = User2prize::findOne(['key' => $key]);
		$user = User::findOne($user2prize->id_user);

		$user2prize->status = 5;
		$user2prize->update();

		if (true) { // нужна валидация
			$user->address = $address;
			$user->update();

			return [
				'title' => 'Приз будет отправлен вам',
			];
		}
		return [
			'title' => 'Ошибка'
		];
	}

	/* */
	public function addUser2prize()
	{
		$user2prize = new User2prize();
		$user2prize->id_user = Yii::$app->user->identity->id;
		$user2prize->id_prize = $this->prize['id'];
		$user2prize->count = $this->count;
		$user2prize->key = $this->key;
        return $user2prize->save();
	}

	/* Get random number in range $min and $max (return int) */
	public static function getSecureRand($max = 9, $min = 1)
	{
		if (function_exists('random_int')) {
			return random_int($min, $max);
			
		} else {
			if (function_exists('openssl_random_pseudo_bytes')) {
				$isStrict = true;
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
				
			} else {
				return rand($min, $max);
			}
		}
	}
}
