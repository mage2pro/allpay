define ([
	'jquery'
	, 'df'
	, 'Df_Checkout/js/action/place-order'
	, 'Df_Checkout/js/data'
	, 'Df_Core/js/redirectWithPost'
	, 'Magento_Checkout/js/model/payment/additional-validators'
 	, 'Df_Payment/js/view/payment/default'
], function(
	$, df, placeOrderAction, dfCheckout, redirectWithPost, additionalValidators, Component
) {
	'use strict';
	return Component.extend({
		defaults: {
			clientConfig: {id: 'dfe-all-pay'}
			,code: 'dfe_all_pay'
			,template: 'Dfe_AllPay/item'
		},
		/**
		 * 2016-07-27
		 * @return {Boolean}
		 */
		askForBillingAddress: function() {return this.config('askForBillingAddress');},
		/**
		 * 2016-07-01
   		 * @override
   		 */
		getData: function() {return {additional_data: {}, method: this.item.method};},
		/**
		 * 2016-07-01
		 * Перекрыли родительский метод,
		 * чтобы подставить свой placeOrderAction вместо родительского.
		 * @override
		 * https://github.com/magento/magento2/blob/981d1f/app/code/Magento/Checkout/view/frontend/web/js/view/payment/default.js#L161-L165
		 * @return {jQuery.Deferred}
		*/
		getPlaceOrderDeferredObject: function() {
			return $.when(placeOrderAction(this.getData(), this.messageContainer, 'dfe-allpay'));
		},
		/**
		 * 2016-07-01
		 * @return {String}
		*/
		getTitle: function() {
			var result = this._super();
			return result + (!this.isTest() ? '' : ' [<b>TEST MODE</b>]');
		},
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
		 * 2016-07-01
		 * @override
		 * https://github.com/magento/magento2/blob/981d1f/app/code/Magento/Checkout/view/frontend/web/js/view/payment/default.js#L127-L159
		 * @return {Boolean}
		*/
		placeOrder: function(data, event) {
			var _this = this;
			if (event) {
				event.preventDefault();
			}
			/** @type {Boolean} */
			var result = this.validate() || additionalValidators.validate();
			if (result) {
				this.isPlaceOrderActionAllowed(false);
				this.getPlaceOrderDeferredObject()
					.fail(function() {_this.isPlaceOrderActionAllowed(true);})
					.done(function(json) {
					  	_this.afterPlaceOrder();
					 	/** @type {Object} */
						var data = $.parseJSON(json);
						// 2016-07-10
						// @see \Dfe\AllPay\Method::getConfigPaymentAction()
					  	redirectWithPost(data.uri, data.params);
					})
				;
			}
			return result;
		}
	});
});
