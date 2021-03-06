<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('permission:product.view')->only('index');
        $this->middleware('permission:product.create')->only('store');
        $this->middleware('permission:product.edit')->only('update');
        $this->middleware('permission:product.delete')->only('destroy');
    }

    public function index()
    {
        $product =filterData('\App\Models\Product');
        $companies = getAllCompanies();
        if (!auth()->user()->hasRole('super-admin')) {
            $product = Product::where('company_id',auth()->user()->company->id)->get();
        }
        return view('admin.product', compact('product','companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.addProduct');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'product_name' => ['required'],
            'description' => ['required'],
            'regex' => ['required', 'unique:products'],
            'permissionUrl' =>['required','url'],
            'urlProduct'=>['required','url']
        ]);
        
        $productModel = new Product;
        $productModel->createProduct($request->all());

        addToLog("Menambahkan product ".$request->product_name);
        return redirect(route('admin.product.index'))->with('status', 'Data inserted successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'product_name' => 'required',
            'description' => 'required',
            'regex' => ['required'],
            'permissionUrl' => ['required', 'url']
        ]);

        $productModel = new Product;
        $productModel->updateProduct($request, $product->id);
        
        addToLog("Edit product id ".$product->id);
        return redirect(route('admin.product.index'))->with('status', 'Data updated successfully');
    }

    public function updateCode(Request $request, Product $product) {
        $productModel = new Product;
        $productModel->updateCode($request, $product->id);

        return
        redirect(route('admin.product.index'))->with('status', 'Code updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Product::destroy($product->id);
        addToLog("Delete product ".$product->product_name);
        return redirect(route('admin.product.index'))->with('status', 'Item deleted successfully');
    }
    // custom

    public function searchByCompany($company)
    {
        $companies = Company::where('name',$company)->get()->first();
        $product= $companies->products;
        $companies = getAllCompanies();
        return view('admin.product', compact('product','companies'));

        
    }
}
