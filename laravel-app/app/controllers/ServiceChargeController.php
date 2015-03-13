<?php

class ServiceChargeController extends \BaseController {
	
	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /servicecharege
	 *
	 * @return Response
	 */
	public function userProfileServiceCharge()
	{
		$inputs = Input::all();
		
		$db = DB::table('sc_dealer_profile as dp')
						->join('dealer as d','d.id','=','dp.dealer__id')
			            ->join('sc_class_of_service_charge as cos', 'dp.class_of_service_charge__id', '=', 'cos.profile_id')
						->select('dp.*', 'cos.profile_name','d.name')
						->orderBy('d.id','asc')
						->where('dp.status','!=','delete');
						
		if (Input::has('dealer__id')) {
			$db->where('dp.dealer__id',$inputs['dealer__id']);
		}
		if (Input::has('class_of_service_charge__id')) {
			$db->where('dp.class_of_service_charge__id',$inputs['class_of_service_charge__id']);
		}
		
		$dealerProfiles = $db->paginate(500)->appends($inputs);
		
		$classOfServices = DB::table('sc_class_of_service_charge')->lists('profile_name','profile_id');
		$this->layout->content = View::make('service-charge.user-profile.list_user_profile',compact('dealerProfiles','classOfServices'));
	}
	
	/**
	 * Display a listing of the resource.
	 * GET /servicecharege
	 *
	 * @return Response
	 */
	public function createUserProfileServiceCharge()
	{
		$dealers = DB::table('dealer')
                    ->where('status','<>','inactive')
					->whereNotIn('dealer_type__id', array(9))
                    ->get();
		$classOfServices = DB::table('sc_class_of_service_charge')
                    ->where('status','<>','inactive')
					->whereNotIn('profile_type', array(9))
                    ->lists('profile_name','profile_id');			
		$this->layout->content = View::make('service-charge.user-profile.create-user-profile', compact('dealers','classOfServices'));
	}
	
	/**
	 * Display a listing of the resource.
	 * GET /servicecharege
	 *
	 * @return Response
	 */
	public function StoreUserProfileServiceCharge()
	{
		$input = Input::all();
		$input['dealer__id'] = Input::get('dealer__id');
		// Must not already exist in the `dealer__id` column of `sc_class_of_service_charge` table
		$rules = array('dealer__id' => 'unique:dealer,id');
		$validator = Validator::make($input, $rules);

		if ($validator->fails()) {

		}
		else {

		}
		
	}
	
	/**
	 * Display a listing of the resource.
	 * GET /servicecharege
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /servicecharege/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /servicecharege
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /servicecharege/{id}
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
	 * GET /servicecharege/{id}/edit
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
	 * PUT /servicecharege/{id}
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
	 * DELETE /servicecharege/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}