@section('title', 'Create Actual Sale')
@section('content')
<?php
$baseUrl = URL::to('/');

?>

<h4>Create Actual Channel Sale</h4>
@include('layouts.partial.render-message')
<div class="row">
	{{ Form::open(array('route' => 'sale-actual.store')) }}
	<div class="col-md-12">
		
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('Channel Owner', 'Channel Owner*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php
			  		if(isset($channelOwners)){
			  		?>
			  		{{ Form::select('channel_owner__id', array('' => 'Please Select')+$channelOwners, '', array('class' => 'form-control','id' => 'channel_owner_id','required'=>'required')) }}
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
			  		<input class="form-control" required="required" type="text" id="datepicker_start" name="datetime" value="<?php if(Input::has('datetime')) {echo  Input::get('datetime');}else{ echo date("Y-m-j");} ?>" />
			  	</div>
			</div>
			<!--<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('currency', 'Currency*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::select('currency__id', array('USD' => 'USD','KHR' => 'KHR'), '', array('class' => 'form-control','id' => 'currency','required'=>'required')) }}
			  	</div>
			</div>-->			
			<?php
			if(isset($sysServiceTypes)){
				foreach ($sysServiceTypes as $sysServiceType) {
			?>
				<div class="row">
				  	<div class="col-md-4 text-right">
				  		{{ Form::label($sysServiceType->service_type_name, ''.$sysServiceType->service_type_name.'*:') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ Form::text($sysServiceType->service_type_id ,'', array('class' => 'form-control','required'=>'required')) }}
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
			  		{{ Form::submit('Save',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		
	</div>
	
	{{ Form::close() }}
</div>
<div class="row">
	<div class="col-md-12 ">
		 @if ($actualSales->count())
		    <table class="table table-striped table-bordered">
		        <thead>
		            <tr>
		                <th>No</th>
				        <th>Channel Owner</th>
				        <th>Sale Date</th>
				        <!--<th>Currency</th>-->
				        <th>Sale Game</th>
				        <th>Created At</th>
				        <th>Created By</th>
				        <th>Action</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php $i = ($actualSales->getCurrentPage() - 1)* $actualSales->getPerPage(); ?>
		            @foreach ($actualSales as $actualSale)
		            	<?php $i += 1; ?>
		                <tr>
		                    <td>{{ $i }}</td>
		                    <td>{{ $actualSale->channel_owner_name }}</td>
		          			<td>{{ $actualSale->datetime }}</td>
		          			<td>
		          				<ul>
		          				<?php
		          					$listGameSales = DB::table('rep_channel_sale_service_type as csst')
													->select('csst.*')
													->join('sys_service_type as st','st.service_type_id','=','csst.service_type__id')
													->orderBy('st.sequence_number')
													->where('csst.channel_sale__id',$actualSale->channel_sale_id)
													->get();
												
									foreach ($listGameSales as $key => $gameSale) {
										echo '<li style="list-style:none;">'.$gameSale->service_type__id.' = '.$gameSale->amount.'</li>';
									}
		          				?>
		          				</ul>
		          			</td>
		          			<td>{{ $actualSale->created_at }}</td>
		          			<td>{{ $actualSale->createdBy }}</td>
		          			<td>
						      	<?php
							  	if (Entrust::can('edit_actual_sale')) {
									echo link_to('sale-actual/'.$actualSale->channel_sale_id.'/edit', 'Edit');
								}
								?>
								
		          			</td>
		          		</tr>
		          	@endforeach
		        </tbody>
		    </table>
			<?php echo $actualSales->links(); ?>
		@else
		@endif
	</div>	
</div>
@stop