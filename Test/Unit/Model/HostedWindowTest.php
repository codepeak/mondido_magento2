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

namespace Mondido\Mondido\Test\Unit\Model;

use Mondido\Mondido\Test\Unit\PaymentPspObjectManager as ObjectManager;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * HostedWindowTest
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class HostedWindowTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /** @var \Magento\Framework\Model\Context | MockObject */
    protected $contextMock;

    /** @var \Magento\Framework\Registry | MockObject */
    protected $registryMock;

    /** @var \Magento\Framework\Api\ExtensionAttributesFactory | MockObject */
    protected $extensionFactoryMock;

    /** @var \Magento\Framework\Api\AttributeValueFactory | MockObject */
    protected $customAttributeFactoryMock;

    /** @var \Magento\Payment\Helper\Data | MockObject */
    protected $paymentDataMock;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface | MockObject */
    protected $scopeConfigMock;

    /** @var \Magento\Payment\Model\Method\Logger | MockObject */
    protected $loggerMock;

    /** @var \Mondido\Mondido\Model\Api\Transaction | MockObject */
    protected $transactionMock;

    /** @var \Magento\Framework\Model\ResourceModel\AbstractResource | MockObject */
    protected $resourceMock;

    /** @var \Magento\Framework\Data\Collection\AbstractDb | MockObject */
    protected $resourceCollectionMock;

    /** @var \Magento\Directory\Helper\Data | MockObject */
    protected $directoryMock;

    /** @var \Mondido\Mondido\Model\HostedWindow | MockObject */
    protected $hostedWindowMock;

    /** @var \Magento\Payment\Model\InfoInterface | MockObject */
    protected $paymentMock;

    /** @var \Magento\Sales\Model\Order | MockObject */
    protected $orderMock;

    /** @var \Magento\Sales\Model\Order\Creditmemo | MockObject */
    protected $creditmemoMock;

    /** @var \Magento\Sales\Model\Order\Invoice | MockObject */
    protected $invoiceMock;

    /** @var int */
    protected $amount;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->contextMock = $this->getMockBuilder(\Magento\Framework\Model\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->registryMock = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->extensionFactoryMock = $this->getMockBuilder(\Magento\Framework\Api\ExtensionAttributesFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->customAttributeFactoryMock = $this->getMockBuilder(\Magento\Framework\Api\AttributeValueFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->paymentDataMock = $this->getMockBuilder(\Magento\Payment\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->scopeConfigMock = $this->getMockBuilder(\Magento\Framework\App\Config\ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loggerMock = $this->getMockBuilder(\Magento\Payment\Model\Method\Logger::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->transactionMock = $this->getMockBuilder(\Mondido\Mondido\Model\Api\Transaction::class)
            ->setMethods(['capture','refund'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->resourceMock = $this->getMockBuilder(\Magento\Framework\Model\ResourceModel\AbstractResource::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->resourceCollectionMock = $this->getMockBuilder(\Magento\Framework\Data\Collection\AbstractDb::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->directoryMock = $this->getMockBuilder(\Magento\Directory\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->creditmemoMock = $this->getMockBuilder(\Magento\Sales\Model\Order\Creditmemo::class)
            ->setMethods(['getInvoice'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->invoiceMock = $this->getMockBuilder(\Magento\Sales\Model\Order\Invoice::class)
            ->setMethods(['canRefund'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->paymentMock = $this->getMockBuilder(\Magento\Payment\Model\InfoInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getOrder',
                    'setTransactionId',
                    'setAdditionalInformation',
                    'setIsTransactionClosed',
                    'getParentTransactionId',
                    'getCreditmemo',
                    'canRefund',
                    'setShouldCloseParentTransaction'
                ]
            )
            ->getMockForAbstractClass();

        $this->orderMock = $this->getMockBuilder(\Magento\Sales\Model\Order::class)
            ->setMethods(
                [
                    'getBaseTotalOnlineRefunded',
                    'getBaseTotalOfflineRefunded',
                    'getCreditmemo'
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->hostedWindowMock = $this->getMockBuilder(
            \Mondido\Mondido\Model\HostedWindow::class
        )->setConstructorArgs([
            'context' => $this->contextMock,
            'registry' => $this->registryMock,
            'extensionFactory' => $this->extensionFactoryMock,
            'customAttributeFactory' => $this->customAttributeFactoryMock,
            'paymentData' => $this->paymentDataMock,
            'scopeConfig' => $this->scopeConfigMock,
            'logger' => $this->loggerMock,
            'transaction' => $this->transactionMock,
            'resource' => $this->resourceMock,
            'resourceCollection' => $this->resourceCollectionMock,
            'data' => [],
            'directory' => $this->directoryMock
        ])
            ->setMethodsExcept(
                [
                    'authorize',
                    'order',
                    'capture',
                    'void',
                    'acceptPayment',
                    'denyPayment',
                    'refund',
                    'getConfigData',
                ]
            )
            ->getMock();
    }

    /**
     * Success authorize test ( method - authorize )
     */
    public function testSuccessAuthorize()
    {
        $this->assertEquals($this->hostedWindowMock, $this->hostedWindowMock->authorize($this->paymentMock, $this->amount));
    }

    /**
     * Success order payment test ( method - order )
     */
    public function testSuccessOrder()
    {
        $this->assertEquals($this->hostedWindowMock, $this->hostedWindowMock->order($this->paymentMock, $this->amount));
    }

    /**
     * Test if result is not an object ( method - capture )
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testCaptureIfResultIsNotObject()
    {
        $this->paymentMock->method('getOrder')->willReturn($this->orderMock);
        $this->transactionMock->method('capture')->with($this->orderMock, $this->amount)->willReturn(false);
        $this->expectExceptionMessage('Could not capture order online');
        $this->hostedWindowMock->capture($this->paymentMock, $this->amount);
    }

    /**
     * Test if result is an object without code property ( method - capture )
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testCaptureIfResultIsObjectWithoutCodeProperty()
    {
        $this->paymentMock->method('getOrder')->willReturn($this->orderMock);
        $this->transactionMock->method('capture')->with($this->orderMock, $this->amount)->willReturn(true);
        $this->expectExceptionMessage('Could not capture order online');
        $this->hostedWindowMock->capture($this->paymentMock, $this->amount);
    }

    /**
     * Test if result is an object with code property ( method - capture )
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testCaptureIfResultIsObjectWithCodeProperty()
    {
        $object = new \stdClass();
        $object->code = 'code';
        $object->description = '';
        $object->name = '';
        $this->paymentMock->method('getOrder')->willReturn($this->orderMock);
        $this->transactionMock->method('capture')->with($this->orderMock, $this->amount)->willReturn(json_encode($object));
        $this->expectExceptionMessage('Mondido returned error code 0:  ()');
        $this->hostedWindowMock->capture($this->paymentMock, $this->amount);
    }

    /**
     * Test if result is an object without id ( method - capture )
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testCaptureIfResultIsObjectWithoutId()
    {
        $object = new \stdClass();
        $this->paymentMock->method('getOrder')->willReturn($this->orderMock);
        $this->transactionMock->method('capture')->with($this->orderMock, $this->amount)->willReturn(json_encode($object));
        $this->expectExceptionMessage('Could not capture order online');
        $this->hostedWindowMock->capture($this->paymentMock, $this->amount);
    }

    /**
     * Success capture test ( method - capture )
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testSuccessCapture()
    {
        $object = new \stdClass();
        $object->id = 25;
        $object->href = 'test';
        $object->status = 'test';
        $this->paymentMock->method('getOrder')->willReturn($this->orderMock);
        $this->transactionMock->method('capture')->with($this->orderMock, $this->amount)->willReturn(json_encode($object));
        $this->paymentMock->method('setTransactionId')->with($object->id)->willReturnSelf();
        $this->paymentMock->method('setIsTransactionClosed')->with(false)->willReturnSelf();
        $this->paymentMock->method('setAdditionalInformation')->withAnyParameters()->willReturnSelf();
        $this->paymentMock->method('setAdditionalInformation')->withAnyParameters()->willReturnSelf();
        $this->paymentMock->method('setAdditionalInformation')->withAnyParameters()->willReturnSelf();
        $this->assertEquals(true, $this->hostedWindowMock->capture($this->paymentMock, $this->amount));
    }

    /**
     * Test if not exist capture transaction id ( method - refund )
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testRefundWithoutCaptureTxnId()
    {
        $this->paymentMock->method('getParentTransactionId')->willReturn(null);
        $this->expectExceptionMessage('We can\'t issue a refund transaction because there is no capture transaction.');
        $this->hostedWindowMock->refund($this->paymentMock, $this->amount);
    }

    /**
     * Test if exist capture transaction id and result code property != 200 ( method - refund )
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testRefundWithCaptureTxnIdAndResultCodeProperty()
    {
        $object = new \stdClass();
        $object->code = 150;
        $object->description =
            'test';
        $object->name = 'test';
        $this->paymentMock->method('getParentTransactionId')->willReturn('test');
        $this->paymentMock->method('getOrder')->willReturn($this->orderMock);
        $this->paymentMock->method('getCreditmemo')->willReturn($this->creditmemoMock);
        $this->creditmemoMock->method('getInvoice')->willReturn($this->invoiceMock);
        $this->invoiceMock->method('canRefund')->willReturn(true);
        $this->orderMock->method('getBaseTotalOnlineRefunded')->willReturn(1.25);
        $this->orderMock->method('getBaseTotalOfflineRefunded')->willReturn(1.25);
        $this->transactionMock->method('refund')->willReturn(json_encode($object));
        $this->expectExceptionMessage('Mondido returned error code 150: test (test)');
        $this->hostedWindowMock->refund($this->paymentMock, $this->amount);
    }

    /**
     * Success refund test ( method - refund )
     */
    public function testSuccessRefund()
    {
        $object = new \stdClass();
        $object->description = 'test';
        $object->name = 'test';
        $object->id = 25;
        $this->paymentMock->method('getParentTransactionId')->willReturn('test');
        $this->paymentMock->method('getOrder')->willReturn($this->orderMock);
        $this->paymentMock->method('getCreditmemo')->willReturn($this->creditmemoMock);
        $this->creditmemoMock->method('getInvoice')->willReturn($this->invoiceMock);
        $this->invoiceMock->method('canRefund')->willReturn(true);
        $this->orderMock->method('getBaseTotalOnlineRefunded')->willReturn(1.25);
        $this->orderMock->method('getBaseTotalOfflineRefunded')->willReturn(1.25);
        $this->transactionMock->method('refund')->willReturn(json_encode($object));
        $this->paymentMock->method('setTransactionId')->withAnyParameters()->willReturnSelf();
        $this->paymentMock->method('setIsTransactionClosed')->with(1)->willReturn($this->orderMock);
        $this->paymentMock->method('setShouldCloseParentTransaction')->with(!$this->invoiceMock)->willReturn($this->orderMock);
        $this->assertEquals($this->hostedWindowMock,$this->hostedWindowMock->refund($this->paymentMock, $this->amount));
    }

    /**
     * Success void method test ( method - void )
     */
    public function testSuccessVoid()
    {
        $this->assertEquals($this->hostedWindowMock, $this->hostedWindowMock->void($this->paymentMock, $this->amount));
    }

    /**
     * Success acceptPayment method test ( method - acceptPayment )
     */
    public function testSuccessAcceptPayment()
    {
        $this->assertEquals(false, $this->hostedWindowMock->acceptPayment($this->paymentMock, $this->amount));
    }

    /**
     * Success denyPayment method test ( method - denyPayment )
     */
    public function testSuccessDenyPayment()
    {
        $this->assertEquals(false, $this->hostedWindowMock->denyPayment($this->paymentMock, $this->amount));
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->objectManager = null;
        $this->contextMock = null;
        $this->registryMock = null;
        $this->extensionFactoryMock = null;
        $this->customAttributeFactoryMock = null;
        $this->paymentDataMock = null;
        $this->scopeConfigMock = null;
        $this->loggerMock = null;
        $this->transactionMock = null;
        $this->resourceMock = null;
        $this->resourceCollectionMock = null;
        $this->directoryMock = null;
        $this->creditmemoMock = null;
        $this->invoiceMock = null;
        $this->paymentMock =null;
        $this->orderMock = null;
        $this->hostedWindowMock = null;
    }
}
