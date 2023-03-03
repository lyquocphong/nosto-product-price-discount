<?php

namespace PhongLy\NostoProductPriceDiscount\Model;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\RuntimeException;
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

        if ($discountPercentage <= 0 || $discountPercentage > 100) {
            throw new RuntimeException(__('Discount percent should be > 0 and < 100'));
        }

        return ceil($originalPrice * ((100 - $discountPercentage) / 100));
    }
}
