<?php

/*
Plugin Name: Randomizer!
Plugin URI: 
Description: This is a plugin for randomize!
Author: Kirill Egorov
Version: 1.0
Author URI: 
*/


define('RANDOMIZER_DIR', plugin_dir_path(__FILE__)); // где находится плагин


/* Подключаем стили и скрипты  */

function randomizer_scripts_styles() {
 
    wp_register_style( 'randomize-css', plugins_url( 'assets/css/randomize.css', __FILE__ ), array(), '', 'all' );  
    wp_enqueue_style('randomize-css');

 	wp_enqueue_script( 'randomize-js', plugins_url('assets/js/randomize.js', __FILE__), array( 'jquery' ), '1.0' , false );

 	wp_localize_script( 'randomize-js', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

}
add_action( 'wp_enqueue_scripts', 'randomizer_scripts_styles' );

/* Создаем верстку  */

function createFormRandomize(){

	$html = '<h2>Рандомные числа!</h2>';

	$html .= '	<form class="randomize_form">
					<div>
						<input type="text" name="first_value" placeholder="Значение" required>
						<input type="number" name="first_percent" placeholder="Процент(%)" min="1" max="100" required>
					</div>
					<div>
						<input type="text" name="first_value" placeholder="Значение" required>
						<input type="number" name="first_percent" placeholder="Процент(%)" min="1" max="100" required>
					</div>
					<div>
						<input type="text" name="first_value" placeholder="Значение" required>
						<input type="number" name="first_percent" placeholder="Процент(%)" min="1" max="100" required>
					</div>
					<div>
						<input type="text" name="first_value" placeholder="Значение" required>
						<input type="number" name="first_percent" placeholder="Процент(%)" min="1" max="100" required>
					</div>
					<input type="submit" name="submit">
				</form>';

	echo $html;
}

add_action('wp_ajax_ajaxRandomize', 'ajaxRandomize_callback');
add_action('wp_ajax_nopriv_ajaxRandomize', 'ajaxRandomize_callback');


/* Обрабатываем данные, полученные Ajax`ом  */

function ajaxRandomize_callback(){

	$info = $_POST['form']; // Получаем данные формы

		foreach ($info as $item) {  // Перебираем инпуты
			
			if ($item['name'] == 'first_percent') { // если инпут с процентами, то записываем в массив $percent
				$percent[] = (int) $item['value'];
			}

			if ($item['name'] == 'first_value') {	// если инпут со значениями, то записываем в массив $value
				$value[] = $item['value'];
			}

		}

	/* Получаем элементы по отдельности  */

	$percent1 = $percent[0];
	$percent2 = $percent[1];
	$percent3 = $percent[2];
	$percent4 = $percent[3];

	$value1 = $value[0];
	$value2 = $value[1];
	$value3 = $value[2];
	$value4 = $value[3];

	/* Задаем максимальное число диапазона */

	$max1 = $percent1 + $percent2;
	$max2 = $max1 + $percent3;
	$max3 = $max2 + $percent4;

	/* Получаем рандомное число */

	$rand = rand(1, 100);

	/* Считаем вероятность выпадения */

	if ($rand >= 1 && $rand <= $percent1) { 

		$result = 'И так... Выпало значение '. $value1 .'! <br> Шанс выпадения был ' . $percent1 .' %'; 

	} elseif ($rand > $percent1 && $rand <= $max1){

		$result = 'И так... Выпало значение '. $value2 .'! <br> Шанс выпадения был ' . $percent2 .'  %'; 

	} elseif ($rand > $max1 && $rand <= $max2){

		$result = 'И так... Выпало значение '. $value3 .'! <br> Шанс выпадения был ' . $percent3 .' %';

	} elseif ($rand > $max2 && $rand <= $max3){

		$result = 'И так... Выпало значение '. $value4 .'! <br> Шанс выпадения был ' . $percent4 .' %';

	} else {

		/* Если сумма введенных чисел < 100, то есть вероятность, что ничего не выпадет */ 

		$result = 'Хмм, кажется, ничего не выпало. <br> Попробуй еще!';

	}

	exit(json_encode($result)); // отдаем результат в форму

}