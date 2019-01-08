<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Ftp;
class ApiFtpController extends Controller
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
        return Ftp::orderByDesc('name')->paginate(15);
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
            'url' => 'required|unique:ftps|max:191'
        ]);

        //validation fails
        if ( $validator->fails() )
            return response()->json(['success'=> false, 'msg' => $validator->errors()->first()], $this->success );

        //Saving data
        $ftp = new Ftp();
        $ftp->name = $request->name;
        $ftp->url = $request->url;

        $ftp->save();

        //return success of fail message
        if( $ftp->id ) :
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
        $items = Ftp::findOrFail( $id );

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
            'url' => 'required|max:191',
        ]);

        //validation fails
        if ( $validator->fails() )
            return response()->json(['success'=> false, 'msg' => $validator->errors()->first()], $this->success);

        //Saving data
        $ftp = Ftp::findOrFail( $id );
        $ftp->name = $request->name;
        $ftp->url = $request->url;

        $ftp->save();

        //return success of fail message
        if( $ftp->id ) :
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
        $item = Ftp::findOrFail( $id );

        if( $item ) :
            $ok = $item->delete();
            return response()->json(['success' => true], $this->success );
        else :
            return response()->json(['success' => false, 'msg' => 'Item not found.'], $this->success);
        endif;
    }
}