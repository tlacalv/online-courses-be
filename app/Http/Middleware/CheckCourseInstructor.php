<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\{Course, Instructor};


class CheckCourseInstructor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,  $action)
    {
      $course = $request->all();
      //get timestamp of start and end from the new course
      $start = date(strtotime($course['date']));
      $end = date($start+$course['duration']*60);

      //get instructor from the new course and get all the courses of said instructor
      $instructor = Instructor::find($course['instructor_id']);
      $courses = $instructor->courses;

      //loop trhough the courses of the instructor
      foreach ($courses as $instructorCourse) {
        // if the action is to edit don't validate the course to edit with itself
        if($action == 'edit') {
          $id = $request->route()->parameters()['course'];
          if($id == $instructorCourse['id']) {
            continue;
          }
        }

        //get timestamp of start and enf from the existing course
        $currentStart = date(strtotime($instructorCourse['date']));
        $currentEnd = date($currentStart+$instructorCourse['duration']*60);
  
        //checks for the start and end of both the new course and the existing course to not overlap
        if(
          ($start>=$currentStart && $start<=$currentEnd) ||
          ($end>=$currentStart && $end<=$currentEnd) ||
          ($currentStart > $start && $currentEnd < $end)
          ) {
          return response(['message' => "The instructor selected has another course at this time"], 403);
        }

      };

      return $next($request);
    }
}
