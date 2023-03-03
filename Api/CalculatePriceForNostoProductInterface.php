<?php

namespace PhongLy\NostoProductPriceDiscount\Api;

interface CalculatePriceForNostoProductInterface
{
    public function execute(float $originalPrice);
}
