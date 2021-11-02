<?php

namespace pcrt\file\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use pcrt\file\models\LoginForm;
use pcrt\file\models\ContactForm;

use pcrt\file\models\Attachments;
use pcrt\file\plugins\DbStoragePlugins;
use pcrt\file\plugins\FileStoragePlugins;

class AttachmentsController extends Controller
{
    public function actionUpload()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();

        $request = null;
        if (is_uploaded_file($_FILES['files']['tmp_name'])) {
          $post['files'] = $_FILES['files'];
          $request = (object)$post;
        }

        \Yii::warning((array)$request);

        $db = new DbStoragePlugins();
        $file = new FileStoragePlugins();


        $db->setNext($file);
        $result = $db->handle((object)$request);

        return 0;
    }

    public function actionList(){
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $request = Yii::$app->request->post();
      $db = new DbStoragePlugins();
      $file = new FileStoragePlugins();
      $db->setNext($file);
      $result = $db->handle((object)$request);

      \Yii::warning($result);

      if (!empty($result->files_list)) {
        $data = $this->renderAjax('_list', [
          'files' => $result->files_list
        ]);
      } else {
        $data = $this->renderAjax('_empty');
      }
      return ['html' => $data];
    }

    public function actionUpdate(){
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $request = Yii::$app->request->post();
      $db = new DbStoragePlugins();
      $file = new FileStoragePlugins();
      $db->setNext($file);
      $result = $db->handle((object)$request);
      return 0;
    }

    public function actionDelete(){
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $request = Yii::$app->request->post();

      $attachment = Attachments::findOne($request['file_id']);

      $olds = $attachment->getArchived();
      usort($olds, function($a, $b) {
        return $a['version'] <=> $b['version'];
      });

      foreach($olds as $old) {
        $attach = Attachments::findOne($old['id']);

        $subRequest = ['model_classname' => 'Attach', 'model_id' => $attach['external_id'], 'file_id' => $attach['id'], 'method' => 'rm'];

        $db = new DbStoragePlugins();
        $file = new FileStoragePlugins();
        $db->setNext($file);
        $db->handle((object)$subRequest);
      }

      $db = new DbStoragePlugins();
      $file = new FileStoragePlugins();
      $db->setNext($file);
      $db->handle((object)$request);

      return 1;
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
