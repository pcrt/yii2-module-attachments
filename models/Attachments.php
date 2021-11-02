<?php

namespace pcrt\file\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "Attachments".
 *
 * @property int $id
 * @property string $mime-type
 * @property string $title
 * @property string $url
 * @property string $created_at
 * @property string $updated_at
 *
 * @property AttachmentsOffers[] $attachmentsOffers
 * @property Offers[] $offers
 * @property AttachmentsRfpItem[] $attachmentsRfpItems
 * @property RfpItems[] $rfpItems
 * @property AttachmentsRfqItem[] $attachmentsRfqItems
 * @property AttachmentsTickets[] $attachmentsTickets
 * @property Ticket[] $tickets
 */
class Attachments extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
          // anonymous behavior, add method getFilter(name=false), setFilter(name,value)
          [
            'class' => BlameableBehavior::class,
            'createdByAttribute' => 'created_by',
            'updatedByAttribute' => 'updated_by',
          ],
          [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'updated_at',
            'value' => new Expression('NOW()'),
          ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['updated_by', 'created_by', 'created_at', 'updated_at', 'expired_date', 'external_id', 'external_table', 'extra', 'size'], 'safe'],
            [['mimetype', 'title', 'url', 'tmp_url', 'original_filename'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mimetype' => Yii::t('app', 'Mime Type'),
            'title' => Yii::t('app', 'Title'),
            'url' => Yii::t('app', 'Url'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getProtocols()
    {
        return $this->hasMany(Protocol::className(), ['id' => 'id_protocol'])->viaTable('AttachmentsProtocol', ['id_attachment' => 'id']);
    }

    public function getAttachmentsProtocol()
    {
        return $this->hasOne(AttachmentsProtocol::className(), ['id_attachment' => 'id']);
    }

    public function composeUrl()
    {
        return Yii::$app->params['openstack']['CONTAINER_URL'] . Yii::$app->params['openstack']['bucket'] . '/' . $this->url;
    }

    private function checkSub($id) {
        return Attachments::find()->where(['=', 'archived', $id])->one();
    }

    public function getArchived()
    {
        $result = [];

        $id = $this->id;
        do {
            $sub = $this->checkSub($id);
            if ($sub) {
                $extra = [];
                if ($sub->extra) {
                    $extra = json_decode($sub->extra);
                }

                $result[] = $sub;
                $id = $sub->id;
            }

        } while ($sub);

        return $result;
    }

    public function getVersion() {
        return count($this->getArchived()) + 1;
    }

    public function prepare() {
        $extra = [];
        if ($this->extra) {
            $extra = json_decode($this->extra, true);
        }

        return [
            'id' => $this->id,
            'url' => $this->composeUrl(),
            'name' => $this->title,
            'obj_name' => $this->url,
            'expired_date' => $this->expired_date,
            'extra' => $extra,
            'archived' => $this->getArchived(),
            'version' => $this->getVersion(),
            'main' => ($this->archived) ? false : true
        ];
    }

    public static function getFromExternal($tableName, $id) {
        $attachments = Attachments::find()->where(['=', 'external_table', $tableName])->andWhere(['=', 'external_id', $id])->andWhere(['IS', 'archived', null])->orderBy(['title' => SORT_ASC])->all();

        $result = [];

        foreach($attachments as $attachment) {
            $result[] = $attachment->prepare();
        }

        return $result;
    }
}
