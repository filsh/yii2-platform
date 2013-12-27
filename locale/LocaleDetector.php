<?php

namespace yii\platform\locale;

interface LocaleDetector
{
    public function detect($languages = []);
}