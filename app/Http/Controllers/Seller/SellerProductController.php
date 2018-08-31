<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Illuminate\Support\Facades\Storage;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products()->get();
        return $this->showAll($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
          $this->validate($request, [
              'name'=>'required',
              'description'=>'required',
              'quantity'=>'required|integer|min:1',
              'image'=>'required|image'
          ]);
          $data = $request->all();
          $data['status'] = Product::UNAVAILABLE_PRODUCT;
          $data['image'] = 'bar1.jpeg';
          $data['seller_id'] = $seller->id;
          
          $product = Product::create($data);
          
          return $this->showOne($product);
          
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function edit(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
          $this->validate($request, [
              'quantity'=>'integer|min:1',
              'image'=>'image',
              'status'=>'in:'.Product::AVAILABLE_PRODUCT.','.Product::UNAVAILABLE_PRODUCT,
          ]);
          
          $this->checkSeller($seller, $product);
          
          $product->fill($request->only([
              'name',
              'description',
              'quantity',
          ]));
            
          if($request->has('status'))
          {
                $product->status = $request->status;
                
                if($product->isAvailable() && $product->categories()->count()==0)
                {
                      return $this->errorResponse("An active must have at least one category",
                                409);
                }
          }
          
          if($request->hasFile('image'))
          {
                Storage::delete($product->image);
          }
          
          if($product->isClean())
          {
                return $this->errorResponse("Please specify different value",
                                422);
          }
          
          $product->save();
          
          return $this->showOne($product);
          
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller,Product $product)
    {
          $this->checkSeller($seller, $product);
          $product->delete();
          return $this->showOne($product);
    }
    
    protected function checkSeller($seller ,$product)
    {
          if($seller->id != $product->seller_id)
          {
                throw new HttpException(422,'The specified seller is not actual seller of product');
          }
    }
}
