@section('title', 'List Member')
@section('content')
	@foreach($members as $member)
		{{$member->username}}
	@endforeach
@stop