<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Traits\APITrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use APITrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        return response()->json(Order::all());
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
    public function store(Request $request)
    {
        $request['user_id'] = $request->user()->id;
        $user_id = $request['user_id'];
        $cart = Cart::Where('user_id', $user_id)->get();
        if ($cart) {
            $message = '';
            foreach ($cart as $cart_product) {
                $product = Product::find($cart_product->product_id);
                if ($cart_product->quantity > $product->quantity) {
                    $message = $message . $product->name . ',';
                }
            }
            if ($message != '') {
                $message = $message . 'product quantity not available at the moment ';
                return $this->returnError($message, 500);
            } else {
                $order = Order::create($request->All());
                if ($order != null) {
                    $total_price = 0;
                    foreach ($cart as $cart_product) {
                        $product = Product::find($cart_product->product_id);
                        if ($cart_product->quantity >= $product->quantity) {
                            OrderProduct::create(['product_id'=>$product->id, 'order_id'=>$order->id,'sale_price'=> $product->sale_price, 'quantity'=>$cart_product->quantity]);
                            $total_price = $total_price + $product->sale_price * $cart_product->quantity;
                            $product->update(['quantity' => $product->quantity - $cart_product->quantity]);
                        }
                    }
                    $order->total_price = $total_price;
                    $order->save();
                   return $this->returnData(true ,'order started ', $order , 200);
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showOrderOfUSer(Request $request)
    {
        $user_id = $request->user()->id;
        $orders = Order::where('user_id' , $user_id)->get();
        return $this->returnData(true , 'orders fo single user' , $orders , 200);
    }

    public function showProductOfOrder(Request $request , $id)
    {
        $user_id = $request->user()->id;
        $order = Order::find($id);
//        return $this->returnSuccessMessage($order);
        if($user_id == $order->user_id){
        $products = OrderProduct::where('order_id' , $id)->get();
        return $this->returnData(true , 'order products' , $products , 200);
    }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
