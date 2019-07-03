<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    public $user;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(function ($request, $next) {

            $user = Auth::user();
            //dd($user->getPermissionNames());
            //$user->removeRole('admin');
            $user->assignRole('admin');
            $id = Auth::id();
            //dd($user);
            if(!$user->hasRole('admin')) {
                //dd(12121);
            }

            //if (session_id() == '') {
                @session_start();
            //}
            $_SESSION['isLoggedIn'] = Auth::check() ? true : false;
            return $next($request);
        });

        $this->middleware('permission:product-list');
        $this->middleware('permission:product-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $products = Product::latest()->paginate(5);
        return view(env('THEME') . '.admin.products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(env('THEME') . '.admin.products.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index', app()->getLocale())
            ->with('success','Product created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($locale, Product $product)
    {
        return view(env('THEME') . '.admin.products.show', compact('product'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, Product $product)
    {
        return view(env('THEME') . '.admin.products.edit', compact('product'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $locale, Product $product)
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index', app()->getLocale())
            ->with('success','Product updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($locale, Product $product)
    {
        $product->delete();

        return redirect()->route('products.index', app()->getLocale())
            ->with('success','Product deleted successfully');
    }
}
