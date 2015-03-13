<?php

class WalletController extends \BaseController {
	
	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /wallet
	 *
	 * @return Response
	 */
	public function index()
	{
		$inputs = Input::all();
		$db = DB::table('dealer as d')
						->join('dealer_type as dt','d.dealer_type__id','=','dt.id')
						->join('w_wallet as w','w.dealer__id','=','d.id')
						->orderBy('w.dealer__id','asc')
						->orderBy('w.sequence_number','asc')
						->where('w.status','active')
						->where('d.dealer_type__id','!=','9')
						->select('d.*','w.*','dt.id as dt_id',DB::raw('(select count(w1.dealer__id) as count_id from dealer d1 inner join w_wallet w1 on d1.id = w1.dealer__id where d1.id = d.id) as num'));
						
						
		if (Input::has('start_date') && Input::has('end_date')) {
			$db->whereBetween('w.last_transaction_on', array($inputs['start_date'], $inputs['end_date']));
		}
		
		if (Input::has('did')) {
			$db->where('d.id',$inputs['did']);
		}
		if (Input::has('name')) {
			$db->where('d.name','LIKE','%'.$inputs['name'].'%');
		}
		if (Input::has('transaction_id')) {
			$db->where('w.last_transaction__id',$inputs['transaction_id']);
		}
		
		$dealerWallets = $db->paginate(75)->appends($inputs);
		$this->layout->content = View::make('wallet.list_wallet',compact('dealerWallets'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /wallet/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /wallet
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /wallet/{id}
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
	 * GET /wallet/{id}/edit
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
	 * PUT /wallet/{id}
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
	 * DELETE /wallet/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}