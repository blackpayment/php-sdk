<?php
require 'vendor/autoload.php'; //Укажите путь до autoload.php
use BlackPayment\BlackPayment;

// Данные магазина
$token 		= '5I68KZ9XKLHH-U3N2EXY55Y75KI8XVY05'; //API токен магазина
$secretKey	= 'K37zQemvVYD-iljp6oJnJ'; //Секретный ключ магазина

// Инициализация класса
$bp = new BlackPayment($token,$secretKey);

// Обработка запроса
try {
    // Проверка запроса и подписи
    $bp->checkRequest();
	
	$paymentid = $_POST['paymentid']; //Идентификатор платежа внутри BLACK PAYMENT
	$order = $_POST['order']; //Идентификатор платежа магазина
	$status = $_POST['status']; //Статус платежа
	$testmode = $_POST['testmode']; //Статус тестового режима
	
	//Проверка тестового режима
	if($testmode == "ENABLE")
	{
		switch ($status) {
			// Проверка статуса
			case 'SUCCESS':
				//Действия в случае успешной оплаты (ТЕСТОВЫЙ РЕЖИМ)
				break;
			case 'FAIL':
				//Действия в случае неуспешной оплаты (ТЕСТОВЫЙ РЕЖИМ)
				break;
			case 'REFUND':
				//Действия в случае возврата (ТЕСТОВЫЙ РЕЖИМ)
				break;
		}
	}
	elseif($testmode == "DISABLE")
	{
		switch ($status) {
			// Проверка статуса
			case 'SUCCESS':
				//Действия в случае успешной оплаты
				break;
			case 'FAIL':
				//Действия в случае неуспешной оплаты
				break;
			case 'REFUND':
				//Действия в случае возврата
				break;
		}
	}
	print $bp->getSuccessResponse();
	
// Что-то не так
} catch (Exception $e) {
	print $bp->getErrorResponse($e->getMessage());
}
