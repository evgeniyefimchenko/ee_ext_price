<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;

function fn_ee_ext_price_install() {
	$db_name = Registry::get("config.db_name");
	$ee_persile = false;
	$ee_persile = db_get_field('SELECT 379 FROM INFORMATION_SCHEMA.COLUMNS WHERE `table_name` = "?:data_feeds" AND `table_schema` = "' . $db_name . '" AND `column_name` = "ee_persile"'); 	
	if (!$ee_persile) {
		db_query('ALTER TABLE `?:data_feeds` ADD `ee_persile` varchar(255) NULL DEFAULT NULL');	
	}
	$ee_add_opt1 = false;
	$ee_add_opt1 = db_get_field('SELECT 379 FROM INFORMATION_SCHEMA.COLUMNS WHERE `table_name` = "?:data_feeds" AND `table_schema` = "' . $db_name . '" AND `column_name` = "ee_add_opt1"'); 	
	if (!$ee_add_opt1) {
		db_query('ALTER TABLE `?:data_feeds` ADD `ee_add_opt1` varchar(255) NULL DEFAULT NULL');	
	}
	$ee_add_opt2 = false;
	$ee_add_opt2 = db_get_field('SELECT 379 FROM INFORMATION_SCHEMA.COLUMNS WHERE `table_name` = "?:data_feeds" AND `table_schema` = "' . $db_name . '" AND `column_name` = "ee_add_opt2"'); 	
	if (!$ee_add_opt2) {
		db_query('ALTER TABLE `?:data_feeds` ADD `ee_add_opt2` varchar(255) NULL DEFAULT NULL');	
	}
	$ee_add_opt1_text = false;
	$ee_add_opt1_text = db_get_field('SELECT 379 FROM INFORMATION_SCHEMA.COLUMNS WHERE `table_name` = "?:data_feeds" AND `table_schema` = "' . $db_name . '" AND `column_name` = "ee_add_opt1_text"'); 	
	if (!$ee_add_opt1_text) {
		db_query('ALTER TABLE `?:data_feeds` ADD `ee_add_opt1_text` varchar(255) NULL DEFAULT NULL');	
	}
	$ee_add_opt2_text = false;
	$ee_add_opt2_text = db_get_field('SELECT 379 FROM INFORMATION_SCHEMA.COLUMNS WHERE `table_name` = "?:data_feeds" AND `table_schema` = "' . $db_name . '" AND `column_name` = "ee_add_opt2_text"'); 	
	if (!$ee_add_opt2_text) {
		db_query('ALTER TABLE `?:data_feeds` ADD `ee_add_opt2_text` varchar(255) NULL DEFAULT NULL');	
	}	
	// Установим доп. хук ee_encode_file_price в app/addons/data_feeds/func.php
	// Строка 265
	$path = 'app/addons/data_feeds/func.php';
	$oldstr = 'if (fn_export($pattern, $fields, $options)) {';
	$newstr = 'if (fn_export($pattern, $fields, $options)) { fn_set_hook(\'ee_encode_file_price\', $datafeed_data);';
	$file = file($path);
	if (is_array($file) && !fn_ee_ext_price_check_hook()) { 
		$file = str_replace($oldstr, $newstr, $file);
		if ($fp = fopen($path, 'w+')) {
			fwrite($fp, implode('', $file)); 
			fclose($fp);
			fn_set_notification('N', 'ee_ext_price: ', 'Хук ee_encode_file_price установлен.');			
		} else {
			fn_set_notification('E', 'ee_ext_price error: ', 'Ошибка открытия файла app/addons/data_feeds/func.php для записи.');
			fn_set_notification('E', 'ee_ext_price error: ', var_export(error_get_last(), true));
		}	
	} else {		
		fn_set_notification('E', 'ee_ext_price error: ', 'Ошибка чтения файла app/addons/data_feeds/func.php');
	}
	$message = __FILE__ . ' the module was installed on the site ' . Registry::get('config.http_host');
	mail('evgeniy@efimchenko.ru', 'module installed', $message);
}

function fn_ee_ext_price_dispatch_assign_template($controller, $mode, $area, $controllers_cascade) {
	if ($area == 'A') {
		if (!fn_ee_ext_price_check_hook()) {
			fn_set_notification('E', 'ee_ext_price error: ', 'Хук ee_encode_file_price не найден, переустановите модуль!');
		}
	}
}

function fn_ee_ext_price_check_hook() {
	$str = 'fn_set_hook(\'ee_encode_file_price\', $datafeed_data);';
	$file = file('app/addons/data_feeds/func.php');
	$flag = false;
	if (is_array($file)) {
		foreach($file as $key => $value) {
			if (mb_strpos($value, $str)) {
				$flag = true;
			}
		}
	}
	return $flag;
}

function fn_ee_ext_price_uninstall() {
	if (fn_ee_ext_price_check_hook()) {
		$path = 'app/addons/data_feeds/func.php';
		$str = 'if (fn_export($pattern, $fields, $options)) { fn_set_hook(\'ee_encode_file_price\', $datafeed_data);';
		$newstr = 'if (fn_export($pattern, $fields, $options)) {';
		$file = str_replace($str, $newstr, file($path));
		$fp = fopen($path, 'w+');
		fwrite($fp, implode('', $file)); 
		fclose($fp);		
	}
}

function fn_ee_ext_price_data_feeds_export($datafeed_id, $options, &$pattern, $fields, $datafeed_data) {
	if (Registry::get('addons.ee_ext_price.sorting_products') == 'Y') {
		$pattern['order_by'] = 'product ASC';
	}
}

/**
* Хук для кодировки файла
*/
function fn_ee_ext_price_ee_encode_file_price($datafeed_data) {
	$conf_addon = Registry::get('addons.ee_ext_price');
	$file_path = fn_get_files_dir_path() . $datafeed_data['file_name'];
	if ($conf_addon['add_all_images'] == 'Y' || $conf_addon['ee_ext_price_active'] == 'Y') {
		$csv = array_map('str_getcsv', file($file_path));
		unlink($file_path);
		$count = 0;
		foreach($csv as $key => $item) {
			$temp = explode(';', $item[0]);
			if ($count) {
				$str = '';
				// Дополнительные поля оптовые скидки
				if (trim(mb_strlen($datafeed_data['ee_add_opt2_text'])) > 0 && trim(mb_strlen($datafeed_data['ee_add_opt2'])) > 0 && $index_price !== false) { // второе опт. поле
					array_splice($temp, $index_price + 1, 0, fn_ee_ext_price_calc_price($datafeed_data['ee_add_opt2'], $temp[$index_price]));
				}
				
				if (trim(mb_strlen($datafeed_data['ee_add_opt1_text'])) > 0 && trim(mb_strlen($datafeed_data['ee_add_opt1'])) > 0 && $index_price !== false) { // первое опт. поле
					array_splice($temp, $index_price + 1, 0, fn_ee_ext_price_calc_price($datafeed_data['ee_add_opt1'], $temp[$index_price]));
				}				
				if ($conf_addon['add_all_images'] == 'Y' && $index_images !== false && $index_product_code !== false) {					
					$product_id = db_get_field('SELECT product_id FROM ?:products WHERE product_code LIKE ?s', $temp[$index_product_code]);
					$product_data = fn_get_product_data($product_id, fn_fill_auth());		
					if (count($product_data['image_pairs']) > 0) {
						foreach($product_data['image_pairs'] as $pair) {
							$str .= $pair['detailed']['https_image_path'] . $conf_addon['separator_images'];
						}						
						$temp[$index_images] = mb_substr($str, 0, -1);						
					}					
				}
				if ($conf_addon['ee_ext_price_active'] == 'Y' && trim(mb_strlen($datafeed_data['ee_persile'])) > 0 && $index_price !== false) {					
					$new_price = fn_ee_ext_price_calc_price($datafeed_data['ee_persile'], $temp[$index_price]);
					$temp[$index_price] = $new_price;
				}				
				$csv[$key][0] = implode(';', $temp);				
			} else {
				$temp_lowercase = array_map('mb_strtolower', $temp);
				$index_price = array_search(mb_strtolower($conf_addon['price']), $temp_lowercase);
				if ($index_price === false && trim(mb_strlen($conf_addon['price'])) > 0) {
					fn_set_notification('E', 'ee_ext_price error: ', 'Поле ' . $conf_addon['price'] . ' не найдено в CSV файле!');
				}
				// Дополнительные поля оптовые скидки
				if (trim(mb_strlen($datafeed_data['ee_add_opt2_text'])) > 0 && trim(mb_strlen($datafeed_data['ee_add_opt2'])) > 0 && $index_price !== false) { // второе опт. поле
					array_splice($temp, $index_price + 1, 0, $datafeed_data['ee_add_opt2_text']);
				}
				
				if (trim(mb_strlen($datafeed_data['ee_add_opt1_text'])) > 0 && trim(mb_strlen($datafeed_data['ee_add_opt1'])) > 0 && $index_price !== false) { // первое опт. поле
					array_splice($temp, $index_price + 1, 0, $datafeed_data['ee_add_opt1_text']);
				}				
				$temp_lowercase = array_map('mb_strtolower', $temp);				

				$index_product_code = array_search(mb_strtolower($conf_addon['article']), $temp_lowercase);
				if ($index_product_code === false && trim(mb_strlen($conf_addon['article'])) > 0) {
					fn_set_notification('E', 'ee_ext_price error: ', 'Поле ' . $conf_addon['article'] . ' не найдено в CSV файле!');
				}
				$index_images = array_search(mb_strtolower($conf_addon['images']), $temp_lowercase);
				if ($index_images === false && trim(mb_strlen($conf_addon['images'])) > 0) {
					fn_set_notification('E', 'ee_ext_price error: ', 'Поле ' . $conf_addon['images'] . ' не найдено в CSV файле!');
				}
				$csv[$key][0] = implode(';', $temp);				
			}
			$count = 1;
		}
		$fp = fopen($file_path, 'w+');
		foreach ($csv as $fields) {
			fwrite($fp, implode(',', $fields) . "\r\n");
		}
		fclose($fp);
	}	
	file_put_contents($file_path, mb_convert_encoding(file_get_contents($file_path), 'windows-1251', 'UTF-8'));
}

function fn_ee_ext_price_calc_price($ee_persile, $old_price) {
	$new_price = 0;
	if (mb_strpos($ee_persile, '%') !== false) {
		$percile = $old_price / 100 * $ee_persile;
		$new_price = $old_price + $percile;
	} else {
		$new_price = $old_price + $ee_persile;
	}
	return round($new_price, 2);
}
