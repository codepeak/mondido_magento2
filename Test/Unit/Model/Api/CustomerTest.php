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

namespace Mondido\Mondido\Test\Unit\Model\Api;

use Mondido\Mondido\Test\Unit\PaymentPspObjectManager as ObjectManager;
use PHPUnit_Framework_MockObject_MockObject as MockObject;


/**
 * CustomerTest
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class CustomerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Mondido\Mondido\Model\Api\Customer | MockObject
     */
    protected $customerApiMock;

    /**
     * @var \Magento\Customer\Model\Data\Customer | MockObject
     */
    protected $customerMock;

    /**
     * @var int
     */
    protected $paymentId = 15;

    /**
     * @var \Magento\Framework\HTTP\Adapter\Curl | MockObject
     */
    protected $adapterMock;

    /**
     * @var \Mondido\Mondido\Model\Config | MockObject
     */
    protected $configMock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface | MockObject
     */
    protected $storeManagerMock;

    /**
     * @var \Magento\Framework\UrlInterface | MockObject
     */
    protected $urlBuilderMock;

    /**
     * @var \Magento\Customer\Model\AddressFactory | MockObject
     */
    protected $addressFactoryMock;

    /**
     * @var \Psr\Log\LoggerInterface | MockObject
     */
    protected $loggerMock;

    /**
     * @var array
     */
    protected $addressFields = [
        'firstname',
        'middlename',
        'lastname',
        'company',
        'street',
        'postcode',
        'city',
        'country_id',
        'region',
        'telephone',
        'vat_id'
    ];

    /**
     * @var array
     */
    protected $expectedSuccessMetadataArray;

    /**
     * @var int
     */
    protected $addressId;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->adapterMock = $this->getMockBuilder(\Magento\Framework\HTTP\Adapter\Curl::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->configMock = $this->getMockBuilder(\Mondido\Mondido\Model\Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeManagerMock = $this->getMockBuilder(\Magento\Store\Model\StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->urlBuilderMock = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->addressFactoryMock = $this->getMockBuilder(\Magento\Customer\Model\AddressFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loggerMock = $this->getMockBuilder(\Psr\Log\LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectedSuccessMetadataArray = [
            'firstname' => 'test firstname',
            'lastname' => 'test lastname',
            'email' => 'test email',
        ];

        $this->addressId = 25;

        $this->customerMock = $this->objectManager->getObject(\Magento\Customer\Model\Data\Customer::class);

        $this->customerApiMock = $this->getMockBuilder(
            \Mondido\Mondido\Model\Api\Customer::class
        )->setConstructorArgs([
            'adapter' => $this->adapterMock,
            'config' => $this->configMock,
            'storeManager' => $this->storeManagerMock,
            'urlBuilder' => $this->urlBuilderMock,
            'addressFactory' => $this->addressFactoryMock,
            'logger' => $this->loggerMock
        ])
            ->setMethodsExcept(['create', 'update', 'getIdByRef'])
            ->getMock();
    }

    /**
     * Test if json response is null ( method - create )
     */
    public function testCreateIfJsonResponseIsNull()
    {
        $this->customerApiMock->method('buildMetadata')->with($this->customerMock)->willReturn(false);
        $this->customerApiMock->method('call')->willReturn(null);
        $this->assertEquals(false, $this->customerApiMock->create($this->customerMock));
    }

    /**
     * Test if response is not array ( method - create )
     */
    public function testCreateIfResponseIsNotArray()
    {
        $this->customerApiMock->method('call')->willReturn('test');
        $this->assertEquals(false, $this->customerApiMock->create($this->customerMock));
    }

    /**
     * Test if response is not an object ( method - create )
     */
    public function testCreateIfResponseIsNotAnObject()
    {
        $arrayMock = [];
        $this->customerApiMock->method('call')->willReturn(json_encode($arrayMock));
        $this->assertEquals(false, $this->customerApiMock->create($this->customerMock));
    }

    /**
     * Test if response is an object without id ( method - create )
     */
    public function testCreateIfResponseIsObjectWithoutId()
    {
        $jsonResponse = [new \stdClass()];
        $this->customerApiMock->method('call')->willReturn(json_encode($jsonResponse));
        $this->assertEquals(false, $this->customerApiMock->create($this->customerMock));
    }

    /**
     * Success create method test ( method - create )
     */
    public function testSuccessCustomerCreate()
    {
        $object = new \stdClass();
        $object->id = 15;
        $jsonResponse = [$object];
        $this->customerApiMock->method('call')->willReturn(json_encode($jsonResponse));
        $this->assertEquals(true, $this->customerApiMock->create($this->customerMock));
    }

    /**
     * Test if json response is null ( method - update )
     */
    public function testUpdateIfJsonResponseIsNull()
    {
        $this->customerApiMock->method('buildMetadata')->with($this->customerMock)->willReturn(false);
        $this->customerApiMock->method('call')->willReturn(null);
        $this->assertEquals(false, $this->customerApiMock->update($this->paymentId, $this->customerMock));
    }

    /**
     * Test if response is not array ( method - update )
     */
    public function testUpdateIfResponseIsNotArray()
    {
        $this->customerApiMock->method('call')->willReturn('hello it is erik');
        $this->assertEquals(false, $this->customerApiMock->update($this->paymentId, $this->customerMock));
    }

    /**
     * Test if response is not an object ( method - update )
     */
    public function testUpdateIfResponseIsNotAnObject()
    {
        $arrayMock = [];
        $this->customerApiMock->method('call')->willReturn(json_encode($arrayMock));
        $this->assertEquals(false, $this->customerApiMock->update($this->paymentId, $this->customerMock));
    }

    /**
     * Test if response is object without id ( method - update )
     */
    public function testUpdateIfResponseIsObjectWithoutId()
    {
        $jsonResponse = [new \stdClass()];
        $this->customerApiMock->method('call')->willReturn(json_encode($jsonResponse));
        $this->assertEquals(false, $this->customerApiMock->update($this->paymentId, $this->customerMock));
    }

    /**
     * Success update method test ( method - update )
     */
    public function testSuccessCustomerUpdate()
    {
        $object = new \stdClass();
        $object->id = 15;
        $jsonResponse = [$object];
        $this->customerApiMock->method('call')->willReturn(json_encode($jsonResponse));
        $this->assertEquals(true, $this->customerApiMock->update($this->paymentId, $this->customerMock));
    }

    /**
     * Test if customer exist ( method - handle )
     */
    public function testHandleIfCustomerExist()
    {
        $this->assertEquals(false, $this->customerApiMock->handle($this->customerMock));
    }

    /**
     * Test if customer have payment id ( method - handle )
     */
    public function testHandleIfExistPaymentId()
    {
        $this->customerApiMock = $this->getMockBuilder(
            \Mondido\Mondido\Model\Api\Customer::class
        )->setConstructorArgs([
            'adapter' => $this->adapterMock,
            'config' => $this->configMock,
            'storeManager' => $this->storeManagerMock,
            'urlBuilder' => $this->urlBuilderMock,
            'addressFactory' => $this->addressFactoryMock,
            'logger' => $this->loggerMock
        ])
            ->setMethods(['getIdByRef', 'update'])
            ->getMock();
        $this->customerMock->setId(15);
        $this->customerApiMock->method('getIdByRef')->willReturn($this->paymentId);
        $this->customerApiMock->method('update')->willReturn(true);
        $this->assertEquals(true, $this->customerApiMock->handle($this->customerMock));
    }

    /**
     * Test if customer without payment id ( method - handle )
     */
    public function testHandleWithoutPaymentId()
    {
        $this->customerApiMock = $this->getMockBuilder(
            \Mondido\Mondido\Model\Api\Customer::class
        )->setConstructorArgs([
            'adapter' => $this->adapterMock,
            'config' => $this->configMock,
            'storeManager' => $this->storeManagerMock,
            'urlBuilder' => $this->urlBuilderMock,
            'addressFactory' => $this->addressFactoryMock,
            'logger' => $this->loggerMock
        ])
            ->setMethods(['getIdByRef', 'create'])
            ->getMock();
        $this->customerMock->setId(15);
        $this->customerApiMock->method('getIdByRef')->willReturn(null);
        $this->customerApiMock->method('create')->willReturn(true);
        $this->assertEquals(true, $this->customerApiMock->handle($this->customerMock));
    }

    /**
     * Test if function getIdByRef without jsonResponse ( method - getIdByRef )
     */
    public function testGetIdByRefIfNotExistJsonResponse()
    {
        $referenceId = 15;
        $this->customerApiMock->method('call')->willReturn(null);
        $this->assertEquals(false, $this->customerApiMock->getIdByRef($referenceId));
    }

    /**
     * Test if response is array or not ( method - getIdByRef )
     */
    public function testGetIdByRefIfResponseIsAnArray()
    {
        $referenceId = 20;
        $this->customerApiMock->method('call')->willReturn('hello it is not an array');
        $this->assertEquals(false, $this->customerApiMock->getIdByRef($referenceId));
    }

    /**
     * Test if response is not an object ( method - getIdByRef )
     */
    public function testGetIdByRefIfResponseIsNotAnObject()
    {
        $referenceId = 25;
        $array = [];
        $this->customerApiMock->method('call')->willReturn(json_encode($array));
        $this->assertEquals(false, $this->customerApiMock->getIdByRef($referenceId));
    }

    /**
     * Test if response is object without id ( method - getIdByRef )
     */
    public function testGetIdByRefIfResponseIsObjectWithoutId()
    {
        $referenceId = 15;
        $jsonResponse = [new \stdClass()];
        $this->customerApiMock->method('call')->willReturn(json_encode($jsonResponse));
        $this->assertEquals(false, $this->customerApiMock->getIdByRef($referenceId));
    }

    /**
     * Success getIdByRef method test ( method - getIdByRef )
     */
    public function testSuccessGetIdByRef()
    {
        $referenceId = 50;
        $object = new \stdClass();
        $object->id = 25;
        $jsonResponse = [$object];
        $this->customerApiMock->method('call')->willReturn(json_encode($jsonResponse));
        $this->assertEquals(true, $this->customerApiMock->getIdByRef($referenceId));
    }

    /**
     * Test if build meta data without default billing but with shipping ( method - buildMetadata )
     */
    public function testBuildMetaDataWithoutDefaultBillingWithShipping()
    {

        $this->customerMock = $this->objectManager->getObject(\Magento\Customer\Model\Data\Customer::class);

        $this->customerMock->setDefaultShipping($this->addressId);
        foreach ($this->expectedSuccessMetadataArray as $mainField => $value) {
            $this->customerMock->setData($mainField, $value);
        }

        $expectedShippingMockAndArray = $this->getAddressArrayAndMock('shipping');

        $this->addressFactoryMock->method('create')->willReturn($expectedShippingMockAndArray['mock']);

        $this->customerApiMock = $this->getMockBuilder(
            \Mondido\Mondido\Model\Api\Customer::class
        )->setConstructorArgs([
            'adapter' => $this->adapterMock,
            'config' => $this->configMock,
            'storeManager' => $this->storeManagerMock,
            'urlBuilder' => $this->urlBuilderMock,
            'addressFactory' => $this->addressFactoryMock,
            'logger' => $this->loggerMock
        ])
            ->setMethodsExcept(['buildMetadata'])
            ->getMock();

        $expected = array_merge($this->expectedSuccessMetadataArray, $expectedShippingMockAndArray['expected_array']);
        $this->assertEquals($expected, $this->customerApiMock->buildMetadata($this->customerMock));
    }

    /**
     * Test if meta data without shipping but with default billing ( method - buildMetadata )
     */
    public function testBuildMetaDataWithoutShippingWithDefaultBilling()
    {
        $this->customerMock = $this->objectManager->getObject(\Magento\Customer\Model\Data\Customer::class);

        $this->customerMock->setDefaultBilling($this->addressId);
        foreach ($this->expectedSuccessMetadataArray as $mainField => $value) {
            $this->customerMock->setData($mainField, $value);
        }

        $expectedBillingMockAndArray = $this->getAddressArrayAndMock('billing');

        $this->addressFactoryMock->method('create')->willReturn($expectedBillingMockAndArray['mock']);

        $this->customerApiMock = $this->getMockBuilder(
            \Mondido\Mondido\Model\Api\Customer::class
        )->setConstructorArgs([
            'adapter' => $this->adapterMock,
            'config' => $this->configMock,
            'storeManager' => $this->storeManagerMock,
            'urlBuilder' => $this->urlBuilderMock,
            'addressFactory' => $this->addressFactoryMock,
            'logger' => $this->loggerMock
        ])
            ->setMethodsExcept(['buildMetadata'])
            ->getMock();

        $expected = array_merge($this->expectedSuccessMetadataArray, $expectedBillingMockAndArray['expected_array']);
        $this->assertEquals($expected, $this->customerApiMock->buildMetadata($this->customerMock));
    }

    /**
     * Success test with billing and shipping ( method - buildMetadata )
     */
    public function testBuildMetaDataWithBillingAndShipping()
    {
        $this->customerMock = $this->objectManager->getObject(\Magento\Customer\Model\Data\Customer::class);

        $this->customerMock->setDefaultBilling($this->addressId);
        $this->customerMock->setDefaultShipping($this->addressId);

        foreach ($this->expectedSuccessMetadataArray as $mainField => $value) {
            $this->customerMock->setData($mainField, $value);
        }

        $expectedBillingMockAndArray = $this->getAddressArrayAndMock('billing');
        $expectedShippingMockAndArray = $this->getAddressArrayAndMock('shipping');

        $this->addressFactoryMock->expects($this->at(0))
            ->method('create')
            ->willReturn($expectedBillingMockAndArray['mock']);

        $this->addressFactoryMock->expects($this->at(1))
            ->method('create')
            ->willReturn($expectedShippingMockAndArray['mock']);

        $this->customerApiMock = $this->getMockBuilder(
            \Mondido\Mondido\Model\Api\Customer::class
        )->setConstructorArgs([
            'adapter' => $this->adapterMock,
            'config' => $this->configMock,
            'storeManager' => $this->storeManagerMock,
            'urlBuilder' => $this->urlBuilderMock,
            'addressFactory' => $this->addressFactoryMock,
            'logger' => $this->loggerMock
        ])
            ->setMethodsExcept(['buildMetadata'])
            ->getMock();

        $expected = array_merge($this->expectedSuccessMetadataArray,$expectedBillingMockAndArray['expected_array'],$expectedShippingMockAndArray['expected_array']);
        $this->assertEquals($expected, $this->customerApiMock->buildMetadata($this->customerMock));
    }

    /**
     * Helper for buildMetadata function
     *
     * @param $address
     * @return array
     */
    public function getAddressArrayAndMock($address)
    {
        $addressExpectedArray = [];

        /** @var \Magento\Customer\Model\Address | \PHPUnit_Framework_MockObject_MockObject $addressMock */
        $addressMock = $this->getMockBuilder(\Magento\Customer\Model\Address::class)
            ->disableOriginalConstructor()
            ->getMock();

        foreach ($this->addressFields as $index => $field) {
            $addressMock->expects($this->at($index + 2))->method('getData')->with($field)->willReturn('test ' . $field);
            $addressExpectedArray[$address.'_' . $field] = 'test ' . $field;
        }

        $addressMock->method('getId')->willReturn($this->addressId);
        $addressMock->expects($this->at(0))->method('load')->with($this->addressId)->willReturnSelf();

        return [
            "expected_array" => $addressExpectedArray,
            "mock" => $addressMock
        ];
    }

    /**
     * Tear down
     *
     * @return void
     */
    public function tearDown()
    {
        $this->objectManager = null;
        $this->adapterMock = null;
        $this->configMock = null;
        $this->storeManagerMock = null;
        $this->urlBuilderMock = null;
        $this->addressFactoryMock = null;
        $this->loggerMock = null;
        $this->customerMock = null;
        $this->customerApiMock = null;
    }
}
