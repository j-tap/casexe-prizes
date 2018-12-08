<h1 class="text-center mb-4">Вход</h1>

<?php
use yii\helpers\Html;
use \yii\widgets\ActiveForm;

$form = ActiveForm::begin(['id'=>'formSignin']);
?>
<div class="row">
	<div class="col-sm-4 col-sm-offset-4 col-8 col-offset-2">
		<?= $form->field($model,'email')->textInput(['autofocus'=>true, 'placeholder' => 'sample@email.com'])->label('E-mail'); ?>
		<?= $form->field($model,'password')->passwordInput(['placeholder' => '******'])->label('Пароль'); ?>

		<div class="form-group mt-3">
			<?= Html::submitButton('Войти', ['class' => 'btn btn-info btn-lg btn-block', 'name' => 'signin-button']) ?>
		</div>
	</div>
</div>

<? ActiveForm::end(); ?>