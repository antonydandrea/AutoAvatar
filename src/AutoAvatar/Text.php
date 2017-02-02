<?php
namespace AutoAvatar;

use AutoAvatar\Helper\ColorFunctions;
use AutoAvatar\Exception\ColorException;

/** 
 * @package AutoAvatar
 */
class Text
{
    /** 
     *
     * @var string
     */
    private $content;
    
    /** 
     *
     * @var int
     */
    private $size;
    
    /** 
     *
     * @var string
     */
    private $font;
    
    /** 
     * An array of potential colors that can be chosen at random.
     * If you want just one color, then provide an array of just 1 item.
     * @var array
     */
    private $color_array;
    
    /** 
     * Leave $color_array empty if you just want to generate a random color.
     * @param string $content
     * @param int $textSize
     * @param string $textFont
     * @param array $color_array
     */
    public function __construct(string $content, int $textSize, string $textFont, array $color_array = [])
    {
        $this->setContent($content);
        $this->setSize($textSize);
        $this->setFont($textFont);
        $this->setColors($color_array);
    }
        
    /**
     * 
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;        
    }
    
    /**
     * 
     * @param int $textSize
     */
    public function setSize(int $textSize)
    {
        $this->size = $textSize;
    }
    
    /**
     * 
     * @param int $textFont
     */
    public function setFont(string $textFont)
    {
        $this->font = $textFont;
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
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
    }
    
    /**
     * 
     * @return int
     */
    public function getSize() : int
    {
        return $this->size;
    }
    
    /**
     * 
     * @return string
     */
    public function getFont() : string
    {
        return $this->font;
    }    
}