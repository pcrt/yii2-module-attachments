<?php

namespace pcrt\file\plugins;

use pcrt\file\AbstractStorageInterface;
use pcrt\file\models\Attachments;

class DbStoragePlugins extends AbstractStorageInterface
{
    public function put(object $request): ?object
    {
        $attachment = new Attachments();
        $attachment->mimetype = $request->files['type'];
        $attachment->size = $request->files['size'];
        $attachment->title = $request->files['name'];
        $attachment->original_filename = $request->files['name'];
        $attachment->url = $request->files['tmp_name'];
        $attachment->tmp_url = $request->files['tmp_name'];
        $attachment->external_id = $request->model_id;
        $attachment->external_table = $request->model_classname;
        $attachment->version = 1;
        $attachment->archived = null;
        $attachment->save();

        $request->new_id = $attachment->id;
        return $request;
    }

    public function get(object $request): ?object
    {
        return $request;
    }

    public function rm(object $request): ?object
    {
        $files = Attachments::findOne($request->file_id);
        if($files){
          $request->file = $files;
          $files->delete();
        }
        return $request;
    }

    public function list(object $request): ?object
    {
        $files = Attachments::find()
            ->andFilterWhere(['external_id' => $request->model_id])
            ->andFilterWhere(['external_table' => $request->model_classname])
            ->andFilterWhere(['IS', 'archived', new \yii\db\Expression('null')])
            ->all();
            
        $request->files_list = $files;
        return $request;
    }

    public function update(object $request): ?object
    {
        $files = Attachments::findOne($request->file_id);
        $field = $request->field;
        $files->$field = $request->value;
        $files->save();
        return $request;
    }

    public function replace(object $request): ?object
    {
        $this->put($request);

        if (!empty($request->file_id)) {
            $newId = $request->new_id;

            $oldAttachment = Attachments::findOne($request->file_id);
            $oldAttachment->archived = $newId;
            $oldAttachment->save();

            $newAttachment = Attachments::findOne($newId);
            $newAttachment->version = $oldAttachment->version + 1;
            $newAttachment->save();
        }

        return $request;
    }
}
