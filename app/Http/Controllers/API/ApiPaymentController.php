<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Payment;
class ApiPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $error;
    protected $success;

    public function __construct()
    {
        $this->success = 200;
        $this->error = 403;
    }
    public function index()
    {
        return Payment::orderByDesc('name')->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //form validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'account_number' => 'required|max:191',
            'type' => 'required|max:191'
        ]);

        //validation fails
        if ( $validator->fails() )
            return response()->json(['success'=> false, 'msg' => $validator->errors()->first()], $this->success );

        //Saving data
        $payment = new Payment();
        $payment->name = $request->name;
        $payment->account_number = $request->account_number;
        $payment->type = $request->type;

        $payment->save();

        //return success of fail message
        if( $payment->id ) :
            return response()->json(['success' => true], $this->success );
        else :
            return response()->json(['success' => false, 'msg' => 'Sorry! cannot create.'], $this->success);
        endif;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $items = Payment::findOrFail( $id );

        //return json response
        return response()->json(['success' => true, 'data' => $items], $this->success );
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
        //form validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'account_number' => 'required|max:191',
            'type' => 'required'
        ]);

        //validation fails
        if ( $validator->fails() )
            return response()->json(['success'=> false, 'msg' => $validator->errors()->first()], $this->success);

        //Saving data
        $area = Payment::findOrFail( $id );
        $area->name = $request->name;
        $area->type = $request->type;
        $area->account_number = $request->account_number;

        $area->save();

        //return success of fail message
        if( $area->id ) :
            return response()->json(['success' => true], $this->success );
        else :
            return response()->json(['success' => false, 'msg' => 'Sorry! cannot update.'], $this->success);
        endif;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Payment::findOrFail( $id );

        if( $item ) :
            $ok = $item->delete();
            return response()->json(['success' => true], $this->success );
        else :
            return response()->json(['success' => false, 'msg' => 'Item not found.'], $this->success);
        endif;
    }
}