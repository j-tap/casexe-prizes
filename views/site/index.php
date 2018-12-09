<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Button;
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
						<span class="glyphicon glyphicon-<?= $category['icon'] ?>" aria-hidden="true"></span>
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
	'header'=>false,
	'footer'=>'
		<button id="modalPrizeDismiss" class="btn btn-warning">Отказаться</button>
		<button id="modalPrizeAccept" class="btn btn-success">Прянять</button>
	',
	'id'=>'modalPrize',
	'size'=>'modal-lg',
]); ?>

<h4 id="modalPrizeTitle"></h4>

<? Modal::end(); ?>