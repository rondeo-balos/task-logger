<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;
use Spatie\Permission\Models\Permission;

class WorkplaceController extends Controller {
    public function index( Request $request ) {
        return response()->json(self::list());
    }
    
    public static function list() {
        $data = \Auth::user()->workplaces;

        return $data;
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

    /**
     * Advance routes
     */
    public function edit( Request $request, $id ) {
        return Inertia::render('Workplace/Edit', [
            'workplace_id' => $id
        ]);
    }

    public function set( Request $request, $id ) {
        session()->put( 'workplace', $id );
        return Redirect::route( 'home' );
    }

    public function giveWorkplacePermissionTo( Request $request, $id ) {
        $permission = $request->get( 'permission' );
        $email = $request->get( 'email' );

        $workplace = Workplace::find( $id );
        try {
            $user = User::where( 'email', $email )->firstOrFail();
            if( $workplace && $user && $workplace->user_id === \Auth::user()->id ) {

                foreach( $permission as $value ) {
                    // Check if the permission exists, if not, create it
                    if( !Permission::where('name', "$value $id")->exists() ) {
                        Permission::create(['name' => "$value $id", 'guard_name' => 'web']);
                    }

                    $user->givePermissionTo( "$value $id" );
                }
            }
        } catch ( \Exception $e ) {
            \Log::error( $e->getMessage() );
        }

        return Redirect::back();
    }

    public function revokeWorkplacePermissionTo( Request $request, $id ) {
        $email = $request->get( 'email' );
        $workplace = Workplace::find( $id );
        
        $user = User::where( 'email', $email )->firstOrFail();
        if( $workplace && $user && $workplace->user_id === \Auth::user()->id ) {
            $user->revokePermissionTo( "read $id" );
            $user->revokePermissionTo( "write $id" );
        }

        return Redirect::back();
    }
}
