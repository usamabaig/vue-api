<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\UserAdAccount;
use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Application;
use FacebookAds\Object\Fields\AdAccountFields;
use Illuminate\Http\Request;

class UserAdAccountController extends Controller
{
   public function store(Request $request){
       /*$user = User::where("api_token" , $request->user_token)->first();
       if($user != null && isset($user)){
       $data = $request->all();
       unset($data['user_token']);
       $data['user_id'] = $user->id;
       UserAdAccount::create($data);
       return response()->json(['status' => 'success' , 'message' => 'Account stored' , 'data' => $data],200);
       }
       else{
           return response()->json(['status' => 'error' , 'message' => 'user not found' , 'data' => null ],404);
       }*/
       $user_data = $request->all();

       $all_ad_account = [];
       foreach ($user_data as $data) {
           if (UserAdAccount::where('ad_account_id',$data['id'])->first() == null) {
               $ad_account = new UserAdAccount();
               $ad_account->user_id = 3;
               $ad_account->ad_account_id = $data['id'];
               $ad_account->ad_account_name = $data['name'];
               $ad_account->is_show = 0;
               $all_ad_account[] = $ad_account->toArray();
           }
       }
       UserAdAccount::insert($all_ad_account);

       return response()->json(['status' => 'success' , 'message' => 'Account stored'],200);
   }

   public function getUserAccount(Request $request){

   }

   public function getUserAccountList(Request $request){
       $data = UserAdAccount::select('ad_account_id', 'ad_account_name')->where([['user_id', '=', $request['user_id']], ['is_show', '=', $request['is_show']]])->get()->toArray();
       if($data != null && isset($data))  {
       return response()->json($data,200);
       }
       else{
       return response()->json(['status' => 'error' , 'message' => 'Record not found' , 'data' => null ],404);
       }
   }

   public function updateUserAdAcount(Request $request){
       $data = UserAdAccount::where('user_id' , $request['user_id'])->where('ad_account_id' , $request['ad_account_id']);
       if($data->first() != null)  {
           $data->first()->is_show == 0 ? $show = 1 : $show = 0;
           $data->update(['is_show' => $show, 'ad_account_custom_name' => $request['custom_name']]);

           $show_ad_account = UserAdAccount::where([['user_id', '=', $request['user_id']], ['is_show', '=', 1]])->get();
           return response()->json($show_ad_account,200);
       }
       else{
           return response()->json(['status' => 'error' , 'message' => 'User account not found' ],404);
       }
   }

   public function getInsights()
   {
       $access_token = 'EAAMs4fSsKdABANgZBLiNxXfkuBOkLeygKnkm3D8YagSN0C8HxxzIq0BbFxr2EQtCCXjRJZBH8ZAO029FpI5joA5jVWqgoo05z9vTG0uyQKvevQnG0FONIQKw0RnvDiSPvgjCpBsedKqwMEVXlUW3ZB8pbJm5j8BvC2GfmLr01Dg4cEscjBIwJ7XKMvsSRb4ZD';
       $ad_account_id = 'act_2417299975250952';
       $app_secret = '8f5710f37376bb7d36bf2953315027e7';
       $app_id = '893773914319312';
       $ywa = Api::init($app_id, $app_secret, $access_token);

       $user_id = "2964155643655014";

       $api = Api::instance();
       $api->setDefaultGraphVersion("3.3");


       $fields = [
           'cpm',
           'ctr',
           'account_currency',
           'account_id',
           'account_name',
           'clicks',
           'cpc',
       ];
       $params = [
           'level' => 'account',
           'filtering' => [],
           'breakdowns' => [],
           'time_range' => ['since' => '2019-09-13','until' => '2019-09-18'],
       ];
       dd((new AdAccount($ad_account_id))->getInsights(
           $fields,
           $params
       ));

       dd();

       $account = (new Application('act_160762233994383'))->getSelf();
       dd($account);
       dd($api ,$ywa );
       return 'token';
   }
}
