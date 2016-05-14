<?php
require 'vendor/autoload.php'; //Укажите путь до autoload.php
use BlackPayment\BlackPayment;

// Данные магазина
$token 		= '5I68KZ9XKLHH-U3N2EXY55Y75KI8XVY05'; //API токен магазина
$secretKey	= 'K37zQemvVYD-iljp6oJnJ'; //Секретный ключ магазина

// Инициализация класса
$bp = new BlackPayment($token,$secretKey);

// Данные платежа
//$lifetime			= 86400; //Время жизни платежа в секундах
$amount				= 42055; //Сумма платежа
$currency			= 'RUB'; //Валюта платежа
$description			= 'Payment For Doge'; //Описание платежа
$order				= 'MUCHORDER'; //Идентификатор платежа внутри магазина

/**
 * Base params: amount, currency, description, order
 *
 * @link http://docs.blackpayment.apiary.io/
 */
$response = $bp->makeAction('makePayment', array(
//'lifetime' => $lifetime,
    'amount' => $amount,
    'currency' => $currency,
    'description' => $description,
    'order' => $order
));

if(isset($response->url)) {
	$paymentid = $response->paymentid; // Идентификатор платежа BLACK PAYMENT
	$paymentUrl = $response->url; // Ссылка на платёж
	
	header("Location: " . $paymentUrl); // Редирект в BLACK PAYMENT
}elseif (isset($response->error)) {
	print 'Ошибка: '.$response->errorDescription;
}else {
	print 'Фатальная ошибка';
}