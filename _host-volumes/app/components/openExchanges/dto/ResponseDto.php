<?php

namespace app\components\openExchanges\dto;

class ResponseDto extends AbstractDto
{
    public string $method = 'GET';
    public string $url;
    public array $data = [];
    public array $headers = [];
    public array $options = [];
}
