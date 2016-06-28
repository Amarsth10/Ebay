<html>
<head></head>
<body>
<h1>Add Products</h1>
{{Form::open(['url'=>'add'])}}
{{Form::label('title','Title:')}}
{{Form::text('title')}}
{{Form::label('description','Description:')}}
{{Form::text('description')}}
{{Form::label('price','Price:')}}
{{Form::text('price')}}
{{Form::label('quantity','Quantity:')}}
{{Form::text('quantity')}}
{{Form::label('image','Image:')}}
{{Form::file('image')}}
{{Form::submit('Add Product')}}
{{Form::close()}}
</body>
</html>