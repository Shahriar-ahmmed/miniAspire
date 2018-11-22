<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::with('user','loan')->orderBy('id','desc')->paginate(15);
        return response()->json([
            'status' => (bool) $accounts,
            'data'   => $accounts,
            'message' => $accounts ? 'Successfully fetch Data' : 'Data fetch fail'
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data'   => null,
                'message' => $validator->messages()
            ],200);
        }else{
            $user = Account::create($request->all());
            return response()->json([
                'status' => (bool) $user,
                'data'   => $user,
                'message' => $user ? 'Successfully Created' : 'Create failed'
            ],$user ? 201:200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = (object)[];
        $account = Account::with('user','loan')->find($id);
        if($account){
            $response->status = true;
            $response->data = $account;
            $response->message = 'Successfully fetch Data';
        }else{
            $response->status = false;
            $response->data = null;
            $response->message = 'Did not Found id, Data fetch fail';
        }
        return response()->json([
            $response
        ], 200);
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
        $response = (object)[];
        $validator = Validator::make($request->all(), [
        'user_id' => 'required',
        'type' => 'required'
        ]);
        if ($validator->fails()) {
            $response->status = false;
            $response->data = null;
            $response->message = $validator->messages();
        }else{
            $user = Account::find($id);
            if($user){
                $user->fill($request->all())->update();
                $response->status = true;
                $response->data = $user;
                $response->message = 'Successfully Updated';
            }else{
                $response->status = false;
                $response->data = null;
                $response->message = 'Update Failed, Did not found id';
            }
        }
        return response()->json([
            $response
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = (object)[];
        $account = Account::find($id);
        if($account){
            /* if its required that loan will be deleted related with account then comment out.
             * */
//            $account->loan->delete();
            $account->delete();
            $response->status = true;
            $response->data = null;
            $response->message = 'Successfully Deleted';
        }else{
            $response->status = false;
            $response->data = null;
            $response->message = 'Did not Found id, Delete Fail';
        }
        return response()->json([
            $response
        ], 200);
    }
}
