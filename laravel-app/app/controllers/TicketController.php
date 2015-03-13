<?php

class TicketController extends \BaseController {
	
	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		$this->printTicket();
	}

	public function printTicket()
	{
		$dealers = DealerBank::where('status','active')->orderBy('dealer__id','asc')->lists('dealer__id','dealer__id');
		$advanceDraws = array(0,1,2,3,4,5,6,7,8,9);

		$this->layout->content = View::make('ticket.print-ticket',compact('dealers','advanceDraws','item'));
	}

	public function printStoreTicket()
	{
		$input = Input::all();
		$rules = array(
			'dealer__id' => 'required',
			'quantity' => 'required|integer'
		);

		$validation = Validator::make($input, $rules);

		$msgs = array();
		if ($validation->passes())
		{
			$dealer__id = Input::get('dealer__id');
			$postid = Input::get('posid');
			$quantity = Input::get('quantity') * 6;
			$advanceDraw = Input::get('advanceDraw');

			$data = array();
			$data['dealer__id'] = $dealer__id;
			$data['posid'] = 'K3202D00024'; //$postid
			$data['quantity'] = $quantity;
			$data['advanceDraw'] = $advanceDraw;

			if (Input::has('getTicket')){
				$response = WebServiceCollection::mohasomnangPrint($data);
				if($response['status'] == "F"){
					$msg = $response['msgs'];
					return Redirect::back()
						->withInput()
						->with('msgs', $msg);

				} else if($response['status'] == "T"){
					$items = $response['data'];
					$msg = array(array('type'=>'success','msg'=>'Print Success'));
					array_push($msgs, $msg);
					return Redirect::back()
						->with('items', $items)
						->with('msgs', $msg)
						->with('dealer_id', $dealer__id);
				}
			}
		}
		return Redirect::back()
			->withInput()
			->withErrors($validation)
			->with('msgs', $msgs);
	}

	public function cancelTicket()
	{
		$dealers = DealerBank::where('status','active')->orderBy('dealer__id','asc')->lists('dealer__id','dealer__id');

		$this->layout->content = View::make('ticket.cancel-ticket',compact('dealers'));
	}

	public function cancelStoreTicket()
	{
		$input = Input::all();
		$rules = array(
			'did' => 'required',
			'tsn' => 'required'
		);

		$validation = Validator::make($input, $rules);

		$msgs = array();
		if ($validation->passes())
		{
			$did = Input::get('did');
			$tsn = Input::get('tsn');

			$data = array();
			$data['did'] = $did;
			$data['tsn'] = $tsn;

			if (Input::has('cancelTicket')){
				$response = WebServiceCollection::mohasomnangPrintCancel($data);
				if($response['status'] == "F"){
					$msg = $response['msgs'];
					return Redirect::back()
						->withInput()
						->with('msgs', $msg);

				} else if($response['status'] == "T"){
					$items = $response['data'];
					$msg = array(array('type'=>'success','msg'=>'Cancel Ticket Success'));
					array_push($msgs, $msg);
					return Redirect::back()
						->with('items', $items)
						->with('msgs', $msg)
						->with('did', $did);
				}
			}
		}
		return Redirect::back()
			->withInput()
			->withErrors($validation)
			->with('msgs', $msgs);
	}

	public function payout()
	{
		$input = Input::all();
		$msgs = array();
		$did = Input::get('did');
		$tsn = Input::get('tsn');

		$data = array();
		$data['did'] = $did;
		$data['tsn'] = $tsn;

		$dealers = array('33333333'=>'33333333','22222222'=>'22222222');

		if (Input::has('cancelTicket')){
			$response = WebServiceCollection::mohasomnangPrintCancel($data);
			if($response['status'] == "F"){
				$msgs = $response['msgs'];

			} else if($response['status'] == "T"){
				$item = $response['data'];
			}
		}

		$this->layout->content = View::make('ticket.payout',compact('dealers','item'));
	}
}