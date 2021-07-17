<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
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
        //get user id
        $request['user_id'] = $request->user()->id;
        $user_id = $request['user_id'];
        //get user cart
        $userCarts = User::find($user_id)->carts()->get();

        if (isset($userCarts[0])){
            $message = '';
            //get products from cart and check if the quantity is available or not
            foreach ($userCarts as $cart_product) {
                $product = Product::find($cart_product->product_id);
                if($product->quantity < $cart_product->quantity){
                    $message = $message . $product->name . ',';
                }
                $product->required_quantity = $cart_product->quantity;
                $products[] = $product;
            }

            //if quantity not available
            if ($message != '') {
                $message = $message . 'product quantity not available at the moment ';
                return $this->returnError($message, 500);
            } else {
                $order = Order::create($request->All());
                if ($order != null) {
                    $total_price = 0;
                    foreach ($products as $product){
                            OrderProduct::create(['product_id'=>$product->id, 'order_id'=>$order->id,'sale_price'=> $product->sale_price, 'quantity'=>$product->required_quantity ]);
                            $total_price = $total_price + $product->sale_price * $product->required_quantity;
                            $available_quantity = $product->quantity - $product->required_quantity ;
                            $product->quantity = $product->quantity - $product->required_quantity ;
                            unset($product->required_quantity);
                            $product->save();
                    }
                    $order->total_price = $total_price;
                     if($order->save()){
                    User::find($user_id)->carts()->delete();
                    $order->products = $products;
                   return $this->returnData(true ,'order started ', $order , 200);
                }
                }
            }
        }
        return $this->returnData(true ,'order startsdsed ', 55 , 200);

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
        $orders = User::find($user_id)->orders()->get();
        foreach ($orders as $order){
            $order->orderProducts = $order->orderProducts()->get();
            foreach ($order->orderproducts as $orderProduct){
                $orderProduct->product = $orderProduct->product()->get();
            }
        }
        return $this->returnData(true , 'orders fo single user' , $orders , 200);
    }

    public function showProductOfOrder(Request $request , $id)
    {
        $user_id = $request->user()->id;
        $order = User::find($user_id)->orders()->where('id', $id)->first() ;
        $orderProducts = $order->orderProducts()->get();
            foreach ($orderProducts as  $orderProduct){
                $orderProduct->product = $orderProduct->product()->first();
            }


        return $this->returnData(true , 'order products' , $orderProducts , 200);
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
