@section('title', 'List Member')
@section('content')
	<h2>Detail</h2>
	<div class="panel">
		<div class="panel-heading">
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100" class="img-circle"> </div>
				<div class=" col-md-9 col-lg-9 ">
					<table class="table table-user-information">
						<tbody>
						<tr>
							<td>Username:</td>
							<td>{{$member->username}}</td>
						</tr>
						<tr>
							<td>Email:</td>
							<td>{{$member->email}}</td>
						</tr>
						<tr>
							<td>First Name:</td>
							<td>{{$member->first_name}}</td>
						</tr>
						<tr>
							<td>Last Name:</td>
							<td>{{$member->last_name}}</td>
						</tr>
						<tr>
							<td>Location:</td>
							<td>{{$member->location}}</td>
						</tr>
						<tr>
							<td>Phone:</td>
							<td>{{$member->phone}}</td>
						</tr>
						<tr>
							<td>Address:</td>
							<td>{{$member->address}}</td>
						</tr>
						<tr>
							<td>Created Date:</td>
							<td>{{$member->created_date}}</td>
						</tr>
						<tr>
							<td>Status</td>
							<td>@if($member->status)
									<button class="btn btn-success"><span class="fa fa-check-circle-o"></span> Active </button>
								@else
									<button class="btn btn-warning"><span class="fa fa-ban"></span> Inactive </button>
								@endif</td>
						</tr>

						</tbody>
					</table>
					<button class="btn btn-default"><span class="fa fa-chevron-circle-left"></span> Back </button>
					<button class="btn btn-primary"><span class="fa fa-edit"></span> Submit </button>
				</div>
			</div>
		</div>
	</div>
@stop