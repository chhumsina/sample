@section('title', 'Print Ticket')
@section('content')
	<script>

		$(document).ready(function(){
			var total = 0;

			$('body').on('keyup', '.num', function(){
				total = $('.num').val() * 6;
				$('.totalTicket').html(total);
			});

			// Allow only number
			$(".num").keydown(function (e) {
				// Allow: backspace, delete, tab, escape, enter and .
				if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
							// Allow: Ctrl+A
						(e.keyCode == 65 && e.ctrlKey === true) ||
							// Allow: home, end, left, right
						(e.keyCode >= 35 && e.keyCode <= 39)) {
					// let it happen, don't do anything
					return;
				}
				// Ensure that it is a number and stop the keypress
				if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
					e.preventDefault();
				}
			});

			@if (Session::has('items'))
				window.onload=function(){window.print()};
			@endif

		});
	</script>

	<?php
	$baseUrl = URL::to('/');
	?>

<h4>Print Ticket</h4>
@include('layouts.partial.render-message')
<div class="row form">
	<div class="col-md-3"></div>
	<div class="col-md-9">
		{{--{{ Form::open(array('url' => url('lucky/getPdf'), 'files'=>true,'class'=>'form-horizontal', 'id'=>'form', 'style'=>'border:solid gray 0px')) }}--}}
		{{ Form::open(array('url' => url('ticket/print-ticket'), 'files'=>true,'class'=>'form-horizontal', 'id'=>'form', 'style'=>'border:solid gray 0px')) }}

		<div class="form-group">
			{{ Form::label('dealer__id', 'Dealer Id *', array('class'=>'col-sm-2 control-label')) }}

			<div class="col-sm-3">
				{{ Form::select('dealer__id', array('' => 'Please Select')+$dealers, null, array('class' => 'form-control','id' => 'dealer__id','required'=>'required'))}}
			</div>
		</div>
		<div class="form-group">
			{{ Form::label('advanceDraw', 'Advance Draw *', array('class'=>'col-sm-2 control-label')) }}

			<div class="col-sm-3">
				{{ Form::select('advanceDraw', array('' => 'Please Select')+$advanceDraws, null, array('class' => 'form-control','id' => 'advanceDraw','required'=>'required'))}}
			</div>
		</div>
		<div class="form-group ticket">
			{{ Form::label('icon', 'Ticket amount *', array('class'=>'col-sm-2 control-label')) }}

			{{ Form::label('icon', '6 &nbsp;&nbsp;&nbsp;&nbsp; x', array('class'=>'control-label numberLeft')) }}
			<div class="col-sm-1">
				{{FORM::text('quantity','',array('class'=>'form-control num', 'required' => 'required'))}}
			</div>
			{{ Form::label('icon', ' &nbsp;= &nbsp;&nbsp;&nbsp;', array('class'=>'control-label numberLefts')) }}
			<span class="totalTicket">0</span>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input class="btn btn-primary" type="submit" name="getTicket" value="Submit" />
				<a href="<?php echo $baseUrl;?>"><span class="btn btn-warning">Back</span></a>
			</div>
		</div>
		{{ Form::close() }}
	</div>
</div>
<div class="row show-print">
	<?php
	$count = 1;
	$item = Session::get('items');
	?>
	@if (Session::has('items'))
	<div class="col-md-12">
			@foreach ($item->num as $num)
				@if($count%2===0)
					<div class="item">
						<div class="date1">
							<span>{{$item->drawdate}}</span>
						</div>
						<div class="date2">
							<span>{{$item->drawdate}}</span>
						</div>
						<div class="tsn">
							<span><?php echo wordwrap($num->tsn,4,'-',true);;?></span>
						</div>
						<div class="did">
							<span>{{Session::get('dealer_id')}}</span>
						</div>
						<div class="number1">
							<i>{{$num->num}}</i>
						</div>
						<div class="number2">
							<i>{{$num->num}}</i>
						</div>
						<div class="barcode">
							<img id="barcode{{$count}}">
							<script type="text/javascript">
								$("#barcode<?php echo $count;?>").JsBarcode("58CB966571A23C6C",{format:"CODE128",displayValue:true,fontSize:12,width:1, height:30});
							</script>
						</div>
					</div>
				@else
				<div class="item">
					<div class="date1Right">
						<span>{{$item->drawdate}}</span>
					</div>
					<div class="date2Right">
						<span>{{$item->drawdate}}</span>
					</div>
					<div class="tsnRight">
						<span><?php echo wordwrap($num->tsn,4,'-',true);;?></span>
					</div>
					<div class="didRight">
						<span>{{Session::get('dealer_id')}}</span>
					</div>
					<div class="number1Right">
						<i>{{$num->num}}</i>
					</div>
					<div class="number2Right">
						<i>{{$num->num}}</i>
					</div>
					<div class="barcodeRight">
						<img id="barcode{{$count}}">
						<script type="text/javascript">
							$("#barcode<?php echo $count;?>").JsBarcode("58CB966571A23C6C",{format:"CODE128",displayValue:true,fontSize:12,width:1, height:30});
						</script>
					</div>
				</div>
				@endif
				<?php $count++;?>
			@endforeach
		</div>
	@endif
</div>

@stop