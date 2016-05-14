PHP-SDK
===========

BLACK PAYMENT PHP SDK. SDK содержит методы для удобного взаимодействия с BLACK PAYMENT API. 
Ниже находятся примеры для старта. Для подробной информации, пожалуйста, обратитесь к нашей документации
http://docs.blackpayment.apiary.io

[![Latest Stable Version](https://poser.pugx.org/blackpayment/php-sdk/v/stable)](https://packagist.org/packages/blackpayment/php-sdk)

Установка
------------
Для установки SDK, необходимо воспользоваться [Composer](http://getcomposer.org/).

Если Вы ещё не используете Composer- это достаточно просто. Вот инструкция по установке.

```PHP
# Установите Composer
curl -sS https://getcomposer.org/installer | php

# Добавьте BLACK PAYMENT в зависимость
php composer.phar require blackpayment/php-sdk:~0.9
```

**Если у Вас нет доступа к SSH, то можете установить Composer по [Этой инструкции](SharedHostInstall.md).**

Дальше, установите автозагрузку Composer в Ваше приложение, чтобы автоматически загружать BLACK PAYMENT SDK в Ваш проект:
```PHP
require 'vendor/autoload.php';
use BlackPayment\BlackPayment;
```

Пример использования
-----
Вот так происходит генерация платежей через наш SDK:

```php
# В первую очередь необходимо инициализировать наш SDK для Вашего магазина. 
$bp = new BlackPayment("ВАШ API TOKEN","ВАШ SECRET KEY");

# Теперь можно отправлять запрос создания платежа.
$response = $bp->makeAction('makePayment', array('amount'    	=> 420, 
												'currency'      => 'RUB', 
												'description'	=> 'Payment For Doge', 
												'order'   		=> 'MUCHORDER'));

# Исследуем ответ от API.
if(isset($response->url)) {
	$paymentid = $response->paymentid; //Идентификатор платежа внутри BLACK PAYMENT
	$paymentUrl = $response->url; //Ссылка платежа
}elseif (isset($response->error)) {
	print 'Ошибка: '.$response->errorDescription;
}else {
	print 'Фатальная ошибка';
}
```

Дополнительная информация
---------------

Более подробные примеры использования Наших методов API описаны в [`/sample`](https://github.com/blackpayment/php-sdk/blob/master/sample).


Поддержка и обратная связь
--------------------

Посетите [наш сайт](https://blackpayment.ru/) для дополнительной информации о нашем API. 

Если Вы нашли баг, пожалуйста отправьте описание нам.
Мы поддерживаем Bug Bounty программу.
[security@blackpapyment.ru](mailto:security@blackpapyment.ru).
