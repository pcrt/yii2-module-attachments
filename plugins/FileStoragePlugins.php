<?php

namespace pcrt\file\plugins;

use pcrt\file\AbstractStorageInterface;
use pcrt\file\models\Attachments;

class FileStoragePlugins extends AbstractStorageInterface
{
    public function put(object $request): ?object
    {
        $ext = pathinfo($request->files['name'], PATHINFO_EXTENSION);

        try {
            $path = \Yii::$app->getModule('attachments')->filePluginSavePath;
        } catch (Exception $e) {
            throw new Exception('Parametro filePluginSavePath mancante');
        }

        $uuid = uniqid($request->model_classname,true);
        $newfilename = $path."/".$uuid.".".$ext;

        \Yii::warning($request->files['tmp_name']);
        \Yii::warning($newfilename);

        if (!is_dir(getcwd() . $path)) {
            mkdir(getcwd() . $path);
        }

        move_uploaded_file($request->files['tmp_name'], getcwd() . $newfilename);

        $attachment = Attachments::findOne($request->new_id);
        if($attachment){
          $attachment->url = $newfilename;
          $attachment->save();
        }

        return $request;
    }

    public function get(object $request): ?object
    {
        return $request;
    }

    public function rm(object $request): ?object
    {
        \Yii::warning((array)$request);

        $file = getcwd() . $request->file->url;
        if (is_file($file)) {
            unlink($file);
        }

        return $request;
    }

    public function list(object $request): ?object
    {
        return $request;
    }

    public function update(object $request): ?object
    {
        return $request;
    }

    public function replace(object $request): ?object
    {
        return self::put($request);
    }
}
