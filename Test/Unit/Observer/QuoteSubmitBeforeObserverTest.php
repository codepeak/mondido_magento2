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

namespace Mondido\Mondido\Test\Unit\Observer;

use Mondido\Mondido\Test\Unit\PaymentPspObjectManager as ObjectManager;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Observer test
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class QuoteSubmitBeforeObserverTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Mondido\Mondido\Test\Unit\PaymentPspObjectManager */
    protected $objectManager;

    /** @var \Mondido\Mondido\Observer\QuoteSubmitBeforeObserver | MockObject */
    protected $quoteSubmitBeforeObserverMock;

    /** @var \Magento\Framework\Event\Observer | MockObject */
    protected $observerMock;

    /** @var \Magento\Quote\Model\Quote | MockObject */
    protected $quoteMock;

    /** @var \Magento\Sales\Model\Order | MockObject */
    protected $orderMock;

    /**
     * Set up
     *
     * @return void
     */
    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->quoteSubmitBeforeObserverMock = $this->objectManager->getObject(\Mondido\Mondido\Observer\QuoteSubmitBeforeObserver::class);

        $this->observerMock = $this->getMockBuilder(\Magento\Framework\Event\Observer::class)
            ->setMethods(['getQuote','getOrder'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->quoteMock = $this->getMockBuilder(\Magento\Quote\Model\Quote::class)
            ->setMethods(['getMondidoTransaction'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->orderMock = $this->objectManager->getObject(\Magento\Sales\Model\Order::class);
    }

    /**
     * Test execute()
     *
     * @return void
     */
    public function testExecute()
    {
        $this->quoteMock->method('getMondidoTransaction')->willReturn('string');
        $this->observerMock->method('getQuote')->willReturn($this->quoteMock);
        $this->observerMock->method('getOrder')->willReturn($this->orderMock);
        $this->quoteSubmitBeforeObserverMock->execute($this->observerMock);
        $this->assertEquals('string',$this->orderMock->getMondidoTransaction());
    }

    /**
     * tearDown f
     */
    public function tearDown()
    {
        $this->objectManager = null;
        $this->observerMock = null;
        $this->quoteMock = null;
        $this->orderMock = null;
        $this->quoteSubmitBeforeObserverMock = null;

    }
}
