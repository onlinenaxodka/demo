<?php

$vendor = test_request($ware->vendor) ?: '-';

$template_uk = array();
$template_ru = array();
$template = array();

foreach ($ware->groups->group as $group) {
	
	foreach ($group->items->item as $item) {
		
		$item_title = test_request($item->title);
		$item_type = test_request($item->type);

		$item_value = '-';
		$item_value_arr = array();

		if ($item_type == 'TYPE_SET') {

			if ($item->values) {

				if ($item->values->option) {

					foreach ($item->values->option as $option) {
						 
						$item_value_arr[] = test_request($option->value) . ' ' . test_request($option->help);

					}

				} elseif ($item->values->value) {

					foreach ($item->values->value as $value) {
						 
						$item_value_arr[] = test_request($value);

					}

				}

				$item_value = implode(', ', $item_value_arr);

			}

		} elseif ($item_type == 'TYPE_FLOAT') {
			
			if ($item->value) {
				$item_format = str_replace(' %', '%%', $item->format);
				$item_value = sprintf(test_request($item_format), test_request($item->value));
			}

		} elseif ($item_type == 'TYPE_STRING') {
			
			if ($item->value) {

				$item_value = test_request($item->value);
				
			}
			
		} elseif ($item_type == 'TYPE_BOOLEAN') {
			
			if ($item->value) {

				switch ($item->value) {
					case '0':
						$item_value = 'отсутствует';
						break;
					case '1':
						$item_value = 'есть';
						break;
					default:
						$item_value = 'отсутствует';
						break;
				}
				
			}
			
		}

		$template_uk[$item_title] = $item_value;
		$template_ru[$item_title] = $item_value;

	}

}

$template_uk['Виробник'] = $vendor;
$template_ru['Производитель'] = $vendor;

$template_uk['Ширина (см)'] = test_request($ware->ercWidth);
$template_ru['Ширина (см)'] = test_request($ware->ercWidth);

$template_uk['Висота (см)'] = test_request($ware->ercHeight);
$template_ru['Высота (см)'] = test_request($ware->ercHeight);

$template_uk['Глибина (см)'] = test_request($ware->ercLength);
$template_ru['Глубина (см)'] = test_request($ware->ercLength);

$template_uk['Вага (кг)'] = test_request($ware->ercWeight);
$template_ru['Вес (кг)'] = test_request($ware->ercWeight);

$template_uk["Об'єм (m3)"] = test_request($ware->ercVolume);
$template_ru['Объем (m3)'] = test_request($ware->ercVolume);

$template['uk'] = $template_uk;
$template['ru'] = $template_ru;

$template = json_encode($template, JSON_UNESCAPED_UNICODE);
$template = str_replace("'", "\'", $template);

?>