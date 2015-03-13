<?php

class DealerBankController extends \BaseController {
	
	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /dealerbank
	 *
	 * @return Response
	 */
	public function index()
	{
		$dealerBanks = DB::table('dealer_banks as db')
			            ->select('db.*','d.name AS dName','d.id AS did','b.*','su.name as upbStaffName','db.status as dealer_bank_status')
						->join('dealer as d', 'd.id', '=', 'db.dealer__id')
						->join('bank as b', 'b.bid', '=', 'db.bank__id')
						->leftJoin('staff AS su','su.id','=','db.updated_by_staff__id')
						->orderBy('d.id','asc')
						->where('db.status','!=','delete')
						->paginate(15);
		
		$banks = DB::table('bank')
				->where('status','active')
				->where('classify','realBank')->lists('bank_name', 'bid');
		$this->layout->content = View::make('dealer-bank.list_dealer_link_bank',compact('dealerBanks','banks'));
	}
	
	/**
	 * Display a listing of the resource.
	 * GET /dealerbank
	 *
	 * @return Response
	 */
	public function search()
	{
		$inputs = Input::all();
		$db = DB::table('dealer_banks as db')
			            ->select('db.*','d.name AS dName','d.id AS did','b.*','su.name as upbStaffName','db.status as dealer_bank_status')
						->join('dealer as d', 'd.id', '=', 'db.dealer__id')
						->join('bank as b', 'b.bid', '=', 'db.bank__id')
						->leftJoin('staff AS su','su.id','=','db.updated_by_staff__id')
						->orderBy('d.id','asc')
						->where('db.status','!=','delete');
						
		if (Input::has('did')) {
			$db->where('d.id',$inputs['did']);
		}
		if (Input::has('name')) {
			$db->where('d.name','LIKE','%'.$inputs['name'].'%');
		}
		if (Input::has('bank__id')) {
			$db->where('db.bank__id',$inputs['bank__id']);
		}
		if (Input::has('status')) {
			$db->where('db.status',$inputs['status']);
		}
			
		$dealerBanks = $db->paginate(15)->appends($inputs);
		$banks = DB::table('bank')
				->where('status','active')
				->where('classify','realBank')->lists('bank_name', 'bid');
		$this->layout->content = View::make('dealer-bank.list_dealer_link_bank',compact('dealerBanks','banks'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /dealerbank/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		$banks = DB::table('bank')
				->where('status','active')
				->where('classify','realBank')->lists('bank_name', 'bid');
		$dealers = Dealer::where('status','active')
				->orWhere(function($query)
	            {
	                //$query->where('status', '=', 'active')
	                  $query->where('status', '=', 'used');
	            })
				->where('dealer_type__id','!=','9')
				->orderBy('id','asc')->lists('name','id');
		$this->layout->content = View::make('dealer-bank.create_dealer_link_bank',compact('banks','dealers'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /dealerbank
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		
        $validation = Validator::make($input, DealerBank::$rules);

        if ($validation->passes())
        {
        	$staffId = Auth::user()->id;
			$input['staff__id'] = $staffId;
			$input['updated_by_staff__id'] = $staffId;
			$input['status'] = 'active';
        	// Start transaction
			DB::beginTransaction();
			
			$dealerBankOb = DealerBank::create($input);
			$dealerBankLog = array(
			'staff__id'=>$staffId,
			'action'=>'add',
			'object_type'=>'dealer_banks',
			'object__id'=>$dealerBankOb->dealer_bank_id,
			'new_data'=>json_encode($input)
			);
			$dealerBankObLog = Logs::create($dealerBankLog);
			$msg = array();
			if( !$dealerBankOb || !$dealerBankObLog)
			{
			    DB::rollback();
				$msg['msg'] = 'Create dealer-link-bank account failed!';
				$msg['type'] = 'error';
				$msgs = array($msg);
			} else {
			    DB::commit();
				$msg['msg'] = 'Create dealer-link-bank account successfully!';
				$msg['type'] = 'success';
				$msgs = array($msg);
				return Redirect::route('dealer-banks.index')->with('msgs', $msgs);
			}
			
        }
		return Redirect::route('dealer-banks.create')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified resource.
	 * GET /dealerbank/{id}
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
	 * GET /dealerbank/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$dealerBank = DB::table('dealer_banks as db')
			            ->select('db.*','d.name AS dName','d.id AS did','b.*','su.name as upbStaffName','db.status as dealer_bank_status')
						->join('dealer as d', 'd.id', '=', 'db.dealer__id')
						->join('bank as b', 'b.bid', '=', 'db.bank__id')
						->leftJoin('staff AS su','su.id','=','db.updated_by_staff__id')
						->where('db.dealer_bank_id','=',$id)
						->first();
						
		$this->layout->content = View::make('dealer-bank.edit_dealer_link_bank',compact('dealerBank'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /dealerbank/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();
		
		$rules = array(
		    'account_name' => 'required',
		    'account' => 'required',
		);
        $validation = Validator::make($input, $rules);

		$msgs = array();
        if ($validation->passes())
        {
        	$staffId = Auth::user()->id;
			$input['updated_by_staff__id'] = $staffId;
        	// Start transaction
			DB::beginTransaction();
			
			$ob = DealerBank::find($id);
			
			
			$dealerBankLog = array(
			'staff__id'=>$staffId,
			'action'=>'update',
			'object_type'=>'dealer_banks',
			'object__id'=>$ob->dealer_bank_id,
			'new_data'=>json_encode($input),
			'old_data'=>json_encode($ob)
			);
			$dealerBankObLog = Logs::create($dealerBankLog);
			
			$dealerBankOb = $ob->update($input);
			
			$msg = array();
			if( !$dealerBankOb || !$dealerBankObLog)
			{
			    DB::rollback();
				$msg['msg'] = 'Update dealer-link-bank account failed!';
				$msg['type'] = 'error';
				$msgs = array($msg);
			} else {
			    DB::commit();
				$msg['msg'] = 'Update dealer-link-bank account successfully!';
				$msg['type'] = 'success';
				$msgs = array($msg);
				return Redirect::route('dealer-banks.index')->with('msgs', $msgs);
			}
			
        }
		return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /dealerbank/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}