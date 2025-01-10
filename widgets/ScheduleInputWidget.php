<?php

namespace app\widgets;

use Yii;
use yii\bootstrap5\Widget;
use app\models\Schedule;
use app\models\ScheduleData;


class ScheduleInputWidget extends Widget
{
    public $name;

    public function init()
    {
        parent::init();
    }
    public function run()
    {
		$pm = $this->name;
		$spm = ScheduleData::find()->all();
		
		return $this->render('input', ['pm' => $pm, 'spm' => $spm, 'model' => (new Schedule)]);
    }
}
