<?php
/**
 * Echonest PHP Library
 *
 * @version 1.0.0
 */

namespace Echonest\Service;

use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Json\Json;
use Zend\I18n\Validator\Alnum as Alnum;

final class Echonest {

    static private $apiKey;

    static public function getApiKey()
    {
        return self::$apiKey;
    }

    static public function setApiKey($value)
    {
        self::$apiKey = $value;
    }

    static public function configure($apiKey)
    {
        self::setApiKey($apiKey);
    }

    static public function query($api, $command, $options = array())
    {
        // Validate configuration
        if (!self::getApiKey())
            throw new \Exception('Echonest has not been configured');

        $http = new Client();
        $http->setUri('http://developer.echonest.com/api/v4/' . $api . '/' . $command);
        $http->setOptions(array('sslverifypeer' => false));
        $http->setMethod('GET');

        $options['api_key'] = self::getApiKey();
        if (!isset($options['format'])) $options['format'] = 'json';
        $http->setParameterGet($options);

        $response = $http->send();
        return Json::decode($response->getBody());
    }
}
