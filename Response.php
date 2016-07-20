<?php
namespace Dfe\AllPay;
use Df\Framework\Controller\Result\Text;
use Df\Sales\Model\Order as DfOrder;
use Dfe\AllPay\Response\ATM;
use Dfe\AllPay\Response\BankCard;
use Magento\Framework\Phrase;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Payment as OP;
/**
 * 2016-07-09
 * The response is documented in the Chapter 7 «Payment Result Notification»
 * on the pages 32-35 of the allPay documentation.
 */
abstract class Response extends \Df\Payment\R\Response {
	/**
	 * 2016-07-12
	 * @return void
	 */
	abstract protected function handleInternal();

	/**
	 * 2016-07-04
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return Text
	 */
	public function handle() {
		/** @var Text $result */
		try {
			$this->log($_REQUEST);
			$this->validate();
			$this->addTransaction();
			/**
			 * 2016-07-14
			 * Если покупатель не смог или не захотел оплатить заказ,
			 * то мы заказ отменяем, а затем, когда платёжная система возврат покупателя в магазин,
			 * то мы проверим, не отменён ли последний заказ,
			 * и если он отменён — то восстановим корзину покупателя.
			 */
			$this->isSuccessful() ? $this->handleInternal() : $this->order()->cancel();
			$this->order()->save();
			/**
			 * 2016-07-15
			 * Send email confirmation to the customer.
			 * https://code.dmitry-fedyuk.com/m2e/allpay/issues/6
			 * It is implemented by analogy with https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Paypal/Model/Ipn.php#L312-L321
			 */
			/**
			 * 2016-07-15
			 * What is the difference between InvoiceSender and OrderSender?
			 * https://mage2.pro/t/1872
			 */
			/**
			 * 2016-07-18
			 * Раньше тут был код:
					$payment = $this->order()->getPayment();
					if ($payment && $payment->getCreatedInvoice()) {
						df_order_send_email($this->order());
					}
			 */
			df_order_send_email($this->order());
			$result = Text::i('1|OK');
			df_log('OK');
		}
		catch (\Exception $e) {
			/**
			 * 2016-07-15
			 * Раньше тут стояло
					if ($this->_order) {
						$this->_order->cancel();
						$this->_order->save();
					}
			 * На самом деле, исключительная ситуация свидетельствует о сбое в программе,
			 * либо о некорректном запросе якобы от платёжного сервера (хакерской попытке, например),
			 * поэтому отменять заказ тут неразумно.
			 * В случае сбоя платёжная система будет присылать
			 * повторные оповещения — вот пусть и присылает,
			 * авось мы к тому времени уже починим программу, если поломка была на нашей строне
			 */
			$result = Text::i('0|' . df_le($e)->getMessage());
			df_log('FAILURE');
			df_log($e);
		}
		return $result;
	}

	/**
	 * 2016-07-12
	 * @used-by \Dfe\AllPay\Response::isSuccessful()
	 * @return int
	 */
	abstract protected function expectedRtnCode();

	/**
	 * 2016-07-19
	 * @used-by \Dfe\AllPay\Block\Info::_prepareSpecificInformation()
	 * @see \Dfe\AllPay\Response\ATM::_prepareSpecificInformation()
	 * @return array(strig => string)
	 */
	public function getInformationForBlock() {return [];}

	/**
	 * 2016-07-09
	 * 2016-07-14
	 * Раньше метод isSuccessful() вызывался из метода @see \Df\Payment\R\Response::validate().
	 * Отныне же @see \Df\Payment\R\Response::validate() проверяет,
	 * корректно ли сообщение от платёжной системы.
	 * Даже если оплата завершилась отказом покупателя, но оповещение об этом корректно,
	 * то @see \Df\Payment\R\Response::validate() вернёт true.
	 * isSuccessful() же проверяет, прошла ли оплата успешно.
	 * @override
	 * @see \Df\Payment\R\Response::isSuccessful()
	 * @return bool
	 */
	public function isSuccessful() {return $this->expectedRtnCode() === intval($this['RtnCode']);}

	/**
	 * 2016-07-18
	 * @return string
	 */
	public function paymentTypeTitle() {
		if (!isset($this->{__METHOD__})) {
			/** @var string $result */
			$result = $this['PaymentType'];
			df_assert_string_not_empty($result);
			/** @var string[] $a */
			$a = explode('_', $result);
			/** @var int $c */
			$c = count($a);
			if (1 < $c) {
				/** @var string $f */
				$f = $a[0];
				/** @var string $l */
				$l = $a[1];
				/** @var string|null */
				$resultD = dfa_deep($this->moduleJson('titles'), [$f, $l]);
				if ($resultD) {
					$resultD = __($resultD);
					$result =
						in_array($f, ['ATM', 'WebATM'])
						? implode(' ', [__($f), $resultD])
						: $resultD
					;
				}
			}
			$this->{__METHOD__} = $result;
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-10
	 * @override
	 * @see \Df\Payment\R\Response::externalIdKey()
	 * @used-by \Df\Payment\R\Response::externalId()
	 * @return string
	 */
	protected function externalIdKey() {return 'TradeNo';}

	/**
	 * 2016-07-09
	 * @override
	 * @see \Df\Payment\R\Response::requestIdKey()
	 * @used-by \Df\Payment\R\Response::requestId()
	 * @return string
	 * «Merchant trade number»
	 * Varchar(20)
	 * «When order is generated, it would send allPay coorperator
	 * a trade number with upper and lower cases of English letters and numbers.»
	 */
	protected function requestIdKey() {return 'MerchantTradeNo';}

	/**
	 * 2016-07-10
	 * @override
	 * @see \Df\Payment\R\Response::signatureKey()
	 * @used-by \Df\Payment\R\Response::signatureProvided()
	 * @return string
	 */
	protected function signatureKey() {return 'CheckMacValue';}

	/**
	 * 2016-07-12
	 * The response is documented in the Chapter 7 «Payment Result Notification»
	 * on the pages 32-35 of the allPay documentation.
	 * @override
	 * @see \Df\Payment\R\Response::testData()
	 * @param string $type
	 * @return array(string => string)
	 */
	protected function testData($type) {
		/** @var string $basename */
		$basename = df_cc_clean('-', df_class_last(get_class($this)), $type);
		return df_json_decode(file_get_contents(BP . "/_my/test/allPay/{$basename}.json"));
	}

	/**
	 * 2016-07-06
	 * @param mixed $message
	 * @return void
	 */
	private function log($message) {if (!df_my_local()) {df_log($message);}}

	/**
	 * 2016-07-13
	 * @override
	 * @see \Df\Payment\R\Response::i()
	 * @param array(string => mixed)|string $params
	 * @return self
	 */
	public static function i($params) {
		return self::ic(
			!is_array($params)
				? ATM::class
				: dfa(['ATM' => ATM::class, 'Credit' => BankCard::class],
					df_first(explode('_', dfa($params, 'PaymentType')))
				)
			, $params
		);
	}
}


