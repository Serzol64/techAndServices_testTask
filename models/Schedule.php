<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Schedule extends Model
{
    public $timezone;
    public $productionCalendar;
    public $days;
    public $timesFrom;
    public $timesTo;

    public function rules()
    {
        return [
            [['days', 'timesFrom', 'timesTo'], 'required'],
            [['timezone', 'productionCalendar'], 'boolean']
        ];
    }
    public function attributeLabels()
    {
        return [
            'timezone' => 'Учитывать часовой пояс',
            'productionCalendar' => 'Учитывать производственный календарь'
        ];
    }
    
}
