<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
<h2>Recovery Password</h2>

<div>
	Dear {{$username}},<br/><br/>

	There was someone try to recovery your password,<br/>
	If it was you, please click the recovery link below:
	<br/><br/>New password: <b>{{$password}}</b><br/>
	{{ URL::to('recovery/password/' . $code) }}.<br/>

</div>

</body>
</html>