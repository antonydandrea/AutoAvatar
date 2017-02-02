<?php
namespace AutoAvatar;

use AutoAvatar\Helper\ColorFunctions;
use AutoAvatar\Exception\UnsupportedFormatException;
use AutoAvatar\Exception\ColorException;

/** 
 * @package AutoAvatar
 */
class Image
{
    /** 
     *
     * @var int
     */
    private $width;
    
    /** 
     *
     * @var int
     */
    private $height;
    
    /** 
     *
     * @var string
     */
    private $format;
    
    /** 
     *
     * @var array
     */
    private $allowed_formats = ['png', 'jpeg', 'jpg', 'gif'];
    
    /** 
     * An array of potential colors that can be chosen at random.
     * If you want just one color, then provide an array of just 1 item.
     * @var array
     */
    private $color_array;
    
    /** 
     * Width and Height in Pixels.
     * Format either png, jpeg, jpg or gif.
     * Leave $color_array empty if you just want to generate a random color.
     * @param int $width
     * @param int $height
     * @param string $format
     * @param array $color_array
     * @throws UnsupportedFormatException
     */
    public function __construct(int $width, int $height, string $format, array $color_array = [])
    {
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setFormat($format);
        $this->setColors($color_array);
    }
    
    /** 
     * 
     * @param int $width
     */
    public function setWidth(int $width)
    {
        $this->width = $width;
    }
    
    /** 
     * 
     * @param int $height
     */
    public function setHeight(int $height)
    {
        $this->height = $height;
    }
    
    /** 
     * 
     * @param string $format
     * @throws UnsupportedFormatException
     */
    public function setFormat(string $format)
    {
        if (in_array($format, $this->allowed_formats)) {
            $this->format = $format;
        } else {
            throw new UnsupportedFormatException($format, $this->allowed_formats);
        }
    }
    
    /**
     * 
     * @param array $colors
     */
    public function setColors(array $colors)
    {
        $this->color_array = $colors;
    }
    
    /** 
     * 
     * @return array
     */
    public function getColors() : array
    {
        return $this->color_array;
    }
    
    /** 
     * 
     * @param int $index
     * @return string
     * @throws ColorException
     */
    public function getColor(int $index) : string
    {
        $color = '';
        if (isset($this->color_array[$index])) {
           $color = $this->color_array[$index];
        } else {
            throw new ColorException($index.' does not exist on image color list.');
        }
        return $color;
    }
    
    /** 
     * Gives back a random color from its internal array of
     * possible colors.
     * Or if there are no colors set, a random rgb from color helper.
     * @return array
     */
    public function getRandomColor() : array
    {
        $color_helper = new ColorFunctions;
        $color = '';
        if (empty($this->color_array)) {
            $color = $color_helper->generateRandomRGB();
        } else {
            $color = $color_helper->hexCodeToRgb($this->color_array[rand(0, (count($this->color_array) - 1))]);
        }
        return $color;
    }
    
    /** 
     * 
     * @return int
     */
    public function getWidth() : int
    {
        return $this->width;
    }
    
    /** 
     * 
     * @return int
     */
    public function getHeight() : int
    {
        return $this->height;
    }
    
    /** 
     * 
     * @return string
     */
    public function getFormat() : string
    {
        return $this->format;
    }
}