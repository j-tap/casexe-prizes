$(() => {
	User.init();
	Lottery.init();
})

const User = {
	init () {
		this.$score = $('#userScore');
		this.score = parseInt( this.$score.text() );
	},
	
	updateScore (newScore) {
		this.score += parseInt(newScore);
		this.$score.html(this.score);
	}
}

const Lottery = {
	init () {
		this.$btnGetPrize = $('#btnGetPrizeAjax');
		this.$modalPrize = $('#modalPrize');

		this.$btnGetPrize.on('click', (event) => {
			$(event.currentTarget).prop('disabled', true);
			this.getPrize(() => {
				$(event.currentTarget).removeAttr('disabled');
			});
			return false;
		});
	},

	getPrize (fComplete) {
		$.ajax({
			url: '/',
			type: 'POST',
			data: {'getPrize': true},
			success: (response) => {
				oResp = $.parseJSON(response);
				this.title = oResp.title;
				this.prize = oResp.prize;
				this.timeout = oResp.timeout || 0;
				
				this.openModal();

				if (this.prize) this.managePrize();
			},
			error: () => {
				console.error('Error get Prize Ajax');
			},
			complete: fComplete()
		});
	},

	openModal () {
		this.$modalPrize.find('.modal-body').empty().append(
			$('<h4/>', {text: this.title})
		);
		this.$modalPrize.modal('show');
	},

	managePrize () {
		switch (this.prize.type) {
			case 'score':
				User.updateScore(this.prize.count);
				break;
		
			case 'money':
				
				break;

			case 'gift':
				
				break;
		}
	}
}