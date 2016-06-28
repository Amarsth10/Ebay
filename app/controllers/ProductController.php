<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 6/23/2016
 * Time: 2:04 PM
 */
class ProductController extends BaseController
{
    public function store()
    {
        $product = new Products;
        $product->title = Input::get('title');
        $product->description = Input::get('description');
        $product->image = Input::get('image');
        $product->price = Input::get('price');
        $product->quantity = Input::get('quantity');
        $product->save();
        return Redirect::to('/');
    }
}
