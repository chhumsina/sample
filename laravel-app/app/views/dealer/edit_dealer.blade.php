@section('title', 'Edit Dealer')
@section('content')
<?php
$baseUrl = URL::to('/');
$khanCode = '';
$communeCode = '';
$villageCode = '';

if (Input::old('commune__code')) {
	$communeCode = Input::old('commune__code');
}
if (Input::old('village__code')) {
	$villageCode = Input::old('village__code');
} else {
	$villageCode = $dealer->village__code;
}

?>
<script type="text/javascript">
	$(document).ready(function(){
		$baseUrl = '{{$baseUrl}}';
		$khanCode = '{{$khanCode}}';
		$communeCode = '{{$communeCode}}';
		$villageCode = '{{$villageCode}}';
		var seleteCommune = $("#commune__code");
		var seleteVillage = $("#village__code");
		var plsSelect = 'Please Select';
		seleteCommune.on("change",function(){
			setCommune($(this));
		});
		setCommune(seleteCommune);
		function setCommune(ob) {
			$val = ob.val();
			loadVillageByCommuneCode($val);
		}
		function loadVillageByCommuneCode($code) {
			seleteVillage.html('');
			seleteVillage.append('<option value="">'+plsSelect+'</option>');
						
			var url = $baseUrl+'/locations/village-by-commune-code/'+$code;
			console.log(url);
	    	$.ajax({ 
		        type: 'GET', 
		        url: url, 
		        data: { code: $code }, 
		        success: function (data) {
		        	console.log(data);
		        	Object.keys(data).forEach(function (key) { 
		        	    var value = data[key];
		        	    console.log("key ="+key);
		        	    console.log("$villageCode = "+$villageCode);
		        	    if ($villageCode != '') {
		        	    	
		        	    	if ($villageCode == key) {
		        	    		seleteVillage.append('<option value="'+key+'" selected="selected">('+key+') '+data[key]+'</option>');
							} else {
								seleteVillage.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
							}
		        	    } else {
		        	    	seleteVillage.append('<option value="'+key+'">('+key+') '+data[key]+'</option>');
		        	    }
		        	    
		        	    // iteration code
		        	});
		        }
		    });
		}
		
	});
</script>
<h4>Edit Dealer</h4>
@include('layouts.partial.render-message')
<div class="row">
	<div class="col-md-12">
		{{ Form::model($dealer, array('method' => 'PATCH', 'route' =>array('dealers.update', $dealer->id))) }}
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('did', 'DID:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{$dealer->id}}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('name', 'Name*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('name', $dealer->name, array('class' => 'form-control','placeholder'=>'Name','required'=>'required')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('national_card_id', 'National ID:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('national_card_id', $dealer->national_card_id, array('class' => 'form-control','placeholder'=>'National ID')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('phone', 'Phone:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('phone', $dealer->phone, array('class' => 'form-control','placeholder'=>'Phone')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('email', 'Email:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('email', $dealer->email, array('class' => 'form-control','placeholder'=>'Email')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('dealer_type__id', 'Dealer Type*:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{'('.$dealer->dealer_type__id.') '.$dealerTypes->name}}
			  		{{Form::hidden('dealer_type__id', $dealer->dealer_type__id)}}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('street', 'Address:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('street', $dealer->street, array('class' => 'form-control','placeholder'=>'Address')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('province', 'Province/City *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ '('.$dealer->province__code.') '.$khan->pname }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('khan__code', 'District/Khan *:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ '('.$dealer->khan__code.') '.$khan->name_en }}
			  	</div>
			</div>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('commune__code', 'Commune :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('commune__code', $dealer->commune__code, array('class' => 'form-control','placeholder'=>'Commune')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('village__code', 'Village :') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('village__code', $dealer->village__code, array('class' => 'form-control','placeholder'=>'Village')) }}
			  	</div>
			</div>
			<?php
			if ($dealer->dealer_type__id == 4) { ?>
				<div class="row" id="parent_dealer_id">
			  	<div class="col-md-4 text-right">
				  		{{ Form::label('parent_id', 'Parent Dealer Id:') }}
				  	</div>
				  	<div class="col-md-4 text-left">
				  		{{ Form::text('parent_id', $dealer->parent_id, array('class' => 'form-control','placeholder'=>'Parent Dealer Id')) }}
				  	</div>
				</div>
			<?php } else {
				 echo Form::hidden('parent_id', $dealer->parent_id, array('class' => 'form-control','placeholder'=>'Parent Dealer Id'));
			}?>
			
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('reference_dealer_id', 'Reference Dealer ID:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::text('reference_dealer_id', $dealer->reference_dealer_id, array('class' => 'form-control','disabled'=>'disabled')) }}
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php 
			  			$status = $dealer->status;
			  			if ($status == 'used') {
			  				echo 'used';
							echo Form::hidden('status', $status);
			  			} else {
			  				echo Form::select('status',array('active' => 'active', 'inactive' => 'inactive', 'reject'=>'reject'),$status,array('id' => 'khan', 'class' => 'form-control','required'=>'required'));
			  			}
			  		?>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status Game:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php 
			  			$statusGame = $dealer->status_game;
			  			echo Form::select('status_game',array('active' => 'active', 'inactive' => 'inactive'),$statusGame,array('class' => 'form-control','required'=>'required'));
			  		?>
			  	</div>
			</div>
			<div class="row">
			  	<div class="col-md-4 text-right">
			  		{{ Form::label('status', 'Status Other Service:') }}
			  	</div>
			  	<div class="col-md-4 text-left">
			  		<?php 
			  			$statusOs = $dealer->status_os;
			  			echo Form::select('status_os',array('active' => 'active', 'inactive' => 'inactive'),$statusOs,array('class' => 'form-control','required'=>'required'));
			  		?>
			  	</div>
			</div>
			<div class="row">
				<div class="col-md-4 text-right"></div>
			  	<div class="col-md-4 text-left">
			  		{{ Form::submit('Update',array('class' => 'btn btn-primary')) }}
			  	</div>
			</div>
			@include('layouts.partial.render-message-form')
		{{ Form::close() }}
	</div>
</div>
@stop