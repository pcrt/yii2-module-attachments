<?php

namespace pcrt\file;

use Yii;

use yii\base\Widget;
use yii\helpers\Url;
use yii\base\InvalidConfigException;


class FileUploader extends Widget
{
    public $selector;
    public $upload_url;
    public $list_url;
    public $delete_url;
    public $update_url;
    public $model_classname;
    public $model_id;
    public $model;



    public function run()
    {
        parent::run();

        Yii::setAlias('@file', __DIR__);

        //$this->getView()->registerAssetBundle(FileUploaderAsset::class);
        FileUploaderAsset::register($this->getView()); 
        //app\FileUploaderAssets::register( $this->getView() );

        if($this->model_classname == "" && $this->model == ""){
          throw new InvalidConfigException("Must set model_classname OR model !");
        }

        return $this->render(
          'main.php',
            [
              'selector' => $this->selector,
              'upload_url' => ($this->upload_url == "") ? Url::to(['attachments/attachments/upload']) : $this->upload_url,
              'list_url' => ($this->list_url == "") ? Url::to(['attachments/attachments/list']) : $this->list_url,
              'delete_url' => ($this->delete_url == "") ? Url::to(['attachments/attachments/delete']) : $this->delete_url,
              'update_url' => ($this->update_url == "") ? Url::to(['attachments/attachments/update']) : $this->update_url,
              'model_classname' => ($this->model_classname == "") ? $this->model::className() : $this->model_classname,
              'model_id' => (!empty($this->model)) ? $this->model->id : $this->model_id,
            ]
        );
    }
}
