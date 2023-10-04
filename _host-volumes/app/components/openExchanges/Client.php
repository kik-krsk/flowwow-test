<?php

namespace app\components\openExchanges;

use yii\httpclient\JsonParser;

class Client extends \yii\httpclient\Client
{
    public const APP_ID = '84364a89b8014f7ab4b49dfde9acf415';
    public $baseUrl = 'https://openexchangerates.org/api';
    public $responseConfig = ['format' => Client::FORMAT_JSON];

    public function init()
    {
        \Yii::$container->set(JsonParser::class, ['asArray' => false]);
        parent::init();
    }

    public function beforeSend($request)
    {
        $request->addData(['app_id' => self::APP_ID]);

        parent::beforeSend($request);
    }
}
