<?php
namespace AutoAvatar;

use AutoAvatar\Image;
use AutoAvatar\Text;
use AutoAvatar\Helper\ColorFunctions;

/** 
 * AutoAvatar
 * 
 * @package AutoAvatar
 */
class ImageCompiler
{
    /** 
     * @var AutoAvatar\Helper\ColorFunctions
     */
    private $colour_helper;
    
    /** 
     *
     * @var string
     */
    private $default_path;
    
    /**
     * An array of Hex codes.
     * If set, the background colors will be randomly chosen from this array. 
     * @var array
     */
    private $color_array;
    
    /** 
     *
     * @var array
     */
    private $text_color_array;
    
    /** 
     * 
     * @param string $defaultPath
     * @param array $colorArray
     * @param array $textcolorArray
     * @param int $defaultTextSize
     * @throws Exception
     */
    public function __construct(string $defaultPath, array $colorArray = [], array $textcolorArray = [])
    {
        $this->default_path = $defaultPath;
        $this->color_array = $colorArray;
        $this->text_color_array = $textcolorArray;
        $this->colour_helper = new ColorFunctions;
    }
 
    /** 
     * Saves image to the path configured in constructor.
     * Returns an array of the chosen colors plus the text.
     * 
     * @param string $fileName
     * @param string $text
     * @param string $colorOverride
     * @param string $textcolorOverride
     * @param int $textSizeOverride
     * @param string $fontOverride
     */
    public function compileImage(string $fileName, Image $imageObj, Text $textObj, string $colorOverride = '', string $textcolorOverride = '')
    {
        try {
            $fullPath = trim($this->default_path, '/').'/'.$fileName;
            if (!empty($colorOverride)) {
                $backgroundcolor = $this->colour_helper->hexCodeToRgb($colorOverride);
            } elseif (!empty($this->color_array)) {
                $backgroundcolor = $this->colour_helper->hexCodeToRgb($this->color_array[rand(0, (count($this->color_array) - 1))]);
            } else {
                $backgroundcolor = $this->colour_helper->generateRandomRGB();
            }
            
            if (!empty($textcolorOverride)) {
                $textcolor = $this->colour_helper->hexCodeToRgb($textcolorOverride);
            } elseif (!empty($this->text_color_array)) {
                $textcolor = $this->colour_helper->hexCodeToRgb($this->text_color_array[rand(0, (count($this->text_color_array) - 1))]);
            } else {
                $textcolor = $this->colour_helper->generateRandomRGB();
            }
            
            $fullPath .= ".{$imageObj->getFormat()}";
            $image = imagecreate($imageObj->getWidth(), $imageObj->getHeight());
            $background_color = imagecolorallocate($image, ...$backgroundcolor);
            $text_color = imagecolorallocate($image, ...$textcolor);
            $imageCordinates = $this->generateTextCoordinates($textObj->getFont(), $imageObj->getWidth(), $imageObj->getHeight(), $textObj->getSize(), $textObj->getContent());       
            imagettftext($image, $textObj->getSize(), 0, $imageCordinates['x'], $imageCordinates['y'], $text_color, $textObj->getFont(), $textObj->getContent());
            $imageWritten = $this->writeImage($image, $fullPath, $imageObj->getFormat());
            imagedestroy($image);
            if (!$imageWritten) {
                throw new \Exception('Image write failed. Check file path permissions.');
            }
            return [
                'background_color'     => $this->colour_helper->rgbToHexCode($backgroundcolor),
                'text_color'           => $this->colour_helper->rgbToHexCode($textcolor),
                'content'               => $textObj->getContent()
            ];
        } catch (\Exception $e) {
            print $e->getMessage(); 
            die();
        }
    }
 
    /** 
     * 
     * @param $image
     * @param string $fullPath
     * @param string $format
     * @return bool
     */
    private function writeImage($image, string $fullPath, string $format) : bool
    {
        $result = false;
        switch($format) {
            case 'png':
                $result = imagepng($image, $fullPath);
                break;
            case 'jpg':
            case 'jpeg':
                $result = imagejpeg($image, $fullPath);
                break;
            case 'gif':
                $result = imagegif($image, $fullPath);
                break;
        }
        return $result;
    }
    
    /** 
     * 
     * @param string $font
     * @param int $imageWidth
     * @param int $imageHeight
     * @param int $textSize
     * @param string $text
     * @return array
     */
    private function generateTextCoordinates(string $font, int $imageWidth, int $imageHeight, int $textSize, string $text) : array
    {
        $centerX = $imageWidth / 2;
        $centerY = $imageHeight / 2;
        list($left, $bottom, $right, , , $top) = imageftbbox($textSize, 0, $font, $text);
        $left_offset = ($right - $left) / 2;
        $top_offset = ($bottom - $top) / 2;
        $x = $centerX - $left_offset;
        $y = $centerY + $top_offset;
        return ['x' => $x, 'y' => $y];
    }
    
    /** 
     * 
     * @return string
     */
    public function getDefaultPath() : string
    {
        return $this->default_path;
    }
    
    /** 
     * 
     * @return array
     */
    public function getcolorArray() : array
    {
        return $this->color_array;
    }
    
    /** 
     * 
     * @param string $defaultPath
     */
    public function setDefaultPath(string $defaultPath)
    {
        $this->default_path = $defaultPath;
    }
        
    /** 
     * 
     * @param array $colorArray
     */
    public function setcolorArray(array $colorArray)
    {
        $this->color_array($colorArray);
    }
    
    /**
     * 
     * @param string $hex
     */
    public function addcolor(string $hex)
    {
        array_push($this->color_array, $hex);
    }
}
