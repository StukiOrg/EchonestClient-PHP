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
    static private $source;

    static public function getApiKey() {
        return self::$apiKey;
    }

    static public function setApiKey($value) {
        self::$apiKey = $value;
    }

    static public function configure(
        $apiKey,
        $source = 'http://developer.echonest.com/api/v4/' # include trailing slash
    ) {
        self::setApiKey($apiKey);
        self::$source = $source;
    }

    static public function query($api, $command, $options = null) {
        // Validate configuration
        if (!self::getApiKey()) {
            throw new \Exception('Echonest has not been configured');
        }

        $http = new Client();
        $http->setUri(self::$source . $api . '/' . $command);
        $http->setOptions(array('sslverifypeer' => false));
        $http->setMethod('GET');

        $format = 'json';

        if(is_array($options)) {
            $http->setUri( self::$source . $api . '/' . $command);
            $options['api_key'] = self::getApiKey();

            if (!isset($options['format'])) {
                $options['format'] = $format;
            } else {
                $format = $options['format'];
            }

            $http->setParameterGet($options);
        } else {
            #options as a query string
            if (!$options )
                throw new \Exception( "The options must be an array or a non empty string" );

            if (!strpos($options, 'api_key'))
                $options .= '&api_key=' . self::getApiKey();

            #find format in query string
            preg_match('/format=([^&]+)&/', $options, $matches);

            if (is_array($matches)) {
                $format = $matches[1] ? $matches[1] : 'json';
            }

            $http->setUri(self::$source . $api . '/' . $command . '?' . $options);
        }

        $response = $http->send();

        #output response according to format
        if ($format == 'xml') {
            return simplexml_load_string($response->getBody());
        } else {
            return Json::decode($response->getBody());
        }
    }
}
