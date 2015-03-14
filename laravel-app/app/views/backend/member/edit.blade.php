@section('title', 'List Member')
@section('content')
	<?php
	$baseUrl = URL::to('/');
	?>

	<h2>Detail</h2>
	<div class="panel">
		<div class="panel-heading">
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100" class="img-circle"> </div>
				<div class=" col-md-9 col-lg-9 ">
					<?php echo Form::open(array('url' => 'backend/member/edit','role' => 'form', 'class'=>'form-inline')) ?>
					<table class="table table-user-information">
						<tbody>
						<tr>
							<td>Status</td>
							{{Form::hidden('id',$member->id,array('id'=>'id'))}}
							{{Form::hidden('status',$member->status)}}
							<td>@if($member->status)
									<button type="submit" name="active" value="active" class="btn btn-success"><span class="fa fa-check-circle-o"></span> Active </button>
								@else
									<button type="submit" name="inActive"  value="inActive" class="btn btn-warning"><span class="fa fa-ban"></span> Inactive </button>
								@endif</td>
						</tr>
						<tr>
							<td>Username:</td>
							<td>{{ Form::text('username', $member->username, array('class' => 'form-control','disabled'=>'')) }}</td>
						</tr>
						<tr>
							<td>Email:</td>
							<td>{{ Form::text('email', $member->email, array('class' => 'form-control')) }}</td>
						</tr>
						<tr>
							<td>First Name:</td>
							<td>{{ Form::text('first_name', $member->first_name, array('class' => 'form-control')) }}</td>
						</tr>
						<tr>
							<td>Last Name:</td>
							<td>{{ Form::text('last_name', $member->last_name, array('class' => 'form-control')) }}</td>
						</tr>
						<tr>
							<td>Location:</td>
							<td>{{ Form::text('location', $member->location, array('class' => 'form-control')) }}</td>
						</tr>
						<tr>
							<td>Phone:</td>
							<td>{{ Form::text('phone', $member->phone, array('class' => 'form-control')) }}</td>
						</tr>
						<tr>
							<td>Address:</td>
							<td>{{ Form::text('address', $member->address, array('class' => 'form-control')) }}</td>
						</tr>
						<tr>
							<td>Created Date:</td>
							<td>{{$member->created_date}}</td>
						</tr>
						</tbody>
					</table>
						<a href="{{$baseUrl}}/backend/member" class="btn btn-default"><span class="fa fa-chevron-circle-left"></span> Back </a>
						<button type="submit" class="btn btn-primary" name="submit" value="submit"><span class="fa fa-edit"></span> Submit </button>
					<?php echo Form::close() ?>
				</div>
			</div>
		</div>
	</div>
@stop