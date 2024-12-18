<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\Request;
use Redirect;

class NotesController extends Controller {

    public function index( Request $request ) {
        return response()->json(self::list());
    }
    
    public static function list() {
        $data = \Auth::user()->workplace->notes;
        return $data;
    }

    public function create( Request $request ) {
        $data = $request->all();

        $id = Notes::create( $data );

        return Redirect::route( 'home' );
    }

    public function delete( Request $request, $id ) {
        Notes::find( $id )->delete();

        return Redirect::route( 'home' );
    }

    public function update( Request $request, $id ) {
        $data = $request->all();

        Notes::find($id)->update( $data );

        return Redirect::route( 'home' );
    }
}
