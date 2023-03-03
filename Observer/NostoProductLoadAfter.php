<?php

namespace PhongLy\NostoProductPriceDiscount\Observer;

use Magento\Catalog\Model\Product as MagentoProduct;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Nosto\Model\Product\Product as NostoProduct;
use PhongLy\NostoProductPriceDiscount\Api\CalculatePriceForNostoProductInterface;

class NostoProductLoadAfter implements ObserverInterface
{
    /**
     * @var CalculatePriceForNostoProductInterface
     */
    protected $calculatePriceForNostoProduct;

    /**
     * @param CalculatePriceForNostoProductInterface $calculatePriceForNostoProduct
     */
    public function __construct(CalculatePriceForNostoProductInterface $calculatePriceForNostoProduct)
    {
        $this->calculatePriceForNostoProduct = $calculatePriceForNostoProduct;
    }

    /**
     * Observer for "nosto_product_load_after" event
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /* @var $nostoProduct NostoProduct */
        $nostoProduct = $observer->getProduct();
        /* @var $magentoProduct MagentoProduct */
        $magentoProduct = $observer->getData('magentoProduct');
        // Check that Magento product is set and get the custom prices from Magento product model
        if ($nostoProduct instanceof NostoProduct && $magentoProduct instanceof MagentoProduct) {
            $newPrice = $this->calculatePriceForNostoProduct->execute((float)$magentoProduct->getFinalPrice());
            $newListPrice = $this->calculatePriceForNostoProduct->execute((float)$magentoProduct->getPrice());

            $nostoProduct->setPrice($newPrice);
            $nostoProduct->setListPrice($newListPrice);
        }
    }
}
