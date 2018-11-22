<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Loan::with('account','repayments','account.user')->orderBy('id','desc')->paginate(15);
        return response()->json([
            'status' => (bool) $loans,
            'data'   => $loans,
            'message' => $loans ? 'Successfully fetch Data' : 'Data fetch fail'
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
        $validator = Validator::make($request->all(), [
            'account_id' => 'required',
            'type' => 'required',
            'repayments_frequency' => 'required',
            'duration' => 'required',
            'interest_rate' => 'required',
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => (bool) false,
                'data'   => null,
                'message' => $validator->messages()
            ],200);
        }else{
            $received_data = $request->all();
            /* repayment frequency monthly so number of instalment = duration ;
              because duration take input as month eg. 2 years = 24 month
            * */
            if($received_data['repayments_frequency']=='monthly'){
                $received_data['number_of_instalment'] = $received_data['duration'];
                $received_data['instalment_amount'] = round(($received_data['amount'] / $received_data['number_of_instalment']),2);
            }
            /* repayment frequency quarterly so number of instalment = duration / 3 ;
              because duration take input as month eg. 2 years = 24 month;
            * */
            elseif ($received_data['repayments_frequency']=='quarterly'){
                $received_data['number_of_instalment'] = ($received_data['duration'] / 3);
                $received_data['instalment_amount'] = round(($received_data['amount'] / $received_data['number_of_instalment']),2);
            }
            /* repayment frequency half yearly so number of instalment = duration / 6 ;
             because duration take input as month eg. 2 years = 24 month;
           * */
            elseif ($received_data['repayments_frequency']=='half_yearly') {
                $received_data['number_of_instalment'] = ($received_data['duration'] / 6);
                $received_data['instalment_amount'] = round(($received_data['amount'] / $received_data['number_of_instalment']),2);
            }
            /* repayment frequency yearly so number of instalment = duration / 12 ;
             because duration take input as month eg. 2 years = 24 month;
           * */
            else {
                $received_data['number_of_instalment'] = ($received_data['duration'] / 12);
                $received_data['instalment_amount'] = round(($received_data['amount'] / $received_data['number_of_instalment']),2);
            }
            $loan = Loan::create($received_data);
            /* if paid amount greater or equal to amount then status wiil be paid */
            if($loan->amount <= $loan->paid_amount){
                $loan->status = 'paid';
                $loan->save();
            }
            return response()->json([
                'status' => (bool) $loan,
                'data'   => $loan,
                'message' => $loan ? 'Successfully Created' : 'Create failed'
            ],$loan ? 201:200);
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
        $loan = Loan::find($id);
        if($loan){
            $response->status = true;
            $response->data = $loan;
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
            'account_id' => 'required',
            'type' => 'required',
            'repayments_frequency' => 'required',
            'duration' => 'required',
            'interest_rate' => 'required',
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            $response->status = false;
            $response->data = null;
            $response->message = $validator->messages();
        }else{
            $received_data = $request->all();
            /* repayment frequency monthly so number of instalment = duration ;
              because duration take input as month eg. 2 years = 24 month
            * */
            if($received_data['repayments_frequency']=='monthly'){
                $received_data['number_of_instalment'] = $received_data['duration'];
                $received_data['instalment_amount'] = round(($received_data['amount'] / $received_data['number_of_instalment']),2);
            }
            /* repayment frequency quarterly so number of instalment = duration / 3 ;
              because duration take input as month eg. 2 years = 24 month;
            * */
            elseif ($received_data['repayments_frequency']=='quarterly'){
                $received_data['number_of_instalment'] = ($received_data['duration'] / 3);
                $received_data['instalment_amount'] = round(($received_data['amount'] / $received_data['number_of_instalment']),2);
            }
            /* repayment frequency half yearly so number of instalment = duration / 6 ;
             because duration take input as month eg. 2 years = 24 month;
           * */
            elseif ($received_data['repayments_frequency']=='half_yearly') {
                $received_data['number_of_instalment'] = ($received_data['duration'] / 6);
                $received_data['instalment_amount'] = round(($received_data['amount'] / $received_data['number_of_instalment']),2);
            }
            /* repayment frequency yearly so number of instalment = duration / 12 ;
             because duration take input as month eg. 2 years = 24 month;
           * */
            else {
                $received_data['number_of_instalment'] = ($received_data['duration'] / 12);
                $received_data['instalment_amount'] = round(($received_data['amount'] / $received_data['number_of_instalment']),2);
            }
            $loan = Loan::find($id);
            if($loan) {
                $loan->fill($received_data)->update();
                /* if paid amount greater or equal to amount then status wiil be paid */
                if ($loan->amount <= $loan->paid_amount) {
                    $loan->status = 'paid';
                    $loan->save();
                }
                $response->status = true;
                $response->data = $loan;
                $response->message = 'Successfully Updated';
            }else{
                    $response->status = false;
                    $response->data = null;
                    $response->message = 'Did not Found id, Data fetch fail';
            }
        }
        return response()->json([
            $response
        ],$response->status?201:200);
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
        $loan = Loan::find($id);
        if($loan){
            /* if its required that payment will be deleted related with loan then comment out.
             * */
//            $loan->repayments()->delete();
            $loan->delete();
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
