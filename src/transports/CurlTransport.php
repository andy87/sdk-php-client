<?php

namespace andy87\sdk\client\transports;

use Exception;
use CurlHandle;
use andy87\sdk\client\base\modules\AbstractTransport;
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
final class CurlTransport extends AbstractTransport
{
    public const EMPTY_RESPONSE = '{empty response}';

    /**
     * Типы контента, которые разрешены для отправки в теле POST-запросов.
     *
     * @var array
     */
    public const ACCESS_POST_FIELDS = [
        ContentType::JSON,
        ContentType::XML,
        ContentType::MULTIPART_FORM_DATA
    ];

    /** @var array  */
    public array $options = [
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

            $content = curl_exec($curl) ?? self::EMPTY_RESPONSE;

            echo PHP_EOL;
            print_r([ 'content' => $content ]);
            echo PHP_EOL;
            sleep(5);

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE) ?: null;

            $response = new Response( $request, $statusCode, $content );

            $curlInfo = $this->handleCustomParams( $curl, $query );

            echo PHP_EOL;
            print_r([ 'curlInfo' => $curlInfo ]);
            echo PHP_EOL;

            curl_close($curl);

            sleep(5);

            if ( !empty($curlInfo)) $response->setCustomParams( $curlInfo );
        }

        return $response;
    }

    /**
     * Установка данных запроса в зависимости от HTTP метода.
     *
     * @param RequestInterface $request
     * @param Query $query
     *
     * @throws Exception
     */
    private function handleData( RequestInterface $request, Query $query ): void
    {
        $method = $query->getMethod();

        if ( in_array( $method, MethodRegistry::POST_FIELDS ) )
        {
            $data = $query->getData();

            if ( !empty($data) )
            {
                if ( $request->getPrompt()->contentTypeIn(self::ACCESS_POST_FIELDS ) )
                {
                    $this->options[CURLOPT_POSTFIELDS] = http_build_query($data);
                }
            }
        }

        $this->options[CURLOPT_CUSTOMREQUEST] = $method;
    }

    /**
     * Обрабатывает пользовательские параметры.
     *
     * @param CurlHandle $curl
     * @param Query $query
     *
     * @return array
     *
     * @throws Exception
     */
    private function handleCustomParams(CurlHandle $curl, Query $query): array
    {
        if ( $params = $query->getCustomParams() )
        {
            foreach ( $params as $key => $value )
            {
                $params[$key] = curl_getinfo($curl, $key);
            }
        }

        return $params;
    }

    private function displayOptions( array $options ): void
    {
        $naming = [
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

        echo PHP_EOL;

        foreach ($options as $key => $value)
        {
            if ( is_string($value) AND empty($value) ) $value = '< string : empty >';

            if ( $value === null ) $value = '{null}';

            if ( isset($naming[$key]) )
            {
                if ( is_array($value) || is_object($value) ) {
                    $value = print_r($value, true);
                } elseif ( is_bool($value) ) {
                    $value = $value ? '< bool : true >' : '< bool : false >';
                } elseif ( is_int($value) || is_float($value) ) {
                    $value = (string) $value;
                }

                $option = sprintf("%s: %s\n", $naming[$key], $value );

            } else {

                $option = sprintf("Unknown option (%d): %s\n", $key, $value );
            }

            echo $option;
        }

        echo PHP_EOL;

        sleep(5);
    }
}