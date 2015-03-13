@section('title', 'Update Actual Sale')
@section('content')
<?php
$baseUrl = URL::to('/');

?>

<h4>Edit Actual Channel Sale</h4>
@include('layouts.partial.render-message')
<div class="row">
	{{ Form::model($actualSale, array('method' => 'PATCH', 'route' =>array('sale-actual.update', $actualSale->channel_sale_id))) }}
	<div class="col-md-12">
		
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('Channel Owner', 'Channel Owner*:') }}
			  		{{ Form::hidden('channel_sale_id', $actualSale->channel_sale_id)}}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  		if(isset($channelOwners)){
			  		?>
			  		{{ Form::select('channel_owner__id', array('' => 'Please Select')+$channelOwners, $actualSale->channel_owner__id, array('class' => 'form-control','id' => 'channel_owner_id','required'=>'required')) }}
			  		<?php
					}
			  		?>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('Date', 'Sale Date*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<input class="form-control" required="required" type="text" id="datepicker_start" name="datetime" value="<?php if(Input::has('datetime')) {echo  Input::get('datetime');}else{ echo $actualSale->datetime;} ?>" />
			  	</div>
			</div>
			<!--<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('currency', 'Currency*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::select('currency__id', array('USD' => 'USD','KHR' => 'KHR'), $actualSale->currency__id, array('class' => 'form-control','id' => 'currency','required'=>'required')) }}
			  	</div>
			</div>-->
			
			<?php
			if(isset($sysServiceTypes)){
				$listGameSales = DB::table('rep_channel_sale_service_type as csst')
													->select('csst.*','st.service_type_name')
													->join('sys_service_type as st','st.service_type_id','=','csst.service_type__id')
													->orderBy('st.sequence_number')
													->where('csst.channel_sale__id',$actualSale->channel_sale_id)
													->get();
													
				foreach ($listGameSales as $gameSale) {
			?>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label($gameSale->service_type__id,$gameSale->service_type_name.'*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text($gameSale->service_type__id ,$gameSale->amount, array('class' => 'form-control','required'=>'required')) }}
			  	</div>
		  	</div>
			<?php	
				}	
			}
			?>
			
			<div class="row">
				<div class="col-md-4 text-right"></div>
			  	<div class="col-md-4 text-left">
				<br/>	
			  		{{ Form::submit('Update',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		
	</div>
	
	{{ Form::close() }}
</div>
@stop