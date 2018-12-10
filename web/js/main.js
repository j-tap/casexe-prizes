$(() => {
	User.init();
	Lottery.init();
	Msg.init();
})

const User = {
	init () {
		this.$score = $('#userScore');
		this.score = parseInt( this.$score.text() );
	},
	
	updateScore (newScore) {
		this.score = parseInt(newScore); 
		this.$score.html(this.score);
	}
}

const Lottery = {
	init () {
		this.$btnGetPrize = $('#btnGetPrizeAjax');
		this.$modalPrize = $('#modalPrize');
		this.$modalPrizeTitle = $('#modalPrizeTitle');
		this.$modalPrizeSubTitle = $('#modalPrizeSubTitle');
		this.$btnModalPrizeAccept = $('#btnModalPrizeAccept');
		this.$btnModalPrizeDismiss = $('#btnModalPrizeDismiss');
		this.$modalPrizeIcon = $('#modalPrizeIcon');
		this.$modalMoney = $('#modalMoney');
		this.$btnModalMoneyAccept = $('#btnModalMoneyAccept');
		this.$btnModalMoneyConvert = $('#btnModalMoneyConvert');
		this.$inputModalMoneyCart = $('#inputModalMoneyCart');
		this.$modalGift = $('#modalGift');
		this.$btnModalGiftAccept = $('#btnModalGiftAccept');
		this.$inputModalGiftAddress = $('#inputModalGiftAddress');

		$('.modal').modal({backdrop:'static', keyboard:false, show:false});

		this.$btnGetPrize.on('click', (event) => {
			$(event.currentTarget).prop('disabled', true);
			this.getPrize(() => {
				$(event.currentTarget).removeAttr('disabled');
			});
			return false;
		});

		this.$btnModalPrizeDismiss.on('click', (event) => {
			this.getAjax({type: 'dismissPrize', key: this.key}, () => {
				this.$modalPrize.modal('hide');
			});
			return false;
		});

		this.$btnModalPrizeAccept.on('click', (event) => {
			if (!this.prize) return true;
			this.managePrize();
			this.getAjax({type: 'acceptPrize', key: this.key}, () => {
				this.$modalPrize.modal('hide');
			});
			return false;
		});
	},

	managePrize () {
		switch (this.prize.type) {
			case 'score':
				let newScore = User.score += parseInt(this.prize.count);
				User.updateScore(newScore);
				break;
		
			case 'money':
				$('.modal').modal('hide');
				this.$modalMoney.modal('show');

				this.$btnModalMoneyConvert.on('click', (event) => {
					let $btn = $(event.currentTarget);
					$btn.prop('disabled', true);
					this.$modalMoney.modal('hide');
					
					this.getAjax({type: 'convertMoney', key: this.key}, (oResp) => {
						this.$modalMoney.modal('hide');
						if (oResp.is) User.updateScore(oResp.score);
						else Msg.show(oResp.title);
					}, () => {
						$btn.removeAttr('disabled');
					});

					return false;
				});

				this.$btnModalMoneyAccept.on('click', (event) => {
					if (true) { // нужна валидация
						let $btn = $(event.currentTarget);
						$btn.prop('disabled', true);
						
						User.cart = this.$inputModalMoneyCart.val();
						this.$modalMoney.modal('hide');
						
						this.getAjax({
							type: 'moneySend', 
							cart: User.cart, 
							key: this.key
						}, (oResp) => {
							this.$modalMoney.modal('hide');
							Msg.show(oResp.title);
						}, () => {
							$btn.removeAttr('disabled');
						});
					}
					return false;
				});
				break;

			case 'gift':
				$('.modal').modal('hide');
				this.$modalGift.modal('show');
				
				this.$btnModalGiftAccept.on('click', (event) => {
					if (true) { // нужна валидация
						let $btn = $(event.currentTarget);
						$btn.prop('disabled', true);
						User.address = this.$inputModalGiftAddress.val();
						this.$modalGift.modal('hide');
						
						this.getAjax({
							type: 'giftSend',
							key: this.key,
							address: User.address
						}, (oResp) => {
							this.$modalMoney.modal('hide');
							Msg.show(oResp.title);
						}, () => {
							$btn.removeAttr('disabled');
						});
					}
					return false;
				});
				break;
		}
	},

	getPrize (fComplete) {
		this.getAjax({type: 'getPrize'}, () => {
			this.key = oResp.prize.key;
			this.title = oResp.title;
			this.subtitle = oResp.subtitle;
			this.prize = oResp.prize;
			this.timeout = oResp.timeout || 0;
			
			$('.modal').modal('hide');

			if (oResp.prize) {
				this.$modalPrizeTitle.empty().text(this.title);
				this.$modalPrizeSubTitle.empty().text(this.subtitle);
				this.$modalPrizeIcon.removeClassMask('glyphicon-*').addClass('glyphicon-'+ this.prize.icon);
				this.$modalPrize.modal('show');
			} else {
				Msg.show(this.title);
			}

		}, fComplete);
	},

	getAjax (oData, fSuccess, fComplete) {
		$.ajax({
			url: '/',
			type: 'POST',
			data: oData,
			success: (resp) => {
				oResp = $.parseJSON(resp);
				//console.log(oResp);
				fSuccess(oResp);
			},
			error: (xhr, exception) => {
				console.error('Error! Status: '+ xhr.status +'. Exception: '+ exception);
				console.info(xhr.responseText)
			},
			complete: () => {
				if (fComplete) fComplete();
			}
		});
	}
}

const Msg = {
	init () {
		this.$modal = $('#modalMsg');
		this.$modalTitle = $('#modalMsgTitle');
		this.$modalBody = $('#modalMsgBody');

		this.$modal.modal({backdrop:true, keyboard:true, show:false});
	},

	show (title, text) {
		this.$modalTitle.text(title);
		this.$modalBody.text(text);
		this.$modal.modal('show');
	},
	hide () {
		this.$modalTitle.empty();
		this.$modalBody.empty();
		this.$modal.modal('hide');
	}
}

$.fn.removeClassMask = function (mask) {
	return this.removeClass(function (i, cls) {
		let repl = mask.replace(/\*/g, '\\S+');
		return (cls.match(new RegExp('\\b' + repl + '', 'g')) || []).join(' ');
	});
};