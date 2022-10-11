This module integrates a Magento 2 based webstore with the **[歐付寶 O'Pay (allPay)](https://www.opay.tw)** Taiwanese payment service.  
The module is **free** and **open source**.

## Demo videos

1. [Capture a **bank card** payment](https://www.youtube.com/watch?v=tmy-YbPGrio).
2. [Capture an **ATM** payment](https://www.youtube.com/watch?v=1S-j8UbXZtA).
3. [Capture a **Barcode** payment](https://www.youtube.com/watch?v=ujA-BOQV6GM).
4. [A payment by **installments**](https://www.youtube.com/watch?v=rAkXZlP8Xok).
5. [Payment **options** on the Magento checkout screen](https://www.youtube.com/watch?v=V0vYTeRALyo).
6. [**Mobile** mode](https://www.youtube.com/watch?v=vZGABg-31xo).
7. [**Fast** mode: skip the billing address form filling](https://www.youtube.com/watch?v=a-gTR5JNlwk).

## Screenshots
### Frontend checkout screen
![](https://mage2.pro/uploads/default/original/2X/d/d5a9df1dccbd3b39848379b0aa7e5465c4a21adf.png)

### Frontend checkout screen in the fast mode
![](https://mage2.pro/uploads/default/original/2X/8/8c51244f8c9d30eb1afdea2cb8efcb45a91e0d39.png)

### Backend orders list
![](https://mage2.pro/uploads/default/original/2X/d/da7d7adc8ff2ba83924a51fe6d9d5c73db949833.png)

### Backend settings
![](https://mage2.pro/uploads/default/original/2X/c/c4d1d3bfe10360ca3d21dc978338a50be8138dc3.png)

## How to install
[Hire me in Upwork](https://www.upwork.com/fl/mage2pro), and I will: 
- install and configure the module properly on your website
- answer your questions
- solve compatiblity problems with third-party checkout, shipping, marketing modules
- implement new features you need 

### 2. Self-installation
```
bin/magento maintenance:enable
rm -f composer.lock
composer clear-cache
composer require mage2pro/allpay:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy en_US zh_Hant_TW <additional locales, e.g.: zh_Hans_CN>
bin/magento maintenance:disable
```

## How to update
```
bin/magento maintenance:enable
composer remove mage2pro/allpay
rm -f composer.lock
composer clear-cache
composer require mage2pro/allpay:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy en_US zh_Hant_TW <additional locales, e.g.: zh_Hans_CN>
bin/magento maintenance:disable
```