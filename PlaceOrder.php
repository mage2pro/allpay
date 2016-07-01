<?php
namespace Dfe\AllPay;
use Magento\Checkout\Api\GuestPaymentInformationManagementInterface as IGuest;
use Magento\Checkout\Api\PaymentInformationManagementInterface as IRegistered;
use Magento\Quote\Api\Data\AddressInterface as IAddress;
use Magento\Quote\Api\Data\PaymentInterface as IPayment;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment;
class PlaceOrder {
	/**
	 * 2016-05-04
	 * @param string $cartId
	 * @param string $email
	 * @param IPayment $paymentMethod
	 * @param IAddress|null $billingAddress
	 * @throws \Magento\Framework\Exception\CouldNotSaveException
	 * @return string
	 */
	public function guest($cartId, $email, IPayment $paymentMethod, IAddress $billingAddress = null) {
		/** @var IGuest $iGuest */
		$iGuest = df_o(IGuest::class);
		return $this->response($iGuest->savePaymentInformationAndPlaceOrder(
			$cartId, $email, $paymentMethod, $billingAddress
		));
	}

	/**
	 * 2016-05-04
	 * @param int $cartId
	 * @param IPayment $paymentMethod
	 * @param IAddress|null $billingAddress
	 * @throws \Magento\Framework\Exception\CouldNotSaveException
	 * @return string
	 */
	public function registered($cartId, IPayment $paymentMethod, IAddress $billingAddress = null) {
		/** @var IRegistered $iRegistered */
		$iRegistered = df_o(IRegistered::class);
		return $this->response($iRegistered->savePaymentInformationAndPlaceOrder(
			$cartId, $paymentMethod, $billingAddress
		));
	}

	/**
	 * 2016-05-04
	 * @param int $orderId
	 * @return string|null
	 */
	private function response($orderId) {
		return df_order($orderId)->getPayment()->getAdditionalInformation(Method::REDIRECT_URL);
	}
}


