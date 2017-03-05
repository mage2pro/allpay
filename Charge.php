<?php
namespace Dfe\AllPay;
use Df\Payment\Settings\Options as O;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Dfe\AllPay\Source\Option;
use Dfe\AllPay\Source\PaymentIdentificationType as Identification;
use Magento\Sales\Model\Order\Item as OI;
/**
 * 2016-07-04
 * @method Method m()
 * @method Settings ss()
 */
final class Charge extends \Df\PaypalClone\Charge {
	/**
	 * 2016-08-29
	 * @override
	 * @see \Df\PaypalClone\Charge\IRequestIdKey::requestIdKey()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @used-by \Df\PaypalClone\Webhook::parentIdRawKey()
	 * @return string
	 */
	static function requestIdKey() {return 'MerchantTradeNo';}

	/**
	 * 2016-07-04
	 * @override
	 * @see \Df\PaypalClone\Charge::params()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return array(string => mixed)
	 */
	protected function params() {return $this->descriptionOnKiosk() + [
		/**
		 * 2016-07-02
		 * «Select default payment type».
		 * Varchar(20)
		 * «allPay would provide follow payment types, please send it when generating an order:
		 * 		Credit: Credit Card.
		 * 		WebATM: webATM.
		 * 		ATM: physical ATM machine.
		 * 		CVS: CVS code.
		 * 		BARCODE: BARCODE.
		 * 		Tenpay: Tenpay.
		 * 		TopUpUsed: consume with account balance.
		 * 		ALL: no selected payment type.
		 * allPay would show the page to select payment type.
		 * Must be filled.
		 *
		 * 2016-07-05
		 * Если указать отличное от «ALL» значение этого параметра,
		 * то у покупателя не будет возможность выбрать другой способ оплаты.
		 * Мы позволяем администратору ограничить множество доступных покупателю способов оплаты,
		 * но нам удобнее реализовать это ограничение через параметр «IgnorePayment» (смотрите ниже),
		 * а для параметра «ChoosePayment» поэтому указываем значение «ALL».
		 *
		 * 2016-07-05
		 * Как оказалось, «IgnorePayment» работает с косяками:
		 * [allPay] Unable to disable the «TopUpUsed» method
		 * and an undocumented «Overseas» method with the «IgnorePayment» parameter.
		 * https://mage2.pro/t/1852
		 *
		 * Поэтому, если администратор магазина выбрал только один способ оплаты,
		 * то реализуем ограничение посредством «ChoosePayment», а не посредством «IgnorePayment».
		 */
		'ChoosePayment' => $this->pChoosePayment()
		// 2016-07-02
		// «Select the default setup for sub payment».
		// Varchar(20)
		// «If this is set up correctly, users are unable to see cash flow selection page.
		// He could select payment type directly,
		// but “Credit” and “TopUpUsed” would not include this function.
		// For example: if set WebATM on ChoosePayment and set TAISHIN on ChooseSubPayment,
		// then this trade would be made through Tai Shin Bank webATM.
		// Please refer to Table of Payment Type.».
		// Could be empty.
		,'ChooseSubPayment' => ''
		/**
		 * 2016-07-02
		 * «URL for returning pages from Client to merchant».
		 * Varchar(200)
		 * «allPay would show payment complete page.
		 * That page would include “back to merchant” button.
		 * When a member clicks this button, it would redirect webpage to URL it set up.
		 * If this parameter is not set up,
		 * allPay payment complete page would not show “back to merchant” button.
		 * When redirect webpage, it would simply return the page
		 * instead of redirecting payment result to this URL.».
		 *
		 * Could be empty.
		 *
		 * [allPay] What is the difference
		 * between the «OrderResultURL» and «ClientBackURL» parameters? https://mage2.pro/t/1836/2
		 *
		 * «ClientBackURL has value and OrderResultURL is empty:
		 * The browser will go to AllPay's complete(result) page after payment is complete.
		 * There will be a "back to merchant" link on the page.
		 * If clicked, the link will go to ClientBackURL you specified.
		 *
		 * OrderResultURL has value:
		 * The browser will go to OrderResultURL instead after payment is complete.
		 * So, I guess you should use the parameter
		 * to go back magento's order complete page from AllPay.»
		 *
		 * 2016-07-06
		 * На страницах 6-7 документации сказано:
		 * «If merchant does not setup an OrderResultURL,
		 * the order result page will be shown in allPay after it sends order information to allPay.
		 * If merchant setups a ClientBackURL, after it sends order information to allPay,
		 * a “back to partner” button would be shown and generated
		 * by allPay Cash Flow System in order result page.
		 *
		 * 2016-07-18
		 * Для оффлайновых способов оплаты
		 * решил пока использовать «ClientBackURL», а не «ClientRedirectURL»,
		 * потому что при «ClientBackURL» allPay отображает покупателю
		 * подробную инструкцию по оплате.
		 */
		,'ClientBackURL' => $this->customerReturn()
		/**
		 * 2016-07-04
		 * «Payment related information returned by Client end».
		 * Varchar(200)
		 * «allPay would return the payment related information webpage
		 * as a Client end to merchant after an order is generated (not after a payment is made).
		 * It would include the bank code, virtual account, and expiration date (yyyy/MM/dd).
		 * If this value is left as empty, it would show the order generated page in allPay webpage.
		 * If would like to show this page in your site, please set up the URL.
		 * If this parameter is set up, ClientBackURL parameter would be disable.».
		 *
		 * В другом месте документации (страница 2) написано:
		 * «Add ClientRedirectURL which Client end will return payment information
		 * then redirect to its URL under ATM, CVS, or BARCODE payment type.»
		 *
		 * Could be empty.
		 *
		 * 2016-07-06
		 * «ClientRedirectURL» используется только в сценариях оффлайновой оплаты: ATM, CVS, BARCODE.
		 * Шаг «ClientRedirectURL» показан под номером 15 на странице 8 документации.
		 * Если покупатель выбрал оффлайновый способ оплаты (ATM, CVS, BARCODE),
		 * то платёжная система возвращает покупателя в интернет-магазин
		 * не по адресу «OrderResultURL», а по адресу «ClientRedirectURL» (шаг 15).
		 *
		 * 2016-07-18
		 * Для оффлайновых способов оплаты
		 * решил пока использовать «ClientBackURL», а не «ClientRedirectURL»,
		 * потому что при «ClientBackURL» allPay отображает покупателю
		 * подробную инструкцию по оплате.
		 */
		,'ClientRedirectURL' => ''
		/**
		 * 2016-08-08
		 * «Number of payment on credit card installment».
		 * Int
		 * «When a member selected credit card as its payment type,
		 * seller should notify customers on the number of payment on credit card installment
		 * if he is willing to provide installment service.
		 * If it is not paying by credit card with installment, take 0 as its value».
		 * Could be empty.
		 */
		,'CreditInstallment' => !$this->plan() ? 0 : $this->plan()->numPayments()
		// 2016-07-04
		// «Device Source».
		// Varchar(10)
		// «This parameter would set different layout of payment type selection webpage
		// according to the value it takes.».
		// Could be empty.
		//
		// [allPay] What are the possible values for the «DeviceSource» parameter?
		//  https://mage2.pro/t/1825
		,'DeviceSource' => ''
		// 2016-07-04
		// «CheckMacValue encryption type».
		// Int
		// 		0:	MD5 (default setting)
		//		1:	SHA256
		// Could be empty.
		,'EncryptType' => 0
		// 2016-07-04
		// «Effective payment period».
		// Int
		// «At most 60 days; at least 1 day.
		// Defaulted as 3 days if this is left as blank.».
		// Could be empty.
		/**
		 * 2016-07-04
		 * «Effective payment period».
		 * Int
		 * «At most 60 days; at least 1 day.
		 * Defaulted as 3 days if this is left as blank.».
		 * Could be empty.
		 *
		 * 2016-07-19
		 * Эта опция учитывается только для ATM.
		 */
		,'ExpireDate' => $this->ss()->waitPeriodATM()
		// 2016-07-04
		// «Whether or not to hold the allocation».
		// Int
		// «Whether or not to hold the allocation.
		// If no, take 0 (default value) as its value.
		// If yes, take 1 as its value.
		// Meaning of values listed below:
		// 		0:	allPay according to the contract has allocated the payment to merchant
		// 			after buyer made his payment (this is set as default value).
		// 		1:	after buyer made his payment
		// 			it needs to call “Merchant Allocation/Refund Request” API
		// 			so that allPay could make the payment to merchant.
		// 			If merchant does not request for allocation,
		// 			this order would be kept in allPay until merchant apply for its allocation.
		// This is not suitable for paying by “Credit Card” and “Tenpay.”».
		// Could be empty.
		,'HoldTradeAMT' => 0
		/**
		 * 2016-07-04
		 * «Ignore payment type».
		 * Varchar(100)
		 * «When using ALL as ChoosePayment, user could select not to show his payment type.
		 * If there are more than one payment type, separate them by symbol #.».
		 * An example: ATM#WebATM
		 * Could be empty.
		 *
		 * 2016-07-05
		 * Как оказалось, «IgnorePayment» работает с косяками:
		 * [allPay] Unable to disable the «TopUpUsed» method
		 * and an undocumented «Overseas» method with the «IgnorePayment» parameter.
		 * https://mage2.pro/t/1852
		 *
		 * Поэтому, если администратор магазина выбрал только один способ оплаты,
		 * то реализуем ограничение посредством «ChoosePayment», а не посредством «IgnorePayment».
		 */
		,'IgnorePayment' => $this->pIgnorePayment()
		/**
		 * 2016-08-08
		 * «Paying by credit card with installment.».
		 * Money
		 * «If the amount of paying by credit card with installment
		 * is greater than original total payment amount,
		 * take installment amount as its value.
		 * If it is not paying by credit card with installment, take 0 as its value».
		 * Could be empty.
		 *
		 * 2016-08-13
		 * Раньше здесь стояло: !$this->plan() ? 0 : $this->plan()->amount($this->amountTWD())
		 * Теперь же у нас есть класс @see \Dfe\AllPay\Total\Quote
		 * который уже добавляет к итоговой сумме нашу наценку,
		 * и поэтому здесь теперь уже этого делать не надо.
		 *
		 * 2016-09-06
		 * Значение тут всегда в TWD,
		 * потому что для модуля AllPay платёжная валюта зашита в etc/config.xml,
		 * и администратор не модет её изменить.
		 */
		,'InstallmentAmount' => !$this->plan() ? 0 : $this->amountF()
		// 2016-07-04
		// «Electronic invoice remark».
		// Varchar(1)
		// «This parameter would help generating an invoice after payment is made.
		// If would like to generated an invoice, set Y as its value.».
		// Could be empty.
		,'InvoiceMark' => ''
		// 2016-07-02
		// «Item Name».
		// Varchar(200)
		// «If there are more than one item name
		// and would like to show cash flow selection page line by line,
		// separate the item name with symbol #.».
		// Must be filled.
		,'ItemName' => df_oi_s($this->o(), '#')
		/**
		 * 2016-07-02
		 * «Item URL».
		 * Varchar(200)
		 * [allPay] What is the «ItemURL» payment parameter for? https://mage2.pro/t/1819/2
		 * «You can put product URLs in the parameter.
		 * In case of multiple URLs, you can use + to concatenate them.
		 * "www.allpay.com.tw+www.yahoo.com.tw" <- An example that provided by AllPay
		 * BTW, please note the max length is 200.».
		 *
		 * https://mage2.pro/t/1819/3
		 * «After further confirmation with AllPay,
		 * so far the parameter is not really used in any scenario.».
		 * Could be empty.
		 */
		,'ItemURL' => $this->productUrls()
		// 2016-07-02
		// «Merchant Identification number (provided by allPay)».
		// Varchar(10)
		// Must be filled.
		,'MerchantID' => $this->ss()->merchantID()
		/**
		 * 2016-07-02
		 * «Merchant trade date».
		 * Varchar(20)
		 * «Formatted as yyyy/MM/dd HH:mm:ss».
		 * Example: 2012/03/21 15:40:18
		 * Must be filled.
		 *
		 * 2016-07-04
		 * В данный момент дата отсутствует как у заказа, так и у платежа,
		 * поэтому конструируем дату самостоятельно.
		 * Сделал идентично официальному примеру:
		 * https://github.com/allpay/PHP/blob/953764c/AioExample/Allpay_AIO_CreateOrder.php#L66
		 *
		 * 2016-07-09
		 * Looks like we need to use the Taiwanese time zone,
		 * because the time format does not contain a time zone information,
		 * and allPay uses the Taiwanese time zone in the payment response.
		 * http://php.net/manual/en/function.timezone-offset-get.php#73995
		 */
		,'MerchantTradeDate' => df_now('Y/m/d H:i:s', Method::TIMEZONE)
		/**
		 * 2016-07-04
		 * «If there is a need for an extra payment information».
		 * Varchar(1)
		 * «Set up payment complete notification, return information of order query,
		 * and decide if there is a for an extra payment information
		 * (for return information, please refer to Additional Return Parameter).
		 * Default as N, not reply extra information.
		 * When the parameter is Y, then reply with extra information.».
		 * Could be empty.
		 *
		 * 2016-07-26
		 * Если значением этого параметра сделать «Y»,
		 * то платёжная система вернёт расширенную информацию о платеже:
		 * она описана в главе 9 документации (страницы 38-39).
		 *
		 * Пока не смог проверить подпись при значении «Y»:
		 * https://mage2.pro/t/1898
		 */
		,'NeedExtraPaidInfo' => 'Y'
		/**
		 * 2016-07-04
		 * «Payment result URL returned by Client end».
		 * Varchar(200)
		 * «After a payment is made, and then allPay would redirectly webpage again
		 * to this URL with payment result parameter.
		 * If this parameter is left as blank, it would show payment complete on allPay webpage.
		 * If one would show payment complete webpage on his own site,
		 * set up the URL in this parameter.
		 * (Some of the webATM banks would stay at their own webpages
		 * after a trade is made successfully.
		 * It would not redirect webpages to allPay;
		 * thus, allPay would not redirect webpages to the URL this parameter set up.)
		 * If this parameter is set up, ClientBackURL parameter would be disable.».
		 * Could be empty.
		 *
		 * [allPay] What is the difference
		 * between the «OrderResultURL» and «ClientBackURL» parameters? https://mage2.pro/t/1836/2
		 *
		 * 2016-07-06
		 * «OrderResultURL» используется только в сценариях онлайновой оплаты
		 * (онлайновыми являются все, кроме ATM, CVS, BARCODE).
		 * Шаг «OrderResultURL» показан под номером 15 на странице 7 документации.
		 * Если покупатель выбрал оффлайновый способ оплаты (ATM, CVS, BARCODE),
		 * то платёжная система возвращает покупателя в интернет-магазин
		 * не по адресу «OrderResultURL», а по адресу «ClientRedirectURL»
		 * (шаг 15 на странице 8 документации).
		 *
		 * 2016-07-06
		 * На страницах 6-7 документации сказано:
		 * «If merchant does not setup an OrderResultURL,
		 * the order result page will be shown in allPay after it sends order information to allPay.
		 * If merchant setups a ClientBackURL, after it sends order information to allPay,
		 * a “back to partner” button would be shown and generated
		 * by allPay Cash Flow System in order result page.
		 *
		 * 2016-07-14
		 * Раньше здесь стояло df_url_checkout_success(),
		 * что показывало покупателю страницу об успешности заказа
		 * даже если покупатель не смог или не захотел оплачивать заказ.
		 *
		 * Теперь же, покупатель не смог или не захотел оплатить заказ,
		 * то при соответствующем («ReturnURL») оповещении платёжной системы
		 * мы заказ отменяем, а затем, когда платёжная система возврат покупателя в магазин,
		 * то мы проверим, не отменён ли последний заказ,
		 * и если он отменён — то восстановим корзину покупателя.
		 *
		 * @see \Dfe\AllPay\Controller\CustomerReturn\Index
		 */
		,'OrderResultURL' => $this->customerReturnRemote()
		/**
		 * 2016-07-04
		 * «Payment related information returned by Server end».
		 * Varchar(200)
		 * «allPay would return the payment related information webpage
		 * as a Server end to merchant after an order is generated (not after a payment is made).
		 * It includes not only bank code, virtual account, and expiration date (yyyy/MM/dd).
		 * It would also show related payment information on allPay.».
		 *
		 * Could be empty.
		 *
		 * 2016-07-05
		 * [allPay] What is the difference between the «PaymentInfoURL» and «ReturnURL» notifications?
		 * https://mage2.pro/t/1848
		 *
		 * В другом месте документации (страница 2) написано:
		 * «add PaymentInfoURL which Server end would return payment information
		 * when its method is ATM, CVS, or BARCODE.»
		 *
		 * 2016-07-06
		 * В общем, я понял разницу.
		 * «PaymentInfoURL» используется только в сценариях оффлайновой оплаты: ATM, CVS, BARCODE.
		 * Шаг «PaymentInfoURL» показан под номером 13 на странице 8 документации.
		 * Если покупатель выбрал оффлайновый способ оплаты (ATM, CVS, BARCODE),
		 * то платёжная система оповещает об этом интернет-магазин не по адресу «ReturnURL»,
		 * а по адресу «PaymentInfoURL» (шаг 13),
		 * а оповещение по адресу «ReturnURL» приходит лишь на шаге 21,
		 * когда покупатель уже оплатил заказ оффлайновым способом.
		 */
		,'PaymentInfoURL' => $this->callback('offline')
		// 2016-07-02
		// «Payment type».
		// Varchar(20)
		// «Please use aio as its value».
		// Must be filled.
		,'PaymentType' => 'aio'
		// 2016-07-04
		// «Merchant platform identification number(provided by allPay)».
		// Varchar(10)
		// «This parameter is for project based merchants.
		// The others should leave this as blank.
		// If it is working with a project based merchant,
		// use the MerchantID which seller has appointed with.
		// If there are values in both AllPayID and AccountID, PlatformID could not be left as blank.».
		// Could be empty.
		,'PlatformID' => ''
		// 2016-07-02
		// «Remark».
		// Varchar(100)
		// «Leave it as blank for now.».
		// Could be empty.
		,'Remark' => ''
		/**
		 * 2016-07-02
		 * «Return URL for payment complete notification».
		 * Varchar(200)
		 * «When a customer made a payment,
		 * payment result would be sent by server back end and return to this URL.».
		 * Must be filled.
		 *
		 * Параметр описан в документации на трэш-английском,
		 * но из программного кода модуля для Magento 1.x я понял,
		 * что по этому адресу платёжная система оповещает интернет-магазин о платеже.
		 * В документации этим опомещениям посвящён раздел
		 * «7. Payment Result Notification» на странице 32.
		 *
		 * 2016-05-06
		 * Обратите внимание, что сненарии онлайновой и оффлайновой оплаты различаются.
		 * В сценарии оффлайновой оплаты (ATM, CVS, BARCODE)
		 * платёжная система непосредственно в процессе оформления заказа
		 * оповещает интернет-магазин не по адресу «ReturnURL»,
		 * а по адресу «PaymentInfoURL» (шаг 13 на странице 8 докумениации),
		 * а оповещение по адресу «ReturnURL» приходит лишь на шаге 21,
		 * когда покупатель уже оплатил заказ оффлайновым способом.
		 */
		,'ReturnURL' => $this->callback()
		// 2016-07-02
		// «Trade amount».
		// «Money».
		// Must be filled.
		/**
		 * 2016-07-02
		 * «Trade amount».
		 * «Money».
		 * Must be filled.
		 *
		 * 2016-07-05
		 * Значение должно быть целым.
		 *
		 * 2016-09-06
		 * Значение тут всегда в TWD,
		 * потому что для модуля AllPay платёжная валюта зашита в etc/config.xml,
		 * и администратор не модет её изменить.
		 */
		,'TotalAmount' => $this->amountF()
		// 2016-07-02
		// «Trade description».
		// Varchar(200)
		// Must be filled.
		,'TradeDesc' => $this->text($this->ss()->description())
	];}

	/**
	 * 2016-08-29
	 * 2016-07-02
	 * «Merchant trade number».
	 * Varchar(20)
	 * «Merchant trade number could not be repeated.
	 * It is composed with upper and lower cases of English letter and numbers.»
	 * Must be filled.
	 *
	 * 2016-07-05
	 * Значение может содержать только цифры и латинские буквы.
	 * Все другие символы недопустимы.
	 * В принципе, стандартные номера заказов удовлетворяют этим условиям,
	 * но вот нестандартные, вида ORD-2016/07-00274
	 * (которые делает наш модуль Sales Documents Numberation) — не удовлетворяют.
	 * Поэтому надо перекодировать проблемные символы.
	 *
	 * Второй мыслью было использовать df_encryptor()->encrypt($this->o()->getIncrementId())
	 * Однако хэш md5 имеет длину 32 символа: http://stackoverflow.com/questions/6317276
	 * А хэш sha256 — 64 символа: http://stackoverflow.com/questions/3064133
	 * allPay же ограничивает длину идентификатора 20 символами.
	 *
	 * Поэтому используем иное решение: нестандартный идентификатор транзакции.
	 *
	 * 2016-07-17
	 * Клиент просит, чтобы в качестве идентификатора платежа
	 * всё-таки использовался номер заказа:
	 * https://code.dmitry-fedyuk.com/m2e/allpay/issues/7
	 * В принципе, это разумно: ведь нестандартные номера заказов
	 * (которые, например, делает наш модуль Sales Documents Numberation)
	 * будут использовать лишь немногие клиенты,
	 * большинство же будет использовать стандартные номера заказов,
	 * поэтому разумно предоставить этому большинству возможность
	 * использовать в качестве идентификатора платежа номер заказа.
	 *
	 * 2017-01-05
	 * Локальный внутренний идентификатор транзакции.
	 * Мы намеренно передаваём этот идентификатор локальным (без приставки с именем модуля)
	 * для удобства работы с этими идентификаторами в интерфейсе платёжной системы:
	 * ведь там все идентификаторы имели бы одинаковую приставку.
	 *
	 * @override
	 * @see \Df\PaypalClone\Charge::requestId()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	final protected function requestId() {return Identification::id($this->o());}

	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\PaypalClone\Charge::signatureKey()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function signatureKey() {return 'CheckMacValue';}

	/**
	 * 2016-08-17
	 * @used-by \Dfe\AllPay\Charge::_requestI()
	 * @return array(string => string)
	 */
	private function descriptionOnKiosk() {
		/** @var string[] $lines */
		$lines = df_explode_n($this->text($this->ss()->descriptionOnKiosk()));
		/** @var int $n */
		$n = 1;
		/** @var array(string => string) $result */
		$result = [];
		foreach ($lines as $line) {
			/** @var string $line */
			$result['Desc_' . $n++] = mb_substr($line, 0, 20);
			if ($n > 4) {
				break;
			}
		}
		return $result;
	}

	/**
	 * 2016-07-05
	 * 2016-08-15
	 * В отличие от JavaScript, в PHP оператор || возвращает значение логического типа,
	 * а не первый неложный аргумент.
	 * https://3v4l.org/fmTAA
	 * 2017-03-05
	 * Этот метод возвращает false, если покупатель ещё не определился со способом оплаты.
	 * Такое возможно, если покупатель решил не платить в рассрочку,
	 * а администратор решил разместить выбор единократных опций
	 * на стороне allPay, а не на стороне Magento.
	 * Значение «undefined» задано в шаблоне Dfe_AllPay/one-off/simple.
	 * @return bool
	 */
	private function isSingleOptionChosen() {return dfc($this, function() {return
		$this->m()->option() || 1 === count($this->ss()->options()->allowed())
	;});}

	/**
	 * 2016-07-05
	 * Если указать отличное от «ALL» значение этого параметра,
	 * то у покупателя не будет возможность выбрать другой способ оплаты.
	 * Мы позволяем администратору ограничить множество доступных покупателю способов оплаты,
	 * но нам удобнее реализовать это ограничение через параметр «IgnorePayment» (смотрите ниже),
	 * а для параметра «ChoosePayment» поэтому указываем значение «ALL».
	 *
	 * 2016-07-05
	 * Как оказалось, «IgnorePayment» работает с косяками:
	 * [allPay] Unable to disable the «TopUpUsed» method
	 * and an undocumented «Overseas» method with the «IgnorePayment» parameter.
	 * https://mage2.pro/t/1852
	 *
	 * Поэтому, если администратор магазина выбрал только один способ оплаты,
	 * то реализуем ограничение посредством «ChoosePayment», а не посредством «IgnorePayment».
	 * @return string
	 */
	private function pChoosePayment() {return dfc($this, function() {
		/** @var O $o */
		$o = $this->ss()->options();
		return $this->plan() ? Option::BANK_CARD : ($this->m()->option() ?: (
			!$o->isLimited() || !$this->isSingleOptionChosen() ? 'ALL' : df_first($o->allowed())
		));
	});}

	/**
	 * 2016-07-05
	 * Как оказалось, «IgnorePayment» работает с косяками:
	 * [allPay] Unable to disable the «TopUpUsed» method
	 * and an undocumented «Overseas» method with the «IgnorePayment» parameter.
	 * https://mage2.pro/t/1852
	 *
	 * Поэтому, если администратор магазина выбрал только один способ оплаты,
	 * то реализуем ограничение посредством «ChoosePayment», а не посредством «IgnorePayment».
	 * @return string
	 */
	private function pIgnorePayment() {/** @var O $o */ $o = $this->ss()->options(); return df_ccc('#',
		// 2016-08-17
		// https://code.dmitry-fedyuk.com/m2e/allpay/issues/14
		array_merge(['ALL' === $this->pChoosePayment() ? 'Alipay' : null],
			!$o->isLimited() || $this->isSingleOptionChosen() ? [] : $o->denied()
		)
	);}

	/**
	 * 2016-08-08
	 * @return Plan|null
	 */
	private function plan() {return $this->m()->plan();}

	/**
	 * 2016-07-05
	 * «Item URL».
	 * Varchar(200)
	 * [allPay] What is the «ItemURL» payment parameter for? https://mage2.pro/t/1819/2
	 * «You can put product URLs in the parameter.
	 * In case of multiple URLs, you can use + to concatenate them.
	 * "www.allpay.com.tw+www.yahoo.com.tw" <- An example that provided by AllPay
	 * BTW, please note the max length is 200.».
	 *
	 * https://mage2.pro/t/1819/3
	 * «After further confirmation with AllPay,
	 * so far the parameter is not really used in any scenario.».
	 * Could be empty.
	 * @return string
	 */
	private function productUrls() {return df_ccc('+', $this->oiLeafs(function(OI $i) {return
		df_oi_url($i)
	;}));}
}