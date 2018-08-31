<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Transaction;

class ProductBuyerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, Product $product, User $buyer)
    {
         $rules = [
             'quantity'=>'required|integer|min:1',
         ];
          
          $this->validate($request, $rules);
          
          
          if($buyer->id == $product->seller_id)
          {
                return $this->errorResponse("Buyer should be different from Seller",
                                409);
          }
          
          if(!$buyer->isVerified())
          {
                 return $this->errorResponse("Buyer should be verified user",
                                409);
          }
          
          if(!$product->seller->isVerified())
          {
                 return $this->errorResponse("Seller should be verified user",
                                409);
          }
          
          if($product->status != $product->isAvailable())
          {
                return $this->errorResponse("Product is not available",
                                409);
          }
          
          if($product->quantity < $request->quantity)
          {
                return $this->errorResponse("Not enough product",
                                409);
          }
          
          
          
          return DB::transaction(function() use ($request, $product, $buyer){
                
                  $product->quantity -= $request->quantity;
                  
                  $product->save();
                  
                  $transaction = Transaction::create([
                        'quantity' => $request->quantity,
                        'product_id' => $product->id,
                        'buyer_id' => $buyer->id,
                  ]);
                  
                 
              
                  if($product->quantity == 0 && $product->isAvailable())
                  {
                        $product->status = Product::UNAVAILABLE_PRODUCT;
                        $product->save();
                  }
              
       
                  return $this->showOne($transaction);
          });
          
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
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
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
