<?php

header('Content-Type: text/html; charset=utf-8');

include_once __DIR__ . '/../../../config.php';

/*require_once __DIR__ . '/../../../include/libs/Excel/vendor/autoload.php';

$path = __DIR__ . '/../../files/xml_providers/himoto/import_excel/categories.xlsx';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (file_exists($path)) {

	$oReader = new Xlsx();
	//$oReader = IOFactory::createReaderForFile($sFile);

	$oSpreadsheet = $oReader->load($path);
	$oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();

	$categories_arr = array();

	for ($iRow = 2; $iRow <= $oCells->getHighestRow(); $iRow++) {

		$oCell = $oCells->get('C'.$iRow);
		if ($oCell) $oCell->getValue();
		$offer_category_id = test_request($oCell->getValue());
		$offer_category_id = intval($offer_category_id);

		$oCell = $oCells->get('D'.$iRow);
		if ($oCell) $oCell->getValue();
		$linkname = test_request($oCell->getValue());

		//$categories_arr[] = array($offer_category_id, $linkname);
		echo 'array('.$offer_category_id.', \''.$linkname.'\'),<br>';
	}

}*/

$categories_arr = array(
array(50, 'nanoquadrocopters45'),
array(51, 'miniquadrocopters5656'),
array(52, 'midquadrocopters67686'),
array(53, 'dxfhgfjkjh'),
array(57, 'dfgfthfgjyf'),
array(58, 'dfgfhgfjfg'),
array(59, 'xdfhfghjghkg'),
array(62, 'dxfhfghgffgjtj'),
array(63, 'fghghjghgh'),
array(64, 'dxfhfgjgh'),
array(65, 'dfgfhfggfhj'),
array(66, 'fhjghkgfhf'),
array(67, 'dfhfghjfhjgh'),
array(68, 'dgfdghfgjgfj'),
array(471, 'xdfghfghjghlhjk'),
array(37, 'dfhgjhgk'),
array(38, 'fhgfjghjh'),
array(463, 'fghghjghhgk'),
array(46, 'dfghfghfgfg'),
array(47, 'dfhfgjgjghkgy'),
array(78, 'fgjfgkhl'),
array(79, 'xfhghjhgkhj'),
array(80, 'dgfdhdrthrthttrh'),
array(86, 'dfhfghjghjghk'),
array(87, 'dfghfgjgghk'),
array(88, 'dfgghfgfgjfggfhk'),
array(90, 'dfghfghfgjghkhk'),
array(91, 'dfghfytyujgjgy'),
array(92, 'dfgrthtrhrthrt'),
array(3, 'dfgfdghfghfgj'),
array(6, 'fhgfjfgjfgjfg'),
array(7, 'dfghfgjfggh'),
array(8, 'dfhjghgfjgfhkfh'),
array(9, 'fghgfjfgjghjgf'),
array(516, 'fghjgghkylu'),
array(114, 'dfhfyjfytykty'),
array(113, 'fhgjgjgh'),
array(11, 'ghjghkhgkhgj'),
array(12, 'xfhfgfgnfgnfgnf'),
array(13, 'fhgjghghkh'),
array(14, 'fghgjghjghkghk'),
array(15, 'dfhfgjfghk'),
array(16, 'xdfhgfjgh'),
array(496, 'xfxhgfhgfjgfjfgj'),
array(437, 'cfgjgfjgfjtfj'),
array(19, 'cfghgjfgjghgh'),
array(20, 'dfhfgjgfjgh'),
array(21, 'xdfghfgjghgh'),
array(22, 'fgjhghihgkghk'),
array(23, 'dtrhtrhth'),
array(24, 'dgfhfghfghf'),
array(25, 'fhggfjgjgh'),
array(29, 'hfghgfjgfhjg'),
array(30, 'dffhgghghjgh'),
array(31, 'dfcfghgfjgf'),
array(32, 'dfghghgjgh'),
array(519, 'fghgjghjhjg'),
array(95, 'dfgfhgfhgf'),
array(96, 'fhgfghfghfg'),
array(97, 'fxhfghfghfg'),
array(436, 'dffghgfhfghf'),
array(99, 'fgfghfgfg'),
array(100, 'fhfggfghfghgf'),
array(98, 'hgjghkhertyyuuiio'),
array(540, 'fghghjgul'),
array(541, 'fcghghjgh'),
array(103, 'fhfghghfgjgh'),
array(104, 'fhgjhgjghjghjgh'),
array(433, 'fghjghkjhgkj'),
array(108, 'dfghfghgfjgh'),
array(524, 'dfgfhfhfg'),
array(106, 'fhgjghjgjhg'),
array(110, 'fdbhcfgjggfhjgh'),
array(111, 'xfghcgfjfgjg'),
array(506, 'peltpensforbabies5676767'),
array(499, 'feltpensaremagic'),
array(501, 'dfhgfj'),
array(500, 'paintinganddecoration'),
array(502, 'stencils687687'),
array(503, 'fingerpaints6765768'),
array(504, 'watercolorpaints565756'),
array(505, 'childrensnailpolishes5756'),
array(483, 'fhgfjhgkhjlj67787878'),
array(403, 'ghmhjj9090909090909'),
array(461, 'cartracks456456'),
array(402, 'fhfghfghfg46456565645645'),
array(507, 'sdgfdghfghfgjfg'),
array(508, 'fhfjghjgjjyyj567667867867867'),
array(509, 'dfghfhfghhfg7567567567567567566756'),
array(510, 'fhfhfjtt'),
array(414, 'dfgfdhfghgffg'),
array(411, 'fghfghgfhghfghr766867867867'),
array(512, 'dxfghdfghfdghfg'),
array(537, 'dxfghdfghfdghfg'),
array(514, 'fbhgfhgh'),
array(538, 'dfgfgghfghfghg'),
array(539, 'nightoys565756'),
array(535, 'fhfghfghfgh'),
array(513, 'fhfghfghfgh'),
array(531, 'childrensmusicalinstruments5566756'),
array(532, 'childrensmusicalinstruments5566756'),
array(530, 'childrensmusicalinstruments5566756'),
array(416, 'klkl6576876679789'),
array(429, '45657568678789890dfhghjghj'),
array(430, 'we6567867898'),
array(431, 'w4565467689870988'),
array(543, 'rdtyththtyhty567676767'),
array(399, 'dolls5676756'),
array(400, 'dolls5676756'),
array(439, '45655756fghfgnfghnfgnfgnfgnfg'),
array(440, '4567876gvnvhgvhghnghnghn'),
array(117, '4e5676867fgghnghngh'),
array(118, 'dxfghfghfghgf567868789978'),
array(119, '345464656756hhhjhjmhmhmhmhmhjm'),
array(120, 'e4e456756867987gjghkhhgkhj'),
array(121, '34564565fghnhfghfghfghfgjf'),
array(122, '4565756756756fhfgjgfjghjgh'),
array(123, '45656756fghfgfghfghfghfghfghf'),
array(125, '4565656756ccgbfgbfgbfgbf'),
array(126, '456565756jghhjghjghjghjgh'),
array(127, 'fcghgfjghhjghjghjgh'),
array(129, 'dffhfghfhfghfghfre7677676756'),
array(130, 'dxfggfhfggh3w45657657675'),
array(131, 'batteries4657'),
array(133, 'xvjhghhge546656768768'),
array(134, 'fghjghjghjgh5476867867'),
array(135, 'dffghjghkhr55678697809'),
array(136, 'fhgfgjgjhjghw43565465465'),
array(137, 'fhfghfghgfhgf65768678678786'),
array(140, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(141, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(142, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(143, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(144, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(145, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(146, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(148, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(149, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(491, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(152, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(153, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(154, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(156, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(157, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(158, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(159, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(160, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(488, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(489, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(162, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(163, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(164, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(417, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(167, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(168, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(170, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(171, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(172, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(418, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(174, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(410, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(493, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(175, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(177, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(178, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(181, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(182, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(183, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(184, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(185, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(186, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(187, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(188, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(189, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(191, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(192, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(193, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(194, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(195, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(196, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(198, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(199, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(201, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(202, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(203, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(206, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(207, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(208, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(492, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(210, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(211, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(212, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(214, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(215, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(216, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(217, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(220, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(221, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(222, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(223, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(224, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(226, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(227, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(228, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(229, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(230, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(231, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(232, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(434, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(234, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(479, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(460, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(237, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(238, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(239, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(240, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(241, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(242, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(244, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(245, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(246, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(247, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(248, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(249, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(250, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(252, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(253, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(254, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(478, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(257, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(477, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(258, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(259, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(490, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(261, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(262, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(263, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(264, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(265, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(267, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(268, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(441, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(271, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(272, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(273, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(425, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(472, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(275, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(276, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(277, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(278, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(280, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(470, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(283, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(279, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(281, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(282, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(467, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(468, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(469, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(422, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(423, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(424, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(442, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(464, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(476, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(286, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(287, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(288, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(289, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(290, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(291, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(292, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(293, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(294, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(295, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(296, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(297, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(298, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(299, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(300, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(301, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(303, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(304, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(305, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(306, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(307, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(308, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(309, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(310, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(311, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(312, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(313, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(314, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(315, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(316, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(317, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(318, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(319, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(320, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(321, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(322, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(323, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(324, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(325, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(326, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(327, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(328, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(329, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(330, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(331, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(332, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(333, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(334, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(404, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(405, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(406, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(407, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(408, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(409, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(428, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(455, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(456, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(457, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(458, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(459, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(473, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(474, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(475, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(480, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(517, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(518, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(337, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(338, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(339, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(340, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(341, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(342, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(343, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(344, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(345, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(346, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(347, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(426, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(349, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(350, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(351, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(352, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(353, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(356, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(357, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(358, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(359, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(360, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(361, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(362, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(363, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(364, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(365, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(427, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(367, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(368, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(369, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(370, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(371, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(372, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(373, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(374, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(375, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(376, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(377, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(378, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(379, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(380, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(381, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(382, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(383, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(384, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(385, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(386, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(443, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(444, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(445, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(446, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(447, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(448, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(449, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(450, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(451, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(452, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(453, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(454, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(544, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(545, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(546, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(551, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(550, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(549, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(548, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(547, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(389, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(390, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(391, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(392, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(393, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(394, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(395, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(465, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(397, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(466, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam'),
array(552, 'zaryadnie_ustroystva_i_aksessuari_k_radioupravlyaemim_modelyam')
);

$xml_file = 'https://distributions.com.ua/user_downloads/2abc734fa90b957671671989267c4b67/content_yml/content_yml.xml';

if (filter_var($xml_file, FILTER_VALIDATE_URL) !== false) {
	$headers = get_headers($xml_file, 1);
	if (stripos($headers[0], "200 OK")) {
		if (strpos($headers["Content-Type"], 'xml') !== false) {
			$exists_xml_file = true;
		} else {
			$exists_xml_file = false;
		}
	} else {
		$exists_xml_file = false;
	}
} else {
	$exists_xml_file = false;
}

	if ($exists_xml_file == true) {

	    $xml = simplexml_load_file($xml_file);

		foreach ($xml->shop->offers->offer as $offer) {

			$goods_vendor_id = test_request($offer['id']);

			$offer_category_id = test_request($offer->categoryId);
			$offer_category_id = intval($offer_category_id);

			foreach ($categories_arr as $category) {
				
				if ($category[0] == $offer_category_id) {

					$category_linkname = $category[1];

				} 

			}

//echo $category_linkname.'<br>';

			$sql = "SELECT * FROM `goods` WHERE `vendor_id`='{$goods_vendor_id}' AND `user_id`=407 LIMIT 1";
			$query = mysqli_query($db, $sql) or die(mysqli_error($db));
			$goods = mysqli_fetch_assoc($query);
			$count_goods = mysqli_num_rows($query);

			$goods_id = $goods['id'];
			
			if ($count_goods > 0) {

				$sql = "UPDATE `goods` SET `category`='{$category_linkname}', `updated`='{$current_date}' WHERE `id`='{$goods_id}' AND `user_id`=407";
				$query = mysqli_query($db, $sql) or die(mysqli_error($db));

			}

		}

	} else {

	    file_put_contents("../../files/xml_providers/himoto/himoto.log", date('Y-m-d H:i:s')." - Не удалось открыть файл ".$xml_file.".\n", FILE_APPEND | LOCK_EX);

	}

?>