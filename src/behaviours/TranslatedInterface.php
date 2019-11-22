<?php

namespace ziya\Translate\behaviours;


interface TranslatedInterface
{
    public function __construct($owner, $attribute);


    public function __toString();

    public function other($lang);

    public function all();
}