<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\User;
use App\Models\Workplace;
use App\Notifications\AccessShared;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
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
        try {
            Log::info( 'creating workplace' );
            $data = $request->all();
            $id = Workplace::create( $data )->id;
            session()->put( 'workplace', $id );
            // Creating initial tags
            Tags::create([
                'tag' => 'N/A', 
                'color' => 'secondary-emphasis',
                'workplace_id' => $id
            ]);
            return Redirect::route( 'home' )
                ->with( 'status', [
                    'code' => 201,
                    'status' => 'Workplace Successfully Created!'
                ]);
        } catch( \Exception $e ) {
            Log::error( $e->getMessage() );
        }
        return Redirect::back()
            ->with( 'status', [
                'code' => 500,
                'status' => 'Error, Please see logs!'
            ]);
    }

    public function delete( Request $request, $id ) {
        Workplace::find( $id )->delete();
        return Redirect::route( 'home' );
    }

    public function update( Request $request, $id ) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $workplace = Workplace::findOrFail($id);
        if ($workplace->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to update this workplace.');
        }

        $workplace->update($validated);

        return Redirect::back()->with('status', [
            'code' => 200,
            'status' => 'Workplace updated.',
        ]);
    }

    /**
     * Advance routes
     */
    public function edit( Request $request, $id ) {
        $workplace = Workplace::find($id);
        if (!$workplace || $workplace->user_id !== Auth::id()) {
            abort(403, 'Only the owner can edit settings.');
        }
        $users = User::with('permissions')->whereHas( 'permissions', function($query) use ($id) {
            $query->where( 'name', 'LIKE', '% ' . $id );
        })->get();
        return Inertia::render('Workplace/Edit', [
            'workplace_id' => $id,
            'workplace' => $workplace,
            'users' => $users
        ]);
    }

    public function set( Request $request, $id ) {
        session()->put( 'workplace', $id );
        return Redirect::route( 'home' );
    }

    public function giveWorkplacePermissionTo( Request $request, $id ) {
        $permission = $request->get( 'permission', [] );
        if (!is_array($permission)) {
            $permission = [$permission];
        }
        $email = $request->get( 'email' );

        $workplace = Workplace::find( $id );
        try {
            $user = User::where( 'email', $email )->firstOrFail();
            if( $workplace && $user && $workplace->user_id === \Auth::user()->id ) {

                foreach( $permission as $value ) {
                    if (!$value) {
                        continue;
                    }
                    // Check if the permission exists, if not, create it
                    if( !Permission::where('name', "$value $id")->exists() ) {
                        Permission::create(['name' => "$value $id", 'guard_name' => 'web']);
                    }

                    $user->givePermissionTo( "$value $id" );
                }

                $link = route( 'workplace.set', [$workplace->id]);
                $user->notify( new AccessShared( $workplace->name, implode( ', ', $permission ), $link ) );
            }
        } catch ( \Exception $e ) {
            Log::error( $e->getMessage() );
        }

        return Redirect::back();
    }

    public function revokeWorkplacePermissionTo( Request $request, $id ) {
        $email = $request->get( 'email' );
        $permissions = $request->get('permission', ['read', 'write', 'view-other']);
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }
        $workplace = Workplace::find( $id );
        
        $user = User::where( 'email', $email )->firstOrFail();
        if( $workplace && $user && $workplace->user_id === \Auth::user()->id ) {
            foreach ($permissions as $value) {
                if (!$value) {
                    continue;
                }
                $user->revokePermissionTo( "$value $id" );
            }
        }

        return Redirect::back();
    }
}
