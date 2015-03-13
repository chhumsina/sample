<?php

class AnnouncementController extends \BaseController {

	/**
	 * The layout that should be used for responses.
	 */
	protected $layout = 'layouts.master';
	
	/**
	 * Display a listing of the resource.
	 * GET /announcement
	 *
	 * @return Response
	 */
	public function index()
	{
		//$anns = Announcement::all()->orderBy("created_at");
		//$anns = Announcement::orderBy('created_at','desc')->where('status','!=','delete')->get();
		$anns = Announcement::orderBy('created_at','desc')->paginate(15);
		$this->layout->content = View::make('announcement.list_ann',compact('anns'));
	}
	
	/**
	 * Display a listing of the resource.
	 * GET /announcement
	 *
	 * @return Response
	 */
	public function search()
	{
		$inputs = Input::all();
		$db = Announcement::orderBy('created_at','desc');
		if (Input::has('title')) {
			$db->where('title','LIKE',"%".$inputs['title']."%");
		}
		if (Input::has('status')) {
			$db->where('status',$inputs['status']);
		}		
		$anns = $db->paginate(15)->appends($inputs);//$db->where('status','!=','delete')->paginate(15)->appends($inputs);
		$this->layout->content = View::make('announcement.list_ann',compact('anns'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /announcement/create
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->layout->content = View::make('announcement.create_ann');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /announcement
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
		$input['status'] = 't';
		Announcement::$rules['title'] = 'unique:announcements,title|required|min:2';
        $validation = Validator::make($input, Announcement::$rules);

        if ($validation->passes())
        {
        	$staffId = Auth::user()->id;
			
        	if (Input::hasFile('message_kh')) {
		        $file            = Input::file('message_kh');
		        $destinationPath = public_path().'/images/uploads/';
				$millisecond = round(microtime(true)*1000);
		        $filename        = $millisecond.'_'.str_random(2) . '_' . $file->getClientOriginalName();
		        $uploadSuccess   = $file->move($destinationPath, $filename);
								
				if ($uploadSuccess) {
					$input['staff__id']=Auth::user()->id;
					$input['message_kh']=$filename;
					
					// Start transaction
					DB::beginTransaction();
					
					$ann = Announcement::create($input);
					$annLog = array(
					'staff__id'=>$staffId,
					'action'=>'add',
					'object_type'=>'announcement',
					'object__id'=>$ann->id,
					'new_data'=>json_encode($input)
					);
					$annLog = Logs::create($annLog);
					
					$msg = array();
					if( !$ann || !$annLog )
					{
					    DB::rollback();
						$msg['msg'] = 'Create announcements failed!';
						$msg['type'] = 'error';
					} else {
					    // Else commit the queries
					    DB::commit();
						$msg['msg'] = 'Create announcement successfully!';
						$msg['type'] = 'success';
					}
					$msgs = array($msg);
					return Redirect::route('announcements.index')->with('msgs', $msgs);
				} else {
					return $uploadSuccess;
				}
		    } 
        }
		return Redirect::route('announcements.create')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
        
	}

	/**
	 * Display the specified resource.
	 * GET /announcement/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		echo $id;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /announcement/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$announcement = Announcement::find($id);
		$this->layout->content = View::make('announcement.edit_ann',array('announcement'=>$announcement));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /announcement/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();
        $validation = Validator::make($input, Announcement::$rules);
        if ($validation->passes())
        {
            $ann = Announcement::find($id);
			$staffId = Auth::user()->id;
			$input['modify_by_staff__id']=$staffId;
			
			DB::beginTransaction();
			$successAnn = false;
			if (Input::hasFile('message_kh_update')) {
		        $file            = Input::file('message_kh_update');
		        $destinationPath = public_path().'/images/uploads/';
				$millisecond = round(microtime(true)*1000);
		        $filename        = $millisecond.'_'.str_random(2) . '_' . $file->getClientOriginalName();
		        $uploadSuccess   = $file->move($destinationPath, $filename);
								
				if ($uploadSuccess) {
					$input['message_kh']=$filename;
					$successAnn = $ann->update($input);
				}
		    } else {
		    	$successAnn = $ann->update($input);
		    }
			
			$annLog = array(
			'staff__id'=>$staffId,
			'action'=>'update',
			'object_type'=>'announcement',
			'object__id'=>$id,
			'old_data'=>json_encode($ann),
			'new_data'=>json_encode($input)
			);
			$successAnnLog = Logs::create($annLog);
			$msg = array();
			if( !$successAnn || !$successAnnLog )
			{
			    DB::rollback();
				$msg['msg'] = 'Update announcement failed!';
				$msg['type'] = 'error';
			} else {
			    // Else commit the queries
			    DB::commit();
				$msg['msg'] = 'Update announcement successfully!';
				$msg['type'] = 'success';
			}
			$msgs = array($msg);
            return Redirect::route('announcements.index')->with('msgs', $msgs);
        }
		return Redirect::route('announcements.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /announcement/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//Announcement::find($id)->delete();
		$staffId = Auth::user()->id;
		
		$ann = Announcement::find($id);
		$oldAnn = $ann;
		
		$ann['modify_by_staff__id'] = $staffId;
		$ann['status'] = 'delete';
		
		DB::beginTransaction();
		$successAnn = $ann->update();
		
		$annLog = array(
			'staff__id'=>$staffId,
			'action'=>'delete',
			'object_type'=>'announcement',
			'object__id'=>$id,
			'old_data'=>json_encode($oldAnn),
			'new_data'=>json_encode($ann)
			);
		$successAnnLog = Logs::create($annLog);
		$msg = array();
		if( !$successAnn || !$successAnnLog )
		{
			DB::rollback();
			$msg['msg'] = 'Delete Announcement failed!';
			$msg['type'] = 'error';
		} else {
			// Else commit the queries
			DB::commit();
			$msg['msg'] = 'Delete Announcement successfully!';
			$msg['type'] = 'success';
		}
		$msgs = array($msg);
        return Redirect::route('announcements.index')->with('msgs', $msgs);
	}
}