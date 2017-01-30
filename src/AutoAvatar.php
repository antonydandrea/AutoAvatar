<?php
/** 
 * AutoAvatar
 * 
 * version 0.2
 */
class AutoAvatar
{
    /** 
     *
     * @var string
     */
    private $default_path;
    
    /** 
     *
     * @var int
     */
    private $default_width;
    
    /** 
     *
     * @var int
     */
    private $default_height;
    
    /**
     * An array of Hex codes.
     * If set, the background colours will be randomly chosen from this array. 
     * @var array
     */
    private $colour_array;
    
    /** 
     *
     * @var array
     */
    private $text_colour_array;
    
    /** 
     *
     * @var string
     */
    private $default_font;
    
    /** 
     *
     * @var int
     */
    private $default_text_size;
    
    /** 
     * 
     * @param string $defaultPath
     * @param array $colourArray
     * @param array $textColourArray
     * @param int $defaultWidth
     * @param int $defaultHeight
     * @param int $defaultTextSize
     * @param string $defaultFont
     */
    public function __construct(string $defaultPath, array $colourArray = [], array $textColourArray = [], int $defaultWidth = 70, int $defaultHeight = 70, int $defaultTextSize = 30, string $defaultFont = '')
    {
        $this->default_path = $defaultPath;
        $this->default_width = $defaultWidth;
        $this->default_height = $defaultHeight;
        $this->colour_array = $colourArray;
        $this->text_colour_array = $textColourArray;
        $this->default_font = $defaultFont;
        $this->default_text_size = $defaultTextSize;
    }
 
    /** 
     * Saves image to the path configured in constructor.
     * Returns an array of the chosen colours plus the text.
     * 
     * @param string $fileName
     * @param string $text
     * @param string $colourOverride
     * @param string $textColourOverride
     * @param int $widthOverride
     * @param int $heightOverride
     * @param int $textSizeOverride
     * @param string $fontOverride
     */
    public function generateNewImage(string $fileName, string $text, string $colourOverride = '', string $textColourOverride = '', int $widthOverride = 0, int $heightOverride = 0, int $textSizeOverride = 0, string $fontOverride = '')
    {
        try {
            $fullPath = trim($this->default_path, '/').'/'.$fileName;
            if (!empty($colourOverride)) {
                $backgroundColour = $this->hexCodeToRgb($colourOverride);
            } elseif (!empty($this->colour_array)) {
                $backgroundColour = $this->hexCodeToRgb($this->colour_array[rand(0, (count($this->colour_array) - 1))]);
            } else {
                $backgroundColour = $this->generateRandomRGB();
            }
            
            if (!empty($textColourOverride)) {
                $textColour = $this->hexCodeToRgb($textColourOverride);
            } elseif (!empty($this->text_colour_array)) {
                $textColour = $this->hexCodeToRgb($this->text_colour_array[rand(0, (count($this->text_colour_array) - 1))]);
            } else {
                $textColour = $this->generateRandomRGB();
            }
            
            if (!empty($widthOverride)) {
                $width = $widthOverride;
            } else {
                $width = $this->default_width;
            }
            
            if (!empty($heightOverride)) {
                $height = $heightOverride;
            } else {
                $height = $this->default_height;
            }
            
            if (!empty($textSizeOverride)) {
                $size = $textSizeOverride;
            } else {
                $size = $this->default_text_size;
            }
            
            if (!empty($fontOverride)) {
                $font = $fontOverride;
            } else {
                $font = $this->default_font;
            }
            $image = imagecreate($width, $height);
            $background_color = imagecolorallocate($image, ...$backgroundColour);
            $text_color = imagecolorallocate($image, ...$textColour);
            $imageCordinates = $this->generateTextCoordinates($font, $width, $height, $size, $text);       
            imagettftext($image, $size, 0, $imageCordinates['x'], $imageCordinates['y'], $text_color, $font, $text);
            imagepng($image, $fullPath);
            imagedestroy($image);
            return [
                'background'    => $this->rgbToHexCode($backgroundColour),
                'text'          => $this->rgbToHexCode($textColour),
                'content'       => $text
            ];
        } catch (\Exception $e) {
            print $e->getMessage(); 
            die();
        }
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
     * @param array rgb
     * @return string
     */
    private function rgbToHexCode(array $rgb) : string
    {
        $hex = '#';
        foreach ($rgb as $colour) {
            $hex .= dechex($colour);
        }
        return $hex;
    }
    
    /** 
     * 
     * @param string $hex
     * @return array
     * @throws \Exception
     */
    private function hexCodeToRgb(string $hex) : array
    {
        $rgb = [];
        $cleanHex = trim($hex, '#');
        if (strlen($cleanHex) === 3) {
            $cleanHex .= $cleanHex;
        }
        $hexArray = $this->splitHex($cleanHex);
        if (count($hexArray) === 3) {
            foreach ($hexArray as $colour => $hex) {
                $rgb[$colour] = hexdec($hex);
            }
        } else {
            throw new \Exception("Invalid Hex Code: ".$hex);
        }
        return $rgb;
    }
    
    /** 
     * 
     * @param string $hex
     * @param bool $intKeys
     * @return array
     */
    private function splitHex(string $hex, bool $intKeys = true) : array
    {
        $splitHex = [];
        $cleanHex = trim($hex, '#');
        $matched = preg_match('/(?<red>[0-9A-F]{2})(?<green>[0-9A-F]{2})(?<blue>[0-9A-F]{2})/', $cleanHex, $hexArray);
        if ($matched) {
            foreach (array_slice($hexArray, 1) as $key => $value) {
                if (!$intKeys && is_string($key)) {
                    $splitHex[$key] = $value;
                } elseif ($intKeys && is_int($key)) {
                    $splitHex[$key] = $value;
                }
            }
        }
        return $splitHex;
    }
    
    /** 
     * 
     * @return array
     */
    private function generateRandomRGB() : array
    {
        return [
            "red"   => rand(0, 255),
            "green" => rand(0, 255),
            "blue"  => rand(0, 255)
        ];
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
     * @return int
     */
    public function getDefaultWidth() : int
    {
        return $this->default_width;
    }
    
    /** 
     * 
     * @return int
     */
    public function getDefaultHeight() : int
    {
        return $this->default_height;
    }
    
    /** 
     * 
     * @return array
     */
    public function getColourArray() : array
    {
        return $this->colour_array;
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
     * @param int $defaultWidth
     */
    public function setDefaultWidth(int $defaultWidth)
    {
        $this->default_width = $defaultWidth;
    }
    
    /** 
     * 
     * @param int $defaultHeight
     */
    public function setDefaultHeight(int $defaultHeight)
    {
        $this->default_height = $defaultHeight;
    }
    
    /** 
     * 
     * @param array $colourArray
     */
    public function setColourArray(array $colourArray)
    {
        $this->colour_array($colourArray);
    }
    
    /**
     * 
     * @param string $hex
     */
    public function addColour(string $hex)
    {
        array_push($this->colour_array, $hex);
    }
}
