<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use laxity7\yii2\behaviors\JsonFieldBehavior;


/**
 * ContactForm is the model behind the contact form.
 */
class ScheduleData extends ActiveRecord
{
    
    public function rules()
    {
        return [
            [['days'], 'required'],
            [['timezone', 'productionCalendar', 'isSpecial'], 'boolean']
        ];
    }
    public static function tableName()
    {
        return 'TestTask_Schedule';
    }
    public function behaviors(): array
    {
        return [
            [
                'class'        => JsonFieldBehavior::class,
                'fields'       => ['days'],
                'jsonOptions'  => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                'skipEmpty'    => false,
                'asArray'      => true,
            ],
        ];
    }
}
