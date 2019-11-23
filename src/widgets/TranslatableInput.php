<?php

namespace ziya\Translate;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\widgets\ActiveForm;

class TranslatableInput extends Widget
{
    public $languages;
    /**
     * @var Model
     */
    public $model;
    /**
     * @var ActiveForm
     */
    public $form;
    /**
     * @var string
     */
    public $attribute;
    /**
     * @var array
     */
    public $options;

    /**
     * @var string
     */
    public $type = 'textInput';
    /**
     * @var array
     */
    public $fieldOptions = [];
    /**
     * @var array
     */
    public $inputOptions = [];
    /**
     * @var array
     */
    public $labelOptions = [];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->validateFields(['languages','type','model','attribute']);
    }


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        foreach ($this->languages as $lang) {
            echo $this->form
                ->field($this->model, "{$this->attribute}[{$lang}]", $this->fieldOptions)
                ->{$this->type}(
                    array_merge($this->inputOptions,['value' => $this->model->{$this->attribute}->other($lang)])
                )
                ->label($this->model->getAttributeLabel($this->attribute) . "_{$lang}", $this->labelOptions);
        }
    }

    private function validateFields(array $array)
    {
        if (!in_array($this->type,['textInput','textArea'])) {
            throw new InvalidConfigException("Type should be textInput or textArea");
        }
        foreach ($array as $item) {
            if (empty($this->{$item}) || $this->{$item} == null) {
                throw new InvalidConfigException("{$item} should be set. {$item} should be array ");
            }
        }
    }
}