<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);
        // echo "<pre>";print_r($products->toArray()['data'][0]);exit();
        return view('products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
            'name' => 'required',
            'father_name' => 'required',
            'detail' => 'required',
            'file' => 'required|image|max:5048',

        ]);
       $fileName = null;
    if (request()->hasFile('file')) {
        $file = request()->file('file');
        $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        $file->move('uploads/', $fileName);
        unset($request->file);
        $request['file']=$fileName; 
        // echo "<pre>";print_r($request->file);exit(); 
    }
    // print_r($file);exit();
       // echo "<pre>";print_r($request);exit();
       $data=[
            'name' => $request->name,
            'father_name' => $request->father_name,
            'detail' => $request->detail,
            'file' => $fileName

        ];
        Product::create($data);
   
        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
         return view('products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
       return view('products.edit',compact('product'));
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
         $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
  
        $product->update($request->all());
  
        return redirect()->route('products.index')
                        ->with('success','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
         $product->delete();
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
}
