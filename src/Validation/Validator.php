<?php

namespace App\Validation;

use App\Domain\Exceptions\NotAllowedException;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    public $errors = [];

    public function validate($request , array $rules): Validator
    {
        foreach ($rules as $field =>$rule)
        {
            try{
                $rule->setName($field)->assert(self::getParam($request, $field));
            }catch(NestedValidationException $ex)
            {
                $this->errors[$field] = $ex->getMessages();
            }
        }
        return $this;
    }

    public function failed(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get Parameter from a request
     *
     * @param $request
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public static function getParam($request, $key, $default=null)
    {
        $postParams = $request->getParsedBody();

        $getParams = $request->getQueryParams();

        $getBody = json_decode($request->getBody(),true);

        $result = $default;

        if(is_array($postParams) && isset($postParams[$key]))
        {
            $result = $postParams[$key];

        }else if(is_object($postParams) && property_exists($postParams, $key))
        {
            $result = $postParams->$key;
        }
        else if(is_array($getBody) && isset($getBody[$key]))
        {
            $result = $getBody[$key];

        }else if(isset($getParams[$key]))
        {
            $result = $getParams[$key];
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