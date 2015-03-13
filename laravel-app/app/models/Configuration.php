<?php
class Configuration {
	//public static $wsdlWebService = "http://localhost:8080/axis2/services/EasyService?wsdl";
	public static $wsdlWebService = "http://192.168.168.107:8080/axis2/services/EasyService?wsdl";
	public static $webServiceToTxnProcesser = "http://192.168.168.107:3000/api/";
	//public static $webServiceToEasyAPI = "http://192.168.168.107:3001/";
	public static $webServiceToPrint = "http://192.168.168.107:3004/api/";
	public static $webServiceToPrintCancel = "http://192.168.168.107:3004/api/";
	public static $webServiceCheckPayout = "http://192.168.168.107:3004/api/";
	public static $webServicePostReport = "http://192.168.168.107:3008/api/";

	//--------------easy api key 3000--------------------------------------------
	public static $apiKey = "fUNKE6ux3xGNJ6g338RFt";
	public static $apiSecret = "hy84nZJ8PUfDs2VUKVTjH4gkYZFemG7Utf4xANyc";
	
	//--------------ptu api key 3001--------------------------------------------
	public static $ptuApiKey = "bZvZ757FcefdRAg8NKYAZ";
	public static $ptuApiSecret = "nRAySnEJY4N6UuWmwqrZLafKSpPAcALxTfjvRKVz";
	
	//-----------local sosvan--------------
	//public static $webServiceToTxnProcesser = "http://192.168.168.59:3000/api/";
	public static $webServiceToEasyAPI = "http://192.168.168.21:3001/";
}
?>