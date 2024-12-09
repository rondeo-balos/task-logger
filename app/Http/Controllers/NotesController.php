<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\Request;

class NotesController extends Controller {
    public function list( Request $request ) {
        $data = Notes::orderBy( 'ID' )->get();

        return response()->json( $data );
    }

    public function create( Request $request ) {
        $data = $request->all();

        $id = Notes::create( $data );

        return response()->json([$id]);
    }

    public function delete( Request $request, $id ) {
        Notes::find( $id )->delete();

        return response()->json([$id]);
    }

    public function update( Request $request, $id ) {
        $data = $request->all();

        Notes::find($id)->update( $data );

        return response()->json($id);
    }
}
