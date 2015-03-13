<?php

class DealerController extends \BaseController {

	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /dealer
	 *
	 * @return Response
	 */
	public function index()
	{
		//$dealers = Dealer::orderBy('created_at','desc')->where('status','!=','delete')->paginate(15);
		$dealers = DB::table('dealer')
			            ->join('dealer_type', 'dealer.dealer_type__id', '=', 'dealer_type.id')
						->leftJoin('staff','staff.id','=','dealer.staff__id')
						->orderBy('dealer.created_at','desc')
						->where('dealer.status','!=','delete')
						->where('dealer.dealer_type__id','!=','9')
						->select('dealer.*', 'dealer_type.name AS tname','staff.name as sname')
						->paginate(10);
		
		$listProvinces = DB::table('province')->lists('name_en','code');
		$listDealerTypes = DealerType::lists('name', 'id');
		$this->layout->content = View::make('dealer.list_dealer',compact('dealers','listDealerTypes','listProvinces'));
	}
	
	/**
	 * Display a listing of the resource.
	 * GET /dealer
	 *
	 * @return Response
	 */
	public function search()
	{
		$inputs = Input::all();
		
		$db = DB::table('dealer')
			            ->join('dealer_type', 'dealer.dealer_type__id', '=', 'dealer_type.id')
						->select('dealer.*', 'dealer_type.name AS tname','staff.name as sname')
						->join('staff','staff.id','=','dealer.staff__id')
						->orderBy('dealer.created_at','desc')
						->where('dealer.status','!=','delete')
						->where('dealer.dealer_type__id','!=','9');
						
		if (Input::has('id')) {
			$db->where('dealer.id',$inputs['id']);
		}
		if (Input::has('name')) {
			$db->where('dealer.name','LIKE','%'.$inputs['name'].'%');
		}
		if (Input::has('dealer_type__id')) {
			$db->where('dealer.dealer_type__id',$inputs['dealer_type__id']);
		}
		if (Input::has('status')) {
			$db->where('dealer.status',$inputs['status']);
		}
		if (Input::has('province__code')) {
			$db->where('dealer.province__code',$inputs['province__code']);
		}
		if (Input::has('khan__code')) {
			$db->where('dealer.khan__code',$inputs['khan__code']);
		}
		$n = 15;
		if (Input::has('n_record')) {
			$n = Input::get('n_record');
		}		
		$dealers = $db->paginate($n)->appends($inputs);
		
		$listProvinces = DB::table('province')->lists('name_en','code');
		$listDealerTypes = DealerType::lists('name', 'id');
		$this->layout->content = View::make('dealer.list_dealer',compact('dealers','listDealerTypes','listProvinces'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /dealer/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//$listDealerTypes = DealerType::select('id','name')->get();
		$listDealerTypes = DealerType::lists('name', 'id');
		$listProvinces = Province::lists('name_en', 'code');
		$this->layout->content = View::make('dealer.create_dealer',compact('listDealerTypes','listProvinces'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /dealer
	 *
	 * @return Response
	 */
	public function store()
	{
		DB::beginTransaction();
		$dateTime = date("Y-m-d H:i:s");
		
		$input = Input::all();
		$input['status'] = 'active';
		$input['status_game'] = 'active';
		$input['status_os'] = 'active';
		$input['type_account'] = 'real';
		$input['password'] =  '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4';
		
        $validation = Validator::make($input, Dealer::$rules);
		
		$updateDealerTypeTxn = true;
		$insertDealerTypeTxn = true;
		$msgs = array();
		if ($validation->passes()) {
			try {	
				$khanCode = $input['khan__code'];
				$typeId = $input['dealer_type__id'];
				
				if (Input::has('parent_id')) {
					$parentId = Input::get('parent_id');
					$parent = Dealer::where('id',$parentId)
									->where(function($query)
						            {
						                $query->where('dealer_type__id', '=', 2)
											  ->orWhere('dealer_type__id',1);
						            })
									->get();
					if ($parent == null || count($parent) <= 0) {
						$msgs = array();
						$msg = array('type'=>'error','msg'=>'Parent Dealer ID Not Exist!');
						array_push($msgs,$msg);
						return Redirect::route('dealers.create')
			            ->withInput()
			            ->withErrors($validation)
						->with('msgs', $msgs);
						
					}
				}
	
				if (Input::has('reference_dealer_id')) {
					$parentId = Input::get('reference_dealer_id');
					$parent = Dealer::where('id',$parentId)
									->get();
					if ($parent == null || count($parent) <= 0) {
						$msgs = array();
						$msg = array('type'=>'error','msg'=>'Reference Dealer ID Not Exist!');
						array_push($msgs,$msg);
						return Redirect::route('dealers.create')
			            ->withInput()
			            ->withErrors($validation)
						->with('msgs', $msgs);
						
					}
				}
				
				//----Generate DID------
				$khanDealerType = KhanDealerType::where('khan__code','=',$khanCode)
							->where('dealer_type__id','=',$typeId)
							->first();
				$numDealer = 0;
				if ($khanDealerType != null) {
					$numDealer = $khanDealerType->num_dealer;
					$numDealer += 1;
					
					$updateDealerTypeTxn = DB::table('khan_dealer_types')
						->where('khan__code','=',$khanCode)
						->where('dealer_type__id','=',$typeId)
						->update(array('num_dealer'=>$numDealer));
				} else {
					$lastDealer = DB::table('dealer')
									->where('khan__code','=',$khanCode)
									->where('dealer_type__id','=',$typeId)
									->orderBy('id','desc')
									->first();
									
					if ($lastDealer != null) {
						$did = $lastDealer->id;
						$didNum =substr($did,5,strlen($did)-5);
						$didNum +=1;
						$numDealer = $didNum;
					} else {
						$numDealer = 1;
					}
					
					$insertDealerTypeTxn = DB::table('khan_dealer_types')
						->insert(array('khan__code'=>$khanCode,'dealer_type__id'=>$typeId,'num_dealer'=>$numDealer));				
				}
				
				$did = $khanCode.$typeId;		
									
				if ($numDealer <= 99) {
					if ($numDealer <= 9) {
						$did .= "00".$numDealer;
					} else {
						$did .= "0".$numDealer;
					}
				} else {
					$did .= $numDealer;
				}
				//----End Generate DID-----
				
				$did = $this->checkDIDRecursive($did,$khanCode,$typeId,$numDealer);
						
				$input['id'] = $did;
							
				Dealer::$rules['id'] = 'unique:dealer,id';
		        $validation = Validator::make($input, Dealer::$rules);
				if ($validation->passes())
		        {
		        	$staffId = Auth::user()->id;
					$input['staff__id']=$staffId;
					
					if ($input['parent_id'] == '') {
						unset($input['parent_id']);
					}
					if ($input['reference_dealer_id'] == '') {
						unset($input['reference_dealer_id']);
					}
					
					$ob = Dealer::create($input);
					$obLog = array(
						'staff__id'=>$staffId,
						'action'=>'add',
						'object_type'=>'dealer',
						'object__id'=>$ob->id,
						'new_data'=>json_encode($input)
					);
					$obLog = Logs::create($obLog);
					
					//--------------------Auto Generate Wallet---------------------------------
					WWallet::generate5Wallets($ob->id);
					//--------------------Auto Assing Service Charge---------------------------
					
					DB::table('sc_dealer_profile')->insert(
					    array('dealer__id' => $ob->id,
					    	  'application__id' => 'easy',
							  'class_of_service_charge__id' => $typeId,
							  'status' => 'active',
							  'created_at' => $dateTime,
							  'staff__id' => 0,
							  'remark'=>'System Auto Assign Class Of Service!')
					);
					$msg = array();
					if( !$ob || !$obLog  || !$insertDealerTypeTxn || !$updateDealerTypeTxn)
					{
					    DB::rollback();
						$msg['msg'] = 'Create dealer failed!';
						$msg['type'] = 'error';
					} else {
					    DB::commit();
						$msg['msg'] = 'Create dealer successfully with DID = '.$did;
						$msg['type'] = 'success';
					}
					array_push($msgs,$msg);
					return Redirect::route('dealers.index')->with('msgs', $msgs);
		        }
			} catch (Exception $e) {
				DB::rollback();
				$msg['msg'] = 'Refund was failed!'.$e;
				$msg['type'] = 'error';
				array_push($msgs,$msg);
			}
		}
		return Redirect::route('dealers.create')
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}

	/**
	 * Display the specified resource.
	 * GET /dealer/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$dealer = Dealer::find($id);
		$dealerTypes = DealerType::find($dealer->dealer_type__id);
		
		$khan = DB::table('khan')
			->select('khan.*','province.name_en AS pname')
			->join('province', 'khan.province__code', '=', 'province.code')
			->where('khan.code','=',$dealer->khan__code)
			->first();	
		
		
		
		//$listProvinces = Province::lists('name_en', 'code');
		$kandCode = $dealer->khan__code;
		//$provinceCode = substr($kandCode,0,2);
		//$listKhans = Khan::where('province__code','=',$provinceCode)->lists('name_en', 'code');
		
		$createdBy = Staff::find($dealer->staff__id);
		$updatedBy = Staff::find($dealer->updated_by_staff__id);
		$commune = DB::table('communes')->where('code','=',$dealer->commune__code)->first();
		$village = DB::table('villages')->where('code','=',$dealer->village__code)->first();
		
						
		$dealerBanks = DB::table('dealer_banks as db')
			            ->select('db.*','d.name AS dName','d.id AS did','b.*','su.name as upbStaffName','db.status as dealer_bank_status')
						->join('dealer as d', 'd.id', '=', 'db.dealer__id')
						->join('bank as b', 'b.bid', '=', 'db.bank__id')
						->leftJoin('staff AS su','su.id','=','db.updated_by_staff__id')
						->orderBy('d.id','asc')
						->where('db.status','active')
						->where('db.dealer__id',$id)->get();
		
		$this->layout->content = View::make('dealer.show_dealer',compact('dealer','dealerTypes','khan','commune','village','createdBy','updatedBy','dealerBanks'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /dealer/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$dealer = Dealer::find($id);
		$dealerTypes = DealerType::find($dealer->dealer_type__id);
		
		$khan = DB::table('khan')
			->select('khan.*','province.name_en AS pname')
			->join('province', 'khan.province__code', '=', 'province.code')
			->where('khan.code','=',$dealer->khan__code)
			->first();	
		
		
		
		//$listProvinces = Province::lists('name_en', 'code');
		$kandCode = $dealer->khan__code;
		//$provinceCode = substr($kandCode,0,2);
		//$listKhans = Khan::where('province__code','=',$provinceCode)->lists('name_en', 'code');
		
		$listCommunes = DB::table('communes')->where('khan__code','=',$kandCode)->lists('name_en', 'code');
		$listVillages = DB::table('villages')->where('commune__code','=',$dealer->commune__code)->lists('name_en', 'code');
		
		$this->layout->content = View::make('dealer.edit_dealer',compact('dealer','dealerTypes','khan','listCommunes','listVillages'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /dealer/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();
        $validation = Validator::make($input, Dealer::$rules);
        if ($validation->passes())
        {
        	if ($input['dealer_type__id'] = '4' && Input::has('parent_id')) {
        		$parentId = Input::get('parent_id');
        		if ($parentId != '0') {
					$parent = Dealer::where('id',$parentId)
									->where(function($query)
						            {
						                $query->where('dealer_type__id', '=', 2)
											  ->orWhere('dealer_type__id',1);
						            })
									->get();
					if ($parent == null || count($parent) <= 0) {
						$msgs = array();
						$msg = array('type'=>'error','msg'=>'Parent Dealer ID Not Exist!');
						array_push($msgs,$msg);
						return Redirect::route('dealers.edit', $id)
			            ->withInput()
			            ->withErrors($validation)
						->with('msgs', $msgs);
						
					}
        		}
				
			} else {
				unset($input['parent_id']);
				$input['parent_id'] = 0;
			}
			
			if (Input::has('reference_dealer_id')) {
				$parentId = Input::get('reference_dealer_id');
				$parent = Dealer::where('id',$parentId)
								->get();
				if ($parent == null || count($parent) <= 0) {
					$msgs = array();
					$msg = array('type'=>'error','msg'=>'Reference Dealer ID Not Exist!');
					array_push($msgs,$msg);
					return Redirect::route('dealers.edit', $id)
		            ->withInput()
		            ->withErrors($validation)
					->with('msgs', $msgs);
					
				}
			}
			
			//-------------------------------------------------------
        	$ob = Dealer::find($id);
			$staffId = Auth::user()->id;
			
			$input['updated_by_staff__id']=$staffId;
			
			DB::beginTransaction();
			
			$txnUpdateTerminalDealer = true;
			$txnUpdateTerminalDealerLog = true;
			
			$txnUpdateTerminal = true;
			$txnUpdateTerminalLog = true;
			//----update terminal and terminals with status check--------------
			$msgs = array();
			if ($ob->status != $input['status']) {
				if ($ob->status == 'used') {
					$terminalDealerOb = DB::table('terminal')
						->where('dealer__id','=',$id)
						->where('status','=','active');
					
					$terminalDealer = $terminalDealerOb->first();					
					if ($terminalDealer != null) {
						
						//---------Update TerminalDealer (table terminal)---------
						$arrayUpdate = array('status'=>'suspend');
						$txnUpdateTerminalDealer = $terminalDealerOb->update($arrayUpdate);
						$obLog = array(
							'staff__id'=>$staffId,
							'action'=>'update',
							'object_type'=>'terminal',
							'object__id'=>'serial:'.$terminalDealer->serial.',did:'.$terminalDealer->dealer__id,
							'old_data'=>json_encode($terminalDealer),
							'new_data'=>json_encode($arrayUpdate),
							'reason'=>'update terminal auto when update dealer from used to'.$input['status']
							);
						$txnUpdateTerminalDealerLog = Logs::create($obLog);
						
						
						//----------Update Terminal (table terminals)-------------
						$arrayUpdate = array('status'=>'active');
						$terminalOb = DB::table('terminals')
						->where('serial','=',$terminalDealer->serial);
						
						$terminal = $terminalOb->first();
						$txnUpdateTerminal = $terminalOb->update($arrayUpdate);
						$obLog = array(
							'staff__id'=>$staffId,
							'action'=>'update',
							'object_type'=>'terminals',
							'object__id'=>$terminal->serial,
							'old_data'=>json_encode($terminal),
							'new_data'=>json_encode($arrayUpdate),
							'reason'=>'update terminals auto when update dealer from used to'.$input['status']
							);
						$txnUpdateTerminalLog = Logs::create($obLog);
						
						
						$msg1 = array('msg'=>'Update auto with Terminal-Dealer to suspend!','type'=>'success');
						$msg2 = array('msg'=>'Update auto with Termianl to active','type'=>'success');
						array_push($msgs,$msg1);
						array_push($msgs,$msg2);
					}
					
				}
			}
			//----end check----------------------------------------------------
			if ($ob->status_os == 'inactive' && $input['status_os'] == 'active') {
				$input['num_fail_authenticate_pin'] =  '0';
				$input['num_fail_security_code'] =  '0';
			}
			
			
			unset($input['dealer_type__id']);
			$obLog = array(
			'staff__id'=>$staffId,
			'action'=>'update',
			'object_type'=>'dealer',
			'object__id'=>$id,
			'old_data'=>json_encode($ob),
			'new_data'=>json_encode($input)
			);
			$successObLog = Logs::create($obLog);
			
			$successOb = $ob->update($input);
			
			$msg = array();
			if( !$successOb || !$successObLog || !$txnUpdateTerminalDealer|| !$txnUpdateTerminalDealerLog ||  !$txnUpdateTerminal || !$txnUpdateTerminalLog)
			{
			    DB::rollback();
				$msg['msg'] = 'Update dealer failed!';
				$msg['type'] = 'error';
			} else {
			    // Else commit the queries
			    DB::commit();
				$msg['msg'] = 'Update dealer successfully!';
				$msg['type'] = 'success';
			}
			array_push($msgs,$msg);
            return Redirect::route('dealers.index')->with('msgs', $msgs);
   		}
		return Redirect::route('dealers.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /dealer/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$staffId = Auth::user()->id;
		
		$ob = Dealer::find($id);
		$oldOb = $ob;
		
		DB::beginTransaction();
		$arrayUpdate = array('updated_by_staff__id'=>$staffId,'status'=>'delete');
		
		
		$obLog = array(
			'staff__id'=>$staffId,
			'action'=>'delete',
			'object_type'=>'dealer',
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
			$msg['msg'] = 'Delete dealer failed!';
			$msg['type'] = 'error';
		} else {
			// Else commit the queries
			DB::commit();
			$msg['msg'] = 'Delete dealer successfully!';
			$msg['type'] = 'success';
		}
		$msgs = array($msg);
        return Redirect::route('dealers.index')->with('msgs', $msgs);
	}
	
	private function checkDIDRecursive($id,$khanCode,$typeId,$numDealer) {
		$lastDealer = DB::table('dealer')
								->where('id','=',$id)
								->first();
								
		if ($lastDealer != null) {
			$numDealer += 1;
			
			$did = $khanCode.$typeId;		
								
			if ($numDealer <= 99) {
				if ($numDealer <= 9) {
					$did .= "00".$numDealer;
				} else {
					$did .= "0".$numDealer;
				}
			} else {
				$did .= $numDealer;
			}
			
			$updateDealerTypeTxn = DB::table('khan_dealer_types')
					->where('khan__code','=',$khanCode)
					->where('dealer_type__id','=',$typeId)
					->update(array('num_dealer'=>$numDealer));
					
					
			return $this->checkDIDRecursive($did,$khanCode,$typeId,$numDealer);
		} else {
			return $id;
		}
	}
	
	/**
	 * Get a listing of the resource.
	 * GET /location
	 *
	 * @return Response
	 */
	public function getDealerByKhanCode($code)
	{
		$dealers = Dealer::where('khan__code','=',$code)->orderBy('id','asc')->lists('name', 'id');
		return Response::json($dealers);
	}
}
