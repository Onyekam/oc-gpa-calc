<?php namespace Expressuals\GpaCalc\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Input;
use Expressuals\GpaCalc\Models\Course;
use Expressuals\GpaCalc\Models\Grade;
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
        $this->grades = $this->getGrades();
    }

    public function getCourses(){
        $courses = Course::all();
        return $courses;
    }

    public function getGrades(){
        $grades = Grade::all();
        return $grades;
    }

    public function onRemoveCourse(){
        $user = Auth::getUser();
        DB::delete(' delete from expressuals_gpacalc_grades_users where crs_id = '.post('course'). ' and user_id = '. $user->id . ' and grade_id = '.post('grade'));
        Flash::success('Course successfully deleted');
        return Redirect::refresh();
        // $removeCourse = StudentGrade::where('crs_id', post('course'))->where('user_id',$user->id)->first();
    }

    public function onRegisterCourses(){
        if (post('courses')) {
            $selectedCourses = post('courses');
            $user = Auth::getUser();
            foreach ($selectedCourses as $selectedCourse) {
                $studentCourse = new StudentGrade;
                $studentCourse->grade_id = 1;
                $studentCourse->user_id = $user['id'];
                $studentCourse->crs_id = $selectedCourse;
                $studentCourse->save();
            }
            Flash::success('Course(s) successfully registered');
            return Redirect::to('/my-account');
        } else {
            Flash::error('There are no courses to add');
            return Redirect::to('/my-account');
        }
        
    }

    public function onGradeUpdate(){
        $user = Auth::getUser();
        DB::statement('update expressuals_gpacalc_grades_users SET expressuals_gpacalc_grades_users.grade_id = ' .post('new_grade'). ' where expressuals_gpacalc_grades_users.crs_id = ' .post('course'). ' and expressuals_gpacalc_grades_users.user_id = ' .$user->id. ' and expressuals_gpacalc_grades_users.grade_id = ' .post('grade'));
        Flash::success('Course grade successfully updated');
        return Redirect::refresh();
    }

    public $courses;
    public $user;
    public $userCourses = [];
    public $grades;
}


