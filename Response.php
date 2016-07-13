<?php
namespace Dfe\AllPay;
use Df\Framework\Controller\Result\Text;
use Df\Sales\Model\Order as DfOrder;
use Dfe\AllPay\Response\ATM;
use Dfe\AllPay\Response\BankCard;
use Magento\Sales\Model\Order;
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
			$this->handleInternal();
			$this->order()->save();
			$result = Text::i('1|OK');
			df_log('OK');
		}
		catch (\Exception $e) {
			if ($this->_order) {
				$this->_order->cancel();
				$this->_order->save();
			}
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
	 * 2016-07-10
	 * @see \Df\Payment\R\Response::externalIdKey()
	 * @used-by \Df\Payment\R\Response::externalId()
	 * @return string
	 */
	protected function externalIdKey() {return 'TradeNo';}

	/**
	 * 2016-07-09
	 * @override
	 * @see \Df\Payment\R\Response::isSuccessful()
	 * @used-by \Df\Payment\R\Response::validate()
	 * @return bool
	 */
	protected function isSuccessful() {return $this->expectedRtnCode() === intval($this['RtnCode']);}

	/**
	 * 2016-07-09
	 * @override
	 * @see \Df\Payment\R\Response::messageKey()
	 * @used-by \Df\Payment\R\Response::message()
	 * @return string
	 * «Trade message»
	 */
	protected function messageKey() {return 'RtnMsg';}

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
	 * @return array(string => string)
	 */
	protected function testData() {
		/** @var string $type */
		$type = df_class_last(get_class($this));
		return df_json_decode(file_get_contents(BP . "/_my/test/allPay/{$type}.json"));
	}

	/**
	 * 2016-07-06
	 * @param mixed $message
	 * @return void
	 */
	private function log($message) {if (!df_is_it_my_local_pc()) {df_log($message);}}

	/**
	 * 2016-07-13
	 * @override
	 * @see \Df\Payment\R\Response::i()
	 * @param array(string => mixed)|true $params
	 * @return self
	 */
	public static function i($params) {
		return self::ic(
			true === $params
				? BankCard::class
				: dfa(['ATM' => ATM::class, 'Credit' => BankCard::class],
					df_first(explode('_', dfa($params, 'PaymentType')))
				)
			, $params
		);
	}
}


