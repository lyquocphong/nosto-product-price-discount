<?php

namespace PhongLy\NostoProductPriceDiscount\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const ENABLED_CONFIG_PATH = 'nosto_product_price_discount/general/enable';
    private const DISCOUNT_PERCENT_CONFIG_PATH = 'nosto_product_price_discount/general/discount_percent';

    /**
     * @var ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            self::ENABLED_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE,
        );
    }

    /**
     * @return int
     */
    public function getDiscountPercent()
    {
        return (int) $this->scopeConfig->getValue(
            self::DISCOUNT_PERCENT_CONFIG_PATH,
            ScopeInterface::SCOPE_STORE,
        );
    }
}
