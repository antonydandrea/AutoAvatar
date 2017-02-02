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
    private $color_helper;
    
    /** 
     *
     * @var string
     */
    private $default_path;
    
    /** 
     * 
     * @param string $defaultPath
     * @param array $colorArray
     * @param array $textcolorArray
     * @param int $defaultTextSize
     * @throws Exception
     */
    public function __construct(string $defaultPath)
    {
        $this->default_path = $defaultPath;
        $this->color_helper = new ColorFunctions;
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
     * @throws \Exception
     */
    public function compileImage(string $fileName, Image $imageObj, Text $textObj, string $colorOverride = '', string $textcolorOverride = '')
    {
        $fullPath = rtrim($this->default_path, '/').'/'.$fileName;

        if (!empty($colorOverride)) {
            $backgroundcolor = $this->color_helper->hexCodeToRgb($colorOverride);
        } else {
            $backgroundcolor = $imageObj->getRandomColor();
        }

        if (!empty($textcolorOverride)) {
            $textcolor = $this->color_helper->hexCodeToRgb($textcolorOverride);
        } else {
            $textcolor = $textObj->getRandomColor();
        }

        $fullPath .= ".{$imageObj->getFormat()}";

        $image = imagecreate($imageObj->getWidth(), $imageObj->getHeight());

        $background_color = imagecolorallocate($image, ...$backgroundcolor);
        $text_color = imagecolorallocate($image, ...$textcolor);

        $textCordinates = $this->generateTextCoordinates($textObj->getFont(), $imageObj->getWidth(), $imageObj->getHeight(), $textObj->getSize(), $textObj->getContent());       
        imagettftext($image, $textObj->getSize(), 0, $textCordinates['x'], $textCordinates['y'], $text_color, $textObj->getFont(), $textObj->getContent());

        $imageWritten = $this->writeImage($image, $fullPath, $imageObj->getFormat());
        imagedestroy($image);

        if (!$imageWritten) {
            throw new \Exception('Image write failed ('.$fullPath.'). Check file path permissions.');
        }
        return [
            'background_color'      => $this->color_helper->rgbToHexCode($backgroundcolor),
            'text_color'            => $this->color_helper->rgbToHexCode($textcolor),
            'content'               => $textObj->getContent()
        ];
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
}