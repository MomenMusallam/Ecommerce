<?php


namespace App\Traits;


use phpDocumentor\Reflection\Types\True_;

trait APITrait
{
    public function jsonForApi($status , $message , $items, $code ){
    if($status){
        $items = json_decode(json_encode($items, true), true);
        $arr = [ 'status' => 'true' , 'message' => $message , 'data'=>$items];
//        $jsonFormat = json_encode($arr);
        return response()->json($arr);
    }
    }
}
