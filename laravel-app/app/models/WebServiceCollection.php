<?php

class WebServiceCollection  {
	public static function uploadPin($data,$fileUrl) {
		$response = array();
		$msgs = array();
		$res = null;
		try {
			$title = $data['title'];
			$operatorId = $data['operator__id'];
			$remark = $data['remark'];
			$staffId = $data['staff__id'];
			
			$command  = "curl  ".Configuration::$webServiceToEasyAPI."ptu/pin/ -F \"avatar=@".$fileUrl."\" -F \"title=".$title."\" -F \"remark=".$remark."\" -F \"operator__id=".$operatorId."\" -F \"staff__id=".$staffId."\" "."-H \"Api-Key: bZvZ757FcefdRAg8NKYAZ\" -H \"Api-Secret: nRAySnEJY4N6UuWmwqrZLafKSpPAcALxTfjvRKVz\"";
			//echo $command;
			//die();
			$results = exec($command);
			$obJson = json_decode($results);		
			echo $results;
			echo $obJson->message;
			
			if ($obJson->status) {
				$response['status'] = 'T';
				
				$faces = $obJson->data->faces;
				
				foreach ($faces as $key => $value) {
					$m = "Upload pin : face=".$value->face." , mumber=".$value->value;
					$msg = array('type'=>'success','msg'=>$m);
					array_push($msgs,$msg);
				}
				
				$response['msgs'] = $msgs;
			} else {
				$response['status'] = 'F';
				$msg = array('type'=>'error','msg'=>$obJson->message);
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
			}
			return $response;
		} catch (Exception $e) {
			$msg = array('type'=>'error','msg'=>'Error Exeption'.$e);
			array_push($msgs,$msg);
			$response['status'] = 'F';
			$response['msgs'] = $msgs;
			
			return $response;
		}
	}


	public static function uploadPinOld($data,$fileUrl) {
		$response = array();
		$msgs = array();
		
		//$data = array('type'=>'game','currency'=>'KHR','did'=>'11111111','amount'=>'200','gateway'=>'web');
		//$data = array('type'=>$type,'currency'=>$currency,'did'=>$did,'amount'=>$amount,'gateway'=>$gatetway);
		$res = null;
		//$client = new GuzzleHttp\Client();
		try {
			//$input['ar'] = 
			//print_r($filePath);
			//die();
			//print_r($data['avatar']);
			//die();
			/*unset($data['avatar']);
			
			$path = public_path().'/images/upload_pin_stock/';
			 
			$curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_POST, 1);
			$file = curl_file_create($path.'metfone.txt', 'text/plain', $path.'metfone.txt');
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $file);
			*/
			//$data['avatar'] = $file;
			//$file = $data['avatar'];
			//var_dump($file);
			//die();
			//print_r($fileTemp);
			//die();
			//print_r($file);
			//die();
			//$data['avatar'] = $file->getFilename();
			
			/*$request = $client->createRequest('POST', Configuration::$webServiceToEasyAPI.'ptu/pin', [
		        'config' => [
		            'curl' => [
		                CURLOPT_SSL_VERIFYPEER => false,
		            ]
		        ]
		    ]);
			$postBody = $request->getBody();
			$photo = $data['avatar'];
    		$postBody->addFile(new \GuzzleHttp\Post\PostFile('avatar', $photo));
			
			$response = $client->send($request);
			 */
			 
		    /*try {
		        $response = $guzzle->send($request);
		        echo $response;
		    } catch(Exception $e) {
		        echo $e->getMessage();
		    }*/
			//$response = $client->post(Configuration::$webServiceToEasyAPI.'ptu/pin',['body'=>$data]);
			
			print_r($response->getStatusCode());
			if ($response->getStatusCode() == '201') {
				$json = $response->json();
				if ($json['status']) {
					$response['status'] = 'T';
					$msg = array('type'=>'success','msg'=>$json['message']);
				} else {
					$response['status'] = 'F';
					$msg = array('type'=>'error','msg'=>$json['message']);
				}
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
				return $response;
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption'.$e);
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;
				
				return $response;
			}
		} catch (Exception $e) {
			//echo $e;
			//die();
			if ($e->hasResponse()) {
				$jsonString = $e->getResponse()->getBody();
				$obJson = json_decode($jsonString);
				echo $jsonString;
				die();
				echo $obJson->status;
				if (!$obJson->status) {
					$msg = array('type'=>'error','msg'=>$obJson->message);
					array_push($msgs,$msg);
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					
					return $response;
				}
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption From Other API');
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;
				
				return $response;
			}
		}
	}

	public static function checkWithdrawReq($type,$currency,$did,$amount,$gatetway) {
		$response = array();
		$msgs = array();
		
		//$data = array('type'=>'game','currency'=>'KHR','did'=>'11111111','amount'=>'200','gateway'=>'web');
		$data = array('type'=>$type,'currency'=>$currency,'did'=>$did,'amount'=>$amount,'gateway'=>$gatetway);
		$dataHeader = array('Api-Key'=>Configuration::$apiKey,'Api-Secret'=>Configuration::$apiSecret);
		$res = null;
		$client = new GuzzleHttp\Client();
		try {
			$res = $client->post(Configuration::$webServiceToTxnProcesser.'transaction/check_withdraw',['body'=>$data,'headers'=>$dataHeader]);
			
			print_r($res->getStatusCode());
			if ($res->getStatusCode() == '201') {
				$json = $res->json();
				if ($json['status']) {
					$response['status'] = 'T';
					$msg = array('type'=>'success','msg'=>$json['message']);
				} else {
					$response['status'] = 'F';
					$msg = array('type'=>'error','msg'=>$json['message']);
				}
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
				return $response;
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption'.$e);
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;
				
				return $response;
			}
		} catch (Exception $e) {
			//echo $e;
			//die();
			if ($e->hasResponse()) {
				$jsonString = $e->getResponse()->getBody();
				$obJson = json_decode($jsonString);
				echo $jsonString;
				echo $obJson->status;
				if (!$obJson->status) {
					$msg = array('type'=>'error','msg'=>$obJson->message);
					array_push($msgs,$msg);
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					
					return $response;
				}
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption'.$e);
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;
				
				return $response;
			}
		}
	}

	public static function masterProcessSub($data) {
		$response = array();
		$msgs = array();
		
		//$data = array('type'=>'game','currency'=>'KHR','did'=>'11111111','amount'=>'200','gateway'=>'web');
		//$data = array('type'=>$type,'currency'=>$currency,'did'=>$did,'amount'=>$amount,'gateway'=>$gatetway);
		$res = null;
		$client = new GuzzleHttp\Client();
		try {
			$dataHeader = array('Api-Key'=>Configuration::$apiKey,'Api-Secret'=>Configuration::$apiSecret);
			$res = $client->post(Configuration::$webServiceToTxnProcesser.'transaction/master_process_sub',['body'=>$data,'headers'=>$dataHeader]);
			
			print_r($res->getStatusCode());
			if ($res->getStatusCode() == '201') {
				$json = $res->json();
				if ($json['status']) {
					$response['status'] = 'T';
					$response['data'] = $json['data'];
					$msg = array('type'=>'success','msg'=>$json['message']);
				} else {
					$response['status'] = 'F';
					$msg = array('type'=>'error','msg'=>$json['message']);
				}
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
				return $response;
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption From Other API');
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;
				
				return $response;
			}
		} catch (Exception $e) {
			if ($e->hasResponse()) {
				$jsonString = $e->getResponse()->getBody();
				$obJson = json_decode($jsonString);
				//echo $jsonString;
				//echo $obJson->status;
				//die();
				if (!$obJson->status) {
					$msg = array('type'=>'error','msg'=>$obJson->message);
					array_push($msgs,$msg);
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					
					return $response;
				}
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption From Other API');
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;
				
				return $response;
			}
		}
	}

	public static function reportPinStock($data) {
		$response = array();
		$msgs = array();
		$res = null;
		$client = new GuzzleHttp\Client();
		try {
			$queryString = '?operator_id='.$data['operator_id'].'&face='.$data['face'].'&status='.$data['status'].'&check_exprie='.$data['check_exprie'].'&start_date='.$data['start_date'].'&end_date='.$data['end_date'].'&n='.$data['n'].'&page='.$data['page'];
			$dataHeader = array('Api-Key'=>Configuration::$ptuApiKey,'Api-Secret'=>Configuration::$ptuApiSecret);
			$request = $client->createRequest('GET', Configuration::$webServiceToEasyAPI.'ptu/report/pins_stock/'.$queryString, ['body'=>$data,'headers'=>$dataHeader]);
			$responseAPI = $client->send($request);
			//echo $queryString;
			if ($responseAPI->getStatusCode() == '201' || $responseAPI->getStatusCode() == '200') {
				$json = $responseAPI->json();
				if ($json['status']) {
					$response['status'] = 'T';
					$response['data'] = $json['data'];
					$msg = array('type'=>'success','msg'=>$json['message']);
				} else {
					$response['status'] = 'F';
					$msg = array('type'=>'error','msg'=>$json['message']);
				}
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
				return $response;
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption From Other API');
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;
				
				return $response;
			}
		} catch (Exception $e) {
			
			if ($e->hasResponse()) {
				$jsonString = $e->getResponse()->getBody();
				$obJson = json_decode($jsonString);
				//echo $jsonString;
				//echo $obJson->status;
				if (!$obJson->status) {
					$msg = array('type'=>'error','msg'=>$obJson->message);
					array_push($msgs,$msg);
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					
					return $response;
				}
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption From Other API');
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;
				
				return $response;
			}
			var_dump($response);
			echo $e;die();
		}
	}

	// summary pin code stock
	public static function summaryPinCodeStock($data) {
		$response = array();
		$msgs = array();
		$res = null;
		$client = new GuzzleHttp\Client();
		try {
			$queryString = '?operator_id='.$data['operator_id'].'&start_date='.$data['start_date'].'&end_date='.$data['end_date'];
			$dataHeader = array('Api-Key'=>Configuration::$ptuApiKey,'Api-Secret'=>Configuration::$ptuApiSecret);
			$request = $client->createRequest('GET', Configuration::$webServiceToEasyAPI.'ptu/report/view_pins_code/'.$queryString, ['body'=>$data,'headers'=>$dataHeader]);
			$responseAPI = $client->send($request);
			//echo $queryString;
			if ($responseAPI->getStatusCode() == '201' || $responseAPI->getStatusCode() == '200') {
				$json = $responseAPI->json();
				if ($json['status']) {
					$response['status'] = 'T';
					$response['data'] = $json['data'];
					$msg = array('type'=>'success','msg'=>$json['message']);
				} else {
					$response['status'] = 'F';
					$msg = array('type'=>'error','msg'=>$json['message']);
				}
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
				return $response;
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption From Other API');
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;

				return $response;
			}
		} catch (Exception $e) {

			if ($e->hasResponse()) {
				$jsonString = $e->getResponse()->getBody();
				$obJson = json_decode($jsonString);
				echo $jsonString;
				//echo $obJson->status;
				//print_r($jsonString);
				die();
				
				//-----------------------
				if (!$obJson->status) {
					$msg = array('type'=>'error','msg'=>$obJson->message);
					array_push($msgs,$msg);
					$response['status'] = 'F';
					$response['msgs'] = $msgs;

					return $response;
				}
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption From Other API');
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;

				return $response;
			}
			var_dump($response);
			echo $e;die();
		}
	}

	public static function getOperatorList() {
		$response = array();
		$msgs = array();
		$res = null;
		$client = new GuzzleHttp\Client();
		try {
			$dataHeader = array('Api-Key'=>Configuration::$ptuApiKey,'Api-Secret'=>Configuration::$ptuApiSecret);			
			$request = $client->createRequest('GET', Configuration::$webServiceToEasyAPI.'ptu/report/get_all_operator',['headers'=>$dataHeader]);
			$responseAPI = $client->send($request);
			if ($responseAPI->getStatusCode() == '201' || $responseAPI->getStatusCode() == '200') {
				$json = $responseAPI->json();
				if ($json['status']) {
					$response['status'] = 'T';
					$response['data'] = $json['data'];
					$msg = array('type'=>'success','msg'=>$json['message']);
				} else {
					$response['status'] = 'F';
					$msg = array('type'=>'error','msg'=>$json['message']);
				}
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
				return $response;
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption From Other API');
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;
				
				return $response;
			}
		} catch (Exception $e) {
			if ($e->hasResponse()) {
				$jsonString = $e->getResponse()->getBody();
				$obJson = json_decode($jsonString);
				//echo $jsonString;
				//echo $obJson->status;
				//die();
				if (!$obJson->status) {
					$msg = array('type'=>'error','msg'=>$obJson->message);
					array_push($msgs,$msg);
					$response['status'] = 'F';
					$response['msgs'] = $msgs;
					
					return $response;
				}
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption From Other API');
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;
				
				return $response;
			}
		}
	}

	// Mohasamnang print
	public static function mohasomnangPrint($data) {
		$response = array();
		$msgs = array();
		$res = null;
		try {
			$dealer__id = $data['dealer__id'];
			$quantity = $data['quantity'];
			$adv = $data['adv'];

			$command  = "curl  ".Configuration::$webServiceToPrint."mohasamnang/print -d \"dealer__id=".$dealer__id."\" -d \"quantity=".$quantity."\" -d \"adv=".$adv."\" "."-H \"Api-Key: dAJLWdMNwT3NsTBRhNmmc\" -H \"Api-Secret: 8AqtJVTwtrLzYsfsND3sqGzeN4vdG2Vd48RunhL4\"";
			//echo $command;
			//die();
			$results = exec($command);
			$obJson = json_decode($results);
			//dd($obJson);
			//echo $results;\

			//echo $obJson->message;
			if ($obJson->status) {
				$response['status'] = 'T';
				$response['msgs'] = $msgs;
				$response['data'] = $obJson->data;
			} else {
				$response['status'] = 'F';
				$msg = array('type'=>'error','msg'=>$obJson->message);
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
			}
			return $response;
		} catch (Exception $e) {
			$msg = array('type'=>'error','msg'=>'Error Exeption = '.$e);
			array_push($msgs,$msg);
			$response['status'] = 'F';
			$response['msgs'] = $msgs;

			return $response;
		}
	}

	// Mohasamnang cancel print
	public static function mohasomnangPrintCancel($data) {
		$response = array();
		$msgs = array();
		$res = null;
		try {
			$did = $data['did'];
			$tsn = $data['tsn'];
			$gateway = "web";

			$command  = "curl  ".Configuration::$webServiceToPrintCancel."mohasamnang/cancel_game -d \"did=".$did."\" -d \"tsn=".$tsn."\" -d \"gateway=".$gateway."\" "."-H \"Api-Key: dAJLWdMNwT3NsTBRhNmmc\" -H \"Api-Secret: 8AqtJVTwtrLzYsfsND3sqGzeN4vdG2Vd48RunhL4\"";
			//echo $command;
			//die();
			$results = exec($command);
			$obJson = json_decode($results);
			//echo $results;

			//echo $obJson->message;
			if ($obJson->status) {
				$response['status'] = 'T';
				$response['msgs'] = $msgs;
				$response['data'] = $obJson->data;
			} else {
				$response['status'] = 'F';
				$msg = array('type'=>'error','msg'=>$obJson->message);
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
			}
			return $response;
		} catch (Exception $e) {
			$msg = array('type'=>'error','msg'=>'Error Exeption'.$e);
			array_push($msgs,$msg);
			$response['status'] = 'F';
			$response['msgs'] = $msgs;

			return $response;
		}
	}

	// Mohasamnang Win check
	public static function mohasomnangWincheck($data) {

		$response = array();
		$msgs = array();
		$res = null;
		try {
			$did = $data['did'];
			$tsn = $data['tsn'];
			$gateway = "web";

			$command  = "curl  ".Configuration::$webServiceCheckPayout."mohasamnang/wincheck -d \"did=".$did."\" -d \"tsn=".$tsn."\" -d \"gateway=".$gateway."\" "."-H \"Api-Key: dAJLWdMNwT3NsTBRhNmmc\" -H \"Api-Secret: 8AqtJVTwtrLzYsfsND3sqGzeN4vdG2Vd48RunhL4\"";
			//echo $command;
			//die();
			$results = exec($command);
			$obJson = json_decode($results);
			//echo $results;

			//echo $obJson->message;
			if ($obJson->status) {
				$response['status'] = 'T';
				$response['msgs'] = $msgs;
				$response['data'] = $obJson->data;
			} else {
				$response['status'] = 'F';
				$msg = array('type'=>'error','msg'=>$obJson->message);
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
			}
			return $response;
		} catch (Exception $e) {
			$msg = array('type'=>'error','msg'=>'Error Exeption'.$e);
			array_push($msgs,$msg);
			$response['status'] = 'F';
			$response['msgs'] = $msgs;

			return $response;
		}
	}

	// Mohasamnang Payout
	public static function mohasomnangPayout($data) {

		$response = array();
		$msgs = array();
		$res = null;
		try {
			$did = $data['did'];
			$tsn = $data['tsn'];
			$gateway = "web";

			$command  = "curl  ".Configuration::$webServiceCheckPayout."mohasamnang/payout -d \"did=".$did."\" -d \"tsn=".$tsn."\" -d \"gateway=".$gateway."\" "."-H \"Api-Key: dAJLWdMNwT3NsTBRhNmmc\" -H \"Api-Secret: 8AqtJVTwtrLzYsfsND3sqGzeN4vdG2Vd48RunhL4\"";
			//echo $command;
			//die();
			$results = exec($command);
			$obJson = json_decode($results);
			//echo $results;

			//echo $obJson->message;
			if ($obJson->status) {
				$response['status'] = 'T';
				$response['msgs'] = $msgs;
				$response['data'] = $obJson->data;
			} else {
				$response['status'] = 'F';
				$msg = array('type'=>'error','msg'=>$obJson->message);
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
			}
			return $response;
		} catch (Exception $e) {
			$msg = array('type'=>'error','msg'=>'Error Exeption'.$e);
			array_push($msgs,$msg);
			$response['status'] = 'F';
			$response['msgs'] = $msgs;

			return $response;
		}
	}

	// Post report
	public static function mohasomnangPostReport($data) {
		$response = array();
		$msgs = array();
		$res = null;
		$client = new GuzzleHttp\Client();
		try {
			$queryString = '?did='.$data['did'].'&start_date='.$data['start_date'].'&end_date='.$data['end_date'];
			$dataHeader = array('Api-Key'=>Configuration::$ptuApiKey,'Api-Secret'=>Configuration::$ptuApiSecret);
			$request = $client->createRequest('GET', Configuration::$webServicePostReport.'mohasamnang/post_report'.$queryString, ['body'=>$data,'headers'=>$dataHeader]);
			$responseAPI = $client->send($request);
			//echo $queryString;
			if ($responseAPI->getStatusCode() == '201' || $responseAPI->getStatusCode() == '200') {
				$json = $responseAPI->json();
				if ($json['status']) {
					$response['status'] = 'T';
					$response['data'] = $json['data'];
					$msg = array('type'=>'success','msg'=>$json['message']);
				} else {
					$response['status'] = 'F';
					$msg = array('type'=>'error','msg'=>$json['message']);
				}
				array_push($msgs,$msg);
				$response['msgs'] = $msgs;
				return $response;
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption From Other API');
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;

				return $response;
			}
		} catch (Exception $e) {

			if ($e->hasResponse()) {
				$jsonString = $e->getResponse()->getBody();
				$obJson = json_decode($jsonString);
				echo $jsonString;
				//echo $obJson->status;
				//print_r($jsonString);
				die();

				//-----------------------
				if (!$obJson->status) {
					$msg = array('type'=>'error','msg'=>$obJson->message);
					array_push($msgs,$msg);
					$response['status'] = 'F';
					$response['msgs'] = $msgs;

					return $response;
				}
			} else {
				$msg = array('type'=>'error','msg'=>'Error Exeption From Other API');
				array_push($msgs,$msg);
				$response['status'] = 'F';
				$response['msgs'] = $msgs;

				return $response;
			}
			var_dump($response);
			echo $e;die();
		}
	}
}