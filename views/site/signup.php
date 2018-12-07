<h1 class="text-center mb-4">Регистрация</h1>

<?php
use yii\helpers\Html;
use \yii\widgets\ActiveForm;

$form = ActiveForm::begin(['id'=>'formSignup']);
?>
<div class="row">
	<div class="col-sm-4 col-sm-offset-4 col-8 col-offset-2">
		<?= $form->field($model,'email')->textInput(['autofocus' => true, 'placeholder' => 'sample@email.com'])->label('E-mail'); ?>
		<?= $form->field($model,'password')->passwordInput(['placeholder' => '******'])->label('Пароль'); ?>

		<div class="form-group mt-3">
			<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success btn-lg btn-block', 'name' => 'signup-button']) ?>
		</div>
	</div>
</div>

<? ActiveForm::end(); ?>