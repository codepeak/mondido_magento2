<?php
/**
 * Mondido
 *
 * PHP version 5.6
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */

namespace Mondido\Mondido\Test\Unit\Model\Session;

use Mondido\Mondido\Test\Unit\PaymentPspObjectManager as ObjectManager;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * SuccessValidatorTest
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class SuccessValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /** @var  \Mondido\Mondido\Model\Session\SuccessValidator | MockObject */
    protected $successValidatorMock;

    /** @var \Magento\Checkout\Model\Session | MockObject */
    protected $checkoutSessionMock;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->checkoutSessionMock = $this->getMockBuilder(
            \Magento\Checkout\Model\Session::class
        )
            ->setMethods(
                [
                    'getLastSuccessQuoteId',
                    'getLastQuoteId',
                    'getLastRealOrderId'
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->successValidatorMock = $this->getMockBuilder(
            \Mondido\Mondido\Model\Session\SuccessValidator::class
        )
            ->setConstructorArgs(
                [
                    'checkoutSession' => $this->checkoutSessionMock
                ]
            )
            ->setMethodsExcept(['isValid'])
            ->getMock();
    }

    /**
     * Test if not exist last success quote id ( method - isValid )
     *
     * @return void
     */
    public function testIsValidIfNotExistLastSuccessQuoteId()
    {
        $this->checkoutSessionMock->method('getLastSuccessQuoteId')->willReturn(false);
        $this->assertEquals(false,$this->successValidatorMock->isValid());
    }

    /**
     * Test if not exist last quote id ( method - isValid )
     */
    public function testIsValidIfNotExistLastQuoteId()
    {
        $this->checkoutSessionMock->method('getLastSuccessQuoteId')->willReturn(true);
        $this->checkoutSessionMock->method('getLastQuoteId')->willReturn(null);
        $this->assertEquals(false,$this->successValidatorMock->isValid());
    }

    /**
     * Test if not exist last real order id ( method - isValid )
     */
    public function testIsValidIfNotExistLastRealOrderId()
    {
        $this->checkoutSessionMock->method('getLastSuccessQuoteId')->willReturn(true);
        $this->checkoutSessionMock->method('getLastQuoteId')->willReturn(true);
        $this->checkoutSessionMock->method('getLastRealOrderId')->willReturn(null);
        $this->assertEquals(false,$this->successValidatorMock->isValid());
    }

    /**
     * Success method ( method - isValid )
     */
    public function testIsValidSuccess()
    {
        $this->checkoutSessionMock->method('getLastSuccessQuoteId')->willReturn(true);
        $this->checkoutSessionMock->method('getLastQuoteId')->willReturn(true);
        $this->checkoutSessionMock->method('getLastRealOrderId')->willReturn(true);
        $this->assertEquals(true,$this->successValidatorMock->isValid());
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->objectManager = null;
        $this->checkoutSessionMock = null;
        $this->successValidatorMock = null;
    }
}
