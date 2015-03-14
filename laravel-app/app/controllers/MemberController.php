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
	public function edit($slug)
	{
		$member = Member::whereUsername($slug)->first();
		$this->layout->content = View::make('backend.member.edit', compact('member'));
	}

	public function update()
	{
		$inputs = Input::all();
		$msgs = array();
		$id = $inputs['id'];
		if(Input::has('active'))
		{
			DB::statement('UPDATE member SET status=0 WHERE status=1;');
			$msg = array('type'=>'success','msg'=>'The account is inactive now!');
			array_push($msgs,$msg);
			return Redirect::back()
				->with('msgs', $msgs);
		}elseif(Input::has('inActive')){
			DB::statement('UPDATE member SET status=1 WHERE status=0;');
			$msg = array('type'=>'success','msg'=>'The account is active now!');
			array_push($msgs,$msg);
			return Redirect::back()
				->with('msgs', $msgs);

		}elseif(Input::has('submit')){
			$nerd = Member::find($id);
			$nerd->email      = Input::get('email');
			$nerd->first_name = Input::get('first_name');
			$nerd->last_name = Input::get('last_name');
			$nerd->location = Input::get('location');
			$nerd->phone = Input::get('phone');
			$nerd->address = Input::get('address');
			$nerd->save();
			$msg = array('type'=>'success','msg'=>'The account is update successfully');
			array_push($msgs,$msg);
			return Redirect::back()
				->with('msgs', $msgs);
		}
		$msg = array('type'=>'error','msg'=>':)');
		array_push($msgs,$msg);
		return Redirect::back()
			->with('msgs', $msgs);
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