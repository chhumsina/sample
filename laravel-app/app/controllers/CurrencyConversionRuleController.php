<?php

class CurrencyConversionRuleController extends \BaseController {
	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /currencyconversionrule
	 *
	 * @return Response
	 */
	public function index()
	{
		$currencyConversionRules = DB::table('c_currency_conversion_rule as ccr')
						->leftJoin('staff','staff.id','=','ccr.updated_by_staff__id')
						->orderBy('ccr.sequence_number','asc')
						->where('ccr.status','active')
						->select('ccr.*','staff.name as staffName')
						->paginate(500);
		
		$this->layout->content = View::make('currency-conversion-rule.list_currency_conversion_rule',compact('currencyConversionRules'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /currencyconversionrule/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /currencyconversionrule
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /currencyconversionrule/{id}
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
	 * GET /currencyconversionrule/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$currencyConversionRule = CurrencyConversionRule::find($id);
		$this->layout->content = View::make('currency-conversion-rule.edit_currency_conversion_rule',compact('currencyConversionRule'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /currencyconversionrule/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$ob = CurrencyConversionRule::find($id);
		
		$input = Input::all();
		$input['from_currency__id'] = $ob->from_currency__id;
		$input['to_currency__id'] = $ob->	to_currency__id;
        $validation = Validator::make($input, CurrencyConversionRule::$rules);
        if ($validation->passes())
        {
        	
			$staffId = Auth::user()->id;
			
			$input['staff__id']=$staffId;
			$input['updated_by_staff__id']=$staffId;
			$input['status']='active';
			$input['sequence_number'] = $ob->sequence_number;
			
			$fromCurrency = $input['from_currency__id'];
			$toCurrency = $input['to_currency__id'];
			
			DB::beginTransaction();
			
			$arrayUpdate = array('status'=>'inactive','updated_by_staff__id'=>$staffId);
			$successOb = $ob->update($arrayUpdate);
			DB::commit();
			
			unset($input['c_conversion_rule_id']);
			$obNewCCR = CurrencyConversionRule::create($input);
			
			unset($ob->status);
			$obLog = array(
				'staff__id'=>$staffId,
				'action'=>'update',
				'object_type'=>'c_currency_conversion_rule',
				'object__id'=>'from:'.$fromCurrency.',to:'.$toCurrency,
				'old_data'=>json_encode($ob),
				'new_data'=>json_encode($input)
			);
			$successObLog = Logs::create($obLog);
			
			$msgs = array();
			$msg = array();
			if( !$successOb || !$successObLog || !$obNewCCR)
			{
			    DB::rollback();
				$msg['msg'] = 'Update currency conversion rules failed!';
				$msg['type'] = 'error';
			} else {
			    // Else commit the queries
			    DB::commit();
				$msg['msg'] = 'Update currency conversion rules successfully!';
				$msg['type'] = 'success';
			}
			array_push($msgs,$msg);
            return Redirect::route('currency-conversion-rules.index')->with('msgs', $msgs);
   		}
		return Redirect::route('currency-conversion-rules.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /currencyconversionrule/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}