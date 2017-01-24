<?php

class ProfilePicGenerator
{
    private $default_path;
    
    private $default_width;
    
    private $default_height;
    
    /**
     * An array of Hex codes.
     * If set, the background colours will be randomly chosen from this array. 
     * @var array
     */
    private $colour_array;
    
    private $text_colour_array;
    
    public function __construct(string $defaultPath = null, array $colourArray = [], array $textColourArray = [], int $defaultWidth = 70, int $defaultHeight = 70)
    {
        $this->default_path = $defaultPath;
        $this->default_width = $defaultWidth;
        $this->default_height = $defaultHeight;
        $this->colour_array = $colourArray;
        $this->text_colour_array = $textColourArray;
    }
 
    public function generateNewImage(string $fileName, string $text, string $colourOverride = '', string $textColourOverride = '', int $widthOverride = 0, $heightOverride = 0)
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
            header("Content-Type: image/png");
            $image = imagecreate($width, $height);
            $background_color = imagecolorallocate($image, ...$backgroundColour);
            $text_color = imagecolorallocate($image, ...$textColour);
            imagestring($image, 1, 5, 5,  $text, $text_color);
            imagepng($image, $fullPath);
            imagedestroy($image);
        } catch (\Exception $e) {
            print $e->getMessage(); 
            die();
        }
    }
 
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
    
    private function generateRandomRGB() : array
    {
        return [
            "red"   => rand(0, 255),
            "green" => rand(0, 255),
            "blue"  => rand(0, 255)
        ];
    }
    
    public function getDefaultPath() : string
    {
        return $this->default_path;
    }
    
    public function getDefaultWidth() : int
    {
        return $this->default_width;
    }
    
    public function getDefaultHeight() : int
    {
        return $this->default_height;
    }
    
    public function getColourArray() : array
    {
        return $this->colour_array;
    }
    
    public function setDefaultPath(string $defaultPath)
    {
        $this->default_path = $defaultPath;
    }
    
    public function setDefaultWidth(int $defaultWidth)
    {
        $this->default_width = $defaultWidth;
    }

    public function setDefaultHeight(int $defaultHeight)
    {
        $this->default_height = $defaultHeight;
    }
    
    public function setColourArray(array $colourArray)
    {
        $this->colour_array($colourArray);
    }
    
    public function addColour(string $hex)
    {
        array_push($this->colour_array, $hex);
    }
}
