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

namespace Mondido\Mondido\Test\Unit\Plugin\Block\Cart;

use Mondido\Mondido\Test\Unit\PaymentPspObjectManager as ObjectManager;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * SidebarTest
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class SidebarTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /** @var \Magento\Framework\UrlInterface | MockObject */
    protected $urlBuilderMock;

    /** @var  \Mondido\Mondido\Model\Config | MockObject */
    protected $configMock;

    /** @var \Magento\Checkout\Block\Cart\Sidebar | MockObject */
    protected $originalSidebarMock;

    /** @var \Mondido\Mondido\Plugin\Block\Cart\Sidebar | MockObject */
    protected $sidebarMock;

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
        $this->originalSidebarMock = $this->getMockBuilder(\Magento\Checkout\Block\Cart\Sidebar::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sidebarMock = $this->getMockBuilder(
            \Mondido\Mondido\Plugin\Block\Cart\Sidebar::class
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
        $this->assertEquals('string',$this->sidebarMock->afterGetCheckoutUrl($this->originalSidebarMock,'string'));
    }

    /**
     * Success test afterGetCheckoutUrl ( method - afterGetCheckoutUrl )
     */
    public function testAfterGetCheckoutUrl()
    {
        $this->configMock->method('isActive')->willReturn(true);
        $this->urlBuilderMock->method('getUrl')->with('mondido/checkout')->willReturn('test');
        $this->assertEquals('test',$this->sidebarMock->afterGetCheckoutUrl($this->originalSidebarMock,'string'));
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
        $this->originalSidebarMock = null;
        $this->sidebarMock = null;
    }
}
