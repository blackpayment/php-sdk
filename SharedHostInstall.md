Установка Composer без SSH
========================

Если у Вас нет доступа к SSH, Вы можете установить Composer по описанной инструкции.

Установка
------------

Linux / Mac OSX:  
*PHP обычно установлен по умолчанию. Инструкции с [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).*  

1. curl -sS https://getcomposer.org/installer | php  
2. php composer.phar require require blackpayment/php-sdk:~0.9
3. Файлы будут загружены на Ваш компьютер.
4. Загрузите эти файлы на Ваш веб сервер.   


Windows:  
*PHP должен быть установлен на Вашем веб сервере, [скачать](http://windows.php.net/download/0). Инструкции с [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-windows).* 

1. Скачайте и установите [Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe).  
2. Откройте командную строку и введите "php composer require blackpayment/php-sdk:~0.9".  
3. Файлы будут загружены на Ваш компьютер. 
4. Загрузите эти файлы на Ваш веб сервер.  


Поддержка и обратная связь
--------------------

Посетите [наш сайт](https://blackpayment.ru/) для дополнительной информации о нашем API. 

Если Вы нашли баг, пожалуйста отправьте описание нам.
Мы поддерживаем Bug Bounty программу.
[security@blackpapyment.ru](mailto:security@blackpapyment.ru).