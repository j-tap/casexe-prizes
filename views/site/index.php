<h1 class="text-center mb-4">Розыгрыш призов</h1>
<section class="section-getprizes">
	<h3>Вы можете получить случайный приз</h3>
	<p class="text-muted mb-3">*призы ограничены</p>
	<ul class="list-unstyled row">

		<? foreach ($categories as $category): ?>
			<li class="col-sm-4" id="prize<?= $category['id'] ?>">
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
			<button class="btn btn-danger btn-block btn-lg" id="btnGetPrize">Получить!</button>

<? 
$js = <<<JS
	$('#btnGetPrize').on('click', function () {
		$.ajax({
			url: '/',
			type: 'POST',
			data: {'getPrize': true},
			success: function (res) {
				console.log(res);
			},
			error: function () {
				console.error('Error! #btnGetPrize click');
			}
		});
		return false;
	});
JS;

$this->registerJs($js);
?>

		</div>
	</div>
</section>