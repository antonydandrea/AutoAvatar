<?php
namespace AutoAvatar;

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
     * 
     * @param string $content
     * @param int $textSize
     * @param string $textFont
     */
    public function __construct(string $content, int $textSize, string $textFont)
    {
        $this->setContent($content);
        $this->setSize($textSize);
        $this->setFont($textFont);
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
}