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
		this.$modalPrizeTitle = $('#modalPrizeTitle');
		this.$modalPrizeAccept = $('#modalPrizeAccept');
		this.$modalPrizeDismiss = $('#modalPrizeDismiss');

		this.$btnGetPrize.on('click', (event) => {
			$(event.currentTarget).prop('disabled', true);
			this.getPrize(() => {
				$(event.currentTarget).removeAttr('disabled');
			});
			return false;
		});

		this.$modalPrizeDismiss.on('click', (event) => {
			this.dismissPrize();
			return false;
		});

		this.$modalPrizeAccept.on('click', (event) => {
			this.acceptPrize();
			return false;
		});
	},

	openModal () {
		this.$modalPrizeTitle.empty().text(this.title);
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
			},
			error: () => {
				console.error('Error getPrize Ajax');
			},
			complete: fComplete()
		});
	},

	dismissPrize () {
		this.$modalPrize.modal('hide');
	},

	acceptPrize () {
		if (!this.prize) return true;
		this.managePrize();

		$.ajax({
			url: '/',
			type: 'POST',
			data: {'acceptPrize': true},
			success: (response) => {
				oResp = $.parseJSON(response);
				console.log(oResp);
			},
			error: () => {
				console.error('Error acceptPrize Ajax');
			},
			complete: () => {}
		});
	}
}