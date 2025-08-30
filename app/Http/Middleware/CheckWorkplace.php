<?php

namespace App\Http\Middleware;

use App\Models\Workplace;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckWorkplace {

    public function handle(Request $request, Closure $next, string $permission ): Response {
        if( !Auth::user()->workplaces ) {
            return Redirect::route( 'workplace' );
        }

        if( !session()->has('workplace') ) {
            return redirect()->route('workplace');
        }
        
        $workplace_id = session( 'workplace', 1 );
        $workplace = Workplace::find( $workplace_id );

        if( $workplace ) {
            $is_permitted = $workplace->user_id == Auth::id() || Auth::user()->hasPermissionTo( "$permission $workplace_id" );
            if( $workplace && $is_permitted ) {
                return $next($request);
            }
        }

        // Redirect ang show forbidden message
        return Redirect::route( 'workplace' );
    }
}
