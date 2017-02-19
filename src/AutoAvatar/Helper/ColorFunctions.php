<?php
namespace AutoAvatar\Helper;

use AutoAvatar\Exception\ColorException;

/** 
 * @package AutoAvatar\Helper
 */
class ColorFunctions
{
    /**
     * @param array rgb
     * @return string
     * @throws ColorException
     */
    public function rgbToHexCode(array $rgb) : string
    {
        if (!$this->isRGBValid($rgb)) {
            throw new ColorException("Invalid RGB Array: ". implode(', ', $rgb));
        }
        $hex = '#';
        foreach ($rgb as $color) {
            $hex .= str_pad(dechex($color), 2, '0', 0);
        }
        return $hex;
    }
    
    /** 
     * 
     * @param string $hex
     * @return array
     * @throws ColorException
     */
    public function hexCodeToRgb(string $hex) : array
    {
        $rgb = [];
        $cleanHex = trim($hex, '#');
        if (strlen($cleanHex) === 3) {
            $cleanHex .= $cleanHex;
        }
        $hexArray = $this->splitHex($cleanHex);
        if (count($hexArray) === 3) {
            foreach ($hexArray as $color => $hex) {
                $rgb[$color] = hexdec($hex);
            }
        } else {
            throw new ColorException("Invalid Hex Code: ".$hex);
        }
        return $rgb;
    }
    
    /** 
     * 
     * @param string $hex
     * @param bool $intKeys
     * @return array
     */
    public function splitHex(string $hex, bool $intKeys = true) : array
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
    public function generateRandomRGB() : array
    {
        return [
            "red"   => rand(0, 255),
            "green" => rand(0, 255),
            "blue"  => rand(0, 255)
        ];
    }
    
    /** 
     * @param array $rgb
     * @return bool
     */
    public function isRGBValid(array $rgb) : bool
    {
        $valid = false;
        if (count($rgb) === 3) {
            if (max($rgb) <= 255 && min($rgb) >= 0) {
                $valid = true;
            }
        }
        return $valid;
    }
}
