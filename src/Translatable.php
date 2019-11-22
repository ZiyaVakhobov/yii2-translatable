<?php

namespace ziya\Translate;


use yii\base\Arrayable;
use yii\base\ArrayableTrait;
use yii\helpers\ArrayHelper;
use yii\web\Link;
use yii\web\Linkable;
use ziya\Translate\behaviours\TranslatedInterface;

trait Translatable
{
    use ArrayableTrait {
        ArrayableTrait::toArray as ____oldToArray;
    }
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = [];
        $modelFields = $this->resolveFields($fields, $expand);
        $modelFields = ($modelFields == null)?$this->attributes:$modelFields;
        foreach ($modelFields as $field => $definition) {
            $attribute = $definition;
            if ($definition instanceof  TranslatedInterface) {
                $attribute = (string) $definition->jsonSerialize();
            }
            if (is_callable($definition)) {
                $attribute = $definition($this, $field);
            }
            if ($recursive) {
                $nestedFields = $this->extractFieldsFor($fields, $field);
                $nestedExpand = $this->extractFieldsFor($expand, $field);

                if ($attribute instanceof  TranslatedInterface) {
                    $attribute = (string) $attribute->jsonSerialize();
                }
                if ($attribute instanceof Arrayable) {
                    $attribute = $attribute->toArray($nestedFields, $nestedExpand);
                } elseif (is_array($attribute)) {
                    $attribute = array_map(
                        function ($item) use ($nestedFields, $nestedExpand) {
                            if ($item instanceof Arrayable) {
                                return $item->toArray($nestedFields, $nestedExpand);
                            }
                            return $item;
                        },
                        $attribute
                    );
                }
            }
            $data[$field] = $attribute;
        }


        if ($this instanceof Linkable) {
            $data['_links'] = Link::serialize($this->getLinks());
        }
        return $recursive ? ArrayHelper::toArray($data) : $data;
    }
    public function toRawArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = [];
        foreach ($this->attributes as $field => $definition) {
            $attribute =  $definition;
            if ($definition instanceof  TranslatedInterface) {
                $attribute =  $definition->all();
            }
            if (is_callable($definition)) {
                $attribute = $definition($this, $field);
            }
            if ($recursive) {
                $nestedFields = $this->extractFieldsFor($fields, $field);
                $nestedExpand = $this->extractFieldsFor($expand, $field);
                if ($attribute instanceof Arrayable) {
                    $attribute = $attribute->toArray($nestedFields, $nestedExpand);
                } elseif (is_array($attribute)) {
                    $attribute = array_map(
                        function ($item) use ($nestedFields, $nestedExpand) {
                            if ($item instanceof Arrayable) {
                                return $item->toArray($nestedFields, $nestedExpand);
                            }
                            return $item;
                        },
                        $attribute
                    );
                }
            }
            $data[$field] = $attribute;
        }

        if ($this instanceof Linkable) {
            $data['_links'] = Link::serialize($this->getLinks());
        }

        return $recursive ? ArrayHelper::toArray($data) : $data;
    }
    public function jsonSerialize() {
        return $this->attributes;
    }
}