<?php

namespace pcrt\file;


class Module extends \yii\base\Module
{
    public $filePluginSavePath;

    public function init()
    {
        parent::init();
        \Yii::setAlias('@file', __DIR__);
    }
}
