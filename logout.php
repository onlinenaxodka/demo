<?php

session_start();

if (isset($_SESSION['user'])) unset($_SESSION['user']);

if (isset($_SESSION['cart'])) unset($_SESSION['cart']);
if (isset($_SESSION['filter_goods_admin'])) unset($_SESSION['filter_goods_admin']);
if (isset($_SESSION['filter_goods_visits'])) unset($_SESSION['filter_goods_visits']);
if (isset($_SESSION['filter_goods_homework'])) unset($_SESSION['filter_goods_homework']);
if (isset($_SESSION['search_user'])) unset($_SESSION['search_user']);
if (isset($_SESSION['filter_user'])) unset($_SESSION['filter_user']);
if (isset($_SESSION['last_time_request_pb'])) unset($_SESSION['last_time_request_pb']);
if (isset($_SESSION['breadcrumb'])) unset($_SESSION['breadcrumb']);
if (isset($_SESSION['category_tmp'])) unset($_SESSION['category_tmp']);
if (isset($_SESSION['partner_id'])) unset($_SESSION['partner_id']);
if (isset($_SESSION['user_selected'])) unset($_SESSION['user_selected']);
if (isset($_SESSION['lang'])) unset($_SESSION['lang']);
if (isset($_SESSION['domen_mail'])) unset($_SESSION['domen_mail']);
if (isset($_SESSION['order_filter'])) unset($_SESSION['order_filter']);

if (isset($_GET['remind'])) {

	header('Location: /remind/');
	exit;

} else {

	header('Location: /');
	exit;

}

?>