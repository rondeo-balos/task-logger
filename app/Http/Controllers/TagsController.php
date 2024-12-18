<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use Illuminate\Http\Request;
use Redirect;

class TagsController extends Controller {

    public function index( Request $request ) {
        return response()->json(self::list());
    }
    
    public static function list() {
        $data = \Auth::user()->workplace->tags;
        return $data;
    }

    public function create( Request $request ) {
        $data = $request->all();

        $id = Tags::create( $data );

        return Redirect::route( 'home' );
    }

    public function delete( Request $request, $id ) {
        Tags::find( $id )->delete();

        return Redirect::route( 'home' );
    }

    public function update( Request $request, $id ) {
        $data = $request->all();

        Tags::find($id)->update( $data );

        return Redirect::route( 'home' );
    }
}
