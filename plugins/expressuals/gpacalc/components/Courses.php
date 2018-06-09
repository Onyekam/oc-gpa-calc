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
        $this->onEstimateMarksForNextGrade();
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

    public function onGradeUpdate2(){
        $user = Auth::getUser();
        $courses = post('course');
        $newGrades = post('new_grade');
        $grades = post('grade');
        for ($i=0; $i < count($courses); $i++) { 
            DB::statement('update expressuals_gpacalc_grades_users SET expressuals_gpacalc_grades_users.grade_id = ' .$newGrades[$i]. ' where expressuals_gpacalc_grades_users.crs_id = ' .$courses[$i]. ' and expressuals_gpacalc_grades_users.user_id = ' .$user->id. ' and expressuals_gpacalc_grades_users.grade_id = ' .$grades[$i]);
        }
        $this->onCalculateGPA();
        Flash::success('Course grade successfully updated');
        return Redirect::refresh();
    }

    public function onCalculateGPAback(){
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

    public function onCalculateGPA(){
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
        $grades = [];
        foreach ($user->courses as $value) {
                //$gradePointSum += $value->grades[0]->grade_point * $value->course_hours;
                array_push($grades, $value->grades[0]->grade_point);
        }
        // if (!empty($lvl100grades) && count($lvl100grades) != 0) {
        //     $lvl100gpa = array_sum($lvl100grades) / count($lvl100grades);
        //     array_push($gpasArray, $lvl100gpa);
        // } elseif (!empty($lvl200grades) && count($lvl200grades) != 0 ) {
        //     $lvl200gpa = array_sum($lvl200grades) / count($lvl200grades);
        //     array_push($gpasArray, $lvl200gpa);
        // } elseif (!empty($lvl300grades) && count($lvl300grades) != 0 ) {
        //     $lvl300gpa = array_sum($lvl300grades) / count($lvl300grades);
        //     array_push($gpasArray, $lvl300gpa);
        // } elseif (!empty($lvl400grades) && count($lvl400grades) != 0 ) {
        //     $lvl400gpa = array_sum($lvl400grades) / count($lvl400grades);
        //     array_push($gpasArray, $lvl400gpa);
        // }
        $this->fcgpa = round(array_sum($grades) / count($grades),2);
        //$this->fcgpa = round($gradePointSum / $creditSum,2);
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
        if (!empty($lvl100grades) && count($lvl100grades) != 0) {
            $lvl100gpa = array_sum($lvl100grades) / count($lvl100grades);
            array_push($gpasArray, $lvl100gpa);
        } elseif (!empty($lvl200grades) && count($lvl200grades) != 0 ) {
            $lvl200gpa = array_sum($lvl200grades) / count($lvl200grades);
            array_push($gpasArray, $lvl200gpa);
        } elseif (!empty($lvl300grades) && count($lvl300grades) != 0 ) {
            $lvl300gpa = array_sum($lvl300grades) / count($lvl300grades);
            array_push($gpasArray, $lvl300gpa);
        } elseif (!empty($lvl400grades) && count($lvl400grades) != 0 ) {
            $lvl400gpa = array_sum($lvl400grades) / count($lvl400grades);
            array_push($gpasArray, $lvl400gpa);
        }
        $this->fcgpa = array_sum($gpasArray) / count($gpasArray);
        //$this->fcgpa = round($gradePointSum / $creditSum,2);
        $updateStudentGpa = User::find($user->id);
        $updateStudentGpa->gpa = $this->fcgpa;
        $updateStudentGpa->save();
        
        
        // return $this->fcgpa;
    }

    public function onEstimateMarksForNextGrade() {
        $firstClassMin = 80;
        $secondClassUpperMin = 75;
        $secondClassLowerMin = 60;
        $thirdClassMin = 55;
        $passMin = 50;
        $totalNumOfCourses = 44; 
        $user = Auth::getUser();
        $currentNumOfCourses = count($user->courses);
        $currentGPA = $user->gpa;
        $totalMarks = 0;
        $desiredMark = 80;


        foreach ($user->courses as $value) {
            if ($value->grades[0]->letter_grade == 'A') {
                $totalMarks += 80;
            } elseif ($value->grades[0]->letter_grade == 'B+') {
               $totalMarks += 75;
            } elseif ($value->grades[0]->letter_grade == 'B') {
                $totalMarks += 70;
            } elseif ($value->grades[0]->letter_grade == 'C+') {
                $totalMarks += 65;
            } elseif ($value->grades[0]->letter_grade == 'C') {
                $totalMarks += 60;
            } elseif ($value->grades[0]->letter_grade == 'D+') {
                $totalMarks += 55;
            } elseif ($value->grades[0]->letter_grade == 'D') {
                $totalMarks += 50;
            } elseif ($value->grades[0]->letter_grade == 'F') {
                $totalMarks += 0;
            } else {
                $totalMarks += 0;
            }
        
        }

        if ($user->gpa <= 4 && $user->gpa >= 3.6) {
            //$this->expectedClass = "You are in the range of a First Class";
        } elseif ($user->gpa < 3.6 && $user->gpa >= 3.0) {
            $this->expectedClass = "You are in the range of a Second Class Upper Division";
            $desiredMark = $firstClassMin;
        } elseif ($user->gpa < 3.0 && $user->gpa >= 2.0) {
            //$this->expectedClass = "You are in the range of a Second Class Lower Division";
            $desiredMark = $secondClassUpperMin;
        } elseif ($user->gpa < 2.0 && $user->gpa >= 1.5) {
            //$this->expectedClass = "You are in the range of a Third Class";
            $desiredMark = $secondClassLowerMin;
        } elseif ($user->gpa < 1.5 && $user->gpa >= 1.0) {
            //$this->expectedClass = "You are in the range of a Pass";
            $desiredMark = $thirdClassUpperMin;
        } else {
            //$this->expectedClass = "You are Failing";
            $desiredMark = $passMin;
        }

        if ($currentNumOfCourses < $totalNumOfCourses) {
            if ($user->gpa >= 3.6) {
                $this->estimatedMessage = "Maintain your current marks and grades for a First class";
            } else {
                $estimatedMark = ($desiredMark * ($currentNumOfCourses + 1)) - $totalMarks;
                $this->estimatedMessage = "To get to your next class you need to get at least ". $estimatedMark . " marks.";
            }
            
        } else {
           $this->estimatedMessage = "You've taken the required number of courses";
        }
        

        
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
        if ($user->gpa <= 4 && $user->gpa >= 3.6) {
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
    public $estimatedMessage;
}


