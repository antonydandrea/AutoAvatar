<?php
use PHPUnit\Framework\TestCase;
use AutoAvatar\Helper\ColorFunctions;
use AutoAvatar\Exception\ColorException;

class ColorFunctionsTest extends TestCase
{
    /**
     * @return array
     */
    public function validRgbProvider()
    {
        return [
            [
                [255, 255, 255],
                '#ffffff'
            ],
            [
                [0, 0, 0],
                '#000000'
            ],
            [
                [0, 0, 0, 0],
                ''
            ],
            [
                [0, 0, 256],
                ''
            ],
            [
                [-1, 0, 256],
                ''
            ],
        ];
    }
    
    /**
     * @var array $rgb
     * @var string $expectedHex 
     * @dataProvider validRgbProvider
     */
    public function testRgbToHexCode($rgb, $expectedHex)
    {
        $colorHelper = new ColorFunctions();
        try {
            $hex = $colorHelper->rgbToHexCode($rgb);
            $this->assertEquals($expectedHex, $hex);
        } catch (\Exception $e) {
            $this->assertInstanceOf(ColorException::class, $e);
        }
    }
}