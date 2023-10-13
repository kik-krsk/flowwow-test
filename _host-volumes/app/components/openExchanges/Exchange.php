<?php

namespace app\components\openExchanges;

use app\components\openExchanges\dto\ErrorDto;
use app\components\openExchanges\dto\LatestRequestDto;
use app\components\openExchanges\dto\LatestResponseDto;
use app\components\openExchanges\dto\ResponseDto;
use Codeception\Util\HttpCode;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

class Exchange
{
    public function __construct(protected Client $client = new Client()) {}

    /** /latest.json.
     * @see https://docs.openexchangerates.org/reference/latest-json
     *
     * @param ?LatestResponseDto $latestResponseDto
     *
     * @return LatestRequestDto
     *
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function getLastest(?LatestResponseDto $latestResponseDto = null): LatestRequestDto
    {
        $data = [];
        if ($latestResponseDto) {
            $data['base'] = $latestResponseDto->base;
            $data['symbols'] = $latestResponseDto->symbols;
            $data['prettyprint'] = $latestResponseDto->prettyPrint;
            $data['show_alternative'] = $latestResponseDto->showAlternative;
        }
        $responseDto = new ResponseDto();
        $responseDto->url = 'latest.json';
        $responseDto->data = array_filter($data);

        $requestResult = $this->getData($responseDto);

        return new LatestRequestDto($requestResult);
    }

    /**
     * @param ResponseDto $responseDto
     *
     * @return mixed
     *
     * @throws InvalidConfigException
     * @throws Exception
     */
    protected function getData(ResponseDto $responseDto)
    {
        $request = $this->client->createRequest()
            ->setMethod($responseDto->method)
            ->setUrl($responseDto->url);
        if (!empty($responseDto->data)) {
            $request->setData($responseDto->data);
        }
        if (!empty($responseDto->headers)) {
            $request->setHeaders($responseDto->headers);
        }
        if (!empty($responseDto->options)) {
            $request->setOptions($responseDto->options);
        }
        $response = $request->send();
        if ($response->isOk) {
            return $response->data;
        }
        \Yii::error(var_export($response->data, true), 'openexchanges');
        $responseData = new ErrorDto($response->data);
        match ((int) $response->statusCode) {
            HttpCode::NOT_FOUND => throw new \yii\web\NotFoundHttpException($responseData->description),
            HttpCode::UNAUTHORIZED => throw new \yii\web\UnauthorizedHttpException($responseData->description),
            HttpCode::TOO_MANY_REQUESTS => throw new \yii\web\MethodNotAllowedHttpException($responseData->description),
            HttpCode::FORBIDDEN => throw new \yii\web\ForbiddenHttpException($responseData->description),
            HttpCode::BAD_REQUEST => throw new \yii\web\BadRequestHttpException($responseData->description),
            default => throw new \yii\web\HttpException('Unknown status code: ' . $response->statusCode),
        };
    }
}
