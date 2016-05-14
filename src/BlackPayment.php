<?php
/**
 * BLACK PAYMENT API PHP-SDK
 *
 * The MIT License (MIT)
 * Copyright (c) 2016 BLACK PAYMENT LLC.
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category        blackpayment
 * @package         blackpayment/blackpayment
 * @version         0.9.1
 * @author          BLACK PAYMENT TEAM <team@blackpayment.ru>
 * @copyright       Copyright (c) 2016 BLACK PAYMENT LLC.
 * @license         https://opensource.org/licenses/MIT The MIT License
 *
 * EXTENSION INFORMATION
 *
 * BLACK PAYMENT API DOCS       https://blackpayment.ru/docs
 *
 */

namespace BlackPayment;

class BlackPayment
{
    /**
     *  Эта перменная содержит массив доступных методов API BLACK PAYMENT;
     *  Используется при проверке методов запроса.
     *
     * @var allowedActions
     */
    private $allowedActions = array('makePayment', 'checkPaymentStatus', 'closePayment', 'sendPayment');

    /**
     *  Эта перменная содержит массив доступных параметров для методов API BLACK PAYMENT;
     *  Используется при проверке параметров запроса.
     *
     * @var requiredActionParams
     */
    private $requiredActionParams = array(
        'makePayment' => array('amount', 'currency', 'description', 'order'),
        'checkPaymentStatus' => array('paymentid'),
        'closePayment' => array('paymentid'),
        'sendPayment' => array('paymentid','data'),
        'result' => array('paymentid','status','order','testmode','salt','hash')
    );

    /**
     *  Эта перменная содержит URL адрес API BLACK PAYMENT;
     *  Используется при отправке запроса.
     *
     * @var apiUrl
     */
    private $apiUrl = 'https://blackpayment.ru/api/';

    /**
     *  Эта перменная содержит API TOKEN магазина;
     *  Используется при идентификации запроса.
     *
     * @var string
     */
    private $token;

    /**
     *  Эта перменная содержит SECRET KEY магазина;
     *  Используется при генерации подписи запроса.
     *
     * @var string
     */
    private $secretKey;

    /**
     *  Эта перменная содержит случайное число;
     *  Используется при генерации подписи запроса.
     *
     * @var int
     */
    private $salt;

    /**
     * @param string $token
     * @param string $secretKey
     * @param int $salt
     */
    public function __construct($token = null,$secretKey = null)
    {
        $this->token = $token;
        $this->secretKey = $secretKey;
        $this->salt = time().mt_rand(0,100000);
    }

    /**
     *  Эта функция генерирует подпись запроса в SHA-512;
     *  Используется для подписи запроса.
     *
     * @param string $action
     * @param array $params
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    private function getSha512Hash($action,$params)
    {
        switch ($action){
            case "makePayment":
                if(isset($params['lifetime'])) {
                    $hash = strtoupper(hash('sha512',$params['amount'].$params['currency'].$params['description'].$params['order'].$params['lifetime'].$this->salt.$this->secretKey));
                }else {
                    $hash = strtoupper(hash('sha512',$params['amount'].$params['currency'].$params['description'].$params['order'].$this->salt.$this->secretKey));
                }
                break;
            case "checkPaymentStatus":
            case "closePayment":
                $hash = strtoupper(hash('sha512',$params['paymentid'].$this->salt.$this->secretKey));
                break;
            case "sendPayment":
                $hash = strtoupper(hash('sha512',$params['paymentid'].$params['data'].$this->salt.$this->secretKey));
                break;
            case "result":
                $hash = strtoupper(hash('sha512',$params['paymentid'].$params['status'].$params['order'].$params['testmode'].$params['salt'].$this->secretKey));
                break;
            default:
                throw new \InvalidArgumentException('Action not found');
                break;
        }
        return $hash;
    }

    /**
     *  Эта функция выполняет запрос в API BLACK PAYMENT;
     *  Проверяет метод, проверяет параметры запроса, генерирует подпись, отправляет запрос;
     *  Возвращает ответ API BLACK PAYMENT.
     *
     * @param string $action
     * @param array $params
     *
     * @return object
     *
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public function makeAction($action, $params = array())
    {
        if (empty($this->token)) {
            throw new \InvalidArgumentException('Token not found');
        }
        if (empty($this->secretKey)) {
            throw new \InvalidArgumentException('SecretKey not found');
        }
        if (empty($this->salt)) {
            throw new \InvalidArgumentException('Salt not found');
        }
        if (!in_array($action, $this->allowedActions)) {
            throw new \UnexpectedValueException('Action is not allowed');
        }
        if (isset($this->requiredActionParams[$action])) {
            foreach ($this->requiredActionParams[$action] as $param) {
                if (!isset($params[$param])) {
                    throw new \InvalidArgumentException('Param '.$param.' is null');
                }
            }
        }
        $params['token'] = $this->token;
        $params['salt'] = $this->salt;
        $params['hash'] = $this->getSha512Hash($action,$params);
        $request = $this->apiUrl.'?action='.$action.'&'.http_build_query($params);
        $responseSettings = stream_context_create(array(
            'http' => array('ignore_errors' => true),
        ));
        $response = json_decode(file_get_contents($request, false, $responseSettings));
        if (json_last_error() === JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('API error.');
        }
        return $response;
    }

    /**
     *  Эта функция проверяет запрос от Outbound сервера BLACK PAYMENT;
     *  Используется для обеспечения безопасности метода Result.
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function checkRequest()
    {
        $params = $_POST;
        foreach ($this->requiredActionParams['result'] as $param) {
            if (!isset($params[$param])) {
                throw new \InvalidArgumentException('Param '.$param.' not found');
            }
        }
        if ($params['hash'] != $this->getSha512Hash('result',$params)) {
            throw new \InvalidArgumentException('Hashes does not match');
        }
        return true;
    }

    /**
     * Эта функция генерирует успешный ответ для Outbound сервера BLACK PAYMENT;
     * Используется для отображения успешного ответа в методе Result.
     *
     * @param $message
     *
     * @return string
     */
    public function getSuccessResponse()
    {
        return json_encode(array(
            "status" => "OK"
        ));
    }

    /**
     * Эта функция генерирует неуспешный ответ для Outbound сервера BLACK PAYMENT;
     * Используется для отображения неуспешный ответа в методе Result.
     *
     * @param $message
     *
     * @return string
     */
    public function getErrorResponse($message)
    {
        return json_encode(array(
            "error" => true,
            "errorDescription" => $message
        ));
    }
}