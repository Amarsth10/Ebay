<html>
<head></head>
<body>
<h2>Register</h2>
<div align="center">
<table >
<tr>
{{ Form::open(['url'=>'register']) }}
<td>{{ Form::label('fname','First Name:') }}</td>
<td>{{ Form::text('fname') }}</td>
<td>{{$errors->first('fname')}}</td>
<td>{{ Form::label('lname','Last Name:') }}</td>
<td>{{ Form::text('lname') }}</td>
<td>{{$errors->first('lname')}}</td>
</tr>
<tr>
<td>{{ Form::label('address','Address:') }}</td>
<td>{{ Form::text('address') }}</td>
<td>{{$errors->first('address')}}</td>
</tr>
<tr>
<td>{{ Form::label('contact','Contact:') }}</td>
<td>{{ Form::text('contact') }}</td>
<td>{{$errors->first('contact')}}</td>
</tr>
<tr>
<td>{{ Form::label('username','Username:') }}</td>
<td>{{ Form::text('username') }}</td>
<td>{{$errors->first('username')}}</td>
</tr>
<tr>
<td>{{ Form::label('password','Password:') }}</td>
<td>{{ Form::password('password') }}</td>
<td>{{$errors->first('password')}}</td>
</td>
</tr>
<td>
{{ Form::submit('Create User') }}
</td>
{{ Form::close() }}
</table>
</div>
</body>
</html>