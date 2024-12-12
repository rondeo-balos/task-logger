<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Redirect;

class TasksController extends Controller {

    public function index( Request $request ) {
        return response()->json(self::list());
    }
    
    public static function list() {
        $data = Tasks::whereBetween('start', [strtotime(date('Y-m-01')), strtotime(date('Y-m-t'))])->orderByDesc( 'start' )->get();

        if($data) {
            // Group by week
            $data = $data->groupBy( function($task) {
                $date = Carbon::parse($task->start);
                return (int) $date->format('W');
            });

            // Then group by day and by task id
            $data = $data->map( function($group) {
                return $group->groupBy( function($task) {
                    $date = Carbon::parse($task->start);
                    return (int) $date->format('d');
                })->map(function($dayTasks) {
                    // Convert the day tasks into an object with 'id' as the key
                    return $dayTasks->keyBy('id');
                });;
            });
        }
        
        return $data;
    }

    public function create( Request $request ) {
        $data = $request->all();

        $id = Tasks::create( $data );

        return Redirect::route( 'home' );
    }

    public function delete( Request $request, $id ) {
        Tasks::find( $id )->delete();

        return Redirect::route( 'home' );
    }

    public function update( Request $request, $id ) {
        $data = $request->all();
        unset($data['start_raw']);
        unset($data['end_raw']);

        Tasks::find($id)->update( $data );

        return Redirect::route( 'home' );
    }

}
