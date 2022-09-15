<?php

namespace App\Domain\Validation;

use App\Domain\Exceptions\NotAllowedException;

class Validator
{
    /**
     * Get Parameter from a request
     *
     * @param $request
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public static function getParam($request, $key, $default = null)
    {
        $postParams = $request->getParsedBody();

        $result = $default;

        if (is_array($postParams) && isset($postParams[$key])) {
            $result = $postParams[$key];
        } elseif (is_object($postParams) && property_exists($postParams, $key)) {
            $result = $postParams->$key;
        }

        return $result;
    }



    /**
     * Validation for ticket code Length
     *
     * @param string $string
     * @return string
     * @throws NotAllowedException
     */
    public static function validateLength(string $string): string
    {
        if (strlen($string) >= 10) {
            throw new NotAllowedException('Length too big');
        }

        return $string;
    }


    /**
     * Validation if value is inside an Array
     *
     * @param string $string
     * @param string $value
     * @param array $array
     * @return mixed|null
     */
    public static function validateValue(string $string, string $value, array $array)
    {
        foreach ($array as $val) {
            if ($val[$string] === $value) {
                return $value;
            }
        }
        return null;
    }
}
