<?php

namespace pcrt\file;

use yii\web\AssetBundle;

use yii\helpers\FileHelper;

class FileUploaderAsset extends AssetBundle
{
  
    public $sourcePath = __DIR__ . '/assets';

    public $js = [];

    public $css = [];

    public function __construct() {
      $files = FileHelper::findFiles(\Yii::getAlias('@file') . '/assets/dist');

      foreach($files as $file) {
        if (strpos($file, '.js') !== false) {
          $this->js[] = 'dist/' . basename($file);
        }

        if (strpos($file, '.css') !== false) {
          $this->css[] = 'dist/' . basename($file);
        }
      }
    }

    public $depends = [
      'yii\web\YiiAsset',
      'app\assets\AppAsset'
    ];
}
