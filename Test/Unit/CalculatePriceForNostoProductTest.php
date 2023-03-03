<?php

namespace PhongLy\NostoProductPriceDiscount\Test\Unit;

use PHPUnit\Framework\TestCase;
use Magento\Customer\Model\Session as CustomerSession;
use PhongLy\NostoProductPriceDiscount\Helper\Config as ConfigHelper;
use PhongLy\NostoProductPriceDiscount\Model\CalculatePriceForNostoProduct;
use Magento\Framework\Exception\RuntimeException;

class CalculatePriceForNostoProductTest extends TestCase
{
    /**
     * @var CalculatePriceForNostoProduct
     */
    protected $calculatePriceForNostoProduct;

    /**
     * @var CustomerSession
     */
    protected $customerSessionMock;

    /**
     * @var ConfigHelper
     */
    protected $configHelperMock;

    /**
     * @var string
     */
    protected $expectedMessage;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->customerSessionMock = $this->createMock(CustomerSession::class);
        $this->configHelperMock = $this->createMock(ConfigHelper::class);
        $this->calculatePriceForNostoProduct = new CalculatePriceForNostoProduct(
            $this->customerSessionMock,
            $this->configHelperMock
        );
    }

    /**
     * Test calculate price when module is disabled and customer is logged in
     *
     * @covers calculatePriceForNostoProduct::execute()
     * @return void
     */
    public function testCalculateWhenModuleIsDisabledAndCustomerIsLoggedIn()
    {
        $this->configHelperMock->expects($this->once())->method('isModuleEnabled')->willReturn(false);

        $originalPrice = 100;
        $calculatePrice = $this->calculatePriceForNostoProduct->execute($originalPrice);
        self::assertEquals($originalPrice, $calculatePrice);
    }

    /**
     * Test calculate price when module is enabled and customer is not logged in
     *
     * @covers calculatePriceForNostoProduct::execute()
     * @return void
     */
    public function testCalculateWhenCustomerIsNotLoggedInAndModuleEnabled()
    {
        $this->customerSessionMock->expects($this->once())->method('isLoggedIn')->willReturn(false);
        $this->configHelperMock->expects($this->once())->method('isModuleEnabled')->willReturn(true);

        $originalPrice = 100;
        $calculatePrice = $this->calculatePriceForNostoProduct->execute($originalPrice);
        self::assertEquals($originalPrice, $calculatePrice);
    }

    /**
     * Test price calculation
     *
     * @covers calculatePriceForNostoProduct::execute()
     * @return void
     */
    public function testPriceCalculation()
    {
        $this->customerSessionMock->expects($this->once())->method('isLoggedIn')->willReturn(true);
        $this->configHelperMock->expects($this->once())->method('isModuleEnabled')->willReturn(true);

        $discountPercent = 10;
        $this->configHelperMock->expects($this->once())->method('getDiscountPercent')
            ->willReturn($discountPercent);

        $originalPrice = 100;
        $expectedPrice = 90;
        $calculatePrice = $this->calculatePriceForNostoProduct->execute($originalPrice);
        self::assertEquals($expectedPrice, $calculatePrice);
    }

    /**
     *
     * @return void
     * @throws RuntimeException
     */
    public function testPriceCalculationWithDiscountPercentIsZero()
    {
        $this->customerSessionMock->expects($this->once())->method('isLoggedIn')->willReturn(true);
        $this->configHelperMock->expects($this->once())->method('isModuleEnabled')->willReturn(true);

        $discountPercent = 0;
        $this->configHelperMock->expects($this->once())->method('getDiscountPercent')
            ->willReturn($discountPercent);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Discount percent should be > 0 and < 100');

        $originalPrice = 100;
        $this->calculatePriceForNostoProduct->execute($originalPrice);
    }

    /**
     * @return void
     * @throws RuntimeException
     */
    public function testPriceCalculationWithDiscountPercentLessThanZero()
    {
        $this->customerSessionMock->expects($this->once())->method('isLoggedIn')->willReturn(true);
        $this->configHelperMock->expects($this->once())->method('isModuleEnabled')->willReturn(true);

        $discountPercent = -1;
        $this->configHelperMock->expects($this->once())->method('getDiscountPercent')
            ->willReturn($discountPercent);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Discount percent should be > 0 and < 100');

        $originalPrice = 100;
        $this->calculatePriceForNostoProduct->execute($originalPrice);
    }

    /**
     * @return void
     * @throws RuntimeException
     */
    public function testPriceCalculationWithDiscountPercentGreaterThan100()
    {
        $this->customerSessionMock->expects($this->once())->method('isLoggedIn')->willReturn(true);
        $this->configHelperMock->expects($this->once())->method('isModuleEnabled')->willReturn(true);

        $discountPercent = -1;
        $this->configHelperMock->expects($this->once())->method('getDiscountPercent')
            ->willReturn($discountPercent);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Discount percent should be > 0 and < 100');

        $originalPrice = 101;
        $this->calculatePriceForNostoProduct->execute($originalPrice);
    }
}
