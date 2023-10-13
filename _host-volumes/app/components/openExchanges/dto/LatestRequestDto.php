<?php

namespace app\components\openExchanges\dto;
use app\components\openExchanges\dto\AbstractDto;

class LatestRequestDto extends AbstractDto
{
    public string $disclaimer;
    public string $license;
    public string $timestamp;
    public string $base;
    public array $rates;
}
