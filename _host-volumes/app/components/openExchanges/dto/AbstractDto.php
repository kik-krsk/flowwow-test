<?php

namespace app\components\openExchanges\dto;

abstract class AbstractDto
{
    public function __construct(array $properties = [])
    {
        foreach ($properties as $property => $value) {
            $this->{$property} = $value;
        }
    }
}
