<?php
namespace AutoAvatar;

use AutoAvatar\Exception\UnsupportedFormatException;

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
     * Width and Height in Pixels.
     * Format either png, jpeg, jpg or gif.
     * @param int $width
     * @param int $height
     * @param string $format
     * @throws UnsupportedFormatException
     */
    public function __construct(int $width, int $height, string $format)
    {
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setFormat($format);
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