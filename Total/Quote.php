<?php
namespace Dfe\AllPay\Total;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Dfe\AllPay\Method as M;
use Dfe\AllPay\Settings as S;
use Dfe\AllPay\TWD;
use Magento\Payment\Model\InfoInterface as IPayment;
use Magento\Quote\Api\Data\ShippingAssignmentInterface as IShippingAssignment;
use Magento\Quote\Model\Quote as Q;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote\Address\Total\CollectorInterface;
use Magento\Quote\Model\Quote\Payment as QP;
use Magento\Quote\Model\ShippingAssignment;
use Magento\Sales\Model\Order\Payment as OP;
/**
 * 2016-08-13
 * Оказывается, что недостаточно реализовать интерфейсы
 * @see \Magento\Quote\Model\Quote\Address\Total\CollectorInterface
 * и @see \Magento\Quote\Model\Quote\Address\Total\ReaderInterface
 * а надо именно наследоваться от @see \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
 * иначе получим сбой «The address total model should be extended
 * from Magento\Quote\Model\Quote\Address\Total\AbstractTotal»
 * @see \Magento\Quote\Model\Quote\Address\Total\Collector::_initModelInstance()
 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Quote/Model/Quote/Address/Total/Collector.php#L122-L129
 */
class Quote extends AbstractTotal {
	/**
	 * 2016-08-13
	 * Как правило, мы попадаем сюда дважды для одного и того же заказа:
	 * для адреса доставки и для платёжного адреса,
	 * потому что totals рассчитываются в контексте каждого адреса:
	 * @see \Magento\Quote\Model\Quote\TotalsCollector::collect()
	 * https://github.com/magento/magento2/blob/200880/app/code/Magento/Quote/Model/Quote/TotalsCollector.php#L143-L144
	 *
	 * При этом при повторном попадении (для платёжного адреса),
	 * все данные, как правило, содержат нули, потому что первый же коллектор
	 * @see \Magento\Quote\Model\Quote\Address\Total\Subtotal::collect()
	 * работает только для адреса доставки:
	 * https://github.com/magento/magento2/blob/200880/app/code/Magento/Quote/Model/Quote/Address/Total/Subtotal.php#L50-L51
	 *
	 * @override
	 * @see \Magento\Quote\Model\Quote\Address\Total\AbstractTotal::collect()
	 *
	 * @used-by \Magento\Quote\Model\Quote\TotalsCollector::collectAddressTotals()
	 * https://github.com/magento/magento2/blob/200880/app/code/Magento/Quote/Model/Quote/TotalsCollector.php#L263-L266
	 * How does \Magento\Quote\Model\Quote\TotalsCollector::collectAddressTotals() work?
	 * https://mage2.pro/t/1950
	 *
	 * @param Q $quote
	 * @param IShippingAssignment|ShippingAssignment $shippingAssignment
	 * @param Total $total
	 * @return CollectorInterface
	 */
	function collect(Q $quote, IShippingAssignment $shippingAssignment, Total $total) {
		/**
		 * 2016-08-15
		 * Что интересно, при первых вызовах $payment->getMethod() возвращает null:
		 * @see \Magento\Checkout\Model\PaymentInformationManagement::savePaymentInformation()
		 * Там такой код:
		 *		if ($billingAddress) {
		 *			$this->billingAddressManagement->assign($cartId, $billingAddress);
		 *		}
		 *		$this->paymentMethodManagement->set($cartId, $paymentMethod);
		 * Так вот, впервые мы попадаем сюда уже при вызове assign,
		 * и тогда у $quote ещё отсутствует ссылка на $payment,
		 * хотя сам объект $payment к тому времени уже создан.
		 *
		 * Что интересно (и аналогично комментарию выше) при первом попадении сюда $planId ещё отсутствует,
		 * потому что мы попадаем сюда из @see \Magento\Quote\Model\Quote\Payment::importData(),
		 * и там такой код:
		 *		$quote->collectTotals();
		 *		(...)
		 *		$method->assignData($data);
		 * $planId инициализируется только на assignData, а сюда мы попадаем уже на collectTotals.
		 */
		/** @var QP $qp */
		/** @var int|null $planId */
		if (($qp = dfp($quote)) && $qp->getMethod() === M::codeS() && ($planId = dfp_iia($qp, 'plan'))) {
			/** @var S $s */
			$s = dfps($qp);
			/** @var Plan $plan */
			$plan = df_assert($s->installmentSales()->plans($planId));
			$this->setCode('dfe_allpay');
			parent::collect($quote, $shippingAssignment, $total);
			/** @var string $quoteCurrency */
			$quoteCurrency = $quote->getQuoteCurrencyCode();
			/** @var string $baseCurrency */
			$baseCurrency = $quote->getBaseCurrencyCode();
			/** 2016-08-13 По аналогии с @see \Magento\Quote\Model\Quote\Address\Total\Grand::collect() */
			/** @var float $totals */
			$totals = array_sum($total->getAllTotalAmounts());
			/** @var float|int $totalsNew */
			$totalsNew = $plan->amount($totals, $quoteCurrency);
			/** @var float $fee */
			$fee = TWD::round($totalsNew - $totals, $quoteCurrency);
			$this->_setAmount($fee);
			/** @var float $baseTotals */
			$baseTotals = array_sum($total->getAllBaseTotalAmounts());
			/** @var float|int $baseTotalsNew */
			$baseTotalsNew = $plan->amount($baseTotals, $baseCurrency);
			/** @var float $feeBase */
			$feeBase = TWD::round($baseTotalsNew - $baseTotals, $baseCurrency);
			$this->_setBaseAmount($feeBase);
			$this->iiAdd($qp, $fee, $feeBase);
		}
		return $this;
	}

	/**
	 * 2016-08-13
	 * @override
	 * @see \Magento\Quote\Model\Quote\Address\Total\AbstractTotal::fetch()
	 * @param Q $quote
	 * @param Total $total
	 * @return array
	 */
	function fetch(Q $quote, Total $total) {return [];}

	/**
	 * 2016-08-14
	 * @used-by \Dfe\AllPay\Total\Quote::collect()
	 * @param QP $payment
	 * @param float $fee
	 * @param float $feeBase
	 */
	private function iiAdd(QP $payment, $fee, $feeBase) {
		/** @var int $id */
		// 2016-08-15
		// Адрес уже сохранён в БД и имеет идентификатор даже для анонимного покупателя:
		// проверил собственноручно.
		$id = df_assert(intval($this->_getAddress()->getId()));
		/** @var array(int => array(string => float)) $values */
		$values = df_eta($payment->getAdditionalInformation(self::$II_KEY));
		$payment->setAdditionalInformation(self::$II_KEY,
			[$id => [self::$II_FEE => $fee, self::$II_FEE_BASE => $feeBase]] + $values
		);
	}

	/**
	 * 2016-08-14
	 * Этот метод удобно вызывать с оператором list: https://3v4l.org/ofTeZ
	 * @used-by \Dfe\AllPay\Total\Quote::iiAdd()
	 * @param IPayment|QP|OP $payment
	 * @return null|float[]
	 */
	static function iiGet(IPayment $payment) {
		/** @var array(int => array(string => float)) $values */
		$values = df_eta($payment->getAdditionalInformation(self::$II_KEY));
		/** @var float $fee */
		$fee = 0;
		/** @var float $fee */
		$feeBase = 0;
		foreach ($values as $valuesForAddress) {
			/** @var array(string => float) $valuesForAddress */
			$fee += $valuesForAddress[self::$II_FEE];
			$feeBase += $valuesForAddress[self::$II_FEE_BASE];
		}
		return [$fee, $feeBase];
	}

	/**
	 * 2016-08-14
	 * @used-by \Dfe\AllPay\Total\Quote::iiSet()
	 * @var string
	 */
	private static $II_KEY = 'dfe_allpay';

	/**
	 * 2016-08-14
	 * @used-by \Dfe\AllPay\Total\Quote::iiSet()
	 * @var string
	 */
	private static $II_FEE = 'fee';

	/**
	 * 2016-08-14
	 * @used-by \Dfe\AllPay\Total\Quote::iiSet()
	 * @var string
	 */
	private static $II_FEE_BASE = 'feeBase';
}