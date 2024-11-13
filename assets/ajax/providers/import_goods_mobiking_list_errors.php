<?php

include_once __DIR__ . '/../../../config.php';

$xml_file = 'https://httpclient.mobiking.com.ua:9443/bb629b72bae94fa8bb2fbb55160cfef6_8a34d45fe7d64be78066d98e1ccd5599.xml';

//if (file_exists($xml_file)) {

	$xml = simplexml_load_file($xml_file);

	$top_kurs = test_request($xml->Курс);
	$top_kurs = floatval($top_kurs);

	echo 'Курс: '.$top_kurs.'<br><br>';

?>

<h2>Есть чтото недозаполнено в этих товарах</h2>
<table border="1" style="width: 100%; margin-bottom: 50px;">
	<thead>
		<tr>
			<th>№</th>
			<th>Код</th>
			<th>Наименование</th>
			<th>ОсновноеИзображение</th>
			<th>Остаток</th>
			<th>ЦенаЗакупки</th>
			<th>ЦенаРРЦ</th>
			<th>ДопОписание</th>
			<th>Свойства</th>
			<th>Категория товара</th>
			<th>Подкатегория товаров</th>
			<th>Производитель</th>
		</tr>
	</thead>
	<tbody>

<?

	$n=0;

	foreach ($xml->Item as $item) {

		$goods_vendor_id = test_request($item->Код);

		$goods_availability = test_request($item->Остаток);
		$goods_availability = intval($goods_availability);

		$price_purchase = test_request($item->ЦенаЗакупки);
		$price_purchase = floatval($price_purchase);
		$price_sale = test_request($item->ЦенаРРЦ);
		$price_sale = floatval($price_sale);

		foreach ($item->Свойства->ItemSv as $category) {

			if ($category['Value'] == 'Категория товара') $category_main = strval($category['Name']);
			if ($category['Value'] == 'Подкатегория товаров') $category_main_item = strval($category['Name']);

		}

		if (empty($item->ОсновноеИзображение) or $goods_availability <= 0 or $price_purchase <= 0 or $price_sale <= 0 or empty($item->ДопОписание) or empty($item->Свойства) or empty($category_main) or empty($category_main_item) or empty($item->Производитель)) {

			$n++;

?>

		<tr>
			<td><?=$n?></td>
			<td><?=$item->Код?></td>
			<td><?=$item->Наименование?></td>
			<td><?=$item->ОсновноеИзображение?></td>
			<td><?=$item->Остаток?></td>
			<td><?=$item->ЦенаЗакупки?></td>
			<td><?=$item->ЦенаРРЦ?></td>
			<td><?=$item->ДопОписание?></td>
			<td>
<?

			foreach ($item->Свойства->ItemSv as $category) echo '<b>'.$category['Value'].':</b> '.$category['Name'].'<br>';

?>
			</td>
			<td>
<?

			foreach ($item->Свойства->ItemSv as $category) if ($category['Value']=='Категория товара') echo $category['Name'];

?>
			</td>
			<td>
<?

			foreach ($item->Свойства->ItemSv as $category) if ($category['Value']=='Подкатегория товаров') echo $category['Name'];

?>
			</td>
			<td><?=$item->Производитель?></td>
		</tr>

<?

		}

	}

?>
	</tbody>
</table>

<?

//}

/*echo '<pre>';
print_r($categories);
echo '</pre>';*/

?>