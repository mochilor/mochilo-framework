<?php

namespace Mochilo\Tests;

use Mochilo\Config;
use Mochilo\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $configMock;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper = new Helper($this->configMock);

        parent::setUp();
    }

    public function testImg()
    {
        $baseUrl = 'http://example.com';
        $imgUrl = '/img';

        $this->configMock
            ->expects($this->at(0))
            ->method('get')
            ->with('base_url')
            ->willReturn($baseUrl);

        $this->configMock
            ->expects($this->at(1))
            ->method('get')
            ->with('img_url')
            ->willReturn($imgUrl);

        $src = 'path/to/image.jpg';
        $height = 100;
        $width = 250;
        $alt = 'Alt text';
        $class = 'css-class';
        $id = 'css-id';

        $imgFormat = '<img src="%s" height="%d" width="%d" alt="%s" class="%s" id="%s" />';
        $result = sprintf(
            $imgFormat,
            sprintf('%s%s/%s', $baseUrl, $imgUrl, $src),
            $height,
            $width,
            $alt,
            $class,
            $id
        );

        $this->assertEquals(
            $result,
            $this->helper->img($src, $height, $width, $alt, $class, $id)
        );
    }
}
