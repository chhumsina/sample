<?php

class CustomerCallController extends \BaseController {

	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /customercall
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /customercall/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$channelOwners = DB::table('cc_channel_owners')->orderBy('sequence_num','asc')->lists();
		$subjects = DB::table('cc_channel_owners')->orderBy('sequence_num','asc')->lists();
		$this->layout->content = View::make('customer.create_customer_call',compact('channelOwners','subjects'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /customercall
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /customercall/{id}
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
	 * GET /customercall/{id}/edit
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
	 * PUT /customercall/{id}
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
	 * DELETE /customercall/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}