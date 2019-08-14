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
 * Config model test
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class ConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface | MockObject*/
    protected $scopeConfigMock;

    /** @var \Magento\Framework\App\ProductMetadataInterface | MockObject */
    protected $productMetadataMock;

    /** @var \Magento\Framework\Module\ModuleListInterface | MockObject */
    protected $moduleListMock;

    /** @var  \Mondido\Mondido\Model\Config | MockObject */
    protected $configMock;

    /**
     * Set up
     *
     * @return void
     */
    public function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->scopeConfigMock = $this->getMockBuilder(
            \Magento\Framework\App\Config\ScopeConfigInterface::class
        )
            ->getMock();
        $this->productMetadataMock = $this->getMockBuilder(
            \Magento\Framework\App\ProductMetadataInterface::class
        )
            ->getMock();
        $this->moduleListMock = $this->getMockBuilder(
            \Magento\Framework\Module\ModuleListInterface::class
        )
            ->getMock();


        $this->configMock = $this->objectManager->getObject(
            \Mondido\Mondido\Model\Config::class,
            ['scopeConfig' => $this->scopeConfigMock]
        );
    }

    /**
     * Test getMerchantId()
     *
     * @return void
     */
    public function testGetMerchantId()
    {
        $value = 872;

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($value);

        $this->assertEquals($value, $this->configMock->getMerchantId());
    }

    /**
     * Test getPassword()
     *
     * @return void
     */
    public function testGetPassword()
    {
        $value = 'topsecret';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($value);

        $this->assertEquals($value, $this->configMock->getPassword());
    }

    /**
     * Test getSecret()
     *
     * @return void
     */
    public function testGetSecret()
    {
        $value = 'topsecret';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($value);

        $this->assertEquals($value, $this->configMock->getSecret());
    }

    /**
     * Test getPaymentAction()
     *
     * @return void
     */
    public function testGetPaymentAction()
    {
        $value = 'authorize';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($value);

        $this->assertEquals($value, $this->configMock->getPaymentAction());
    }

    /**
     * Test isActive()
     *
     * @return void
     */
    public function testIsActive()
    {
        $value = true;

        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->willReturn($value);

        $this->assertEquals($value, $this->configMock->isActive());
    }

    /**
     * Test isTest()
     *
     * @return void
     */
    public function testIsTest()
    {
        $value = true;

        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->willReturn($value);

        $this->assertEquals($value, $this->configMock->isTest());
    }

    /**
     * Test getAllowedCountries()
     */
    public function testGetAllowedCountries()
    {
        $value = 'countries';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($value);

        $this->assertEquals($value,$this->configMock->getAllowedCountries());
    }

    /**
     * Test getDefaultCountry()
     */
    public function testGetDefaultCountry()
    {
        $value = 'default';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($value);

        $this->assertEquals($value,$this->configMock->getDefaultCountry());
    }

    /**
     * Test getMagentoVersion()
     */
    public function testGetMagentoVersion()
    {
        $magentoVersion = $this->productMetadataMock->getVersion();
        $this->assertEquals($magentoVersion,$this->configMock->getMagentoVersion());
    }

    /**
     * Test getMagentoEdition()
     */
    public function testGetMagentoEdition()
    {
        $magentoEdition = $this->productMetadataMock->getEdition();
        $this->assertEquals($magentoEdition,$this->configMock->getMagentoEdition());
    }

    /**
     * Test getModuleInformation()
     */
    public function testGetModuleInformation()
    {
        $moduleList = $this->moduleListMock->getOne('Mondido_Mondido');
        $this->assertEquals($moduleList,$this->configMock->getModuleInformation());
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->objectManager = null;
        $this->configMock = null;
        $this->moduleListMock = null;
        $this->productMetadataMock = null;
        $this->scopeConfigMock = null;
    }
}
