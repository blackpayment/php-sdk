<?php
require 'vendor/autoload.php'; //Укажите путь до autoload.php
use BlackPayment\BlackPayment;

// Данные магазина
$token 		= '5I68KZ9XKLHH-U3N2EXY55Y75KI8XVY05'; //API токен магазина
$secretKey	= 'K37zQemvVYD-iljp6oJnJ'; //Секретный ключ магазина

// Инициализация класса
$bp = new BlackPayment($token,$secretKey);

// Данные клиента
$data = "tester@blackpayment.ru"; //Email клиента

// Данные платежа
$paymentid = 'ww5cx1a-x97ew2w-wmb5372o-zwvb1d6-pxoz068'; // Идентификатор платежа внутри BLACK PAYMENT

/**
 * Base params: paymentid, data
 *
 * @link http://docs.blackpayment.apiary.io/
 */
$response = $bp->makeAction('sendPayment', array(
    'paymentid' => $paymentid,
    'data' => $data
));

if(isset($response->status) AND $response->status == "OK") {
	print 'Уведомление о платеже было отправлено';
}elseif (isset($response->error)) {
	print 'Ошибка: '.$response->errorDescription;
}else {
	print 'Фатальная ошибка';
}