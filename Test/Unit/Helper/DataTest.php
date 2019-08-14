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
class DataTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /** @var \Mondido\Mondido\Helper\Data | MockObject */
    protected $dataMock;

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);

        $this->dataMock = $this->getMockBuilder(\Mondido\Mondido\Helper\Data::class)
            ->setMethodsExcept(['formatNumber'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Success test for formatNumber method ( method - formatNumber )
     */
    public function testFormatNumber()
    {
        $this->assertEquals(
            number_format(
                5000, 2, '.', ''
            ),
            $this->dataMock->formatNumber(
                5000, 2, '.', ''
            )
        );
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->objectManager = null;
    }
}
