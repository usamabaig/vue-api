<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{public $successStatus = 200;/**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
//        return request('email');
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
//            return [$success];
            $data = ['data' => response()->json(['success' => $success , 'user' => $user],$this->successStatus)];
            return $data;
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')-> accessToken;
        $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this-> successStatus);
    }
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this-> successStatus);
    }

    public function updateDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $user = Auth::user()->update(['email' => $request->email , 'name' => $request->name]);
        return response()->json(['success' => $user], $this-> successStatus);
    }

    public function updatePassowrd(Request $request){
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $current_password = Auth::User()->password;
        if(Hash::check($request->password, $current_password))
        {
            $user_id = Auth::User()->id;
            $obj_user = User::find($user_id);
            $obj_user->password = Hash::make($request->password);;
            $obj_user->save();
            return response()->json(['success' => 'User Information updated'], $this-> successStatus);
        }
        else
        {
            $error = array('current-password' => 'Please enter correct current password');
            return response()->json(array('error' => $error), 400);
        }
    }


}