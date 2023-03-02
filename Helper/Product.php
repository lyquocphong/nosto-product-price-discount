<?php

namespace PhongLy\NostoProductPriceDiscount\Helper;

use Magento\Customer\Model\Session as CustomerSession;
use PhongLy\NostoProductPriceDiscount\Helper\Config as ConfigHelper;

class Product
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @param CustomerSession $customerSession
     * @param ConfigHelper $configHelper
     *
     * @return void
     */
    public function __construct(
        CustomerSession $customerSession,
        ConfigHelper $configHelper
    ) {
        $this->customerSession= $customerSession;
        $this->configHelper = $configHelper;
    }

    /**
     * @param float $originalPrice
     *
     * @return float
     */
    public function handlePriceForNostoProduct(float $originalPrice)
    {
        $isEnable = $this->configHelper->isModuleEnabled();

        if (!$isEnable) {
            return $originalPrice;
        }

        $discountPercentage = $this->configHelper->getDiscountPercent();

        return ceil($originalPrice * ((100 - $discountPercentage) / 100));
    }
}
