<?php
// update these with the real location of your two .pem files. keep them above/outside your webroot folder
define('PRODUCTION_CERTIFICATE_KEY', 'ApplePay.key.pem');
define('PRODUCTION_CERTIFICATE_PATH', 'ApplePay.crt.pem');
  
// This is the password you were asked to create in terminal when you extracted ApplePay.key.pem
define('PRODUCTION_CERTIFICATE_KEY_PASS', '123456'); 

//define('PRODUCTION_MERCHANTIDENTIFIER', openssl_x509_parse( file_get_contents( PRODUCTION_CERTIFICATE_PATH ))['subject']['UID'] ); //if you have a recent version of PHP, you can leave this line as-is. http://uk.php.net/openssl_x509_parse will parse your certificate and retrieve the relevant line of text from it e.g. merchant.com.name, merchant.com.mydomain or merchant.com.mydomain.shop
// if the above line isn't working for you for some reason, comment it out and uncomment the next line instead, entering in your merchant identifier you created in your apple developer account
define('PRODUCTION_MERCHANTIDENTIFIER', 'merchant.pg.com');

define('PRODUCTION_DOMAINNAME', $_SERVER["HTTP_HOST"]); //you can leave this line as-is too, it will take the domain from the server you run it on e.g. shop.mydomain.com or mydomain.com
// if the line above isn't working for you, replace it with the one below, updating it for your own domain name
// define('PRODUCTION_DOMAINNAME', 'mydomain.com');


define('PRODUCTION_CURRENCYCODE', 'SAR');	// https://en.wikipedia.org/wiki/ISO_4217
define('PRODUCTION_COUNTRYCODE', 'SA');		// https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
define('PRODUCTION_DISPLAYNAME', 'My Test Shop');

define('TERMINAL_ID','TER5392572');
define('PASSWORD','TER25123653637313042804');
define('KEY', 'f1aa70c3de58cbd8b132b50799f4ea7b120168a43b6c20ab99298064a7df77e1'); 
define('URL', 'https://pg.alinmapay.com.sa/Transactions/v2/payments/pay-request'); 
define('DEBUG', 'true');
?>
