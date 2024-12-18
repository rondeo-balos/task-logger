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
        $data = \Auth::user()->workplace->tasks;
        
        $dailyTotals = [];
        $weeklyTotals = [];
        $monthlyTotal = 0;

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

            // Calculate daily, weekly, and monthly totals
            foreach ($data as $week => $days) {
                $weeklyTotal = 0;

                foreach ($days as $day => $tasks) {
                    // Calculate the total duration for this day
                    $dailyTotal = $tasks->sum(function ($task) {
                        return $task->end - $task->start;
                    });

                    // Store daily total
                    $dailyTotals[$day] = $dailyTotal;

                    // Add to weekly total
                    $weeklyTotal += $dailyTotal;
                }

                // Store weekly total
                $weeklyTotals[$week] = $weeklyTotal;

                // Add to monthly total
                $monthlyTotal += $weeklyTotal;
            }
        }
        
        return [
            'data' => $data,
            'total' => [
                'daily' => $dailyTotals,
                'weekly' => $weeklyTotals,
                'monthly' => $monthlyTotal
            ]
        ];
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
