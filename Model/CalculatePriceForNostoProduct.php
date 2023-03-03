<?php

namespace PhongLy\NostoProductPriceDiscount\Model;

use Magento\Customer\Model\Session as CustomerSession;
use PhongLy\NostoProductPriceDiscount\Helper\Config as ConfigHelper;
use PhongLy\NostoProductPriceDiscount\Api\CalculatePriceForNostoProductInterface;

class CalculatePriceForNostoProduct implements CalculatePriceForNostoProductInterface
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
    public function execute(float $originalPrice)
    {
        $isEnable = $this->configHelper->isModuleEnabled();

        if (!$isEnable || !$this->customerSession->isLoggedIn()) {
            return $originalPrice;
        }

        $discountPercentage = $this->configHelper->getDiscountPercent();

        return ceil($originalPrice * ((100 - $discountPercentage) / 100));
    }
}
