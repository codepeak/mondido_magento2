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

namespace Mondido\Mondido\Test\Unit\Helper;

use Mondido\Mondido\Test\Unit\PaymentPspObjectManager as ObjectManager;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Data helper test
 *
 * @category Mondido
 * @package  Mondido_Mondido
 * @author   Andreas Karlsson <andreas@kodbruket.se>
 * @license  MIT License https://opensource.org/licenses/MIT
 * @link     https://www.mondido.com
 */
class IsoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /** @var \Mondido\Mondido\Helper\Iso | MockObject */
    protected $isoMock;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        $this->isoMock = $this->getMockBuilder(\Mondido\Mondido\Helper\Iso::class)
            ->setMethodsExcept(
                [
                    'convertFromAlpha2',
                    'getTranslateArray'
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Assure we get an array from "getTranslateArray" method
     *
     * @return void
     */
    public function testTranslateArray()
    {
        $resultDataSet = $this->isoMock->getTranslateArray();
        $this->assertTrue(is_array($resultDataSet));
    }

    /**
     * Test the raw data used in translations from alpha 2 to alpha 3
     *
     * @return void
     */
    public function testTranslateArrayDataContent()
    {
        foreach ($this->isoMock->getTranslateArray() as $key => $value) {
            $this->assertTrue(strlen($key) === 2);
            $this->assertTrue(strlen($value) === 3);
        }
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->objectManager = null;
        $this->isoMock = null;
    }
}
