<?php

class MemberController extends \BaseController {

	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.backend';
	
	public function lists()
	{
		$query = Member::where('use_type',2);
		$n = 20;
		$members = $query->paginate($n);

		$this->layout->content = View::make('backend.member.list',compact('members'));
	}

	public function search()
	{
		$inputs = Input::all();
		$username = $inputs['username'];
		$email = $inputs['email'];
		$status = $inputs['status'];

		$query = Member::where('use_type', '=', '2');

		// Adds a clause to the query
		if ($username) {
			$query->where('username', 'LIKE', "%$username%");
		}
		if ($email) {
			$query->where('email', 'LIKE', "%$email%");
		}
		if (Input::has('status')) {
			$query->where('status', 'LIKE', "%$status%");
		}

		$n = 2;
		$members = $query->paginate($n)->appends($inputs);

		$this->layout->content = View::make('backend.member.list', compact('members'));
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