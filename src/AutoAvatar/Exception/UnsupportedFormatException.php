<?php
namespace AutoAvatar\Exception;

/** 
 * @package AutoAvatar\Exception
 */
class UnsupportedFormatException extends \Exception
{
    /** 
     * Give just the offending format and an array of valid formats and the
     * message will be generated for you.
     * @param string $invalidFormat
     * @param string $supportedFormats
     */
    public function __construct(string $invalidFormat, array $supportedFormats)
    {
        $message = 'Unsupported format: '.$invalidFormat.'. Supported formats: '.implode(', ', $supportedFormats).'.';
        parent::__construct($message);
    }
}