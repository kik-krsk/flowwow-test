<?php

namespace app\components\openExchanges\dto;

class ErrorDto
{
    public bool $error;
    public int $status;
    public string $message;
    public string $description;
}
