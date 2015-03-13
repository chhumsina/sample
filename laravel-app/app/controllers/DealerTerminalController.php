<?php

class DealerTerminalController extends \BaseController {

	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /dealerterminal
	 *
	 * @return Response
	 */
	public function index()
	{
		$dealerTerminals = DB::table('terminal')
			            ->select('terminal.*','dealer.name AS dName','sc.name AS crbName','su.name AS upbName')
						->join('dealer', 'terminal.dealer__id', '=', 'dealer.id')
						->leftJoin('staff AS sc','sc.id','=','terminal.staff_id')
						->leftJoin('staff AS su','su.id','=','terminal.updated_by_staff__id')
						->orderBy('terminal.datetime','desc')
						->where('terminal.status','!=','delete')
						->paginate(10);
		
		$this->layout->content = View::make('dealer-terminal.list_dealer_link_terminal',compact('dealerTerminals'));
	}
	
	/**
	 * Display a listing of the resource.
	 * GET /announcement
	 *
	 * @return Response
	 */
	public function search()
	{
		$inputs = Input::all();
		$db = DB::table('terminal')
						->select('terminal.*','dealer.name AS dName','sc.name AS crbName','su.name AS upbName')
						->join('dealer', 'terminal.dealer__id', '=', 'dealer.id')
						->leftJoin('staff AS sc','sc.id','=','terminal.staff_id')
						->leftJoin('staff AS su','su.id','=','terminal.updated_by_staff__id')
						->orderBy('terminal.datetime','desc')
						->where('terminal.status','!=','delete');
							
		if (Input::has('did')) {
			$db->where('terminal.dealer__id','=',$inputs['did']);
		}
		if (Input::has('name')) {
			$db->where('dealer.name','LIKE',"%".$inputs['name']."%");
		}
		if (Input::has('serial')) {
			$db->where('terminal.serial','LIKE',$inputs['serial']);
		}
		if (Input::has('status')) {
			$db->where('terminal.status',$inputs['status']);
		}
		$n = 15;
		if (Input::has('n_record')) {
			$n = Input::get('n_record');
		}		
		$dealerTerminals = $db->paginate($n)->appends($inputs);
		$this->layout->content = View::make('dealer-terminal.list_dealer_link_terminal',compact('dealerTerminals'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /dealerterminal/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$dealers = Dealer::where('status','active')->orderBy('id','asc')->lists('name','id');
		$this->layout->content = View::make('dealer-terminal.create_dealer_link_terminal',compact('dealers'));
		//$terminals = Terminal::where('status','active')->orderBy('created_at','desc')->lists('serial','serial');
		//$this->layout->content = View::make('dealer-terminal.create_dealer_link_terminal',compact('dealers','terminals'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /dealerterminal
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
        $validation = Validator::make($input, DealerTerminal::$rules);

		// Start transaction
		DB::beginTransaction();
		
		$txnCreateLinkDealerTerminal = false;
		$txnCreateLinkDealerTerminalLog = false;
		$txnUpdateTerminalStatus = false;
		$txnUpdateTerminalStatusLog = false;
		$txnUpdateDealerStatus = false;
		$txnUpdateDealerStatusLog = false;
					
		$msgs = array();
		$msg = array();
		if ($validation->passes())
        {
        	$dateTime = date("Y-m-d H:i:s");
        	
        	$staffId = Auth::user()->id;
			$terminal = DB::table('terminals')->where('serial',$input['serial'])->first();
			if ($terminal != null) {
				if ($terminal->status == 'active') {
					$dealer = DB::table('dealer')->where('id',$input['dealer__id'])->first();
					if ($dealer != null) {
						if ($dealer->status == 'active') {
							$dealerTerminal_serial = DB::table('terminal')
													->where('serial',$input['serial'])
													->where('status','active')
													->first();
																		
							if ($dealerTerminal_serial == null) {
								$dealerTerminal_did = DB::table('terminal')
													->where('dealer__id',$input['dealer__id'])
													->where('status','active')
													->first();
								if ($dealerTerminal_did == null) {
									$dealerTerminal = DB::table('terminal')
													->where('serial',$input['serial'])
													->where('dealer__id',$input['dealer__id'])
													->first();
									if ($dealerTerminal == null) {
										//-------------Add terminal-dealer---------------------
										$input['name'] = '';
										$input['imsi'] = $terminal->imsi;
										$input['ecard_id'] = $terminal->ecard_id;									
										$input['status'] = 'active';
										$input['staff_id'] = $staffId;
										$input['datetime'] = $dateTime;
										
										if (input::has('_token')) {
											unset($input['_token']);
										}
										$txnCreateLinkDealerTerminal = DB::table('terminal')
																	->insert($input);	
										
										$obLog = array(
										'staff__id'=>$staffId,
										'action'=>'add',
										'object_type'=>'terminal',
										'object__id'=>'serial:'.$input['serial'].','.'did:'.$input['dealer__id'],
										'new_data'=>json_encode($input)
										);
										$txnCreateLinkDealerTerminalLog = Logs::create($obLog);
																			
										//-------------Update Terminal status to used-----------
										$arrayUpdate = array('updated_by_staff__id'=>$staffId,'updated_at'=>$dateTime,'status'=>'used');
										$txnUpdateTerminalStatus = DB::table('terminals')->where('serial',$input['serial'])
																		->update($arrayUpdate);
																		
										$obLog = array(
											'staff__id'=>$staffId,
											'action'=>'update',
											'object_type'=>'terminals',
											'object__id'=>$terminal->serial,
											'old_data'=>json_encode($terminal),
											'new_data'=>json_encode($arrayUpdate),
											'reason'=>'Update terminals status to be used auto when add dealer-link-terminal'
										);
										$txnUpdateTerminalStatusLog = Logs::create($obLog);	
																		
										//-------------Update Dealer status to used-------------
										$arrayUpdate = array('updated_by_staff__id'=>$staffId,'updated_at'=>$dateTime,'status'=>'used');
										$txnUpdateDealerStatus = DB::table('dealer')->where('id',$input['dealer__id'])
																		->update($arrayUpdate);
																		
										$obLog = array(
											'staff__id'=>$staffId,
											'action'=>'update',
											'object_type'=>'dealer',
											'object__id'=>$input['dealer__id'],
											'old_data'=>json_encode($dealer),
											'new_data'=>json_encode($arrayUpdate),
											'reason'=>'Updete dealer status to be used auto when add dealer-link-terminal'
										);
										$txnUpdateDealerStatusLog = Logs::create($obLog);
			
			
										if( !$txnCreateLinkDealerTerminal || !$txnCreateLinkDealerTerminalLog ||
											!$txnUpdateTerminalStatus || !$txnUpdateTerminalStatusLog ||
											!$txnUpdateDealerStatus || !$txnUpdateDealerStatusLog )
										{
										    DB::rollback();
											$msg['msg'] = 'Create Dealer-Link-Terminal failed!';
											$msg['type'] = 'error';
											array_push($msgs,$msg);
										} else {
										    DB::commit();
											$msg = array('msg'=>'Create Dealer-Link-Terminal successfully!','type'=>'success');
											array_push($msgs,$msg);
											$msg = array('msg'=>'Update Termianl to be used!','type'=>'success');
											array_push($msgs,$msg);
											$msg = array('msg'=>'Update Dealer to be used!','type'=>'success');
											array_push($msgs,$msg);
										}
										return Redirect::route('dealer-terminals.index')->with('msgs', $msgs);
									} else {
										$msg = array('msg'=>'Terminal and Dealer was already linked! its status = '.$dealerTerminal->status,'type'=>'error');
										array_push($msgs,$msg);
									}
								} else {
									$pls = 'Please contact administrator!';
									$msg = array('msg'=>'Dealer was used with Terminal serial = '.$dealerTerminal_did->serial.' '.$pls,
												 'type'=>'error');
									array_push($msgs,$msg);
								}
							} else {
								$pls = 'Please contact administrator!';
								$msg = array('msg'=>'Terminal was used with dealer ID = '.$dealerTerminal_serial->dealer__id.' '.$pls,
											 'type'=>'error');
								array_push($msgs,$msg);
							}
						} else {
							$msg = array('msg'=>"Dealer ID was ".$dealer->status."!",'type'=>'error');
							array_push($msgs,$msg);
						}
					} else {
						$msg = array('msg'=>'Dealer ID was not exist!','type'=>'error');
						array_push($msgs,$msg);
					}
				} else {
					$msg = array('msg'=>"Terminal serial was ".$terminal->status."!",'type'=>'error');
					array_push($msgs,$msg);
				}
			} else {
				$msg = array('msg'=>'Terminal serial was not exist!','type'=>'error');
				array_push($msgs,$msg);
			}
        }
		return Redirect::route('dealer-terminals.create')
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}

	/**
	 * Display the specified resource.
	 * GET /dealerterminal/{id}
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
	 * GET /dealerterminal/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$dealerTerminal = DB::table('terminal')
							->where('serial',$id)
							->where('dealer__id',Input::get('did'))
							->first();
		$this->layout->content = View::make('dealer-terminal.edit_dealer_link_terminal',compact('dealerTerminal'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /dealerterminal/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$serial = $id;
		$input = Input::all();
        $validation = Validator::make($input, DealerTerminal::$rules);
		
		// Start transaction
		DB::beginTransaction();
		
		$txnUpdateLinkDealerTerminal = false;
		$txnUpdateLinkDealerTerminalLog = false;
		$txnUpdateTerminalStatus = false;
		$txnUpdateTerminalStatusLog = false;
		$txnUpdateDealerStatus = false;
		$txnUpdateDealerStatusLog = false;
					
		$msgs = array();
		$msg = array();
        if ($validation->passes())
        {
        	$dateTime = date("Y-m-d H:i:s");
			$staffId = Auth::user()->id;
            $status = $input['status'];
			$did = Input::get('did');
			
		   	$arrayUpdate = array('status'=>$status,'updated_at'=>$dateTime,'updated_by_staff__id'=>$staffId);
			$dealerTerminalDb = DB::table('terminal')
								->where('serial',$serial)
								->where('dealer__id',$did);
			$dealerTerminal = $dealerTerminalDb->first();
			//--------end Update Dealer-link-Terminal--------------				
			if ($status != $dealerTerminal->status) {
				if ($status == 'suspend') {
					//-------------Update Terminal status to active-----------
					$arrayUpdate = array('updated_by_staff__id'=>$staffId,'updated_at'=>$dateTime,'status'=>'active');
					$terminalDb = DB::table('terminals')->where('serial',$serial);
					
					$terminal	= $terminalDb->first();						
					$txnUpdateTerminalStatus = 	$terminalDb->update($arrayUpdate);							
					$obLog = array(
						'staff__id'=>$staffId,
						'action'=>'update',
						'object_type'=>'terminals',
						'object__id'=>$terminal->serial,
						'old_data'=>json_encode($terminal),
						'new_data'=>json_encode($arrayUpdate),
						'reason'=>'Update terminals status to be active auto when update status of dealer-link-terminal to suspend'
					);
					$txnUpdateTerminalStatusLog = Logs::create($obLog);							
					//-------------Update Dealer status to active-------------
					$arrayUpdate = array('updated_by_staff__id'=>$staffId,'updated_at'=>$dateTime,'status'=>'active');
					$dealerDb = DB::table('dealer')->where('id',$did);
					
					$dealer = $dealerDb->first();
					$txnUpdateDealerStatus = $dealerDb->update($arrayUpdate);						
					$obLog = array(
						'staff__id'=>$staffId,
						'action'=>'update',
						'object_type'=>'dealer',
						'object__id'=>$dealer->id,
						'old_data'=>json_encode($dealer),
						'new_data'=>json_encode($arrayUpdate),
						'reason'=>'Updete dealer status to be active auto when update status of dealer-link-terminal to suspend'
					);
					$txnUpdateDealerStatusLog = Logs::create($obLog);
					$statChange = 'active';
				} else if ($status == 'active') {
					//-------------Update Terminal status to used-----------
					$arrayUpdate = array('updated_by_staff__id'=>$staffId,'updated_at'=>$dateTime,'status'=>'used');
					$terminalDb = DB::table('terminals')->where('serial',$serial);
					$terminal	= $terminalDb->first();
					
					$terminalLinkDb = DB::table('terminal')->where('serial',$serial)->where('status','active');
					$terminalLink	= $terminalLinkDb->first();
					
					if ($terminalLink != null) {
						$msg = array('msg'=>"You can not update while Terminal serial = ".$serial." with status was active in Dealer-Link-Terminal",'type'=>'error');
						array_push($msgs,$msg);
					} else {
						if ($terminal->status == 'active') {
							$txnUpdateTerminalStatus = 	$terminalDb->update($arrayUpdate);							
							$obLog = array(
								'staff__id'=>$staffId,
								'action'=>'update',
								'object_type'=>'terminals',
								'object__id'=>$terminal->serial,
								'old_data'=>json_encode($terminal),
								'new_data'=>json_encode($arrayUpdate),
								'reason'=>'Update terminals status to be used auto when update status of dealer-link-terminal to active'
							);
							$txnUpdateTerminalStatusLog = Logs::create($obLog);
							$statChange = 'used';			
						} else if ($terminal->status == 'used') {
							$msg = array('msg'=>"You can not update while Terminal serial = ".$serial." was used!",'type'=>'error');
							array_push($msgs,$msg);
						} else {
							$msg = array('msg'=>"You can not update while Terminal serial = ".$serial." with status was ".$terminal->status."!",'type'=>'error');
							array_push($msgs,$msg);
						}
					}
									
					
					
					//-------------Update Dealer status to used-----------
					$arrayUpdate = array('updated_by_staff__id'=>$staffId,'updated_at'=>$dateTime,'status'=>'used');
					$dealerDb = DB::table('dealer')->where('id',$did);
					$dealer	= $dealerDb->first();
					
					$dealerLinkDb = DB::table('terminal')->where('dealer__id',$did)->where('status','active');
					$dealerLink	= $dealerLinkDb->get();
					
					if ($dealerLink != null) {
						$msg = array('msg'=>"You can not update while Dealer Id = ".$did." with status was active in Dealer-Link-Terminal",'type'=>'error');
						array_push($msgs,$msg);
					} else {
						if ($dealer->status == 'active') {
							$txnUpdateDealerStatus = 	$dealerDb->update($arrayUpdate);							
							$obLog = array(
								'staff__id'=>$staffId,
								'action'=>'update',
								'object_type'=>'dealer',
								'object__id'=>$dealer->id,
								'old_data'=>json_encode($terminal),
								'new_data'=>json_encode($arrayUpdate),
								'reason'=>'Update dealer status to be used auto when update status of dealer-link-terminal to active'
							);
							$txnUpdateDealerStatusLog = Logs::create($obLog);
							$statChange = 'used';	
						} else if ($dealer->status == 'used') {
							$msg = array('msg'=>"You can not update while Dealer ID = ".$did." was used!",'type'=>'error');
							array_push($msgs,$msg);
						} else {
							$msg = array('msg'=>"You can not update while Dealer ID = ".$did." with status was ".$dealer->status."!",'type'=>'error');
							array_push($msgs,$msg);
						}
					}
				}


				$arrayUpdate = array('status'=>$status,'updated_at'=>$dateTime,'updated_by_staff__id'=>$staffId);
				//------------Update Dealer-link-Terminal--------------
				$txnUpdateLinkDealerTerminal = $dealerTerminalDb->update($arrayUpdate);
				$obLog = array(
					'staff__id'=>$staffId,
					'action'=>'update',
					'object_type'=>'terminal',
					'object__id'=>'serial:'.$serial.','.'did:'.$did,
					'old_data'=>json_encode($dealerTerminal),
					'new_data'=>json_encode($arrayUpdate)
				);
				$txnUpdateLinkDealerTerminalLog = Logs::create($obLog);
				
				if( !$txnUpdateLinkDealerTerminal || !$txnUpdateLinkDealerTerminalLog ||
					!$txnUpdateTerminalStatus || !$txnUpdateTerminalStatusLog ||
					!$txnUpdateDealerStatus || !$txnUpdateDealerStatusLog )
				{
				    DB::rollback();
					$msg['msg'] = 'Update Dealer-Link-Terminal failed!';
					$msg['type'] = 'error';
					array_push($msgs,$msg);
				} else {
				    DB::commit();
					$msg = array('msg'=>'Update Dealer-Link-Terminal successfully!','type'=>'success');
					array_push($msgs,$msg);
					$msg = array('msg'=>'Update Dealer to be '.$statChange.'!','type'=>'success');
					array_push($msgs,$msg);
					$msg = array('msg'=>'Update Terminal to be '.$statChange.'!','type'=>'success');
					array_push($msgs,$msg);	
					return Redirect::route('dealer-terminals.index')->with('msgs', $msgs);
				}
			}
        }
		//return Redirect::back()->with('success','Record deleted successfully');
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.')
            ->with('msgs', $msgs);
			//link_to_route('dealer-terminals.edit', 'Edit', array($dealerTerminal->serial,'did'=>$dealerTerminal->dealer__id));
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /dealerterminal/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
