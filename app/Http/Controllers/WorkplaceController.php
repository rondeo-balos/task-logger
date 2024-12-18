<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Http\Request;
use Redirect;

class WorkplaceController extends Controller {
    public function index( Request $request ) {
        return response()->json(self::list());
    }
    
    public static function list() {
        $data = \Auth::user()->workplaces;
        return $data;
    }

    public function set( Request $request, $id ) {
        session()->put( 'workplace', $id );
        return Redirect::route( 'home' );
    }

    public function create( Request $request ) {
        $data = $request->all();
        $id = Workplace::create( $data )->id;
        session()->put( 'workplace', $id );
        // Creating initial tags
        Tags::create([
            'tag' => 'N/A', 
            'color' => 'secondary-emphasis',
            'workplace_id' => $id
        ]);
        return Redirect::route( 'home' );
    }

    public function delete( Request $request, $id ) {
        Workplace::find( $id )->delete();
        return Redirect::route( 'home' );
    }

    public function update( Request $request, $id ) {
        $data = $request->all();
        Workplace::find($id)->update( $data );
        return Redirect::route( 'home' );
    }

    public function giveWorkplacePermissionTo( Request $request, $id ) {
        $permission = $request->get( 'permission' );
        $email = $request->get( 'email' );

        $workplace = Workplace::find( $id );
        $user = User::where( 'email', $email );
        if( $workplace && $user && $workplace->user_id === \Auth::user()->id ) {
            $user->givePermissionTo( "$permission $id" );
        }

        return Redirect::back();
    }
}
