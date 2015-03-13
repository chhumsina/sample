<?php

class UploadActualChannelSale extends \BaseController {

	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /uploadactualchannelsale
	 *
	 * @return Response
	 */
	public function index()
	{
		$inputs = Input::All();
		
		$db = DB::table('rep_channel_sale_upload as csu')
				->select('csu.*','s.name as createdBy')
				->join('staff as s','s.id','=','csu.staff__id')
				->where('csu.status','!=','delete')
				->orderBy('csu.created_at','desc');
		
		if (Input::has('start_date') && Input::has('end_date')) {
			$db->whereBetween('csu.created_at', array($inputs['start_date'], $inputs['end_date'].' 23:59'));
		}
		
		$uploadActualSales = $db->paginate(15)->appends($inputs);
		
		$this->layout->content = View::make('sale-actual.list_upload_actual_sale',compact('uploadActualSales'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /uploadactualchannelsale/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /uploadactualchannelsale
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /uploadactualchannelsale/{id}
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
	 * GET /uploadactualchannelsale/{id}/edit
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
	 * PUT /uploadactualchannelsale/{id}
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
	 * DELETE /uploadactualchannelsale/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$staffId = Auth::user()->id;
		$msgs = array();
		try {
			$ob = ChannelSaleUpload::find($id);
			$oldOb = $ob;
			
			DB::beginTransaction();
			$arrayUpdate = array('updated_by_staff__id'=>$staffId,'status'=>'delete');
			
			$obLog = array(
				'staff__id'=>$staffId,
				'action'=>'delete',
				'object_type'=>'upload_actual_channel_sale',
				'object__id'=>$id,
				'old_data'=>json_encode($oldOb),
				'new_data'=>json_encode($arrayUpdate)
			);
			
			$successObLog = Logs::create($obLog);
			$successOb = $ob->update($arrayUpdate);
			
			$objectSale = DB::table('rep_channel_sale')
						->where('channel_sale_upload__id',$id);
			
			$objectSale->update($arrayUpdate);
			
			$msg = array();
			DB::commit();
			$msg['msg'] = 'Delete upload actual channel sale successfully!';
			$msg['type'] = 'success';
			array_push($msgs,$msg);
		} catch (Exception $e) {
			DB::rollback();
			$msg = array();
			$msg['msg'] = 'Delete upload actual channel sale failed!';
			$msg['type'] = 'error';
			array_push($msgs,$msg);
		}		
        return Redirect::route('upload-sale-actuals.index')->with('msgs', $msgs);
	}

}