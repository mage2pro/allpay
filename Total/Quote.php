<?php
namespace Dfe\AllPay\Total;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Dfe\AllPay\Method;
use Dfe\AllPay\Settings as S;
use Magento\Quote\Api\Data\ShippingAssignmentInterface as IShippingAssignment;
use Magento\Quote\Model\Quote as Q;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote\Address\Total\CollectorInterface;
use Magento\Quote\Model\Quote\Payment as QP;
use Magento\Quote\Model\ShippingAssignment;
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
	public function collect(Q $quote, IShippingAssignment $shippingAssignment, Total $total) {
		/** @var QP $payment */
		$payment = $quote->getPayment();
		if ($payment && $payment->getMethod() === Method::codeS()) {
			/** @var int|null $planId */
			$planId = df_payment_iia($payment, 'plan');
			if ($planId) {
				/** @var Plan $plan */
				$plan = S::s()->installmentSales()->plans($planId);
				if ($plan) {
					$this->setCode('dfe_allpay');
					parent::collect($quote, $shippingAssignment, $total);
					/** @var string $quoteCurrency */
					$quoteCurrency = $quote->getQuoteCurrencyCode();
					/** @var string $baseCurrency */
					$baseCurrency = $quote->getBaseCurrencyCode();
					/**
					 * 2016-08-13
					 * По аналогии с @see \Magento\Quote\Model\Quote\Address\Total\Grand::collect()
					 */
					/** @var float $totals */
					$totals = array_sum($total->getAllTotalAmounts());
					/** @var float|int $totalsNew */
					$totalsNew = $plan->amount($totals, $quoteCurrency);
					$this->_setAmount($totalsNew - $totals);
					/** @var float $baseTotals */
					$baseTotals = array_sum($total->getAllBaseTotalAmounts());
					/** @var float|int $baseTotalsNew */
					$baseTotalsNew = $plan->amount($baseTotals, $baseCurrency);
					$this->_setBaseAmount($baseTotalsNew - $baseTotals);
				}
			}
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
	public function fetch(Q $quote, Total $total) {
		return [];
	}
}


