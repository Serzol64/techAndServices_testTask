<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\bootstrap5\Modal;
use Carbon\Carbon;

 
?>

<section id="form">
    <h2><?php echo $this->title; ?></h2>
    <span>Установите рабочее время</span>

    <?php $form = ActiveForm::begin(['id' => $pm]); ?>
 
	<div class="switchers">
		<?php 
		    echo $form->field($model, 'timezone')->checkbox(['value' => TRUE, 'checked' => TRUE, 'id' => 'timezone']); 
			echo $form->field($model, 'productionCalendar')->checkbox(['value' => TRUE, 'checked' => FALSE, 'id' => 'calendar']); 
		?>
	</div>	

	<div class="multipleForm">
        <?php 
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
            
            echo '<ul class="list-group">';
            if(isset($spm)){
                $j = 0;
                foreach($spm as $item){
                    if($item->isSpecial === 1){
                        $setEvent = 'onChange="workAddTime(\'special\', ' . $j . ')"';
                        $times = '<div disabled><input type="time" name="timesFromHome" value="' . $getTime($item->days)[0] . '" /> - <input type="time" name="timesToHome" value="' . $getTime($item->days)[1] . '" ' . $setEvent . '/></div>';
                        echo '<li data-item="' . $item->id . '"><span disabled>' . $getPeriod($item->days)[0] . ' - ' . $getPeriod($item->days)[1] . '</span>' . $times . '<div id="buttons-container"><button class="delete-button" onClick="deleteItem(' . $j . ')">&#10007;</button></div></li>';
                    }
                    else{
                        $day = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт'];
                        $d = '';
                        for($i = 0; $i < count($day); $i++){
                            $currentDay = (int) $item->days['day'];
                            $cd = ($currentDay === ($i + 1)) ? 'selected' : 'default';
                            $sd = '<strong>' . $day[$i] . '</strong>'; // Исправление тут
                            
                            $d .= '<a href="#" class="' . $cd .'" onClick="selectDay(event, ' . $j . ', ' . $i . ')">' . $sd . '<input type="hidden" name="day" value="' . ($i + 1) . '" /><input type="hidden" name="status" value="' . $cd . '" /></a>';
                        }
                        
                        $setEvent = 'onChange="workAddTime(\'work\', ' . $j . ')"';
                        $times = '<div disabled><input type="time" name="timesFromHome" value="' . $getTime($item->days)[0] . '" /> - <input type="time" name="timesToHome" value="' . $getTime($item->days)[1] . '" ' . $setEvent . '/></div>';
                        echo '<li data-item="' . $item->id . '"><span disabled><nav>' . $d . '</nav></span>' . $times . '<div id="buttons-container"><button class="delete-button" onClick="deleteItem(' . $j . ')">&#10007;</button></div></li>';
                    }
                    $j++;
                }
            }
            echo '</ul>';
        ?>
	</div>
	
	<div class="butGroup">
		<?php 
		Modal::begin([
		    'title' => '<h2>Добавить особенные дни</h2>',
		    'toggleButton' => ['label' => 'Добавить особенные дни'],
		    'footer' => '<button type="button" class="btn btn-primary" onClick="specialAddTime()" style="background-color: #731982;">Добавить</button><button type="button" class="btn btn-secondary" onClick="closeModal()">Отменить</button>',
		    'class'  =>  'submit'
		]);
		?>
		
		<header><p id="modal_desc">Выберите день или период, когда режим работы не совпадает с рабочим днем или дополнительным интервалом.</p></header>

        <main><div>
                <input type="date" id="start" onChange="showDate()" onInput="showDate()"/>
                <span class="input-group-addon">по</span>
                <input type="date" id="end" onChange="showDate()" onInput="showDate()"/>
        </div></main>
            
            
        <footer>
            	<ul>
            		<li>
            			<span></span>
            			<span>-</span>
            			<span></span>
            		</li>
            		<li>
            			<span>с <input type="time" id="timesFrom"></span>
            			<span>до</span>
            			<span><input type="time" id="timesTo"></span>
            		</li>
            	</ul>
        </footer>
		
		<?php
		Modal::end();
		?>
		<button type="button" class="submit" onclick="setNewWorkTime()">Добавить рабочие дни</button>	
	</div>
    <?php ActiveForm::end(); ?>
</section>
