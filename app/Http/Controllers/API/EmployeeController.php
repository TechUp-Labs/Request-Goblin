<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\GoblinController;
use Illuminate\Http\Request;
use App\Employee;
use Illuminate\Support\Facades\Auth;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $goblin = (new GoblinController)->show($request);
        if($goblin){ return $goblin; }
        $employee = Employee::select("*")->paginate(10);
        if(!isset($employee[0]->id)){
            $status = "error";
            $message = "Please Add Some Data";
            return (new GoblinController)->store($request, $message, $status);
        }
        $status = "success";
        $message = $employee;
        return (new GoblinController)->store($request, $message, $status);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Employee $employee)
    {
        $goblin = (new GoblinController)->show($request);
        if($goblin){ return $goblin; }

         $data = $request->validate([
                'name'=> 'string|required|max:100',
                'age'=> 'numeric|required|between:0,99',
                'gender'=> 'string|required|max:100', //in:male,female // Not Working
                'willing_to_work'=> 'string|required|max:100',
                'languages'=> 'string|required|max:100',
        ]);         
         $employee->name = $request->input("name");
         $employee->age = $request->input("age");

         $gender = array("Male", "Female");
         if(in_array($request->input("gender"), $gender)){
            $employee->gender = $request->input("gender");
         }else{
            $status = "error";
            $message = "Only Male And Females Are Allowed";
            return (new GoblinController)->store($request, $message, $status);
         }

         $willing_to_work = array("Yes", "No");
         if(in_array($request->input("willing_to_work"), $willing_to_work)){
            $employee->willing_to_work = $request->input("willing_to_work");
         }else{
            $status = "error";
            $message = "willing_to_work can be only Yes Or No";
            return (new GoblinController)->store($request, $message, $status);
         }

         $get_lang = explode(",",$request->input("languages"));
         $languages = array("SP", "FR", "EN", "HI");

         // Check Combinations
         $result = array_diff($get_lang, $languages);
         if(empty($result)){
            $employee->languages = json_encode($get_lang);
         }else{
            $status = "error";
            $message = "Only SP,FR,EN,HI Combinations Are Allowed";
            return (new GoblinController)->store($request, $message, $status);
         }

         $employee->save();
         $status = "success";
         $message = $employee;
         return (new GoblinController)->store($request, $message, $status);

         
     }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $goblin = (new GoblinController)->show($request);
        if($goblin){ return $goblin; }

        $employee = Employee::find($id);
        if(!isset($employee->id)){
            $status = "error";
            $message = "Employee Doesn't Exist";
            return (new GoblinController)->store($request, $message, $status);
        }
        //return $employee;
        $status = "success";
        $message = $employee;
        return (new GoblinController)->store($request, $message, $status);
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
        $goblin = (new GoblinController)->show($request);
        if($goblin){ return $goblin; }

        $employee = Employee::find($id);
        if(!isset($employee->id)){
            return["message" => "Employee Doesn't Exist"];
        }
        $data = $request->validate([
                'name'=> 'string|required|max:100',
                'age'=> 'numeric|required|between:0,99',
                'gender'=> 'string|required|max:100', //in:male,female // Not Working
                'willing_to_work'=> 'string|required|max:100',
                'languages'=> 'string|required|max:100',
        ]);

         
         $employee->name = $request->input("name");
         $employee->age = $request->input("age");

         $gender = array("Male", "Female");
         if(in_array($request->input("gender"), $gender)){
            $employee->gender = $request->input("gender");
         }else{
            $status = "error";
            $message = "Only Male And Females Are Allowed";
            return (new GoblinController)->store($request, $message, $status);
         }

         $willing_to_work = array("Yes", "No");
         if(in_array($request->input("willing_to_work"), $willing_to_work)){
            $employee->willing_to_work = $request->input("willing_to_work");
         }else{
            $status = "error";
            $message = "willing_to_work can be only Yes Or No";
            return (new GoblinController)->store($request, $message, $status);
         }

         $get_lang = explode(",",$request->input("languages"));
         $languages = array("SP", "FR", "EN", "HI");

         // Check Combinations
         $result = array_diff($get_lang, $languages);
         if(empty($result)){
            $employee->languages = json_encode($get_lang);
         }else{
            $status = "error";
            $message = "Only SP,FR,EN,HI Combinations Are Allowed";
            return (new GoblinController)->store($request, $message, $status);
         }
         $employee->save();

         //return $employee;
        $status = "success";
        $message = $employee;
        return (new GoblinController)->store($request, $message, $status);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $goblin = (new GoblinController)->show($request);
        if($goblin){ return $goblin; }
        
        $employee = Employee::find($id);
        if(!isset($employee->id)){
            $status = "error";
            $message = "Employee Already Deleted Or Not Exist";
            return (new GoblinController)->store($request, $message, $status);
        }
        $employee->delete();
        $status = "success";
        $message = "Employee Id :- ".$id." Deleted Sucessfully";
        return (new GoblinController)->store($request, $message, $status);

    }
}
