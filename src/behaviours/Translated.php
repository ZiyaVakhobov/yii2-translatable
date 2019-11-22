<?php

namespace ziya\Translate\behaviours;

use JsonSerializable;

class Translated implements TranslatedInterface, JsonSerializable
{
    private $attribute;
    private $toDisplay;
    private $attributeValue;

    public function __construct($owner, $attribute)
    {
        $this->attribute = $attribute;
        $lang = \Yii::$app->language;
        $attributeValue = $owner->$attribute;
        $this->toDisplay = isset($attributeValue[$lang])?$attributeValue[$lang]:'';
        $this->attributeValue = $attributeValue;
    }


    public function __toString()
    {
        return (string)$this->toDisplay;
    }

    public function other($lang)
    {
        return isset($this->attributeValue[$lang])?$this->attributeValue[$lang]:'';
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toDisplay;
    }


    public function all()
    {
        return $this->attributeValue;
    }
}