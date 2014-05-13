<?php

namespace yii\platform\i18n\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property string $language
 * @property string $translation
 * @property integer $create_time
 * @property integer $update_time
 *
 * @property MessageSource $id0
 */
class Message extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language', 'translation'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'language' => 'Language',
            'translation' => 'Translation',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(MessageSource::className(), ['id' => 'id']);
    }
    
    /**
     * TODO: add caching
     */
    public static function initMessage($category, $language, $message, $translation)
    {
        $transaction = self::getDb()->beginTransaction();
        try {
            $source = MessageSource::findOne(['category' => $category, 'message' => $message]);
            if($source === null) {
                $source = new MessageSource();
                $source->setAttributes(['category' => $category, 'message' => $message]);
                if(!$source->save()) {
                    throw new \yii\base\Exception('Unable to create message source.');
                }
            }
            
            self::getDb()->createCommand('INSERT IGNORE INTO ' . self::tableName() . '(id, language, translation, create_time, update_time)
                VALUES(:id, :language, :translation, :create_time, :update_time)', [
                ':id' => $source->id,
                ':language' => $language,
                ':translation' => $translation,
                ':create_time' => time(),
                ':update_time' => time()
            ])->execute();
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}