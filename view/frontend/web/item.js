define ([
	'Df_Core/js/redirectWithPost'
 	,'Df_Payment/js/view/payment/default'
  	,'jquery'
], function(redirectWithPost, parent, $) {'use strict'; return parent.extend({
	/**
	 * 2016-07-27
	 * @return {Boolean}
	 */
	askForBillingAddress: function() {return this.config('askForBillingAddress');},
	defaults: {df: {test: {showBackendTitle: false}}, template: 'Dfe_AllPay/item'},
	/**
	 * 2016-07-01
	 * @override
	 */
	getData: function() {return {additional_data: {}, method: this.item.method};},
	/**
	 * 2016-07-07
	 * @return {Object}
	*/
	initialize: function() {
		this._super();
		if (!this.askForBillingAddress()) {
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
	iPlans: function() {return this.installment().plans;},
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
