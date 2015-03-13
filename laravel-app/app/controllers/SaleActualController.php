<?php

class SaleActualController extends \BaseController {
	
	protected $layout = 'layouts.master';
	/**
	 * Display a listing of the resource.
	 * GET /saleactual
	 *
	 * @return Response
	 */
	public function index()
	{
		$inputs = Input::All();
		
		$db = DB::table('rep_channel_sale as cs')
				->select('cs.*','s.name as createdBy','co.channel_owner_name')
				->join('sys_channel_owner as co','co.channel_owner_id','=','cs.channel_owner__id')
				->join('staff as s','s.id','=','cs.staff__id')
				->where('cs.status','!=','delete')
				//->orderBy('co.sequence_number','asc')
				->orderBy('cs.created_at','desc');
				
				
		if (Input::has('channel_owner__id')) {
			$db->where('cs.channel_owner__id',$inputs['channel_owner__id']);
		}	
		if (Input::has('start_date') && Input::has('end_date')) {
			$db->whereBetween('cs.datetime', array($inputs['start_date'], $inputs['end_date']));
		}
		
		$actualSales = $db->paginate(15)->appends($inputs);
		
		$channelOwners = ChannelOwner::where('status','active')->lists('channel_owner_name','channel_owner_id');

		$this->layout->content = View::make('sale-actual.list_actual_sale',compact('actualSales','channelOwners'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /saleactual/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$staffId = Auth::user()->id;
		$db = DB::table('rep_channel_sale as cs')
				->select('cs.*','s.name as createdBy','co.channel_owner_name')
				->join('sys_channel_owner as co','co.channel_owner_id','=','cs.channel_owner__id')
				->join('staff as s','s.id','=','cs.staff__id')
				->orderBy('cs.created_at','desc')
				->where('cs.status','!=','delete');				
				
		$db->where('cs.created_at','>=',date("Y-m-j"));
		$db->where('cs.staff__id','=',$staffId);
		$actualSales = $db->paginate(5);
		
		
		
		$channelOwners = ChannelOwner::where('status','active')->lists('channel_owner_name','channel_owner_id');
		$sysServiceTypes = ChannelOwner::getSysServiceType();
		$this->layout->content = View::make('sale-actual.create-sale-actual',compact('actualSales','channelOwners','sysServiceTypes'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /saleactual
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		DB::beginTransaction();
		
		$input = Input::all();
		
        $validation = Validator::make($input, ChannelSale::$rules);
		$msgs = array();
		if ($validation->passes())
        {
			try {
				$actualSale = $db = DB::table('rep_channel_sale as sst')
		            ->where('channel_owner__id',$input['channel_owner__id'])
					->where('datetime',$input['datetime'])
					->where('status','active')
					->first();
    			if ($actualSale ==null) {
    				$staffId = Auth::user()->id;
					$input['staff__id']=$staffId;
					
					$ob = ChannelSale::create($input);
					$obLog = array(
						'staff__id'=>$staffId,
						'action'=>'add',
						'object_type'=>'actual_sale',
						'object__id'=>$ob->channel_sale_id,
						'new_data'=>json_encode($input)
					);
					$obLog = Logs::create($obLog);
					
					////get service type //////////////////////////
					$sysServiceTypes = ChannelOwner::getSysServiceType();
					if(isset($sysServiceTypes)){
						$channelSaleServiceType['channel_sale__id']=$ob->channel_sale_id;
						
						foreach ($sysServiceTypes as $sysServiceType) {
							
							$channelSaleServiceType['service_type__id']= $sysServiceType->service_type_id;
							$channelSaleServiceType['amount']=$input[$sysServiceType->service_type_id];
							$ob1 = ChannelSaleServiceType::create($channelSaleServiceType);
						}
					}
					
					DB::commit();
					
					$msg = array();
					$msg['msg'] = 'Create Actual Channel Sale successfull!';
					$msg['type'] = 'success';
					array_push($msgs,$msg);
					return Redirect::route('sale-actual.create')->with('msgs', $msgs);
				} else {
        			$msg['msg'] = 'This date was already add!';
					$msg['type'] = 'error';
					array_push($msgs,$msg);
        		}
				
			} catch (Exception $e) {
				DB::rollback();
				
				$msg = array();
				$msg['msg'] = 'Create Actual Channel Sale failed! Error ='.$e;
				$msg['type'] = 'error';
				array_push($msgs,$msg);
			}
        }
		
		return Redirect::route('sale-actual.create')
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /saleactual/create
	 *
	 * @return Response
	 */
	public function uploadSaleActual()
	{
		$this->layout->content = View::make('sale-actual.upload_actual_sale');
	}
	
	/**
	 * Store a newly created resource in storage.
	 * POST /transaction
	 *
	 * @return Response
	 */
	public function storeUploadSaleActual()
	{
		$input = Input::all();
		$rules['file_name'] = 'required';
        $validation = Validator::make($input,$rules);

		$msgs = array();
        if ($validation->passes())
        {
        	$staffId = Auth::user()->id;
			
        	if (Input::hasFile('file_name')) {
		        $file            = Input::file('file_name');
		        $destinationPath = public_path().'/images/upload_add_actual_channel_sale/';
				$millisecond = round(microtime(true)*1000);
		        $filename        = $millisecond.'_'.str_random(2) . '_' . $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				
				//$extension != 'xlsx'  && $extension != 'xls' && 
				//if ($extension != 'xlsx'  && $extension != 'xls' &&) {
				if ($extension != 'csv') {
					$msg = array('type'=>'error','msg'=>'File Extension = '.$extension.' Was Not Allowed. pls convert to csv file!');
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
				}
				
				
		        $uploadSuccess   = $file->move($destinationPath, $filename);
								
				if ($uploadSuccess) {
					$dateTime = date("Y-m-d H:i:s");
					
					$input['staff__id']=Auth::user()->id;
					$input['file_name']=$filename;
					$input['created_at'] = $dateTime;
					$input['status'] = 'active';
					
					
					$realFilePath = $destinationPath.$filename;
					$excelList = Excel::load($realFilePath, function($reader) {})->toArray();
					
					DB::beginTransaction();
					try {
						
						$ob = ChannelSaleUpload::create($input);
						$obLog = array(
							'staff__id'=>$staffId,
							'action'=>'upload',
							'object_type'=>'upload_actual_channel_sale',
							'object__id'=>$ob->channel_sale_upload_id,
							'new_data'=>json_encode($input)
						);
						$obLog = Logs::create($obLog);

						foreach ($excelList as $index => $row) {
							$row = array_values($row);
							$channelOwnerKey = $row[3];
							$dateupload = $row[1];
							//$dateupload = str_replace("\\","-",$dateupload);

							$draw639Date = $row[9];
							$draw639 = $this->setValue($row[8]);

							$drawPick5Date = $row[11];
							$drawPick5 = $this->setValue($row[10]);

							$subscriber639 = $this->setValue($row[12]);
							$subscriberPick5 = $this->setValue($row[13]);

							$ticket639 = $this->setValue($row[14]);
							$ticketPick5 = $this->setValue($row[15]);

							$active_pos_639 = $this->setValue($row[16]);
							$active_pos_pick5 = $this->setValue($row[17]);

							$active_pos_total = $this->setValue($row[18]);
							$free_count_bet_639 = $this->setValue($row[19]);
							$free_count_bet_pick5 = $this->setValue($row[20]);
							$net_bet_count_639 = $this->setValue($row[21]);
							$net_bet_count_pick5 = $this->setValue($row[22]);
							$total_sell_amount_639 = $this->setValue($row[25]);
							$total_sell_amount_pick5 = $this->setValue($row[26]);

							$cancel_amount_639 = $this->setValue($row[23]);
							$cancel_amount_pick5 = $this->setValue($row[24]);

							$bet639 = $this->setValue($row[4]);
							$bet639Luk = $this->setValue($row[5]);
							$betPick5 = $this->setValue($row[6]);
							$betPick5Luk = $this->setValue($row[7]);

							$channelOwner = ChannelOwner::getChannelOwnerByMapKey($channelOwnerKey);


							//--------------------------------------------

							if ($channelOwner != null) {
								//-------------validate exist----------------
								/*$actualSale = $db = DB::table('rep_channel_sale as sst')
									->where('channel_owner__id',$channelOwner->channel_owner_id)
									->where('datetime',$dateupload)
									->where('status','active')
									->first();
								if ($actualSale ==null) {

								} else {
									$msg['msg'] = 'Channel Owner = '.$channelOwnerKey.' and Date = '.$dateupload.' was already add!';
									$msg['type'] = 'error';
									array_push($msgs,$msg);
									return Redirect::back()
										->withInput()
										->with('msgs', $msgs);
									break;
								}*/
								//--------------------------------------------

								$channelSaleArr = array(
									'datetime'=>$dateupload,
									'channel_owner__id'=>$channelOwner->channel_owner_id,
									'staff__id'=>$staffId,
									'channel_sale_upload__id'=>$ob->channel_sale_upload_id,
									'status'=>'active',
									'draw_pick_5_n'=>$drawPick5,
									'draw_639_n'=>$draw639,
									'subscriber_639_n'=>$subscriber639,
									'subscriber_pick_5'=>$subscriberPick5,
									'ticket_pick_5'=>$ticketPick5,
									'ticket_639'=>$ticket639,
									'active_pos_639'=>$active_pos_639,
									'active_pos_pick5'=>$active_pos_pick5,
									'draw_pick5_date'=>$drawPick5Date,
									'draw_639_date'=>$draw639Date,
									'bet_639'=>$bet639,
									'bet_639_luk'=>$bet639Luk,
									'bet_pick5'=>$betPick5,
									'bet_pick5_luk'=>$betPick5Luk,
									'active_pos_total'=>$active_pos_total,
									'free_count_bet_639'=>$free_count_bet_639,
									'free_count_bet_pick5'=>$free_count_bet_pick5,
									'net_bet_count_639'=>$net_bet_count_639,
									'net_bet_count_pick5'=>$net_bet_count_pick5,
									'total_sell_amount_639'=>$total_sell_amount_639,
									'total_sell_amount_pick5'=>$total_sell_amount_pick5,
									'cancel_amount_639'=>$cancel_amount_639,
									'cancel_amount_pick5'=>$cancel_amount_pick5,
									);

								//----------channel sale--------------------------
								$obChannelSale = ChannelSale::create($channelSaleArr);

								//----------channel sale service type-------------
								/*$channelSaleServiceType['channel_sale__id']= $obChannelSale->channel_sale_id;
								$channelSaleServiceType['service_type__id']= '639';
								$channelSaleServiceType['amount']=$line639;
								$ob1 = ChannelSaleServiceType::create($channelSaleServiceType);

								$channelSaleServiceType['channel_sale__id']= $obChannelSale->channel_sale_id;
								$channelSaleServiceType['service_type__id']= '639luk';
								$channelSaleServiceType['amount']=$line639luk;
								$ob2 = ChannelSaleServiceType::create($channelSaleServiceType);

								$channelSaleServiceType['channel_sale__id']= $obChannelSale->channel_sale_id;
								$channelSaleServiceType['service_type__id']= 'pick5';
								$channelSaleServiceType['amount']=$linepick5;
								$ob3 = ChannelSaleServiceType::create($channelSaleServiceType);

								$channelSaleServiceType['channel_sale__id']= $obChannelSale->channel_sale_id;
								$channelSaleServiceType['service_type__id']= 'pick5l';
								$channelSaleServiceType['amount']=$linepick5l;
								$ob4 = ChannelSaleServiceType::create($channelSaleServiceType);*/

							} else {
								$msg['msg'] = 'Channel Owner = '.$channelOwnerKey;
								$msg['type'] = 'error';
								$msgs = array($msg);
								return Redirect::back()
									->withInput()
									->with('msgs', $msgs);
								break;
							}
							//channel_sale_upload__id

						}
						
					    DB::commit();
						
						$msg['msg'] = 'Upload Actual Channel Sale Game Successfully!';
						$msg['type'] = 'success';
						$msgs = array($msg);
						
					} catch (\Exception $e) {
					    DB::rollback();
						$msg['msg'] = 'Upload Actual Channel Sale Game Failed!'.$e;
						$msg['type'] = 'error';
						$msgs = array($msg);
					}
				} else {
					$msg['msg'] = 'Upload Failed!';
					$msg['type'] = 'error';
					$msgs = array($msg);
				}
		    }
        }
		return Redirect::back()
				            ->withInput()
				            ->withErrors($validation)
				            ->with('msgs', $msgs);
	}

	/**
	 * Display the specified resource.
	 * GET /saleactual/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /saleactual/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$actualSale = ChannelSale::find($id);
		$channelOwners = ChannelOwner::where('status','active')->lists('channel_owner_name','channel_owner_id');
		$sysServiceTypes = ChannelOwner::getSysServiceType();
		$this->layout->content = View::make('sale-actual.edit-sale-actual',compact('actualSale','channelOwners','sysServiceTypes'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /saleactual/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();
        $validation = Validator::make($input, ChannelSale::$rules);
		$msgs = array();
        if ($validation->passes())
        {
        	try {
        		DB::beginTransaction();
				
				$staffId = Auth::user()->id;
				$input['updated_by_staff__id']=$staffId;
				
				$id = $input['channel_sale_id'];
				$actualSale = ChannelSale::find($id);
				
				$oldData = array();
				$oldData['channel_sale_id'] = $actualSale->channel_sale_id;
				$oldData['datetime'] = $actualSale->datetime;
				$oldData['currency__id'] = $actualSale->currency__id;
				
				$sysServiceTypes = ChannelOwner::getSysServiceType();
				if(isset($sysServiceTypes)){
					foreach ($sysServiceTypes as $sysServiceType) {
						
						$channelSaleServiceType['amount']=$input[$sysServiceType->service_type_id];						
						$gameSalesOb = DB::table('rep_channel_sale_service_type as csst')
											->where('csst.channel_sale__id',$actualSale->channel_sale_id)
											->where('csst.service_type__id',$sysServiceType->service_type_id);
											
						$gameSales = $gameSalesOb->first();			
						$oldData[$sysServiceType->service_type_id] = $gameSales->amount;
						
						$gameSalesOb->update($channelSaleServiceType);
						
					}
				}
					
				$obLog = array(
					'staff__id'=>$staffId,
					'action'=>'update',
					'object_type'=>'actual_sale',
					'object__id'=>$id,
					'new_data'=>json_encode($input),
					'old_data'=>json_encode($oldData)
				);
				$obLog = Logs::create($obLog);
				$ob = $actualSale->update($input);
				
				
        		DB::commit();
				$msg['msg'] = 'Update Actual Channel Sale Successfully!';
				$msg['type'] = 'success';
				array_push($msgs,$msg);
				return Redirect::to('sale-actual')->with('msgs', $msgs);
        	} catch (Exception $e) {
        		DB::rollback();
				$msg['msg'] = 'Update Actual Channel Sale  Failed! Error = '.$e;
				$msg['type'] = 'error';
				array_push($msgs,$msg);
        	}
	    	
        }
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /saleactual/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$staffId = Auth::user()->id;
		
		$ob = ChannelSale::find($id);
		$oldOb = $ob;
		
		DB::beginTransaction();
		$arrayUpdate = array('updated_by_staff__id'=>$staffId,'status'=>'delete');
		
		$obLog = array(
			'staff__id'=>$staffId,
			'action'=>'delete',
			'object_type'=>'actual_sale',
			'object__id'=>$id,
			'old_data'=>json_encode($oldOb),
			'new_data'=>json_encode($arrayUpdate)
		);
		
		$successObLog = Logs::create($obLog);
		$successOb = $ob->update($arrayUpdate);
		$msg = array();
		if( !$successOb || !$successObLog )
		{
			DB::rollback();
			$msg['msg'] = 'Delete actual channel sale failed!';
			$msg['type'] = 'error';
		} else {
			// Else commit the queries
			DB::commit();
			$msg['msg'] = 'Delete actual channel sale successfully!';
			$msg['type'] = 'success';
		}
		$msgs = array($msg);
        return Redirect::route('sale-actual.index')->with('msgs', $msgs);
	}
	
	private function setValue($value) {
		if ($value != "" && $value != null) {
			return $value;
		} else {
			return 0;
		}
	}
}