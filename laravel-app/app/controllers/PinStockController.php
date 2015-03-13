<?php

class PinStockController extends \BaseController {

	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Show the form for creating a new resource.
	 * GET /pinstock/create
	 *
	 * @return Response
	 */
	public function uploadPin()
	{
		$responseOperator = WebServiceCollection::getOperatorList();
		$operators = array();
		if($responseOperator['status'] == "F")
		{
									
		} else if ($responseOperator['status'] == "T") {
			$data = $responseOperator['data'];
			foreach ($data as $key => $operator) {
				$operators[$operator['operator_id']] = $operator['name'];
			}
		}
		
		$this->layout->content = View::make('pin-stock.upload_pin',compact('operators'));
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /pinstock
	 *
	 * @return Response
	 */
	public function storeUploadPin()
	{
		$input = Input::all();
		$staffId = Auth::user()->id;
		
		$rules = array(
			'title' => 'required',
			'operator__id' => 'required',
			'avatar' => 'required',
		    'remark' => 'required|max:255'
		);
		$msgs = array();
        $validation = Validator::make($input, $rules);
		if (!$validation->passes()) 
		{
			return Redirect::back()
            ->withInput()
            ->withErrors($validation)
            ->with('msgs', $msgs);
		}
		
		$input['staff__id'] = $staffId;
		
		unset($input['_token']);
		
		$fileTemp = $_FILES['avatar']['tmp_name'];
		
		
		//----------------upload----------------------
		$file            = Input::file('avatar');
        $destinationPath = public_path().'/images/upload_pin_stock/';
		$millisecond = round(microtime(true)*1000);
        $filename        = 'file'.$millisecond.'_'.str_random(2) . '_' . $file->getClientOriginalName();
		$extension = $file->getClientOriginalExtension();
		
		if ($extension != 'txt') {
			$msg = array('type'=>'error','msg'=>'File Extension = '.$extension.' Was Not Allowed');
			array_push($msgs,$msg);
			return Redirect::back()
		            ->withInput()
		            ->withErrors($validation)
		            ->with('msgs', $msgs);
		}
		
		
        $uploadSuccess   = $file->move($destinationPath, $filename);
		if ($uploadSuccess) {
			$url = $destinationPath.$filename;
			$fileUrl = str_replace("\\","/",$url);			
			$response = WebServiceCollection::uploadPin($input,$fileUrl);
			if($response['status'] == "F")
						{
				$msg['msg'] = 'Upload Pin fail!';
				$msg['type'] = 'error';
				
				$msgs = $response['msgs'];
				array_push($msgs,$msg);
						
			} else {
				
				$msg['msg'] = 'Upload Pin successfully!';
				$msg['type'] = 'success';
				
				$msgs = $response['msgs'];
				array_push($msgs,$msg);
				return Redirect::back()->with('msgs', $msgs);
			}
		
		} else {
			$msg = array('type'=>'error','msg'=>'Upload Fail');
			array_push($msgs,$msg);
			return Redirect::back()
		            ->withInput()
		            ->with('msgs', $msgs);
		}
		//--------------------------------------------
		
		
		return Redirect::back()
		            ->withInput()
		            ->with('msgs', $msgs);
	}

	
	public function viewPinStock() {
		$input = Input::all();
		$pinsArray = array();
		
		$data = array();
		$data['n'] = 15;
		$data['page'] = 1;
		$data['operator_id'] = '';
		$data['face'] = '';
		$data['status'] = '';
		$data['check_exprie'] = '';
		$data['start_date'] = '';
		$data['end_date'] = '';
		
		if (Input::has('page')) {
			$data['page'] = Input::get('page');
		}
		if (Input::has('operator_id')) {
			$data['operator_id'] = Input::get('operator_id');
		}
		if (Input::has('face')) {
			$data['face'] = Input::get('face');
		}
		if (Input::has('check_exprie')) {
			$data['check_exprie'] = Input::get('check_exprie');
		}
		if (Input::has('status')) {
			$data['status'] = Input::get('status');
		}
		if (Input::has('start_date') && Input::has('end_date')) {
			$data['start_date'] = Input::get('start_date');
			$data['end_date'] = Input::get('end_date');
		}
		
		$response = WebServiceCollection::reportPinStock($data);
		$msgs = array();
		if($response['status'] == "F")
		{
			$msgs = $response['msgs'];
			$pins = Paginator::make($pinsArray, 0, $data['n']); 						
		} else if ($response['status'] == "T") {
			$data = $response['data'];
			foreach ($data['pins'] as $key => $pin) {
				array_push($pinsArray,$pin);
			}
			
			$pins = Paginator::make($pinsArray, $data['records_count'], $data['n'])->appends($input);
		}
		
		$responseOperator = WebServiceCollection::getOperatorList();
		$operators = array();
		if($responseOperator['status'] == "F")
		{
									
		} else if ($responseOperator['status'] == "T") {
			$data = $responseOperator['data'];
			foreach ($data as $key => $operator) {
				$operators[$operator['operator_id']] = $operator['name'];
			}
		}
		
		$this->layout->content = View::make('pin-stock.list_pin',compact('pins','operators'))->with('msgs', $msgs);
	}

	// Summary pin code stock report
	public function summaryPinCodeStock() {
		$input = Input::all();
		$pinsArray = array();

		$data = array();
		$data['n'] = 15;
		$data['page'] = 1;
		$data['operator_id'] = '';
		$data['face'] = '';
		$data['status'] = '';
		$data['check_exprie'] = '';
		$data['start_date'] = '';
		$data['end_date'] = '';

		if (Input::has('page')) {
			$data['page'] = Input::get('page');
		}
		if (Input::has('operator_id')) {
			$data['operator_id'] = Input::get('operator_id');
		}
		if (Input::has('face')) {
			$data['face'] = Input::get('face');
		}
		if (Input::has('check_exprie')) {
			$data['check_exprie'] = Input::get('check_exprie');
		}
		if (Input::has('status')) {
			$data['status'] = Input::get('status');
		}
		if (Input::has('start_date') && Input::has('end_date')) {
			$data['start_date'] = Input::get('start_date');
			$data['end_date'] = Input::get('end_date');
		}

		$response = WebServiceCollection::summaryPinCodeStock($data);
		$msgs = array();
		if($response['status'] == "F")
		{
			$msgs = $response['msgs'];
			$pins = Paginator::make($pinsArray, 0, $data['n']);
		} else if ($response['status'] == "T") {
			/*$pins = $pinsArray = array(
				//0 => array(
					0 => array(
						'face' => 1,
						'beginning_stock' => 100,
						'sales_stock' => 100,
						'ending_stock' => 100,
						'operator_id' => 1,
						'operator_name' => 'Smart',
					),
					1 => array(
						'face' => 2,
						'beginning_stock' => 200,
						'sales_stock' => 100,
						'ending_stock' => 50,
						'operator_id' => 2,
						'operator_name' => 'Metfone',
					),
					2 => array(
						'face' => 5,
						'beginning_stock' => 100,
						'sales_stock' => 100,
						'ending_stock' => 2100,
						'operator_id' => 1,
						'operator_name' => 'Smart',
					),
					3 => array(
						'face' => 10,
						'beginning_stock' => 200,
						'sales_stock' => 100,
						'ending_stock' => 2100,
						'operator_id' => 1,
						'operator_name' => 'Smart',
					),
					4 => array(
						'face' => 20,
						'beginning_stock' => 100,
						'sales_stock' => 100,
						'ending_stock' => 1,
						'operator_id' => 1,
						'operator_name' => 'Smart',
					),
					5 => array(
						'face' => 50,
						'beginning_stock' => 200,
						'sales_stock' => 100,
						'ending_stock' => 30,
						'operator_id' => 1,
						'operator_name' => 'Smart',
					),
				//),
			);
			*/
			$pins = $response['data'];
			$pinsArray = array();

			foreach ($pins as $key => $pin) {


				$operator_id = $pin['operator_id'];


				//$name = "";
				/*if ($operator_id == '1') {
					$name = 'Smart';
				} else if ($operator_id == '2') {
					$name = 'Metfone';
				} else if ($operator_id == '3') {
					$name = 'Cellcard';
				} else {
					continue;
				}*/

				$operatorName = $pin['operator_name'];
				if (!array_key_exists($operatorName,$pinsArray)) {
					$pinsArray[$operatorName] = array();
				}
				array_push($pinsArray[$operatorName],$pin);
			}
		}

		$responseOperator = WebServiceCollection::getOperatorList();
		$operators = array();
		if($responseOperator['status'] == "F")
		{

		} else if ($responseOperator['status'] == "T") {
			$data = $responseOperator['data'];
			foreach ($data as $key => $operator) {
				$operators[$operator['operator_id']] = $operator['name'];
			}
		}
		////////////Check to export_excel////////////////////////////////////////////////////////////////
		if(isset($_POST['export_excel'])){

			$this->getExcelfile($pinsArray);

		}else{
			$this->layout->content = View::make('pin-stock.list_summary_pin', compact('pinsArray', 'operators'))->with('msgs', $msgs);
		}
	}

	// Export excel
	public function getExcelfile($pinsArray){

		$sumPins = array();

		$nameFile = ucwords(str_replace('-', ' ',Request::segment(2))).'-'.date("Y-m-d H-i");

		$beginningTotal = 0;
		$salesTotal = 0;
		$endingTotal = 0;
		$currency = null;

		Excel::create($nameFile, function ($excel) use($pinsArray) {


			$excel->sheet('Report', function ($sheet) use($pinsArray){

				$sheet->mergeCells('A1:E1');
				$sheet->row(1, array('Summary PIN Code Stock Report'));
				$sheet->row(1, function ($row) {

					// call cell manipulation methods
					$row->setFontFamily('Ariel');
					$row->setFontSize(15);
					$row->setFontWeight('bold');
				});

				$pinsCollection = array();

				$beginningTotal = 0;
				$salesTotal = 0;
				$endingTotal = 0;
				$sumArray = array();

				$numHeader = 2;
				$numTotal = 0;
				foreach ($pinsArray as $key =>$pins) {
					$sheet->row($numHeader, array($key)); // column names
					$sheet->row($sheet->getHighestRow(), function ($row) {
						$row->setFontWeight('bold');
					});

					$sheet->row($numHeader+1, array("Value","In Stock","Beginning Stock","Sales","Stock Available")); // column names
					$sheet->setBorder('A'.($numHeader+1).':E'.($numHeader+1), 'thin');
					$sheet->row($sheet->getHighestRow(), function ($row) {
						$row->setFontWeight('bold');
					});

					$numPin = $numHeader+1;

					$sumArray = array();
					$inStock = 0;
					$beginningTotal = 0;
					$salesTotal = 0;
					$endingTotal = 0;

					foreach ($pins as $pin) {
						$numPin = $numPin + 1;

						$pinsCollection = array($pin['face'],$pin['instock'],$pin['beginning_stock'],$pin['sales_stock'], $pin['ending_stock']);

						$inStock 		= $inStock + $pin['instock'];
						$beginningTotal = $beginningTotal + $pin['beginning_stock'];
						$salesTotal 	= $salesTotal + $pin['sales_stock'];
						$endingTotal 	= $endingTotal + $pin['ending_stock'];

						if(		$pin['face']==1 && $pin['ending_stock']<=200
							|| $pin['face']==2 && $pin['ending_stock']<=100
							|| $pin['face']==5 && $pin['ending_stock']<=50
							|| $pin['face']==20 && $pin['ending_stock']<=10
							|| $pin['face']==50 && $pin['ending_stock']<=2
							|| $pin['face']==10 && $pin['ending_stock']<=0
						) {
							$sheet->row($numPin, function($row) {
								$row->setBackground('#FF0000');
							});
						}
						$sheet->setBorder('A'.$numPin.':E'.$numPin, 'thin');
						$sheet->row($numPin, $pinsCollection);

						$sumArray = array('Total',$inStock,$beginningTotal,$salesTotal,$endingTotal);
					}

					$numTotal = $numPin + 1;
					$sheet->row($numTotal, $sumArray);
					$sheet->setBorder('A'.$numTotal.':E'.$numTotal, 'thin');
					$sheet->row($sheet->getHighestRow(), function ($row) {
						$row->setFontWeight('bold');
					});

					$numHeader = $numTotal+2;
				}
			});

		})->export('xls');
	}

	/**
	 * Display a listing of the resource.
	 * GET /pinstock
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /pinstock/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /pinstock
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /pinstock/{id}
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
	 * GET /pinstock/{id}/edit
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
	 * PUT /pinstock/{id}
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
	 * DELETE /pinstock/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}