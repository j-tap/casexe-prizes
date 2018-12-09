<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class User2prize extends activeRecord
{
    /*  */
	public static function getByUser($id)
	{
		return self::find(['id_user' => $id])->asArray()->all();
	}

     /*  */
	public static function getLastByUser($id)
	{
		return self::find(['id_user' => $id])->orderBy('date DESC')->asArray()->one();
	}

	/*  */
	public function add($id_user, $id_prize, $count = 1)
	{
		$this->id_user = $id_user;
		$this->id_prize = $id_prize;
		$this->count = $count;
        
		return $this->save();
	}

    /*  */
    public function updateStatus($status = 1)
	{
		$this->status = $status;
		return $this->save();
	}
}
