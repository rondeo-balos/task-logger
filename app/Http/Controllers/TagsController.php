<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use Illuminate\Http\Request;

class TagsController extends Controller {
    public function list( Request $request ) {
        $data = Tags::get( ['ID','tag','color']);

        return response()->json( $data );
    }

    public function create( Request $request ) {
        $data = $request->all();

        $id = Tags::create( $data );

        return response()->json([$id]);
    }

    public function delete( Request $request, $id ) {
        Tags::find( $id )->delete();

        return response()->json([$id]);
    }

    public function update( Request $request, $id ) {
        $data = $request->all();

        Tags::find($id)->update( $data );

        return response()->json($id);
    }
}
