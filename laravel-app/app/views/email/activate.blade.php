<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
<h2>Activate Account</h2>

<div>
	Dear {{$username}},<br/><br/>

	Thank you for registering on Khmermoo.<br/><br/>

	Your account has been created and is waiting to be activated. Please follow a activated link below.<br/>
	{{ URL::to('register/activate/' . $code) }}.<br/><br/>
	Thanks you,<br/>
	Khmermoo Admin<br/>
	Khmermoo.com<br/>

</div>

</body>
</html>