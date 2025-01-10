<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use Carbon\Carbon;
use app\models\ScheduleData;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function actionIndex()
    {
        $input = [
            'name' => 'schedule'            
        ];
        return $this->render('index', ['input' => $input]);
    }
    public function actionEndpoint($special) {
        switch ($special){
            case 'TRUE':
                if(isset($_POST['schedule'])){
                    $special = json_decode($_POST['schedule'], TRUE);
                    
                    if(isset($special['special_time'])){
                            $selected = [
                                'start_time' => $special['special_time']['start'],
                                'end_time' => $special['special_time']['end']
                            ];
                            
                            $getPeriod = function($s){
                                $startDate = $s['start_time'];
                                $endDate = $s['end_time'];
                                
                                $kernel = new Carbon($startDate);
                                $kernel1 = new Carbon($endDate);
                                
                                $res = $kernel->locale('ru_RU')->isoFormat('D MMMM');
                                $res1 = $kernel1->locale('ru_RU')->isoFormat('D MMMM');
                                
                                return [$res, $res1];
                            };
                            
                            $getTime = function($s){
                                $startDate = $s['start_time'];
                                $endDate = $s['end_time'];
                                
                                $kernel = new Carbon($startDate);
                                $kernel1 = new Carbon($endDate);
                                
                                $res = $kernel->locale('ru_RU')->isoFormat('HH:mm');
                                $res1 = $kernel1->locale('ru_RU')->isoFormat('HH:mm');
                                
                                return [$res, $res1];
                            };
                            
                            $newData = new ScheduleData();
                            
                            $newData->days = $selected;
                            $newData->isSpecial = TRUE;
                            $newData->productionCalendar = $special['enable_production_calendar'];
                            $newData->timezone = $special['enable_timezone'];
                            
                            
                            
                            if ($newData->save() && $newData->refresh()){
                                
                                
                                \Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
                                $times = '<div><input type="time" name="timesFrom" value="' . $getTime($selected)[0] . '" / > - <input type="time" name="timesTo" value="' . $getTime($selected)[1] . '" /></div>';
                                return '<li data-item="' . $newData->id . '"><span>' . $getPeriod($selected)[0] . ' - ' . $getPeriod($selected)[1] . '</span>' . $times . '<div id="buttons-container"><button class="edit-save-button">&#128393;</button><button class="delete-button">&#10007;</button></div></li>';
                            }
                    }
                }
            break;
            case 'NULL':
                if(isset($_POST['id'])){
                    $currentItem = ScheduleData::findOne(['id' => $_POST['id']]);
                    
                    if($currentItem->delete()){
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return [];
                    }
                }
                break;
            default:
                if(isset($_POST['schedule'])){
                    $schedule = json_decode($_POST['schedule'], TRUE);
                    
                    if(isset($schedule['work_time'])){
                        
                        $newData2 = new ScheduleData();
                        
                        $newData2->days = $schedule['work_time'];
                        $newData2->isSpecial = FALSE;
                        $newData2->productionCalendar = $schedule['enable_production_calendar'];
                        $newData2->timezone = $schedule['enable_timezone'];
                        
                        if ($newData2->save() && $newData2->refresh()){
                            
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            return [];
                        }
                    }
                }
            break;
        }
    }
    
    public function actionEndpointOperation($special, $id) {
        switch ($special){
            
        }
    }
}
