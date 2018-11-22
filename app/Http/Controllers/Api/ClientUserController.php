<?php

namespace App\Http\Controllers\Api;

use App\Models\ClientUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClientUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = ClientUser::with('accounts')->orderBy('id','desc')->paginate(15);
        return response()->json([
            'status' => (bool) $users,
            'data'   => $users,
            'message' => $users ? 'Successfully fetch Data' : 'Data fetch fail'
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
            'name' => 'required',
            'email' => 'required|unique:client_users,email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => (bool) false,
                'data'   => null,
                'message' => $validator->messages()
            ],200);
        }else{
            $user = ClientUser::create($request->all());
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
        $user = ClientUser::with('accounts')->find($id);
        if($user){
            $response->status = true;
            $response->data = $user;
            $response->message = 'Successfully fetch Data';
        }else{
            $response->status = false;
            $response->data = null;
            $response->message = 'Did not Found id, Data fetch fail';
        }
        return response()->json([
            $response
        ],200);
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
            'name' => 'required',
            'email' => [
                'required',
                Rule::unique('client_users')->ignore($id),
            ],
        ]);
        if ($validator->fails()) {
            $response->status = false;
            $response->data = null;
            $response->message = $validator->messages();
        }else{
            $user = ClientUser::find($id);
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
        ],200);
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
        $user = ClientUser::find($id);
        if($user){
            /* if its required that account will be deleted related with user then comment out.
             * */
//            $user->accounts()->delete();
            $user->delete();
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
        ],200);
    }
}
