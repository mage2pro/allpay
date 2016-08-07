define ([
	'df'
	,'Df_Core/js/redirectWithPost'
 	,'Df_Payment/js/view/payment/default'
	,'Dfe_AllPay/plan'
  	,'jquery'
	,'mage/translate'
], function(df, redirectWithPost, parent, Plan, $, $t) {'use strict'; return parent.extend({
	defaults: {
		df: {
			test: {showBackendTitle: false},
			// 2016-08-06
			// @used-by mage2pro/core/Payment/view/frontend/web/template/item.html
			formTemplate: 'Dfe_AllPay/form'
		}
	},
	/**
	 * 2016-07-07
	 * @return {Object}
	*/
	initialize: function() {
		this._super();
		/** @type {Boolean} */
		this.df.askForBillingAddress = this.config('askForBillingAddress');
		if (!this.df.askForBillingAddress) {
			this.isPlaceOrderActionAllowed(true);
		}
		return this;
	},
	/**
	 * 2016-08-04
	 * @return {Object}
	 */
	installment: function() {return this.config('installment');},
	/**
	 * 2016-08-04
	 * @return {Object[]}
	 */
	iPlans: function() {
		if (df.undefined(this._iPlans)) {
			/** @type {Number} */
			var rateToTWD = this.config('currencyRateFromBaseToTWD');
			this._iPlans = $.map(this.installment().plans, function(plan) {
				return Plan({
					count: parseInt(plan.count)
					,fee: parseFloat(plan.fee)
					,rate: parseFloat(plan.rate)
				}, rateToTWD);
			}).sort(function(a, b) {return a.count - b.count;});
		}
		return this._iPlans;
	},
	/** @returns {String} */
	oneOffPriceS: function() {
		return $t('One-off Price: {amount}')
			.replace('{amount}', this.dfc.formatMoney(this.dfc.grandTotal()))
		;
	},
	/**
	 * 2016-08-06
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/js/view/payment/mixin.js
	 * @used-by placeOrderInternal()
	 */
	onSuccess: function(json) {
		/** @type {Object} */
		var data = $.parseJSON(json);
		// 2016-07-10
		// @see \Dfe\AllPay\Method::getConfigPaymentAction()
		redirectWithPost(data.uri, data.params);
	},
	/**
	 * 2016-07-01
	 * @override
	 * @see https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Checkout/view/frontend/web/js/view/payment/default.js#L127-L159
	 * @used-by https://github.com/magento/magento2/blob/2.1.0/lib/web/knockoutjs/knockout.js#L3863
	*/
	placeOrder: function() {
		if (this.validate()) {
			this.placeOrderInternal();
		}
	}
});});
