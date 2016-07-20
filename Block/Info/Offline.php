<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\Response\Offline as R;
/**
 * @method R responseF()
 * @method R responseL()
 */
abstract class Offline extends \Dfe\AllPay\Block\Info {
	/**
	 * 2016-07-20
	 * @return bool
	 */
	protected function isPaid() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = $this->responseL() && $this->responseL()->isPaid();
		}
		return $this->{__METHOD__};
	}
}

