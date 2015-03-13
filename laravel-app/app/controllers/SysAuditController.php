<?php

class SysAuditController extends \BaseController {

	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /sysaudit
	 *
	 * @return Response
	 */
	public function index()
	{
		$logs = Logs::leftJoin('staff AS s','logs.staff__id','=','s.id')
					->select('logs.*','s.name AS staffName')
					->orderBy('logs.id','desc')->paginate(15);
		$this->layout->content = View::make('log.list_log',compact('logs'));
	}

	/**
	 * Display a listing of the resource.
	 * GET /sysaudit
	 *
	 * @return Response
	 */
	public function search()
	{
		$inputs = Input::all();
		$db = Logs::leftJoin('staff AS s','logs.staff__id','=','s.id')
					->select('logs.*','s.name AS staffName')
					->orderBy('logs.id','desc');
		
		if (Input::has('name')) {
			$db->where('s.name','LIKE','%'.$inputs['name'].'%');
		}
		if (Input::has('action')) {
			$db->where('action',$inputs['action']);
		}
		if (Input::has('object_type')) {
			$db->where('object_type',$inputs['object_type']);
		}
		if (Input::has('object__id')) {
			$db->where('object__id',$inputs['object__id']);
		}
		if (Input::has('start_date') && Input::has('end_date')) {
			$db->whereBetween('logs.created_at', array($inputs['start_date'], $inputs['end_date']));
		}
		
		$logs = $db->paginate(15)->appends($inputs);
		$this->layout->content = View::make('log.list_log',compact('logs'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 * GET /sysaudit/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /sysaudit
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /sysaudit/{id}
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
	 * GET /sysaudit/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /sysaudit/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /sysaudit/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}