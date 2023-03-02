<?php

namespace PhongLy\NostoProductPriceDiscount\Observer;

use Magento\Catalog\Model\Product as MagentoProduct;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Nosto\Model\Product\Product as NostoProduct;
use PhongLy\NostoProductPriceDiscount\Helper\Product as ProductHelper;

class NostoProductLoadAfter implements ObserverInterface
{
    /**
     * @var ProductHelper
     */
    protected $productHelper;

    /**
     * @param ProductHelper $productHelper
     */
    public function __construct(ProductHelper $productHelper)
    {
        $this->productHelper = $productHelper;
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
            $newPrice = $this->productHelper->handlePriceForNostoProduct((float)$magentoProduct->getFinalPrice());
            $newListPrice = $this->productHelper->handlePriceForNostoProduct((float)$magentoProduct->getPrice());

            $nostoProduct->setPrice($newPrice);
            $nostoProduct->setListPrice($newListPrice);
        }
    }
}
