<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 6/20/2016
 * Time: 12:59 PM
 */
class UsersController extends BaseController{
    public  function create(){
        return View::make('users.index');
    }

    public function store(){
        $user= new User;
        $user->fname = Input::get('fname');
        $user->lname = Input::get('lname');
        $user->address = Input::get('address');
        $user->contact = Input::get('contact');
        $user->username = Input::get('username');
        $user->password = Hash::make(Input::get('password'));
        $user->save();
        return Redirect::to('users');
    }

}

