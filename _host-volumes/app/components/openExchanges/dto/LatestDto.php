<?php

namespace app\components\openExchanges\dto;

class LatestDto
{
    public string $disclaimer;
    public string $license;
    public string $timestamp;
    public string $base;
    public array $rates;
}
