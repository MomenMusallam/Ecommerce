<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Offer;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Traits\APITrait;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use APITrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ads = Ad::latest()->limit(3)->get();

        $Categories = Category::first()->limit(10)->get();

        $dailyDeals = Offer::latest()->limit(10)->get();
        foreach ($dailyDeals as $dailyDeal){
            $dailyDealsProduct = $dailyDeal->product()->get();
        }

        $recent_added = Product::latest()->limit(10)->get();

        $trendingProductOrders = OrderProduct::select('product_id', OrderProduct::raw('count(*) as total'))->groupBy('product_id')->orderBy('total','DESC')
          ->limit(10)->get();
        foreach ($trendingProductOrders as $trendProduct){
            $trendingProducts[] = $trendProduct->product()->get();
        }

        $homeScreen = collect(['ads' =>$ads , 'categories' => $Categories , 'dailyDeals' => $dailyDealsProduct , 'Recently added' => $recent_added , 'trending'=>$trendingProducts]);
        return $this->returnData(true , 'show all products' ,$homeScreen, 200 );

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
//        $request->validate([
//            'name'=>'required',
//            'quantity'=>'required',
//            'description'=>'required',
//            'price'=>'required',
//            'sale_price'=>'required',
//            'status'=>'required',
//        ]);
//       return Product::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $product->ratings = $product->ratings()->get();
        return $this->returnData(true , 'show single products' ,$product , 200);
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
//        $product = Product::find($id);
//        $product->update($request->all());
//        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        return response()->json(Product::destroy($id));
    }
}
