// 2016-08-06
define (['df', 'Df_Checkout/js/data', 'mage/translate'], function(
	df, dfc, $t
) {'use strict'; return (
	/**
	 * 2016-08-06
	 * @param {Object} plan
	 * @param {Number} plan.count
	 * @param {Number} plan.rate
	 * @param {Number} plan.fee
	 * @param {Number} rateToTWD
	 * @returns {Object}
	 */
	function(plan, rateToTWD) {return {
	/** @returns {Number} */
	amount: function() {
		if (df.undefined(this._amount)) {
			this._amount =
				dfc.grandTotal() * (1 + plan.rate / 100)
				+ plan.fee * rateToTWD * this.numPayments()
			;
		}
		return this._amount;
	},
	/** @returns {String} */
	amountS: function() {
		return $t('Order Total: {amount}').replace('{amount}', dfc.formatMoney(this.amount()));
	},
	count: plan.count,
	/** @returns {String} */
	domId: function() {return 'df-plan-' + plan.count;},
	/** @returns {String} */
	duration: function() {
		return $t(1 === this.count ? '1 month' : '{count} months').replace('{count}', this.count);
	},
	/** @returns {Number} */
	firstPayment: function() {
		if (df.undefined(this._firstPayment)) {
			this._firstPayment = Math.ceil(this.amount() / this.numPayments());
		}
		return this._firstPayment;
	},
	/** @returns {String} */
	firstPaymentS: function() {
		return $t('First Payment: {amount}').replace('{amount}', dfc.formatMoney(this.firstPayment()));
	},
	/**
	 * 2016-08-07
	 * Добавляем 1 к count, потому что count означает количество месяцев,
	 * а платежей на 1 больше, чем месяцев.
	 * @returns {Number}
	 */
	numPayments: function() {return 1 + plan.count;},
	/** @returns {String} */
	title: function() {
		return [plan.count, plan.rate, plan.fee].join(' ')
	}
};});});
