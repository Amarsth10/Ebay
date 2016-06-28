<html>
<head></head>
<body>
<h2>All Products</h2>
               <div id="product_list" align="left">
               @foreach ($products as $product)
               <div>{{ HTML::image($product->image) }}<br>{{'Title: '}}{{ link_to("/products/{$product->title}",$product->title) }}<br>{{'Price: €',$product->price}}
               <br>{{'Quantity: ',$product->quantity}}<br>{{'Description: ',$product->description}}
               {{ Form::open(array('url' => url('AddItem'))) }}
               {{ Form::hidden('quantity',$product->quantity) }}
               {{ Form::hidden('product_id', $product->id) }}
               {{ Form::hidden('title', $product->title) }}
               {{ Form::hidden('price', $product->price) }}
               {{ Form::hidden('description', $product->description) }}
               {{ Form::submit('Add Item') }}
                {{ Form::close() }}</div>
    @endforeach
 </div>
</body>
</html>