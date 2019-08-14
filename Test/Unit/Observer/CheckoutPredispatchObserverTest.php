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
 * CheckoutPredispatchObserverTest
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class CheckoutPredispatchObserverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /** @var \Mondido\Mondido\Model\Api\Transaction | MockObject */
    protected $transactionMock;

    /** @var  \Magento\Framework\Message\ManagerInterface | MockObject */
    protected $messageManagerMock;

    /** @var \Magento\Customer\Model\AddressFactory | MockObject */
    protected $addressFactoryMock;

    /** @var \Magento\Customer\Model\Address | MockObject */
    protected $addressMock;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface | MockObject */
    protected $scopeConfigMock;

    /** @var \Magento\Framework\UrlInterface */
    protected $urlInterfaceMock;

    /** @var \Magento\Customer\Model\Data\Customer | MockObject */
    protected $customerMock;

    /** @var \Mondido\Mondido\Observer\CheckoutPredispatchObserver | MockObject */
    protected $checkoutPredispatchObserverMock;

    /** @var \Magento\Framework\Event\Observer | MockObject */
    protected $observerMock;

    /** @var \Magento\Framework\Event | MockObject */
    protected $eventMock;

    /** @var \Mondido\Mondido\Controller\Checkout\Index | MockObject */
    protected $controllerMock;

    /** @var \Magento\Checkout\Model\Type\Onepage| MockObject */
    protected $onepageMock;

    /** @var \Magento\Quote\Model\Quote\Interceptor | MockObject */
    protected $quoteMock;

    /** @var \Magento\Quote\Model\Quote\Address | MockObject */
    protected $shippingAddressMock;

    /** @var \Magento\Customer\Api\Data\AddressInterface | MockObject */
    protected $addressInterfaceMock;

    /** @var string */
    protected $allowedCountries;

    /** @var string */
    protected $defaultCountry;

    /** @var int */
    protected $idMock;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->allowedCountries = "AF,AL,DZ,AS,AD,AO,AI,AQ,AG,AR,AM,AW,AU,AT,AX,AZ,BS,BH,BD,BB,BY,BE,BZ,BJ,BM,BL,BT,BO,BA,
        BW,BV,BR,IO,VG,BN,BG,BF,BI,KH,CM,CA,CD,CV,KY,CF,TD,CL,CN,CX,CC,CO,KM,CG,CK,CR,HR,CU,CY,CZ,DK,DJ,DM,DO,EC,EG,
        SV,GQ,ER,EE,ET,FK,FO,FJ,FI,FR,GF,PF,TF,GA,GM,GE,DE,GG,GH,GI,GR,GL,GD,GP,GU,GT,GN,GW,GY,HT,HM,HN,HK,HU,IS,IM,
        IN,ID,IR,IQ,IE,IL,IT,CI,JE,JM,JP,JO,KZ,KE,KI,KW,KG,LA,LV,LB,LS,LR,LY,LI,LT,LU,ME,MF,MO,MK,MG,MW,MY,MV,ML,MT,
        MH,MQ,MR,MU,YT,FX,MX,FM,MD,MC,MN,MS,MA,MZ,MM,NA,NR,NP,NL,AN,NC,NZ,NI,NE,NG,NU,NF,KP,MP,NO,OM,PK,PW,PA,PG,PY,
        PE,PH,PN,PL,PS,PT,PR,QA,RE,RO,RS,RU,RW,SH,KN,LC,PM,VC,WS,SM,ST,SA,SN,SC,SL,SG,SK,SI,SB,SO,ZA,GS,KR,ES,LK,SD,
        SR,SJ,SZ,SE,CH,SY,TL,TW,TJ,TZ,TH,TG,TK,TO,TT,TN,TR,TM,TC,TV,VI,UG,UA,AE,GB,US,UM,UY,UZ,VU,VA,VE,VN,WF,EH,YE
        ,ZM,ZW";

        $this->defaultCountry = "US";

        $this->idMock = 25;

        $this->urlInterfaceMock = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->transactionMock = $this->getMockBuilder(\Mondido\Mondido\Model\Api\Transaction::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->messageManagerMock = $this->getMockBuilder(\Magento\Framework\Message\ManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->addressMock = $this->getMockBuilder(\Magento\Customer\Model\Address::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->addressFactoryMock = $this->getMockBuilder(\Magento\Customer\Model\AddressFactory::class)
            ->setMethods(['load', 'create', 'getDataModel'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->scopeConfigMock = $this->getMockBuilder(\Magento\Framework\App\Config\ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->observerMock = $this->getMockBuilder(\Magento\Framework\Event\Observer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->eventMock = $this->getMockBuilder(\Magento\Framework\Event::class)
            ->setMethods(['getControllerAction'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->controllerMock = $this->getMockBuilder(\Mondido\Mondido\Controller\Checkout\Index::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->onepageMock = $this->getMockBuilder(\Magento\Checkout\Model\Type\Onepage::class)
            ->setMethods(['getQuote'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->addressInterfaceMock = $this->getMockBuilder(\Magento\Customer\Api\Data\AddressInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteMock = $this->getMockBuilder(\Magento\Quote\Model\Quote\Interceptor::class)
            ->setMethods(
                [
                    'getCustomer',
                    'getShippingAddress',
                    'collectTotals',
                    'save',
                    'getId',
                    'getMondidoTransaction',
                    'setMondidoTransaction',
                    'getBillingAddress',
                    'importCustomerAddressData'
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerMock = $this->getMockBuilder(\Magento\Customer\Model\Data\Customer::class)
            ->setMethods(['getId', 'getDefaultShipping'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->shippingAddressMock = $this->getMockBuilder(\Magento\Quote\Model\Quote\Address::class)
            ->setMethods(
                [
                    'setCollectShippingRates',
                    'setCountryId',
                    'getCountryId',
                    'save',
                    'collectShippingRates',
                    'importCustomerAddressData',
                    'setShippingMethod',
                    'getShippingMethod'
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();


        $this->checkoutPredispatchObserverMock = $this->getMockBuilder(
            \Mondido\Mondido\Observer\CheckoutPredispatchObserver::class
        )->setConstructorArgs([
            'transaction' => $this->transactionMock,
            'messageManager' => $this->messageManagerMock,
            'addressFactory' => $this->addressFactoryMock,
            'scopeConfig' => $this->scopeConfigMock,
            'urlInterface' => $this->urlInterfaceMock,
        ])
            ->setMethodsExcept(['execute'])
            ->getMock();
    }

    /**
     * Test execute without customer id, quote id, forceDefaultCountry == true and
     * shipping address  don't have shipping method
     */
    public function testExecuteOne()
    {
        $this->getQuoteAndCountries();
        $this->quoteMock->method('getShippingAddress')->with('shipping')->willReturn($this->shippingAddressMock);
        $this->shippingAddressMock->method('getCountryId')->willReturn(null);
        $this->shippingAddressMock->method('setCountryId')->with($this->defaultCountry)->willReturnSelf();
        $this->shippingAddressMock->method('save')->willReturnSelf();

        $this->shippingAddressMock->method('setCollectShippingRates')->with(true)->willReturnSelf();
        $this->shippingAddressMock->method('collectShippingRates')->willReturnSelf();
        $this->shippingAddressMock->method('setShippingMethod')->with('flatrate_flatrate')->willReturnSelf();
        $this->quoteMock->method('collectTotals')->willReturnSelf();
        $this->quoteMock->method('save')->willReturnSelf();

        $this->checkoutPredispatchObserverMock->execute($this->observerMock);
        $this->assertEquals(null, $this->quoteMock->getMondidoTransaction());
    }

    /**
     * Test execute without  quote id, forceDefaultCountry == true and
     * shipping address  don't have shipping method
     */
    public function testExecuteTwo()
    {
        $this->getQuoteAndCountries();
        $this->customerMock->method('getId')->willReturn($this->idMock);
        $this->customerMock->method('getDefaultShipping')->willReturn($this->idMock);
        $this->addressFactoryMock->method('create')->willReturn($this->addressMock);
        $this->addressMock->method('load')->with($this->idMock)->willReturnSelf();
        $this->addressMock->method('getDataModel')->willReturn($this->addressInterfaceMock);
        $this->quoteMock->method('getBillingAddress')->willReturn($this->shippingAddressMock);
        $this->quoteMock->method('getShippingAddress')->willReturn($this->shippingAddressMock);

        $this->shippingAddressMock->method('getCountryId')->willReturn($this->idMock);
        $this->shippingAddressMock->method('setCountryId')->with($this->defaultCountry)->willReturnSelf();
        $this->shippingAddressMock->method('save')->willReturnSelf();


        $this->shippingAddressMock->method('setCollectShippingRates')->with(true)->willReturnSelf();
        $this->shippingAddressMock->method('collectShippingRates')->willReturnSelf();
        $this->shippingAddressMock->method('setShippingMethod')->with('flatrate_flatrate')->willReturnSelf();
        $this->quoteMock->method('collectTotals')->willReturnSelf();
        $this->quoteMock->method('save')->willReturnSelf();

        $this->checkoutPredispatchObserverMock->execute($this->observerMock);
        $this->assertEquals(null,$this->quoteMock->getMondidoTransaction());
    }

    /**
     * Test execute without  quote id, forceDefaultCountry == true
     */
    public function testExecuteThree()
    {
        $this->getQuoteAndCountries();
        $this->customerMock->method('getId')->willReturn($this->idMock);
        $this->customerMock->method('getDefaultShipping')->willReturn($this->idMock);
        $this->addressFactoryMock->method('create')->willReturn($this->addressMock);
        $this->addressMock->method('load')->with($this->idMock)->willReturnSelf();
        $this->addressMock->method('getDataModel')->willReturn($this->addressInterfaceMock);
        $this->quoteMock->method('getBillingAddress')->willReturn($this->shippingAddressMock);
        $this->quoteMock->method('getShippingAddress')->willReturn($this->shippingAddressMock);

        $this->shippingAddressMock->method('getCountryId')->willReturn($this->idMock);
        $this->shippingAddressMock->method('setCountryId')->with($this->defaultCountry)->willReturnSelf();
        $this->shippingAddressMock->method('save')->willReturnSelf();

        $this->shippingAddressMock->method('getShippingMethod')->willReturn('somemethod');

        $this->shippingAddressMock->method('setCollectShippingRates')->with(true)->willReturnSelf();
        $this->shippingAddressMock->method('collectShippingRates')->willReturnSelf();
        $this->shippingAddressMock->method('setShippingMethod')->with('flatrate_flatrate')->willReturnSelf();
        $this->quoteMock->method('collectTotals')->willReturnSelf();
        $this->quoteMock->method('save')->willReturnSelf();

        $this->checkoutPredispatchObserverMock->execute($this->observerMock);
        $this->assertEquals(null,$this->quoteMock->getMondidoTransaction());
    }

    /**
     * Test execute without quote id , forceDefaultCountry == false
     */
    public function testExecuteFour()
    {
        $this->getQuoteAndCountries();
        $this->customerMock->method('getId')->willReturn($this->idMock);
        $this->customerMock->method('getDefaultShipping')->willReturn($this->idMock);
        $this->addressFactoryMock->method('create')->willReturn($this->addressMock);
        $this->addressMock->method('load')->with($this->idMock)->willReturnSelf();
        $this->addressMock->method('getDataModel')->willReturn($this->addressInterfaceMock);
        $this->quoteMock->method('getBillingAddress')->willReturn($this->shippingAddressMock);
        $this->quoteMock->method('getShippingAddress')->willReturn($this->shippingAddressMock);

        $this->addressInterfaceMock->method('getCountryId')->willReturn('ZW');

        $this->shippingAddressMock->method('getShippingMethod')->willReturn('somemethod');

        $this->shippingAddressMock->method('setCollectShippingRates')->with(true)->willReturnSelf();
        $this->shippingAddressMock->method('collectShippingRates')->willReturnSelf();
        $this->shippingAddressMock->method('setShippingMethod')->with('flatrate_flatrate')->willReturnSelf();
        $this->quoteMock->method('collectTotals')->willReturnSelf();
        $this->quoteMock->method('save')->willReturnSelf();

        $this->checkoutPredispatchObserverMock->execute($this->observerMock);
        $this->assertEquals(null,$this->quoteMock->getMondidoTransaction());
    }

    /**
     * Test execute with quote id,response, id property of response, log_events property of response but
     * without quote transaction
     */
    public function testExecuteFive()
    {
        $jsonResponse = new \stdClass();
        $jsonResponse->id = 25;
        $jsonResponse->log_events = 'Please login to your Mondido account to see the log events.';

        $this->getQuoteAndCountries();
        $this->customerMock->method('getId')->willReturn($this->idMock);
        $this->customerMock->method('getDefaultShipping')->willReturn($this->idMock);
        $this->addressFactoryMock->method('create')->willReturn($this->addressMock);
        $this->addressMock->method('load')->with($this->idMock)->willReturnSelf();
        $this->addressMock->method('getDataModel')->willReturn($this->addressInterfaceMock);
        $this->quoteMock->method('getBillingAddress')->willReturn($this->shippingAddressMock);
        $this->quoteMock->method('getShippingAddress')->willReturn($this->shippingAddressMock);

        $this->shippingAddressMock->method('getCountryId')->willReturn($this->idMock);
        $this->shippingAddressMock->method('setCountryId')->with($this->defaultCountry)->willReturnSelf();
        $this->shippingAddressMock->method('save')->willReturnSelf();

        $this->shippingAddressMock->method('getShippingMethod')->willReturn('somemethod');

        $this->shippingAddressMock->method('setCollectShippingRates')->with(true)->willReturnSelf();
        $this->shippingAddressMock->method('collectShippingRates')->willReturnSelf();
        $this->shippingAddressMock->method('setShippingMethod')->with('flatrate_flatrate')->willReturnSelf();
        $this->quoteMock->method('collectTotals')->willReturnSelf();
        $this->quoteMock->method('save')->willReturnSelf();

        $this->quoteMock->method('getId')->willReturn($this->idMock);

        $this->transactionMock->method('create')->with($this->quoteMock)->willReturn(json_encode($jsonResponse));

        $this->quoteMock->method('save')->willReturnSelf();


        $this->checkoutPredispatchObserverMock->execute($this->observerMock);

        $this->assertEquals(null,$this->quoteMock->getMondidoTransaction());
    }




    protected function getQuoteAndCountries()
    {
        $this->observerMock->method('getEvent')->willReturn($this->eventMock);
        $this->eventMock->method('getControllerAction')->willReturn($this->controllerMock);
        $this->controllerMock->method('getOnepage')->willReturn($this->onepageMock);
        $this->onepageMock->method('getQuote')->willReturn($this->quoteMock);
        $this->quoteMock->method('getCustomer')->willReturn($this->customerMock);
        $this->scopeConfigMock->expects($this->at(0))
            ->method('getValue')
            ->with('general/country/allow', \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE)
            ->willReturn($this->allowedCountries);
        $this->scopeConfigMock->expects($this->at(1))
            ->method('getValue')
            ->with('general/country/default', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
            ->willReturn($this->defaultCountry);
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->objectManager = null;
        $this->transactionMock = null;
        $this->messageManagerMock = null;
        $this->addressFactoryMock = null;
        $this->addressMock = null;
        $this->scopeConfigMock = null;
        $this->urlInterfaceMock = null;
        $this->customerMock = null;
        $this->checkoutPredispatchObserverMock = null;
        $this->observerMock = null;
        $this->eventMock = null;
        $this->controllerMock = null;
        $this->onepageMock = null;
        $this->quoteMock = null;
        $this->shippingAddressMock = null;
        $this->addressInterfaceMock = null;
        $this->allowedCountries = null;
        $this->defaultCountry = null;
        $this->idMock = null;
    }
}
