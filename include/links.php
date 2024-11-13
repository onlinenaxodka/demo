<?php

$link = $_SERVER['PHP_SELF'];

switch ($link) {
	case '/account/index.php':
		$title_browser = $title_browser_dashboard;
		$title_content = $title_browser_dashboard;
		$request_file_name = 'dashboard';
		$main_menu_active[0] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard),
			'links' => array('/account/')
		);
		break;
	case '/account/goods.php':
		$title_browser = $title_browser_goods;
		$title_content = $title_browser_goods;
		$request_file_name = 'goods';
		$main_menu_active[1] = 'active';
		$linkstyle = '';
		$jquerylib = '<script src="/assets/js/clipboard.min.js"></script><script>new ClipboardJS(".btn-clipboard");</script>';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_goods),
			'links' => array('/account/', '/account/goods/')
		);
		break;
	case '/account/goods_export.php':
		$title_browser = $title_browser_goods_export;
		$title_content = $title_browser_goods_export;
		$request_file_name = 'goods_export';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_goods_export),
			'links' => array('/account/', '/account/goods_export/')
		);
		break;
	case '/account/orders.php':
		$title_browser = $title_browser_orders;
		$title_content = $title_browser_orders;
		$request_file_name = 'orders';
		$main_menu_active[2] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_orders),
			'links' => array('/account/', '/account/orders/')
		);
		break;
	case '/account/cart.php':
		$title_browser = $title_browser_cart;
		$title_content = $title_browser_cart;
		$request_file_name = 'cart';
		$main_menu_active[3] = 'active';
		$linkstyle = '<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">';
		$jquerylib = '<script src="/assets/js/jquery.inputmask.bundle.min.js"></script><script type="text/javascript">$(\'input\').inputmask();</script><script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_cart),
			'links' => array('/account/', '/account/cart/')
		);
		break;
	case '/account/school.php':
		$title_browser = $title_browser_school;
		$title_content = $title_browser_school;
		$request_file_name = 'school';
		$main_menu_active[4] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_school),
			'links' => array('/account/', '/account/school/')
		);
		break;
	case '/account/school_homework.php':
		$title_browser = $title_browser_school_homework;
		$title_content = $title_browser_school_homework;
		$request_file_name = 'school_homework';
		/*$main_menu_active[4] = 'active';*/
		$linkstyle = '';
		$jquerylib = '<script src="/assets/js/clipboard.min.js"></script><script>new ClipboardJS(".btn-clipboard");</script>';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_school, $breadcrumb_school_homework),
			'links' => array('/account/', '/account/school/', '/account/school_homework/')
		);
		break;
	case '/account/school_lessons.php':
		$title_browser = 'Инструкция';
		$title_content = 'Инструкция';
		$request_file_name = '';
		$main_menu_active[4] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'Инструкция'),
			'links' => array('/account/', '/account/school_lessons/')
		);
		break;
	case '/account/news.php':
		$title_browser = $title_browser_news;
		$title_content = $title_browser_news;
		$request_file_name = '';
		$main_menu_active[6] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_news),
			'links' => array('/account/', '/account/news/')
		);
		break;
	case '/account/support.php':
		$title_browser = $title_browser_support;
		$title_content = $title_browser_support;
		$request_file_name = 'support';
		$main_menu_active[5] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_support),
			'links' => array('/account/', '/account/support/')
		);
		break;
	case '/account/wallet.php':
		$title_browser = $title_browser_wallet;
		$title_content = $title_browser_wallet;
		$request_file_name = 'wallet';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_wallet),
			'links' => array('/account/', '/account/wallet/')
		);
		break;
	case '/account/add_funds.php':
		$title_browser = $title_browser_add_funds;
		$title_content = $title_browser_add_funds;
		$request_file_name = 'add_funds';
		$linkstyle = '';
		$jquerylib = '<script src="/assets/js/clipboard.min.js"></script><script>new ClipboardJS(".btn-clipboard");</script>';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_wallet, $breadcrumb_add_funds),
			'links' => array('/account/', '/account/wallet/', '/account/add_funds/')
		);
		break;
	case '/account/withdrawal.php':
		$title_browser = $title_browser_withdrawal;
		$title_content = $title_browser_withdrawal;
		$request_file_name = 'withdrawal';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_wallet, $breadcrumb_withdrawal),
			'links' => array('/account/', '/account/wallet/', '/account/withdrawal/')
		);
		break;
	case '/account/transactions.php':
		$title_browser = $title_browser_transactions;
		$title_content = $title_browser_transactions;
		$request_file_name = 'transactions';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_wallet, $breadcrumb_transactions),
			'links' => array('/account/', '/account/wallet/', '/account/transactions/')
		);
		break;
	case '/account/subscribers.php':
		$title_browser = $title_browser_subscribers;
		$title_content = $title_browser_subscribers;
		$request_file_name = 'subscribers';
		$linkstyle = '';
		$jquerylib = '<script src="/assets/js/clipboard.min.js"></script>';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_subscribers),
			'links' => array('/account/', '/account/subscribers/')
		);
		break;
	case '/account/partners.php':
		$title_browser = $title_browser_partners;
		$title_content = $title_browser_partners;
		$request_file_name = 'partners';
		$linkstyle = '<link rel="stylesheet" href="/assets/styles/jquery.treegrid.css">';
		$jquerylib = '<script src="/assets/js/clipboard.min.js"></script><script>new ClipboardJS(".btn-clipboard");</script><script type="text/javascript" src="/assets/js/jquery.treegrid.min.js"></script><script type="text/javascript">$(\'.table-partners\').treegrid({expanderExpandedClass: \'fa fa-minus-square\', expanderCollapsedClass: \'fa fa-plus-square\', initialState: \'collapse\'});</script>';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_partners),
			'links' => array('/account/', '/account/partners/')
		);
		break;
	case '/account/partners_work.php':
		$title_browser = $title_browser_partners_work;
		$title_content = $title_browser_partners_work;
		$request_file_name = 'partners_work';
		$linkstyle = '';
		$jquerylib = '<script src="/assets/js/clipboard.min.js"></script><script>new ClipboardJS(".btn-clipboard");</script>';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_partners_work),
			'links' => array('/account/', '/account/partners_work/')
		);
		break;
	case '/account/edit.php':
		$title_browser = $title_browser_user_edit;
		$title_content = $title_browser_user_edit;
		$request_file_name = 'user_edit';
		$linkstyle = '<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"><link rel="stylesheet" type="text/css" href="/assets/styles/slim.min.css">';
		$jquerylib = '<script src="/assets/js/jquery-ui-1.12.1.js"></script><script src="/assets/js/slim.kickstart.min.js"></script><script src="/assets/js/jquery.inputmask.bundle.min.js"></script><script type="text/javascript">$(\'input\').inputmask();</script><script>datepickerCall();</script>';
		/*<script src="//ulogin.ru/js/ulogin.js"></script>*/
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $breadcrumb_profile),
			'links' => array('/account/', '/account/edit/')
		);
		break;
	case '/account/faq.php':
		$title_browser = 'Вопросы и ответы';
		$title_content = 'Вопросы и ответы';
		$request_file_name = '';
		$main_menu_active[8] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'Вопросы и ответы'),
			'links' => array('/account/', '/account/faq/')
		);
		break;
	case '/account/privacy_policy.php':
		$title_browser = $title_browser_privacy_policy;
		$title_content = $title_browser_privacy_policy;
		$request_file_name = '';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, $title_browser_privacy_policy),
			'links' => array('/account/', '/account/privacy_policy/')
		);
		break;
	case '/account/terms.php':
		$title_browser = 'Правила использования';
		$title_content = 'Правила использования';
		$request_file_name = '';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'Правила использования'),
			'links' => array('/account/', '/account/terms/')
		);
		break;
	case '/account/provider_terms.php':
		$title_browser = 'Правила для поставщика';
		$title_content = 'Правила для поставщика';
		$request_file_name = '';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'Правила для поставщика'),
			'links' => array('/account/', '/account/provider_terms/')
		);
		break;
	case '/account/market_tools.php':
		$title_browser = 'Инструменты рекламы';
		$title_content = 'Инструменты рекламы';
		$request_file_name = 'market_tools';
		$linkstyle = '';
		$jquerylib = '<script src="/assets/js/clipboard.min.js"></script><script>new ClipboardJS(".btn-clipboard");</script>';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'Инструменты рекламы'),
			'links' => array('/account/', '/account/market_tools/')
		);
		break;
	case '/account/investor_club.php':
		$title_browser = 'Клуб инвесторов';
		$title_content = 'Клуб инвесторов';
		$request_file_name = 'investor_club';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'Клуб инвесторов'),
			'links' => array('/account/', '/account/investor_club/')
		);
		break;
	case '/account/goods_new.php':
		$title_browser = 'Новый каталог';
		$title_content = 'Новый каталог';
		$request_file_name = 'goods_new';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'Новый каталог'),
			'links' => array('/account/', '/account/goods_new/')
		);
		break;
	case '/account/shops.php':
		$title_browser = 'Интернет магазины';
		$title_content = 'Интернет магазины';
		$request_file_name = 'shops';
		$main_menu_active[7] = 'active';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'Интернет магазины'),
			'links' => array('/account/', '/account/shops/')
		);
		break;
	case '/account/mentor.php':
		$title_browser = 'Мой наставник';
		$title_content = 'Мой наставник';
		$request_file_name = '';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'Мой наставник'),
			'links' => array('/account/', '/account/mentor/')
		);
		break;
	case '/account/kripto_naxodka.php':
		$title_browser = 'KRIPTO NAXODKA';
		$title_content = 'KRIPTO NAXODKA';
		$request_file_name = '';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'KRIPTO NAXODKA'),
			'links' => array('/account/', '/account/kripto_naxodka/')
		);
		break;
	case '/account/storage_orders.php':
		$title_browser = 'Склад - замовлення';
		$title_content = 'Склад - замовлення';
		$request_file_name = '';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'Склад - замовлення'),
			'links' => array('/account/', '/account/storage_orders/')
		);
		break;
	case '/account/storage_goods.php':
		$title_browser = 'Склад - товари';
		$title_content = 'Склад - товари';
		$request_file_name = '';
		$linkstyle = '';
		$jquerylib = '';
		$breadcrumb = array(
			'names' => array($breadcrumb_dashboard, 'Склад - товари'),
			'links' => array('/account/', '/account/storage_goods/')
		);
		break;
}

?>