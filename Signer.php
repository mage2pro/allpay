<?php
namespace Dfe\AllPay;
use Dfe\AllPay\Settings as S;
class Signer extends \Df\Payment\R\Signer {
	/**
	 * 2016-07-10
	 * @override
	 * @see \Df\Payment\R\Signer::sign()
	 * @return string
	 */
	public function sign() {
		/** @var array(string => mixed) $params */
		$params = $this->getData();
		// 2016-07-11
		unset($params['CheckMacValue']);
		/**
		 * 2016-07-04
		 * Step 1
		 * «Sort all parameter being sent alphabetically from A to Z (
		 * if the first letters of some parameter are the same,
		 * sort them by the second letter and so on).»
		 * https://github.com/allpay/PHP/blob/953764c/AioExample/Allpay_AIO_CreateOrder.php#L78
		 * https://github.com/allpay/PHP/blob/953764c/AioExample/Allpay_AIO_CreateOrder.php#L25-L28
		 */
		uksort($params, function($a, $b) {return strcasecmp($a, $b);});
		/**
		 * 2016-07-04
		 * Step 2
		 * «Add HashKey at the front of parameter and HashIV at the end of parameter.»
		 */
		$params = ['HashKey' => S::s()->hashKey()] + $params + ['HashIV' => S::s()->hashIV()];
		/**
		 * 2016-07-04
		 * Step 1.1
		 * «...connect all with &»
		 * Намеренно не используем @see http_build_query(),
		 * потому что она может перекодировать свои аргументы
		 * (в частности, следующие символы внутри них: пробелы, амперсанд, знак равенства).
		 * Вместо этого используем официальный алгоритм:
		 * https://github.com/allpay/PHP/blob/953764c/AioExample/Allpay_AIO_CreateOrder.php#L15-L18
		 */
		/** @var string $result */
		$result = implode('&', df_map(function($key, $value) {
			return implode('=', [$key, $value]);
		}, $params, [], [], DF_BEFORE));
		/**
		 * 2016-07-04
		 * Step 3
		 * «Apply URL encode on entire message.»
		 * В документации есть ещё замечение, что недостаточно применить функцию PHP @uses urlencode(),
		 * а надо ещё дополнительно перекодировать некоторые символы.
		 *
		 * Официальный пример использует именно @uses strtolower, а не @see mb_strtolower()
		 * https://github.com/allpay/PHP/blob/953764c/AioExample/Allpay_AIO_CreateOrder.php#L20-L20
		 * Уточнил, не ошибка ли это: https://mage2.pro/t/1839
		 *
		 * 2016-07-05
		 * Оказалось, что не ошибка, потому что после применения @uses urlencode()
		 * результаты работы @uses strtolower и @see mb_strtolower() должны быть одинаковыми.
		 */
		$result = strtolower(urlencode($result));
		$result = $this->signatureReplace($result);
		return strtoupper(md5($result));
	}

	/**
	 * 2016-07-04
	 * Сделал по аналогии с
	 * https://github.com/allpay/PHP/blob/953764c/AioExample/Allpay_AIO_CreateOrder.php#L3-L10
	 * @param string $s
	 * @return string
	 */
	private function signatureReplace($s) {return strtr($s, [
		'%2d' => '-', '%5f' => '_', '%2e' => '.', '%21' => '!'
		,'%2a' => '*', '%28' => '(', '%29'	=> ')'
	]);}
}
