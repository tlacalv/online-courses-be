<?php

namespace App\Http\Controllers;


use App\Models\{Student, CourseStudent, Course};
use Illuminate\Http\Request;


class StudentController extends Controller
{
    public function __construct() {
      $this->middleware('check-student-course:create')->only('enlist');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Student::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
          'name'=>'required',
          'age' => 'required'
        ]);
        return Student::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Student::with('courses')->get()->find($id)??['empty'=>'true'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if(!$student){
          return response(['message'=>'Resource not found'], 404);
        }
        $student->update($request->all());
        return $student;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Student::destroy($id);
    }

    /**
     * Enlist a student to a course
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enlist(Request $request, $id)
    {
        CourseStudent::create([
          'student_id'=> $id,
          'course_id' =>$request->input('course_id') 
        ]);
        
        return Course::find($request->input('course_id'));
    }
    /**
     * Removes a student from a course
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request, $id)
    {
        return CourseStudent::where('student_id',$id)
        ->where('course_id', $request->input('course_id'))
        ->delete();

        // print_r($toRemove);
        return response($toRemove);
    }
}
