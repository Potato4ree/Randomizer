jQuery(document).ready(function($){

	$(".randomize_form").submit(function (e) {
		e.preventDefault();

		var res;

		// Ограничиваем сумму введенных чисел в полях до 100

	    $.each($(".randomize_form input[type=number]"), function (index, element) {

	        var total = 0;

	        $.each($(".randomize_form input[type=number]").not($(element)), function (innerIndex, innerElement) {

	            total += parseInt($(innerElement).val());

	        });

	        if ($(element).val() > 100 - total) {
				e.preventDefault();

	            alert("Подожди секундочку. Это работает немного не так. Задумка такова: максимальная сумма всех процентов должна быть не больше 100.");

	            res = false; // если сумма > 100, останавливаем скрипт

	            return false;

	        } else {
	        	e.preventDefault();

	            $(element).attr("max", 100 - total);

	        	res = true; 
	        }
	    });

	    if(res != false){ // если меньше, то запускаем Ajax

	    	var data = $(this).serializeArray(); // собираем данные формы в массив

		   	$.ajax({
	          	type: 'POST',
	          	dataType: 'json',
	          	url: MyAjax.ajaxurl,
	        	data: { 
	                'action': 'ajaxRandomize',
	                'form' : data // отправляем данные в php - обработчик
		        },
		          	success: function(response){

		              	$('.randomize_form').html('<p class="result">'+response+'</p>'); //Все работает - выводим результат

		          	},
		          	error: function(jqXHR, textStatus){

		          		alert('Что-то пошло не так, попробуй еще раз позже.'); // Не работает - выдаем ошибку

	            		console.log( "Request failed: " + textStatus );

		          	}
		    });
        

        }

	});


});
