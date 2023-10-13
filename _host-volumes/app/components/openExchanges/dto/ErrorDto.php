<?php

namespace app\components\openExchanges\dto;

class ErrorDto extends AbstractDto
{
    public bool $error;
    public int $status;
    public string $message;
    public string $description;
}
