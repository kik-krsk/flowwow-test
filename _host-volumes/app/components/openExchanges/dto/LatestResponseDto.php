<?php

namespace app\components\openExchanges\dto;

class LatestResponseDto extends AbstractDto
{
    public ?string $base = null;
    public ?string $symbols = null;
    public ?bool $prettyPrint = null;
    public ?bool $showAlternative = null;
}
