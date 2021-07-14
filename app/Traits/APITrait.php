<?php


namespace App\Traits;


use phpDocumentor\Reflection\Types\True_;

trait APITrait
{
    public function returnData($status , $message , $items, $code ){

        $items = json_decode(json_encode($items, true), true);
        $arr = [ 'status' => $status , 'message' => $message ,'code' =>$code, 'data'=>$items ];
//        $jsonFormat = json_encode($arr);
        return response()->json($arr);
    }


    public function returnError($message , $code)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'code' => $code,
            'data' => ''
        ]);
    }


    public function returnSuccessMessage($message = "", $code = "S000")
    {
        return [
            'status' => true,
            'message' => $message,
            'code' => $code,
            'data' => ''
        ];
    }
    public function returnUserToken($token)
    {
       return substr($token , 7);
    }

}
