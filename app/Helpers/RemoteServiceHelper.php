<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('getUser')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function getUser($userId)
    {   
      $url = env('SERVICE_USER_URL').'/users/'.$userId;
      $timeout = env('SERVICE_TIMEOUT');
 
      try{
        $response = Http::timeout($timeout)->get($url);
        
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
      
        return $data;
      } catch(\Throwable $th){
         return response()->json([
             'message' => 'error',
             'http_code' => 500,
             'message' => 'service user'
           ]
         );
      }
    }
}

if (!function_exists('getUserByIds')) {

    function getUserByIds($userIds = [])
    {   
      $url = env('SERVICE_USER_URL').'/users/';
      $timeout = env('SERVICE_TIMEOUT');
 
      try{
        
        if(count($userIds) === 0){
            return response()->json([
                'status' => 'error',
                'http_code' => 500,
                'data' => []
            ]);
        }

        
        $response = Http::timeout($timeout)->get($url,['user_ids[]' => $userIds]);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
      
        return $data;
      } catch(\Throwable $th){
         return response()->json([
             'message' => 'error',
             'http_code' => 500,
             'message' => 'service user unavailable'
           ]
         );
      }
    }
}

if (!function_exists('postOrder')) {
   function postOrders($params){
     $url = env('SERVICE_ORDER_PAYMENT_URL').'api/orders';
     try{
     
      $response = Http::post($url,$params);
      $data = $response->json();
      $data['http_code'] = $response->getStatusCode();
      return $data;

     }catch(\Throwable $e){
        return response()->json([
             'message' => 'error',
             'http_code' => 500,
             'message' => 'service order payment unavailable'
           ]
         );
     }
   }
}
