function JSONQuery(q){
	var back = new FormData();
	back.append('schedule', JSON.stringify(q));
	
	return back;
}

function JSONQueryById(q){
	var back2 = new FormData();
		back2.append('id', q);
		
		return back2;
}

function setNewWorkTime(){ 
	
	const day = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт'];
	let d = '';
    for (let i = 0; i < day.length; i++) {
	   var ep = (document.querySelectorAll('.list-group li').length) + ', ' + i;
	   d += '<a href="#" class="default" onClick="selectDay(event,' + ep + ')">' + day[i] + '<input type="hidden" name="day" value="' + (i + 1) +'" /><input type="hidden" name="status" value="' + false +'" /></a>';
	}

    const days = '<nav>' + d + '</nav>';

	const times = '<div><input type="time" name="timesFromHome" value="" / > - <input type="time" name="timesToHome" value="" onChange="workAddTime(\'work\', ' + document.querySelectorAll('.list-group li').length + ')"/></div>';
	
	
	document.querySelector('.list-group').insertAdjacentHTML('beforeEnd', '<li><span>' + days + '</span>' + times + '<div id="buttons-container"><button class="delete-button" onClick="deleteItem(' + document.querySelectorAll('.list-group li').length + ')">&#10007;</button></div></li>');
	
	return false;
 }
 
 function showDate() {
             let   dateInput = document.getElementById('start'),
			 	   dateInput2 = document.getElementById('end'),
                   selectedDate = new Date(dateInput.value),
			 	   selectedDate2 = new Date(dateInput2.value);
             
             // Получаем день и месяц
             let day = [selectedDate.getDate(), selectedDate2.getDate()],
                 monthIndex = [selectedDate.getMonth(), selectedDate2.getMonth()]; // Месяцы индексируются с нуля

             // Массив названий месяцев
             let months = [
                 'Январь', 'Февраль', 'Март',
                 'Апрель', 'Май', 'Июнь',
                 'Июль', 'Август', 'Сентябрь',
                 'Октябрь', 'Ноябрь', 'Декабрь'
             ];

             // Формируем строку вывода
             let outputText = `${day[0]} ${months[monthIndex[0]]}`,
			     outputText2 = `${day[1]} ${months[monthIndex[1]]}`;

             // Выводим результат
             document.querySelector('footer > ul li span:nth-child(1)').innerHTML = outputText;
			 document.querySelector('footer > ul li span:nth-child(3)').innerHTML = outputText2;
}

function specialAddTime(){
	                  
	let  serviceQuery = JSONQuery({
			special_time:{
				start: document.getElementById('start').value + ' ' + document.getElementById('timesFrom').value + ':00',
				end: document.getElementById('end').value + ' ' + document.getElementById('timesTo').value + ':00'
			},
			enable_production_calendar: !document.getElementById('calendar').checked ? false : true,
			enable_timezone: !document.getElementById('timezone').checked ? false : true
	});
	
	fetch('/back/TRUE', { method: 'POST', body: serviceQuery})
		.then(response => response.json)
		.then(data => {
			window.location.reload(true);
		});
		
	return false;
}

function workAddTime(cmd, index) {
    switch (cmd) {
        case 'special':
            let currentItem = document.querySelectorAll('.list-group li')[index];

            if (currentItem.getAttribute('data-item')) {
                // Ваша логика здесь
            }
            break;

        case 'work':
            let currentItem2 = document.querySelectorAll('.list-group li')[index];

            if (currentItem2.getAttribute('data-item')) {
                // Ваша логика здесь
            } else {
                let newWork = {
                    work_time: {},
                    enable_production_calendar: document.getElementById('calendar').checked ? true : false,
                    enable_timezone: document.getElementById('timezone').checked ? true : false
                };

                var selectedDays = currentItem2.querySelectorAll('span nav a');

                for (let j = 0; j < selectedDays.length; j++) {
                    // Используем querySelector для получения элементов по имени
                    var isSelected = selectedDays[j].querySelector('input[name="status"]').value === 'true';
                    var getSelectedDay = selectedDays[j].querySelector('input[name="day"]').value;

                    if (isSelected) {
                        newWork.work_time = {
                            day: getSelectedDay,
                            start_time: currentItem2.querySelector('input[name="timesFromHome"]').value + ':00',
                            end_time: currentItem2.querySelector('input[name="timesToHome"]').value + ':00'
                        };
                    }
                }
				
				fetch('/back/FALSE', {
				                    method: 'POST', 
				                    body: JSONQuery(newWork)
				})
				.then(response => response.json())
				.then(data => {
				                    window.location.reload(true);
				});
                
            }
            break;
    }
}

function selectDay(event, index, subIndex) {
    event.preventDefault(); // Предотвращаем переход по ссылке

    // Получаем элемент списка по индексу (li)
    let currentItem = document.querySelectorAll('.list-group li')[index];

    // Проверяем, нашли ли мы элемент списка
    if (!currentItem) {
        console.error(`Item at index ${index} not found`);
        return;
    }

    // Получаем всех дней (ссылки) в текущем элементе списка
    let dayLinks = currentItem.querySelectorAll('nav a');

    // Проверяем, есть ли необходимые ссылки
    if (subIndex >= dayLinks.length) {
        console.error(`Day at subIndex ${subIndex} not found in item ${index}`);
        return;
    }

    // Получаем текущую ссылку (день) по subIndex
    let currentDay = dayLinks[subIndex];

    // Получаем статус элемента input
    let statusInput = currentDay.querySelector('input[name="status"]');

    // Проверяем, существует ли statusInput
    if (!statusInput) {
        console.error(`Status input not found in current day`);
        return;
    }

    // Логика работы с классами и статусом
    if (currentDay.classList.contains('selected')) {
        currentDay.classList.remove('selected');
        currentDay.classList.add('default');
        statusInput.value = 'false'; // Устанавливаем статус в false
    } else {
        // Снимаем статус со всех ссылок в текущем элементе
        dayLinks.forEach(link => {
            link.classList.remove('selected');
            link.classList.add('default');
            let input = link.querySelector('input[name="status"]');
            if (input) input.value = 'false';
        });

        currentDay.classList.remove('default');
        currentDay.classList.add('selected');
        statusInput.value = 'true'; // Устанавливаем статус в true
    }

    return false; // Возвращаем false, если это нужно
}

function closeModal(){
	window.location.reload(true);
}

         // Логика удаления элемента
function deleteItem(index){
	let currentDelete = document.querySelectorAll('.list-group li')[index];
	
	if(currentDelete.getAttribute('data-item')){
		fetch('/back/NULL', {method: "POST", body: JSONQueryById(currentDelete.getAttribute('data-item'))})
			.then(response => response.json)
			.then(data => {
				currentDelete.remove();
			});
	}
	else{ currentDelete.remove(); }
}


