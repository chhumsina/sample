<?php

class TerminalController extends \BaseController {

	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	/**
	 * Display a listing of the resource.
	 * GET /terminal
	 *
	 * @return Response
	 */
	public function index()
	{
		$terminals = DB::table('terminals')
						->select('terminals.*','sc.name AS crbName','su.name AS upbName')
						->orderBy('terminals.created_at','desc')
						->where('terminals.status','!=','delete')
						->leftJoin('staff AS sc','sc.id','=','terminals.staff__id')
						->leftJoin('staff AS su','su.id','=','terminals.updated_by_staff__id')
						->paginate(10);
		$stockLocations = DB::table('sys_enumeration')->where('enum_type__id','terminal_stock_location')->lists('name','enum_id');
		$this->layout->content = View::make('terminal.list_terminal',compact('terminals','stockLocations'));
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
		
		$db = DB::table('terminals')
						->select('terminals.*','sc.name AS crbName','su.name AS upbName')
						->orderBy('terminals.created_at','desc')
						->where('terminals.status','!=','delete')
						->leftJoin('staff AS sc','sc.id','=','terminals.staff__id')
						->leftJoin('staff AS su','su.id','=','terminals.updated_by_staff__id');
						
		if (Input::has('serial')) {
			$db->where('serial',$inputs['serial']);
		}
		if (Input::has('imsi')) {
			$db->where('imsi',$inputs['imsi']);
		}
		if (Input::has('ecard')) {
			$db->where('ecard_id',$inputs['ecard']);
		}
		if (Input::has('status')) {
			$db->where('terminals.status',$inputs['status']);
		}
		if (Input::has('stock__location') && Input::get('stock__location') != 'no') {
			$db->where('terminals.stock__location',$inputs['stock__location']);
		}
		
		if (Input::has('stock__location') && Input::get('stock__location') == 'no') {
			$db->where('terminals.stock__location','');
			$db->orWhere('terminals.stock__location', null);
		}
		
		$n = 15;
		if (Input::has('n_record')) {
			$n = Input::get('n_record');
		}
		$terminals = $db->paginate($n)->appends($inputs);
		
		$stockLocations = DB::table('sys_enumeration')->where('enum_type__id','terminal_stock_location')->lists('name','enum_id');
		$this->layout->content = View::make('terminal.list_terminal',compact('terminals','stockLocations'));
	}


	/**
	 * Show the form for creating a new resource.
	 * GET /terminal/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$stockLocations = DB::table('sys_enumeration')->where('enum_type__id','terminal_stock_location')->lists('name','enum_id');
		$this->layout->content = View::make('terminal.create_terminal',compact('stockLocations'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /terminal
	 *
	 * @return Response
	 */
	public function store()
	{
		DB::beginTransaction();
		
		$input = Input::all();
        $validation = Validator::make($input, Terminal::$rules);
		
		if ($validation->passes()) {
			Terminal::$rules['serial'] = 'unique:terminals,serial';
			//Terminal::$rules['imsi'] = 'unique:terminals,imsi';
			//Terminal::$rules['ecard_id'] = 'unique:terminals,ecard_id';
	        $validation = Validator::make($input, Terminal::$rules);
			if ($validation->passes())
	        {
	        	$staffId = Auth::user()->id;
				$input['staff__id']=$staffId;
				
				$ob = Terminal::create($input);
				$obLog = array(
					'staff__id'=>$staffId,
					'action'=>'add',
					'object_type'=>'terminals',
					'object__id'=>$input['serial'],
					'new_data'=>json_encode($input)
				);
				$obLog = Logs::create($obLog);
				$msg = array();
				if( !$ob || !$obLog)
				{
				    DB::rollback();
					$msg['msg'] = 'Create terminal failed!';
					$msg['type'] = 'error';
				} else {
				    DB::commit();
					$msg['msg'] = 'Create terminal successfully with serial = '.$input['serial'];
					$msg['type'] = 'success';
				}
				$msgs = array($msg);
				return Redirect::route('terminals.index')->with('msgs', $msgs);
	        }
		}
		
		return Redirect::route('terminals.create')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified resource.
	 * GET /terminal/{id}
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
	 * GET /terminal/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$terminal = Terminal::find($id);
		$stockLocations = DB::table('sys_enumeration')->where('enum_type__id','terminal_stock_location')->lists('name','enum_id');
		$this->layout->content = View::make('terminal.edit_terminal',compact('stockLocations','terminal'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /terminal/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();
		unset(Terminal::$rules['serial']);
        $validation = Validator::make($input, Terminal::$rules);
        if ($validation->passes())
        {
        	$staffId = Auth::user()->id;
			$input['updated_by_staff__id']=$staffId;
			
			DB::beginTransaction();
			//------------Update terminals----------------
			$terminal = Terminal::find($id);
			$obLog = array(
				'staff__id'=>$staffId,
				'action'=>'update',
				'object_type'=>'terminals',
				'object__id'=>$id,
				'old_data'=>json_encode($terminal),
				'new_data'=>json_encode($input)
			);
			$successObLog = Logs::create($obLog);
			
			$successOb = $terminal->update($input);
			//------------Update terminal----------------
			unset($input['_method']);
			unset($input['_token']);
			unset($input['status']); 
			
			$terminalDB_ = DB::table('terminal')->where('serial', $id);
			
			$terminal_ = $terminalDB_->first();
			if ($terminal_!=null) {
				$input['updated_at'] = date("Y-m-d H:i:s");
				$successOb_ = $terminalDB_->update($input);
			} else {
				$successOb_ = true;
			}
			
			
			$obLog = array(
				'staff__id'=>$staffId,
				'action'=>'update',
				'object_type'=>'terminal',
				'object__id'=>$id,
				'old_data'=>json_encode($terminal_),
				'new_data'=>json_encode($input),
				'reason'=>'Update the rest field of terminal Auto when update terminals'
			);
			$successObLog_ = Logs::create($obLog);
			$msgs = array();
			$msg = array();
			if( !$successOb || !$successObLog ||  !$successOb_ || !$successObLog_ )
			{
			    DB::rollback();
				$msg['msg'] = 'Update terminal failed!';
				$msg['type'] = 'error';
				array_push($msgs,$msg);
			} else {
			    // Else commit the queries
			    DB::commit();
				$msg = array('msg' => 'Update terminal successfully!','type' =>'success');
				array_push($msgs,$msg);
			}
			
            return Redirect::route('terminals.index')->with('msgs', $msgs);
        }
		return Redirect::route('terminals.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /terminal/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$staffId = Auth::user()->id;
		
		$ob = Terminal::find($id);
		$oldOb = $ob;
		
		DB::beginTransaction();
		$arrayUpdate = array('status'=>'delete','updated_by_staff__id'=>$staffId);
		
		
		$obLog = array(
			'staff__id'=>$staffId,
			'action'=>'delete',
			'object_type'=>'terminals',
			'object__id'=>$id,
			'old_data'=>json_encode($ob),
			'new_data'=>json_encode($arrayUpdate)
			);
		$successObLog = Logs::create($obLog);
		$successOb = $ob->update($arrayUpdate);
		$msg = array();
		if( !$successOb || !$successObLog )
		{
			DB::rollback();
			$msg['msg'] = 'Delete terminal failed!';
			$msg['type'] = 'error';
		} else {
			// Else commit the queries
			DB::commit();
			$msg['msg'] = 'Delete terminal successfully!';
			$msg['type'] = 'success';
		}
		$msgs = array($msg);
        return Redirect::route('terminals.index')->with('msgs', $msgs);
	}

}
