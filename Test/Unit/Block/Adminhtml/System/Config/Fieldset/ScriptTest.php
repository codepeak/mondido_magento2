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

namespace Mondido\Mondido\Test\Unit\Block\Adminhtml\System\Config\Fieldset;

use Mondido\Mondido\Test\Unit\PaymentPspObjectManager as ObjectManager;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * ScriptTest
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class ScriptTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /** @var \Mondido\Mondido\Block\Adminhtml\System\Config\Fieldset\Script | MockObject */
    protected $scriptMock;

    /** @var \Magento\Framework\Data\Form\Element\AbstractElement | MockObject */
    protected $elementMock;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->elementMock = $this->getMockBuilder(\Magento\Framework\Data\Form\Element\AbstractElement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->scriptMock = $this->getMockBuilder(
            \Mondido\Mondido\Block\Adminhtml\System\Config\Fieldset\Script::class
        )
            ->setMethodsExcept(['render'])
            ->setMethods(['ToHtml'])
            ->disableOriginalConstructor()
            ->getMock();

    }

    /**
     * Test success render ( method - render )
     */
    public function testRender()
    {
        $this->assertEquals($this->scriptMock->toHtml(),$this->scriptMock->render($this->elementMock));
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->objectManager = null;
        $this->scriptMock = null;
        $this->elementMock = null;
    }
}
