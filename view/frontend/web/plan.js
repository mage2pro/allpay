// 2016-08-06
define (['df', 'df-lodash', 'Df_Checkout/js/data'], function(
	df, _, dfc
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
	amount: df.c(function() {
		return dfc.grandTotal() * (1 + plan.rate / 100) + plan.fee * rateToTWD * this.numPayments();
	}),
	/** @returns {String} */
	amountS: function() {return df.t('Order Total: %s', dfc.formatMoney(this.amount()));},
	count: plan.count,
	/** @returns {String} */
	domId: function() {return 'df-plan-' + plan.count;},
	/** @returns {String} */
	duration: function() {return df.t(1 === this.count ? '1 month' : '%s months', this.count);},
	/** @returns {Number} */
	firstPayment: df.c(function() {return Math.ceil(this.amount() / this.numPayments());}),
	/** @returns {String} */
	firstPaymentS: function() {return df.t(
		'First Payment: %s', dfc.formatMoney(this.firstPayment())
	);},
	/**
	 * 2016-08-07
	 * Добавляем 1 к count, потому что count означает количество месяцев,
	 * а платежей на 1 больше, чем месяцев.
	 * @returns {Number}
	 */
	numPayments: function() {return 1 + plan.count;}
};});});
