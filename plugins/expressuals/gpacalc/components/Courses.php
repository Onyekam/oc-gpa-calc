<?php namespace Expressuals\GpaCalc\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Input;
use Expressuals\GpaCalc\Models\Course;
use Expressuals\GpaCalc\Models\StudentGrade;
use Illuminate\Support\Facades\DB;

use Redirect;
use Session;
use Response;
use Auth;
use Flash;


class Courses extends ComponentBase {
     public function componentDetails(){
		return [
			'name' => 'Courses',
			'description' => 'Students Course Management'
        ];
    }

    public function onRun(){
        $user = Auth::getUser();
        foreach ($user->courses as $course) {
            array_push($this->userCourses, $course->course_name);
        }
        $this->courses = $this->getCourses();
    }

    public function getCourses(){
        $courses = Course::all();
        return $courses;
    }

    public function onRemoveCourse(){
        $user = Auth::getUser();
        DB::delete('delete from expressuals_gpacalc_grades_users where crs_id = '.post('course'). ' and user_id = '. $user->id . ' and grade_id = '.post('grade'));
        // $removeCourse = StudentGrade::where('crs_id', post('course'))->where('user_id',$user->id)->first();
    }

    public function onRegisterCourses(){
        $selectedCourses = post('courses');
        $user = Auth::getUser();

        dd($user->courses);
        foreach ($selectedCourses as $selectedCourse) {
            $studentCourse = new StudentGrade;
            $studentCourse->grade_id = 1;
            $studentCourse->user_id = $user['id'];
            $studentCourse->crs_id = $selectedCourse;
            $studentCourse->save();
        }
        Flash::success('Courses Successfully registered');
		$this->sendNewJobEmail(Input::post('title'));
		return Redirect::to('/my-account');
    }

    public $courses;
    public $user;
    public $userCourses = [];
}


