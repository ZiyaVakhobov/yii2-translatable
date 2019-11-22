<?php

namespace ziya\Translate\behaviours;


use Yii;
use yii\base\Behavior;
use yii\base\Model;
use yii\db\ActiveRecord;

class TranslatableBehaviour extends Behavior
{
    public $attributes;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_INIT =>  'afterFind',
            ActiveRecord::EVENT_AFTER_VALIDATE =>  'afterValidate',
        ];
    }

    public function afterFind()
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->$attribute = new Translated($this->owner, $attribute);
        }
    }

    public function afterValidate()
    {
        /**@var $this->>owner Model **/
        if ($this->owner instanceof  Model) {
            if ($this->owner->hasErrors()) {
                foreach ($this->attributes as $attribute) {
                    $this->owner->$attribute = new Translated($this->owner, $attribute);
                }
            }
        }
    }
}