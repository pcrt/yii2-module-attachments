<?php

namespace pcrt\file;

use yii\web\AssetBundle;


class FileUploaderAsset extends AssetBundle
{
  
    public $sourcePath = __DIR__ . '/assets';

    public $js = [
      //'js/uppy.min.js',
      //'js/FileUploader.js'
      'dist/main.js'
    ];

    public $css = [
      //'css/uppy.min.css',
      //'css/style.css'
      'dist/main.css'
    ];

    public $depends = [
      'yii\web\YiiAsset',
      'app\assets\AppAsset'
    ];
}
