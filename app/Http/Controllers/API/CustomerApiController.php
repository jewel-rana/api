<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;

class CustomerApiController extends Controller
{
    protected $success = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get customer type
        $type = ( isset( $_GET['type'] ) && $_GET['type'] != null ) ? $_GET['type'] : 'Active';
        switch ( $type ) {
            case 'Inactive':
                $status = 2;
                break;
            case 'Pending':
                $status = 0;
                break;
            default:
                $status = 1;
                break;
        }
        $query = Customer::with(['package', 'area']);
        if( $type != 'All')
            $query->where('status', $status);

        $query = $query->paginate(25);

        $customers = [];
        if( !empty( $query ) ) :
            foreach( $query as $customer ) :
                $row['id'] = $customer->id;
                $row['name'] = $customer->name;
                $row['type'] = ucfirst( $customer->type );
                $row['location'] = $customer->area['name'];
                $row['package'] = $customer->package['name'] . ' (' . $customer->package['price'] . ')';
                $row['due'] = 0;
                $row['status'] = $this->_customerStatus( $customer->status );
                array_push( $customers, $row );
            endforeach;
        endif;

        $packages = \App\Package::select('id', 'name', 'price')->get();

        return response()->json(['success' => true, 'customers' => $customers, 'packages' => $packages ], $this->success);
    }

    protected function _customerStatus( $status )
    {
        switch ( $status ) {
            case '2':
                return 'Inactive';
                break;
            case '1':
                return 'Active';
                break;
            default:
                return 'Pending';
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
