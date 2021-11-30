<?php

/**
 * Reusable trait class to be used for Employee Class, Student Class, User Group Class
 */
trait Logger
{
    public function log($logString)
    {
        $className = __CLASS__;
        echo date("Y-m-d h:i:s", time()) . ": [{$className}] {$logString}" . "<br>";
    }

    public function findRole()
    {
        if ($this instanceof Student && $this instanceof Employee) {
            return 'Hybrid';
        } elseif ($this instanceof Employee) {
            return 'Employee';
        } elseif ($this instanceof Student) {
            return 'Student';
        }
    }
}

/**
 * Student Interface(Behaviour)
 */
interface Student{
    public function subjects();
    public function finalResults();
}

/**
 * Employee Interface(Behaviour)
 */
interface Employee{
    public function skills();
    public function skillEvalution();
}

/**
 * Main User class template (e.g: student, employee)
 */
abstract class User
{

    public $name;
    public $role;
    public $age;
    static $instances = 0;
    public $instance;
    public $designation;
    public $subjects;
    public $final_marks;
    public $skills;
    public $final_evaluation;
    public $final_output;

    abstract function initializeData($name, $age, $designation);
    abstract function CreateUser();

    public function __construct()
    {
        $this->instance = ++self::$instances;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function __clone()
    {
        $this->instance = ++self::$instances;
    }
}

/**
 * This class is for Employee users(object)
 */
class Final_Employee extends User implements Employee
{

    use Logger;

    public function initializeData($name, $age, $designation)
    {
        $this->name         = $name;
        $this->role         = $this->findRole();
        $this->age          = $age;
        $this->designation  = $designation;
        $this->final_output = $this->skillEvalution();
        $this->CreateUser();
    }

    public function CreateUser()
    {
        $this->log("Created User: '{$this->name}', Role:'{$this->role}'");
    }

  
    public function skills() {
        $this->skills = array(
            'Professionalism',
            'Etiquette',
            'Communication',
            'Problem Solving',
            'Reliability'
        );

        return $this->skills;
    }

    public function skillEvalution() 
    {
        $this->final_evaluation = array(
            '7 out of 10',
            '6 out of 10',
            '9 out of 10',
            '7 out of 10',
            '6 out of 10'
        );

        return array_combine($this->skills(), $this->final_evaluation);
    }
}

/**
 * This class is for Student users(object)
 */
class Final_Student extends User implements Student
{

    use Logger;

    public function initializeData($name, $age, $designation)
    {
        $this->name         = $name;
        $this->role         = $this->findRole();
        $this->age          = $age;
        $this->designation  = $designation;
        $this->final_output = $this->finalResults();
        $this->CreateUser();
    }

    public function CreateUser()
    {
        $this->log("Created User: '{$this->name}', Role:'{$this->role}'");
    }

    public function subjects() {

       $this->subjects = array(
           'Physics',
           'Chemistry',
           'Biology',
           'Mathematics'
       );

       return $this->subjects;
    }

    public function finalResults()
    {
        $this->final_marks = array(
            '80 out of 100',
            '60 out of 100',
            '50 out of 100',
            '77 out of 100'
        );

        return array_combine($this->subjects(), $this->final_marks);
    }
}


/**
 * This class accepts user object to be stored inside
 */
class UserGroup
{
    use Logger;

    public $className;
    public $usersObj = array();

    public function addUser($user, $key)
    {
        if ($user instanceof User && $user instanceof Student) {
            if ($this->includesUser($user)) {
                $this->usersObj[$key] = $user;
                $this->className      =  __CLASS__;
                $this->log("Added user '$user' to '$this->className'");
            }
        } elseif( $user instanceof User && $user instanceof Employee ) {
            if ($this->includesUser($user)) {
                $this->usersObj[$key] = $user;
                $this->className      =  __CLASS__;
                $this->log("Added user '$user' to '$this->className'");
            }
        } else {
            echo "Please make sure the object passed is an instance of User Class";
        }
    }

    public function includesUser($userObj)
    {
        return (!in_array($userObj, $this->usersObj));
    }

    public function ShowUser()
    {
        echo '<pre>';
        print_r($this->usersObj);
        echo '</pre>';
    }
}

$users = array(
    'user_1' => array(
        'name'        => 'Hillary Clinton',
        'age'         => 20,
        'designation' => 'Employee'
    ),
    'user_2' => array(
        'name'        => 'Donald Trump',
        'age'         => 10,
        'designation' => 'Student'
    ),
    'user_3' => array(
        'name'        => 'Bill Clinton',
        'age'         => 10,
        'designation' => 'Student'
    ),
    'user_4' => array(
        'name'        => 'Barrach Obama',
        'age'         => 40,
        'designation' => 'Employee'
    ),
);


$userGroup = new UserGroup();
$student   = new Final_Student();
$employee  = new Final_Employee();
foreach( $users as $key => $value ) {
    if( $value['designation'] === 'Employee' ) {
        $userObject1 = clone $employee;
        $userObject1->initializeData($value['name'], $value['age'], $value['designation']);
        $userGroup->addUser($userObject1, $key);
    } elseif( $value['designation'] === 'Student' ) {
        $userObject1 = clone $student;
        $userObject1->initializeData($value['name'], $value['age'], $value['designation']);
        $userGroup->addUser($userObject1, $key);
    }
  
}
echo 'User Group => ';
$userGroup->ShowUser();