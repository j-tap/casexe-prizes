<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php $this->registerCsrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
	<?php
	NavBar::begin([
		'brandLabel' => Yii::$app->name,
		'brandUrl' => Yii::$app->homeUrl,
		'options' => [
			'class' => 'navbar-inverse navbar-fixed-top',
		],
	]);

	$navItems = [];

	if (Yii::$app->user->isGuest) {
		switch (Yii::$app->controller->action->id) {
			case 'signin':
				$navItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
				break;

			case 'signup':
				$navItems[] = ['label' => 'Вход', 'url' => ['/site/signin']];
				break;
		}
	} else {
		$navItems[] = '<li><span class="navbar-text">' 
			. Yii::$app->user->identity->email 
			. '</span></li>';

		$navItems[] = '<li><span class="navbar-text">Баллы: <span class="badge badge-success">' 
			. Yii::$app->user->identity->score 
			. '</span></span></li>';

		$navItems[] = '<li>'
			. Html::beginForm(['/site/logout'], 'post')
			. Html::submitButton(
				'<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>'
				. '<span class="sr-only">Выход</span>',
				['class' => 'btn btn-link logout', 'title' => 'Выйти']
			)
			. Html::endForm()
			. '</li>';
	}

	echo Nav::widget([
		'options' => ['class' => 'navbar-nav navbar-right'],
		'items' => $navItems
	]);
	NavBar::end();
	?>

	<div class="container">
		<?= Alert::widget() ?>
		<?= $content ?>
	</div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
