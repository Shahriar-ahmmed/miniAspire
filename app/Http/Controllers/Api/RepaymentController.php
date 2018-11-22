<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repayment;
use App\Models\Loan;
use Illuminate\Support\Facades\Validator;

class RepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $repayments = Repayment::with('loan','loan.account','loan.account.user')->orderBy('id','desc')->paginate(15);
        return response()->json([
            'status' => (bool) $repayments,
            'data'   => $repayments,
            'message' => $repayments ? 'Successfully fetch Data' : 'Data fetch fail'
        ],200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = (object)[];
        $validator = Validator::make($request->all(), [
            'loan_id' => 'required',
            'status' => 'required',
            'amount' => 'required',
            'repayment_date' => 'required',
        ]);
        if ($validator->fails()) {
            $response->status = false;
            $response->data = null;
            $response->message = $validator->messages();
        }else{
            $received_data = $request->all();
            $loan = Loan::where('id',$received_data['loan_id'])->first();
            if($loan->status=='running') {
                $received_data['repayment_date'] = date('Y-m-d G:i:s', strtotime($received_data['repayment_date']));
                /* repayment status late then  penalty fee = 1% of payment amount ;
                 * */
                if ($received_data['status'] == "late") {
                    $received_data['penalty_fee'] = ($received_data['amount'] * 1) / 100;
                }
                $repayment = Repayment::create($received_data);
                $response->status = true;
                $response->data = $repayment;
                $response->message = 'Successfully Created';
            }else{
                $response->status = false;
                $response->data = null;
                $response->message = 'Loan already paid';
            }
        }
        return response()->json([
            $response
        ],200);
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
        $repayment = Repayment::find($id);
        if($repayment){
            $response->status = true;
            $response->data = $repayment;
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
            'loan_id' => 'required',
            'status' => 'required',
            'amount' => 'required',
            'repayment_date' => 'required'
        ]);
        if ($validator->fails()) {
            $response->status = false;
            $response->data = null;
            $response->message = $validator->messages();
        }else{
            $received_data = $request->all();
            if($received_data['status']=='late'){
                $received_data['penalty_fee'] = ($received_data['amount'] * 1)/100;
            }
            $repayment = Repayment::find($id);
            if($repayment){
                $repayment->fill($received_data)->update();
                $response->status = true;
                $response->data = $repayment;
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
        $repayment = Repayment::find($id);
        if($repayment){
            $repayment->delete();
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
