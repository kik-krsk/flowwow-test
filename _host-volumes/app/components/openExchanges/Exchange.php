<?php

namespace app\components\openExchanges;

use app\components\openExchanges\dto\LatestDto;
use Codeception\Util\HttpCode;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

class Exchange
{
    protected ?Client $client;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client();
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $data
     * @param array  $headers
     * @param array  $options
     *
     * @return mixed
     *
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function getData(string $method = 'GET', string $url, array $data = [], array $headers = [], array $options = [])
    {
        $request = $this->client->createRequest()
            ->setMethod($method)
            ->setUrl($url);
        if (!empty($data)) {
            $request->setData($data);
        }
        if (!empty($headers)) {
            $request->setHeaders($data);
        }
        if (!empty($options)) {
            $request->setOptions($options);
        }
        $response = $request->send();
        if ($response->isOk) {
            return $response->data;
        }
        \Yii::error($response->data, 'openexchanges');
        $data = $response->data;
        /** @var \app\components\openExchanges\dto\ErrorDto $data */
        match ((int)$response->statusCode) {
            HttpCode::NOT_FOUND => throw new \yii\web\NotFoundHttpException($data->description),
            HttpCode::UNAUTHORIZED => throw new \yii\web\UnauthorizedHttpException($data->description),
            HttpCode::TOO_MANY_REQUESTS => throw new \yii\web\MethodNotAllowedHttpException($data->description),
            HttpCode::FORBIDDEN => throw new \yii\web\ForbiddenHttpException($data->description),
            HttpCode::BAD_REQUEST => throw new \yii\web\BadRequestHttpException($data->description),
            default => throw new \yii\web\HttpException('Unknown status code: ' . $response->statusCode),
        };
    }

    /** /latest.json.
     * @see https://docs.openexchangerates.org/reference/latest-json
     *
     * @param null|string $base
     * @param null|string $symbols
     * @param null|bool   $prettyPrint
     * @param null|bool   $showAlternative
     *
     * @return LatestDto
     *
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function getLastest(?string $base = null, ?string $symbols = null, ?bool $prettyPrint = null, ?bool $showAlternative = null)
    {
        $data['base'] = $base;
        $data['symbols'] = $symbols;
        $data['prettyprint'] = $prettyPrint;
        $data['show_alternative'] = $showAlternative;

        return $this->getData('GET', 'latest.json', array_filter($data));
    }
}
