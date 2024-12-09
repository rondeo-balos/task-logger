<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TasksController extends Controller {
    
    public function list( Request $request ) {
        $data = Tasks::whereBetween('start', [strtotime(date('Y-m-01')), strtotime(date('Y-m-t'))])->orderByDesc( 'start' )->get();

        if($data) {
            $data = $data->groupBy( function($task) {
                $date = Carbon::parse($task->start);
                return $date->format('W');
            });
            $data = $data->map( function($group) {
                return $group->groupBy( function($task) {
                    $date = Carbon::parse($task->start);
                    return $date->format('d');
                });
            });
        }

        return response()->json( $data );
    }

    public function create( Request $request ) {
        $data = $request->all();

        $id = Tasks::create( $data );

        return response()->json([$id]);
    }

    public function delete( Request $request, $id ) {
        Tasks::find( $id )->delete();

        return response()->json([$id]);
    }

    public function update( Request $request, $id ) {
        $data = $request->all();
        unset($data['start_raw']);
        unset($data['end_raw']);

        Tasks::find($id)->update( $data );

        return response()->json($id);
    }

}
