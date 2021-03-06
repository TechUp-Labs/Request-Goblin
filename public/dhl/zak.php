<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://xmlpitest-ea.dhl.com/XMLShippingServlet',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'<?xml version="1.0" encoding="utf-8"?>
<p:DCTRequest xmlns:p="http://www.dhl.com" xmlns:p1="http://www.dhl.com/datatypes" xmlns:p2="http://www.dhl.com/DCTRequestdatatypes" schemaVersion="2.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dhl.com DCT-req.xsd ">
  <GetQuote>
    <Request>
      <ServiceHeader>
        <MessageTime>2021-12-02T11:28:56.000-08:00</MessageTime>
        <MessageReference>1234567890123456789012345678901</MessageReference>
		<SiteID>v62_43rhe6HJTW</SiteID>
		<Password>6eX0ExFdwP</Password>
      </ServiceHeader>
	  <MetaData>
			<SoftwareName>3PV</SoftwareName>
			<SoftwareVersion>1.0</SoftwareVersion>
		</MetaData>
    </Request>
    <From>
      <CountryCode>CY</CountryCode>
      <Postalcode>1010</Postalcode>
    </From>
    <BkgDetails>
      <PaymentCountryCode>CY</PaymentCountryCode>
      <Date>2017-12-12</Date>
      <ReadyTime>PT10H21M</ReadyTime>
      <ReadyTimeGMTOffset>+01:00</ReadyTimeGMTOffset>
      <DimensionUnit>CM</DimensionUnit>
      <WeightUnit>KG</WeightUnit>
      <Pieces>
        <Piece>
          <PieceID>1</PieceID>
          <Height>100</Height>
          <Depth>50</Depth>
          <Width>70</Width>
          <Weight>66.0</Weight>
        </Piece>
      </Pieces> 
	  <PaymentAccountNumber>123456789</PaymentAccountNumber>
      <IsDutiable>Y</IsDutiable>
      <NetworkTypeCode>AL</NetworkTypeCode>		
	  <QtdShp>
		 <GlobalProductCode>P</GlobalProductCode>
	     <LocalProductCode>P</LocalProductCode>		
	  </QtdShp>
    </BkgDetails>
    <To>
      <CountryCode>GR</CountryCode>
      <Postalcode>18535</Postalcode>
    </To>
   <Dutiable>
      <DeclaredCurrency>EUR</DeclaredCurrency>
      <DeclaredValue>1.0</DeclaredValue>
    </Dutiable>
  </GetQuote>
</p:DCTRequest>
',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/xml'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
