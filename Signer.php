<?php
namespace Dfe\AllPay;
use Dfe\AllPay\Settings as S;
# 2016-07-10
final class Signer extends \Df\PaypalClone\Signer {
	/**
	 * 2016-07-10
	 * @override
	 * @see \Df\PaypalClone\Signer::sign()   
	 * @used-by \Df\PaypalClone\Signer::_sign()
	 */
	protected function sign():string {
		$p = $this->v(); /** @var array(string => mixed) $p */
		unset($p['CheckMacValue']); # 2016-07-11
		/**
		 * 2016-07-04
		 * Step 1
		 * «Sort all parameter being sent alphabetically from A to Z
		 * (if the first letters of some parameter are the same, sort them by the second letter and so on).»
		 * https://github.com/allpay/PHP/blob/953764c/AioExample/Allpay_AIO_CreateOrder.php#L78
		 * https://github.com/allpay/PHP/blob/953764c/AioExample/Allpay_AIO_CreateOrder.php#L25-L28
		 * 2016-07-13
		 * Раньше тут стояло:
		 * 		uksort($params, function($a, $b) {return strcasecmp($a, $b);});
		 * Сегодня заметил, что модуль для Magento 1.x использует более короткую, идентичную по результату реализацию:
		 * 		ksort($params);
		 * 2016-07-26
		 * На самом деле, в нашем случае использовать ksort($params) нельзя,
		 * потому что эта функция помещает в конец списка ключи, начинающиеся с прописной буквы.
		 * В модуле для для Magento 1.x это прокатывает, потому что там ключи предварительно приводятся к нижнему регистру.
		 */
		uksort($p, function($a, $b) {return strcasecmp($a, $b);});
		$s = $this->s(); /** @var S $s */
		# 2016-07-04 Step 2. «Add HashKey at the front of parameter and HashIV at the end of parameter.»
		$p = ['HashKey' => $s->hashKey()] + $p + ['HashIV' => $s->hashIV()];
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
		/** @var string $r */
		$r = implode('&', df_map_k($p, function($k, $v) {return implode('=', [$k, $v]);}));
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
		$r = strtolower(urlencode($r));
		$r = $this->encodeSpecialChars($r);
		return strtoupper(md5($r));
	}

	/**
	 * 2016-07-04
	 * Сделал по аналогии с https://github.com/allpay/PHP/blob/953764c/AioExample/Allpay_AIO_CreateOrder.php#L3-L10
	 * @used-by self::sign()
	 * @param string $s
	 * @return string
	 */
	private function encodeSpecialChars($s) {return strtr($s, [
		'%2d' => '-', '%5f' => '_', '%2e' => '.', '%21' => '!', '%2a' => '*', '%28' => '(', '%29'	=> ')'
	]);}
}