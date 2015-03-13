<?php

class SaleController extends \BaseController {

	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /sale
	 *
	 * @return Response
	 */
	public function index()
	{
		$sales = Sale::orderBy('sale_staff_id','desc')->where('status','!=','delete')->paginate(15);
		$sales_count = DB::table('sales')
             ->where('status','!=','delete')
             ->count();
			 
		$this->layout->content = View::make('sale.list_sale',compact('sales','sales_count'));
	}
	
	/**
	 * Display a listing of the resource.
	 * GET /sale
	 *
	 * @return Response
	 */
	public function search()
	{
		$inputs = Input::all();
		$db = Sale::orderBy('sale_staff_id','desc');
		if (Input::has('sale_staff_id')) {
			$db->where('sale_staff_id',$inputs['sale_staff_id']);
		}
		if (Input::has('id')) {
			$db->where('id',$inputs['id']);
		}
		if (Input::has('name')) {
			$db->where('name','LIKE','%'.$inputs['name'].'%');
		}
		if (Input::has('status')) {
			$db->where('status',$inputs['status']);
		}
		
		$sales = $db->where('status','!=','delete')->paginate(15)->appends($inputs);
		$this->layout->content = View::make('sale.list_sale',compact('sales'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /sale/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$provinces = DB::table('province')->get();
		$this->layout->content = View::make('sale.create_sale',compact('provinces'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /sale
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$input['status'] = 'active';
        $validation = Validator::make($input, Sale::$rules);
		if ($validation->passes()) {
			Sale::$rules['id'] = 'unique:sales,id';
	        $validation = Validator::make($input, Sale::$rules);
			if ($validation->passes())
	        {
	        	if ($input['id'] == '') {
					$input['id'] = null;
				}


	        	$staffId = Auth::user()->id;
				$input['staff__id']=$staffId;
				
				if ($input['parent_id'] == '') {
					unset($input['parent_id']);
				}
				
				// Start transaction
				DB::beginTransaction();
				
				if (Input::has('hold_dealers')) {
					$dealers = Input::get('hold_dealers');
					$input['hold_dealers'] = json_encode($dealers);
					//print_r($dealers);
				}
				
				$sale = Sale::create($input);
				$saleLog = array(
				'staff__id'=>$staffId,
				'action'=>'add',
				'object_type'=>'sale',
				'object__id'=>$sale->id,
				'new_data'=>json_encode($input)
				);
				$saleLog = Logs::create($saleLog);
				$msg = array();
				if( !$sale || !$saleLog )
				{
				    DB::rollback();
					$msg['msg'] = 'Create sale staff failed!';
					$msg['type'] = 'error';
				} else {
				    // Else commit the queries
				    DB::commit();
					$msg['msg'] = 'Create sale staff successfully!';
					$msg['type'] = 'success';
				}
				$msgs = array($msg);
				return Redirect::route('sales.index')->with('msgs', $msgs);
	        }
		}
		return Redirect::route('sales.create')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified resource.
	 * GET /sale/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$sale = Sale::find($id);
		
		$holdDealers = null;
		if ($sale->hold_dealers != '') {
			$holdDealers = json_decode($sale->hold_dealers);
			$holdDealers = DB::table("dealer")->whereIn('id',$holdDealers)
							->select('id','name')->get();
		}
		$this->layout->content = View::make('sale.show_sale',compact('sale','holdDealers'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /sale/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$sale = Sale::find($id);
		$provinces = DB::table('province')->get();
		$this->layout->content = View::make('sale.edit_sale',compact('sale','provinces'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /sale/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();
        $validation = Validator::make($input, Sale::$rules);
		
		$msgs = array();
        if ($validation->passes())
        {
        	$ob = Sale::find($id);
			
			$validate = true;
        	if ($ob->id != $input['id']) {
        		//----------check in sale visit------------------
        		$sales = DB::table('sales_visit')->where('sales__id',$ob->id)->get();
				if ($sales != null) {
					$msg['msg'] = 'Can not update Ecard ID because it was already recorded in sale visit system!';
					$msg['type'] = 'error';
					array_push($msgs,$msg);
					return Redirect::back()
				            ->withInput()
				            ->with('msgs', $msgs);
				}
			
				//---------check unique-------------------------
			
        		$validate = false;
        		Sale::$rules['id'] = 'unique:sales,id';
		        $validation = Validator::make($input, Sale::$rules);
				if ($validation->passes())
		        {
		        	$validate = true;
		        }
        	}
			
			if ($validate) {
				$staffId = Auth::user()->id;
				
				if ($input['id'] == '') {
					$input['id'] = null;
				}
				
				
				if ($input['parent_id'] == '') {
					unset($input['parent_id']);
				}
				$input['updated_by_staff__id']=$staffId;
				if (Input::has('hold_dealers')) {
					$dealers = Input::get('hold_dealers');
					$input['hold_dealers'] = json_encode($dealers);
				} else {
					$input['hold_dealers'] = "";
				}
				DB::beginTransaction();
				$obLog = array(
				'staff__id'=>$staffId,
				'action'=>'update',
				'object_type'=>'sale',
				'object__id'=>$id,
				'old_data'=>json_encode($ob),
				'new_data'=>json_encode($input)
				);
				$successOb = $ob->update($input);
				$successObLog = Logs::create($obLog);
				$msg = array();
				if( !$successOb || !$successObLog )
				{
				    DB::rollback();
					$msg['msg'] = 'Update sale failed!';
					$msg['type'] = 'error';
				} else {
				    // Else commit the queries
				    DB::commit();
					$msg['msg'] = 'Update sale successfully!';
					$msg['type'] = 'success';
				}
				$msgs = array($msg);
	            return Redirect::route('sales.index')->with('msgs', $msgs);
			}
        }
		return Redirect::route('sales.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /sale/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$ob = Sale::find($id);
		$oldOb = $ob;
		//$ob['status'] = 'delete';
		
		DB::beginTransaction();
		$staffId = Auth::user()->id;
		$arrayUpdate = array('updated_by_staff__id'=>$staffId,'status'=>'delete');
		
		$obLog = array(
			'staff__id'=>$staffId,
			'action'=>'delete',
			'object_type'=>'sale',
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
			$msg['msg'] = 'Delete sale staff failed!';
			$msg['type'] = 'error';
		} else {
			// Else commit the queries
			DB::commit();
			$msg['msg'] = 'Delete sale staff successfully!';
			$msg['type'] = 'success';
		}
		$msgs = array($msg);
        return Redirect::route('sales.index')->with('msgs', $msgs);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /sale/create
	 *
	 * @return Response
	 */
	public function target()
	{
		$inputs = Input::All();
		
		$db = DB::table('rep_sale_staff_targeting as sst')
			            ->join('sales as ss', 'ss.sale_staff_id', '=', 'sst.sale_staff__id')
						->join('sys_enumeration as m', 'm.enum_id', '=', 'sst.target_month')
						->join('staff as s','s.id','=','sst.staff__id')
						->orderBy('ss.name','asc')
						->orderBy('m.sequence_num','asc')
						->orderBy('sst.target_week','asc')
						->select('sst.*','s.name as createdBy','ss.name as saleStaffName');
		
		
		if (Input::has('sale_staff_id')) {
			$db->where('sst.sale_staff__id',$inputs['sale_staff_id']);
		}	
		if (Input::has('name')) {
			$db->where('ss.name','LIKE','%'.$inputs['name'].'%');
		}
		if (Input::has('year')) {
			$db->where('sst.target_year',$inputs['year']);
		}
		
		$saleStaffTargets = $db->paginate(24)->appends($inputs);
		$this->layout->content = View::make('sale_staff_target.list_sale_staff_target',compact('saleStaffTargets'));
	}

	/**
	 * GET /uploadSaleTarget/create
	 *
	 * @return Response
	 */
	public function uploadSaleTarget()
	{
		$this->layout->content = View::make('sale.upload_sale');
	}

	/**
	 * POST /storeUploadSaleTarget
	 *
	 * @return Response
	 */
	public function storeUploadSaleTarget()
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
					$input['updated_at'] = $dateTime;
					$input['status'] = 'active';
					$input['type_upload'] = 'Staff Targeting';
					$input['updated_by_staff__id']=$staffId;


					$realFilePath = $destinationPath.$filename;
					$excelList = Excel::load($realFilePath, function($reader) {})->toArray();

					DB::beginTransaction();
					try {

						$ob = SysUpload::create($input);
						$obLog = array(
							'staff__id'=>$staffId,
							'action'=>'upload',
							'object_type'=>'upload_sale_target',
							'object__id'=>$ob->channel_sale_upload_id,
							'new_data'=>json_encode($input)
						);
						$obLog = Logs::create($obLog);

						$counter = 0 ;
						$target_year = 0;
						$saleStaffId = 0;
						$exit = false;
						foreach ($excelList as $index => $row) {
							$row = array_values($row);

							if($counter==0){
								$target_year = $row[1];
							}
							if($counter==1){
								$saleStaffId = $row[1];
							}

							if($counter++ < 4) continue ;

							$target_week = $row[0];
							$target_sale_game = $row[1];
							$target_num_sale_visit = $row[2];
							$target_topup_game = $row[3];
							$target_num_new_recruit = $row[4];

							$channelSaleArr = array(
								'staff__id'=>$staffId,
								'sale_staff__id'=>$saleStaffId,
								'updated_by_staff__id'=>$staffId,
								'target_year'=>$target_year,
								'target_month'=>'Feb',
								'target_week'=>$target_week,
								'target_sale_game'=>$target_sale_game,
								'target_topup_game'=>$target_topup_game,
								'target_num_new_recruit'=>$target_num_new_recruit,
								'target_num_sale_visit'=>$target_num_sale_visit,
								'upload_sale_staff_targeting__id'=>$ob->upload_id,
								'status'=>'active',
							);
							if ($target_week == null || $target_week == '') {
								$msg['msg'] = 'Week = '.$target_week.' was blank';
								$msg['type'] = 'error';
								array_push($msgs,$msg);
								$exit = true;
								continue;
							}

							$staffTargetsExist = SaleStaffTarget::where('sale_staff__id', '=', $saleStaffId)
											->where('target_week',$target_week)->first();
							if ($staffTargetsExist != null) {
								$msg['msg'] = 'Week = '.$target_week.' was exist';
								$msg['type'] = 'error';
								array_push($msgs,$msg);
								$exit = true;
								continue;
							}
							/*foreach($staffTargets as $item){
								if($target_week == $item->target_week) {
									continue 2;

									$msg['msg'] = 'Upload  Dragon Warrior Quota Failed!'.$e;
									$msg['type'] = 'error';
									$msgs = array($msg);
									break;
								}
							}*/
							SaleStaffTarget::create($channelSaleArr);
						}
						if ($exit) {
							DB::rollback();
							return Redirect::back()
								->withInput()
								->withErrors($validation)
								->with('msgs', $msgs);
						}

						DB::commit();

						$msg['msg'] = 'Upload  Dragon Warrior Quota Successfully!';
						$msg['type'] = 'success';
						$msgs = array($msg);

					} catch (\Exception $e) {
						DB::rollback();
						$msg['msg'] = 'Upload  Dragon Warrior Quota Failed!'.$e;
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
	 * Show the form for creating a new resource.
	 * GET /sale/create
	 *
	 * @return Response
	 */
	public function createTarget()
	{
		$staffId = Auth::user()->id;

		$saleStaffs = DB::table('sales')
						->orderBy('name','asc')
						->where('status','active')
						->lists('name','sale_staff_id');

		$months = DB::table('sys_enumeration')
						->where('enum_type__id','month')
						->orderBy('sequence_num','asc')
						->lists('name','enum_id');
		//-----------------------List--------------------------
		$today = date("Y-m-j");

		$db = DB::table('rep_sale_staff_targeting as sst')
			            ->join('sales as ss', 'ss.sale_staff_id', '=', 'sst.sale_staff__id')
						->join('sys_enumeration as m', 'm.enum_id', '=', 'sst.target_month')
						->join('staff as s','s.id','=','sst.staff__id')
						->orderBy('sst.created_at','desc')
						//->orderBy('ss.name','asc')
						//->orderBy('m.sequence_num','asc')
						->select('sst.*','s.name as createdBy','ss.name as saleStaffName');


		$db->where('sst.staff__id',$staffId);
		$db->where('sst.created_at','>=',$today);

		$saleStaffTargets = $db->paginate(15);
		//------------------------------------------------------

		$this->layout->content = View::make('sale_staff_target.create_sale_staff_target',compact('saleStaffTargets','saleStaffs','months'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 * GET /sale/create
	 *
	 * @return Response
	 */
	public function storeTarget()
	{
		$input = Input::all();
        $validation = Validator::make($input, SaleStaffTarget::$rules);
		$msgs = array();
		$staffId = Auth::user()->id;
        if ($validation->passes())
        {
        	try {
        		DB::beginTransaction();
				
        		$target = $db = DB::table('rep_sale_staff_targeting as sst')
			            ->where('sale_staff__id',$input['sale_staff__id'])
						->where('target_year',$input['target_year'])
						->where('target_month',$input['target_month'])
						->where('target_week',$input['target_week'])
						->first();
        		if ($target ==null) {
					$input['staff__id']=$staffId;
					
					$ob = SaleStaffTarget::create($input);
					$obLog = array(
						'staff__id'=>$staffId,
						'action'=>'add',
						'object_type'=>'dragon_warrior_target',
						'object__id'=>$ob->sale_staff_target_id,
						'new_data'=>json_encode($input)
					);
					$obLog = Logs::create($obLog);
					
	        		DB::commit();
					$msg['msg'] = 'Create Dragon Warrior Target Successfully!';
					$msg['type'] = 'success';
					array_push($msgs,$msg);
					return Redirect::back()->with('msgs', $msgs);
        		} else {
        			$msg['msg'] = 'This date was already sat!';
					$msg['type'] = 'error';
					array_push($msgs,$msg);
        		}				
        	} catch (Exception $e) {
        		DB::rollback();
				$msg['msg'] = 'Create Dragon Warrior Target Failed! Error = '.$e;
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
	 * Show the form for creating a new resource.
	 * GET /sale/create
	 *
	 * @return Response
	 */
	public function editTarget($id)
	{
		$target = DB::table('rep_sale_staff_targeting as sst')
						->select('sst.*')
						->where('sst.sale_staff_target_id',$id)
						->first();
		
		$saleStaffs = DB::table('sales')
						->orderBy('name','asc')
						->where('status','active')
						->lists('name','sale_staff_id');
		
		$months = DB::table('sys_enumeration')
						->where('enum_type__id','month')
						->orderBy('sequence_num','asc')
						->lists('name','enum_id');
				
		$this->layout->content = View::make('sale_staff_target.edit_sale_staff_target',compact('saleStaffs','months','target'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 * GET /sale/create
	 *
	 * @return Response
	 */
	public function updateTarget()
	{
		$input = Input::all();
        $validation = Validator::make($input, SaleStaffTarget::$rules);
		$msgs = array();
        if ($validation->passes())
        {
        	try {
        		DB::beginTransaction();
				
				$staffId = Auth::user()->id;
				$input['updated_by_staff__id']=$staffId;
				
				$id = $input['sale_staff_target_id'];
				
        		$obTarget = SaleStaffTarget::find($id);
					
				if ($obTarget->sale_staff__id != $input['sale_staff__id']
				|| $obTarget->target_year != $input['target_year']
				|| $obTarget->target_month != $input['target_month']
				|| $obTarget->target_week != $input['target_week']) {
					$target = $db = DB::table('rep_sale_staff_targeting as sst')
			            ->where('sale_staff__id',$input['sale_staff__id'])
						->where('target_year',$input['target_year'])
						->where('target_month',$input['target_month'])
						->where('target_week',$input['target_week'])
						->first();
						
					if ($target !=null) {
	        			$msg['msg'] = 'This date was already sat!';
						$msg['type'] = 'error';
						array_push($msgs,$msg);
						return Redirect::back()
				            ->withInput()
				            ->with('msgs', $msgs);
	        		}			
						
				}					
				$obLog = array(
					'staff__id'=>$staffId,
					'action'=>'update',
					'object_type'=>'dragon_warrior_target',
					'object__id'=>$obTarget->sale_staff_target_id,
					'new_data'=>json_encode($input),
					'old_data'=>json_encode($obTarget)
				);
				$obLog = Logs::create($obLog);
				$ob = $obTarget->update($input);
				
        		DB::commit();
				$msg['msg'] = 'Update Dragon Warrior Target Successfully!';
				$msg['type'] = 'success';
				array_push($msgs,$msg);
				return Redirect::to('sales/target')->with('msgs', $msgs);
        		
        	} catch (Exception $e) {
        		DB::rollback();
				$msg['msg'] = 'Update Dragon Warrior Target Failed! Error = '.$e;
				$msg['type'] = 'error';
				array_push($msgs,$msg);
        	}
	    	
        }
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}


	
}