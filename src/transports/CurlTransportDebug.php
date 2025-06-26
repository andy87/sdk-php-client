<?php

namespace andy87\sdk\client\transports;

use Exception;
use CurlHandle;
use andy87\sdk\client\base\interfaces\RequestInterface;
use andy87\sdk\client\core\transport\{ Query, Response };
use andy87\sdk\client\helpers\{ ContentType , MethodRegistry };

/**
 * Класс CurlOperator
 *
 * Отправляет запросы к API с использованием cURL.
 *
 * @package src/core/operators
 */
final class CurlTransportDebug extends CurlTransport
{
    private const NAMING = [
        CURLOPT_ENCODING => 'CURLOPT_ENCODING',
        CURLOPT_MAXREDIRS => 'CURLOPT_MAXREDIRS',
        CURLOPT_TIMEOUT => 'CURLOPT_TIMEOUT',
        CURLOPT_RETURNTRANSFER => 'CURLOPT_RETURNTRANSFER',
        CURLOPT_HTTP_VERSION => 'CURLOPT_HTTP_VERSION',
        CURLOPT_HTTPHEADER => 'CURLOPT_HTTPHEADER',
        CURLOPT_URL => 'CURLOPT_URL',
        CURLOPT_POSTFIELDS => 'CURLOPT_POSTFIELDS',
        CURLOPT_FOLLOWLOCATION => 'CURLOPT_FOLLOWLOCATION',
        CURLOPT_CUSTOMREQUEST => 'CURLOPT_CUSTOMREQUEST',
    ];



    /**
     * Отправляет запрос к API.
     *
     * @param RequestInterface $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function sendRequest( RequestInterface $request ): Response
    {
        $query = $request->getQuery();

        $curl = curl_init();

        if ($curl === false) {

            $error = 'Failed to initialize cURL session.';

            $this->errorHandler(__METHOD__, __LINE__, $error);

            $response = new Response( $request, 0 );

            $response->addError($error);

        } else {

            $this->handleData( $request, $query );

            $url = $query->getEndpoint( $request );

            $this->options[CURLOPT_HTTPHEADER] = $query->getHeaders();
            $this->options[CURLOPT_URL] = $url;

            $this->displayOptions($this->options);

            curl_setopt_array( $curl, $this->options );

            $cURL_options = [];

            foreach ($this->options as $key => $value)
            {
                $cURL_options[$key] = [ $this->getCurlOptionName($key), $value ];
            }

            print_r([
                'curl' => is_resource($curl) ? 'Resource' : 'Not a resource',
                'curl_getinfo' => $cURL_options,
                'this->options' => $this->options,
            ]);

            sleep(3);

            $content = curl_exec($curl) ?? self::EMPTY_RESPONSE;
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE) ?: null;
            $curlInfo = $this->handleCustomParams( $curl, $query );

            echo PHP_EOL;
            print_r([
                'content' => $content,
                'statusCode' => $statusCode,
                'curlInfo' => $curlInfo
            ]);
            echo PHP_EOL;
            sleep(5);

            $response = new Response( $request, $statusCode, $content );

            curl_close($curl);

            if ( !empty($curlInfo)) $response->setCustomParams( $curlInfo );
        }

        return $response;
    }

    /**
     * @param array $options
     *
     * @return void
     */
    private function displayOptions( array $options ): void
    {
        echo PHP_EOL;

        foreach ($options as $key => $value)
        {
            if ( is_string($value) AND empty($value) ) $value = '< string : empty >';

            if ( $value === null ) $value = '{null}';

            if ( isset(self::NAMING[$key]) )
            {
                if ( is_array($value) || is_object($value) ) {

                    $value = print_r($value, true);

                } elseif ( is_bool($value) ) {

                    $value = $value ? '< bool : true >' : '< bool : false >';

                } elseif ( is_int($value) || is_float($value) ) {

                    $value = (string) $value;
                }

                $option = sprintf("%s: %s\n", $this->getCurlOptionName($key), $value );

            } else {

                if ( is_array($value) || is_object($value) ) {

                    $value = print_r($value, true);
                }

                $option = sprintf("Unknown option (%d): %s\n", $key, $value );
            }

            echo $option;
        }

        echo PHP_EOL;

        sleep(5);
    }

    /**
     * @param int $id
     *
     * @return string
     */
    private function getCurlOptionName(int $id ): string
    {
        return self::NAMING[$id] ?? 'Unknown option (' . $id . ')';
    }
}