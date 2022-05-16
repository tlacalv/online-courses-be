<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\{Course, Student};


class CheckStudentCourse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $student = Student::with('courses')
                    ->get()
                    ->find($request->route()->parameters()['id']);
        $course = Course::find($request->input('course_id'));

        if(!$student) {
          return response(['message'=>'No student with that ID'],404);
        }
        if(!$course) {
          return response(['message'=>'No course with that ID'],404);
        }


        // //get timestamp of start and end from the new course
        $start = date(strtotime($course['date']));
        $end = date($start+$course['duration']*60);
        $studentCourses = $student->courses;

        //loop trhough the courses of the student
        foreach($studentCourses as $currentCourse) {

          //get timestamp of start and enf from the existing course
          $currentStart = date(strtotime($currentCourse['date']));
          $currentEnd = date($currentStart+$currentCourse['duration']*60);
    
          //checks for the start and end of both the new course and the existing course to not overlap
          if(
            ($start>=$currentStart && $start<=$currentEnd) ||
            ($end>=$currentStart && $end<=$currentEnd) ||
            ($currentStart > $start && $currentEnd < $end)
            ) {
            return response(['message' => "The selected student has another course at this time"], 403);
          }
        }
        

        return $next($request);
    }
}
