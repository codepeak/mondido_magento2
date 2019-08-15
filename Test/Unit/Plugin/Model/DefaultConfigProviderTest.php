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

namespace Mondido\Mondido\Test\Unit\Plugin\Model;

use Mondido\Mondido\Test\Unit\PaymentPspObjectManager as ObjectManager;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * DefaultConfigProviderTest
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class DefaultConfigProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /** @var \Magento\Framework\UrlInterface | MockObject */
    protected $urlBuilderMock;

    /** @var  \Mondido\Mondido\Model\Config | MockObject */
    protected $configMock;

    /** @var \Magento\Checkout\Model\DefaultConfigProvider | MockObject */
    protected $originalDefaultConfigProviderMock;

    /** @var \Mondido\Mondido\Plugin\Model\DefaultConfigProvider | MockObject */
    protected $defaultConfigProviderMock;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->urlBuilderMock = $this->getMockBuilder(\Magento\Framework\UrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->configMock = $this->getMockBuilder(\Mondido\Mondido\Model\Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->originalDefaultConfigProviderMock = $this->getMockBuilder(\Magento\Checkout\Model\DefaultConfigProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->defaultConfigProviderMock = $this->getMockBuilder(
            \Mondido\Mondido\Plugin\Model\DefaultConfigProvider::class
        )->setConstructorArgs([
            'urlBuilder' => $this->urlBuilderMock,
            'config' => $this->configMock,

        ])
            ->setMethodsExcept(
                [
                    'afterGetCheckoutUrl',
                ]
            )
            ->getMock();
    }

    /**
     * Test if config is not active ( method - afterGetCheckoutUrl )
     */
    public function testAfterGetCheckoutUrlIfConfigIsNotActive()
    {
        $this->configMock->method('isActive')->willReturn(false);
        $this->assertEquals('string',$this->defaultConfigProviderMock->afterGetCheckoutUrl($this->originalDefaultConfigProviderMock,'string'));
    }

    /**
     * Success test afterGetCheckoutUrl ( method - afterGetCheckoutUrl )
     */
    public function testAfterGetCheckoutUrl()
    {
        $this->configMock->method('isActive')->willReturn(true);
        $this->urlBuilderMock->method('getUrl')->with('mondido/checkout')->willReturn('test');
        $this->assertEquals('test',$this->defaultConfigProviderMock->afterGetCheckoutUrl($this->originalDefaultConfigProviderMock,'string'));
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->objectManager = null;
        $this->urlBuilderMock = null;
        $this->configMock = null;
        $this->originalDefaultConfigProviderMock = null;
        $this->defaultConfigProviderMock = null;
    }
}
