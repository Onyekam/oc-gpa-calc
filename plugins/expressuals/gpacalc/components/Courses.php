<?php namespace Expressuals\GpaCalc\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Input;
use Expressuals\GpaCalc\Models\Course;
use Expressuals\GpaCalc\Models\Grade;
use Expressuals\GpaCalc\Models\StudentGrade;
use Rainlab\User\Models\User;
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
        $this->expectedClass();
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
        $this->onCalculateGPA();
        Flash::success('Course grade successfully updated');
        return Redirect::refresh();
    }

    public function onCalculateGPA(){
        $user = Auth::getUser();
        $gradePointSum = 0;
        $creditSum = 0;
        foreach ($user->courses as $value) {
            $gradePointSum += $value->grades[0]->grade_point * $value->course_hours;
            if ($value->semester->level == 300 || $value->semester->level == 400) {
                $creditSum += $value->course_hours * 2;
            } else {
                $creditSum += $value->course_hours;
            }
        }
        $this->fcgpa = round($gradePointSum / $creditSum,2);
        $updateStudentGpa = User::find($user->id);
        $updateStudentGpa->gpa = $this->fcgpa;
        $updateStudentGpa->save();
        
        
        // return $this->fcgpa;
    }

    public function onCalculateGPA2(){
        $user = Auth::getUser();
        $gradePointSum = 0;
        $creditSum = 0;
        $lvl100grades = [];
        $lvl200grades = [];
        $lvl300grades = [];
        $lvl400grades = [];
        $lvl100gpa;
        $lvl200gpa;
        $lvl300gpa;
        $lvl400gpa;
        $gpasArray = [];
        foreach ($user->courses as $value) {
            if ($value->semester->level == 100 ) {
                //$gradePointSum += $value->grades[0]->grade_point * $value->course_hours;
                array_push($lvl100grades, $value->grades[0]->grade_point);
               
            } elseif ($value->semester->level == 200) {
                array_push($lvl200grades, $value->grades[0]->grade_point);
                
            } elseif ($value->semester->level == 300) {
                array_push($lvl300grades, $value->grades[0]->grade_point);
                
            } elseif ($value->semester->level == 400) {
                array_push($lvl400grades, $value->grades[0]->grade_point);
                
            } 
        }
        if (!empty($lvl100grades)) {
            $lvl100gpa = array_sum($lvl100grades) / $this->handleDivideByZero(count($lvl100grades));
            array_push($gpasArray, $lvl100gpa);
        } elseif (!empty($lvl200grades)) {
            $lvl200gpa = array_sum($lvl200grades) / $this->handleDivideByZero(count($lvl200grades));
            array_push($gpasArray, $lvl200gpa);
        } elseif (!empty($lvl300grades)) {
            $lvl300gpa = array_sum($lvl300grades) / $this->handleDivideByZero(count($lvl300grades));
            array_push($gpasArray, $lvl300gpa);
        } elseif (!empty($lvl400grades)) {
            $lvl400gpa = array_sum($lvl400grades) / $this->handleDivideByZero(count($lvl400grades));
            array_push($gpasArray, $lvl400gpa);
        }
        $this->fcgpa = array_sum($gpasArray) / count($gpasArray);
        //$this->fcgpa = round($gradePointSum / $creditSum,2);
        $updateStudentGpa = User::find($user->id);
        $updateStudentGpa->gpa = $this->fcgpa;
        $updateStudentGpa->save();
        
        
        // return $this->fcgpa;
    }

    public function handleDivideByZero($number) {
        if ($number == 0) {
            return 1;
        } else {
            return $number;
        }
    }

    public function expectedClass() {
        $user = Auth::getUser();
        if ($user->gpa <= 4 && $user->gpa > 3.6) {
            $this->expectedClass = "You are in the range of a First Class";
        } elseif ($user->gpa < 3.6 && $user->gpa >= 3.0) {
            $this->expectedClass = "You are in the range of a Second Class Upper Division";
        } elseif ($user->gpa < 3.0 && $user->gpa >= 2.0) {
            $this->expectedClass = "You are in the range of a Second Class Lower Division";
        } elseif ($user->gpa < 2.0 && $user->gpa >= 1.5) {
            $this->expectedClass = "You are in the range of a Third Class";
        } elseif ($user->gpa < 1.5 && $user->gpa >= 1.0) {
            $this->expectedClass = "You are in the range of a Pass";
        } else {
            $this->expectedClass = "You are Failing";
        }
    }

    public $courses;
    public $user;
    public $userCourses = [];
    public $grades;
    public $fcgpa;
    public $expectedClass;
}


