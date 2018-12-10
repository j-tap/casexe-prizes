<?php
use yii\bootstrap\Modal;
?>

<h1 class="text-center mb-4">Розыгрыш призов</h1>
<section class="section-getprizes">
	<h3>Вы можете получить случайный приз</h3>
	<p class="text-muted mb-3">*призы ограничены</p>
	<ul class="list-unstyled row">

		<? foreach ($categories as $category): ?>
			<li class="col-xs-4" id="prize<?= $category['id'] ?>">
				<figure class="panel panel-default">
					<div class="panel-body">
						<span class="glyphicon glyphicon-<?= $category['icon'] ?> icon-prize" aria-hidden="true"></span>
					</div>
					<figcaption class="panel-footer"><?= $category['title'] ?></figcaption>
				</figure>
			</li>
		<? endforeach; ?>

	</ul>
	<div class="row mt-4">
		<div class="col-sm-4 col-sm-offset-4 col-8 col-offset-2">
			<button class="btn btn-danger btn-block btn-lg" id="btnGetPrizeAjax">Получить!</button>
		</div>
	</div>
</section>

<? Modal::begin([
	'footer'=>'
		<div class="row">
			<div class="col-xs-6">
				<button id="btnModalPrizeDismiss" class="btn btn-warning btn-lg btn-block">Отказаться</button>
			</div>
			<div class="col-xs-6">
				<button id="btnModalPrizeAccept" class="btn btn-success btn-lg btn-block">Прянять</button>
			</div>
		</div>
	',
	'id'=>'modalPrize',
	'size'=>'modal-md',
	'options' => ['class'=>'modal-prize'],
]); ?>

	<h4 class="text-center mb-1" id="modalPrizeTitle"></h4>
	<h4 class="text-center mb-4" id="modalPrizeSubTitle"></h4>
	<div class="text-center mb-3">
		<span class="glyphicon glyphicon-cog icon-prize" id="modalPrizeIcon" aria-hidden="true"></span>
	</div>

<? Modal::end(); ?>

<? Modal::begin([
	'footer'=>'
		<div class="row">
			<div class="col-xs-6">
				<button class="btn btn-default btn-block" data-dismiss="modal">Отмена</button>
			</div>
			<div class="col-xs-6">
				<button id="btnModalMoneyAccept" class="btn btn-primary btn-block">Отправить</button>
			</div>
		</div>
	',
	'id'=>'modalMoney',
	'size'=>'modal-md',
	'options' => ['class'=>'modal-money'],
]); ?>

	<h4 class="text-center mb-2">Укажите номер вашей карты для зачисления выигрыша</h4>
	<div class="form-group mb-3">
		<input class="form-control" type="text" name="cart" placeholder="0000 0000 0000 0000" id="inputModalMoneyCart">
	</div>

	<h4 class="text-center mb-2">Или конвертируйте деньги в баллы</h4>
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4 col-8 col-offset-2">
			<button id="btnModalMoneyConvert" class="btn btn-success btn-block">Конвертировать</button>
		</div>
	</div>

<? Modal::end(); ?>

<? Modal::begin([
	'footer'=>'
		<div class="row">
			<div class="col-xs-6">
				<button class="btn btn-default btn-block" data-dismiss="modal">Отмена</button>
			</div>
			<div class="col-xs-6">
				<button id="btnModalGiftAccept" class="btn btn-primary btn-block">Отправить</button>
			</div>
		</div>
	',
	'id'=>'modalGift',
	'size'=>'modal-md',
	'options' => ['class'=>'modal-gift'],
]); ?>

	<h4 class="text-center mb-2">Укажите ваш адрес чтобы мы выслали вам выигрыш</h4>
	<div class="form-group mb-3">
		<textarea class="form-control" type="text" name="address" placeholder="Город, улица, дом" id="inputModalGiftAddress"></textarea>
	</div>

<? Modal::end(); ?>