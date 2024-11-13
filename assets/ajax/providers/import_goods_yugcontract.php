<?php

header('Content-Type: text/html; charset=utf-8');

$soap_auth = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" soap:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
   <soap:Body>
      <GetToken xmlns="https://soap.yugcontract.ua/API">
         <login xsi:type="xsd:string">user</login>
         <password xsi:type="xsd:string">password</password>
      </GetToken>
   </soap:Body>
</soap:Envelope>';
 
$ch = curl_init('https://soap.yugcontract.ua');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $soap_auth);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: text/xml; charset=utf-8',
	'SOAPAction: https://soap.yugcontract.ua/API#GetToken')
);

$result = curl_exec($ch);
curl_close($ch);

$clean_soap_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $result);
$soap_auth_xml = simplexml_load_string($clean_soap_xml);
$auth_hash = $soap_auth_xml->Body->GetTokenResponse->Session->hash;
$auth_session_id = $soap_auth_xml->Body->GetTokenResponse->Session->session_id;

$soap_GetCategories = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" soap:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
   <soap:Body>
      <GetCategories xmlns="https://soap.yugcontract.ua/API">
         <Session>
            <hash xsi:type="xsd:string">'.$auth_hash.'</hash>
            <session_id xsi:type="xsd:string">'.$auth_session_id.'</session_id>
         </Session>
      </GetCategories>
   </soap:Body>
</soap:Envelope>';

$ch = curl_init('https://soap.yugcontract.ua');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $soap_GetCategories);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: text/xml; charset=utf-8',
	'SOAPAction: https://soap.yugcontract.ua/API#GetCategories')
);

$result = curl_exec($ch);
curl_close($ch);

$clean_soap_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $result);
$soap_categories_xml = simplexml_load_string($clean_soap_xml);

$soap_GetContentGoods = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" soap:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
   <soap:Body>
      <GetContentGoods xmlns="https://soap.yugcontract.ua/API">
         <Session>
            <hash xsi:type="xsd:string">'.$auth_hash.'</hash>
            <session_id xsi:type="xsd:string">'.$auth_session_id.'</session_id>
         </Session>
         <Content>
            <content_type xsi:type="xsd:string">suggested</content_type>
            <content_id xsi:type="xsd:int">7</content_id>
         </Content>
      </GetContentGoods>
   </soap:Body>
</soap:Envelope>';

$ch = curl_init('https://soap.yugcontract.ua');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $soap_GetContentGoods);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: text/xml; charset=utf-8',
	'SOAPAction: https://soap.yugcontract.ua/API#GetContentGoods')
);

$result = curl_exec($ch);
curl_close($ch);

$clean_soap_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $result);
$soap_offers_xml = simplexml_load_string($clean_soap_xml);

//echo $result;

include_once __DIR__ . '/../../../config.php';
include_once __DIR__ . '/../../../include/libs/classSimpleImage.php';

include_once __DIR__ . '/yugcontract/yml_catalog.php';

$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
$time = number_format($time, 4, '.', '');
echo $time." seconds\n";

?>