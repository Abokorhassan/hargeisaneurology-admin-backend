<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Doctor as DoctorResource;
use App\Doctor;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize =  $request->input('pageSize');
        // Get doctors
        $doctors = Doctor::paginate($pageSize);

        // Return collection of doctors as a resource
        return DoctorResource::collection($doctors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $doctor = new Doctor();

        $doctor->first_name = $request->input('first_name');
        $doctor->second_name = $request->input('second_name');
        $doctor->third_name = $request->input('third_name');
        $doctor->specialty = $request->input('specialty');
        $doctor->email = $request->input('email');
        $doctor->age = $request->input('age');
        $doctor->gender = $request->input('gender');
        $doctor->dob = $request->input('dob');
        $doctor->address = $request->input('address');
        $doctor->pic = $request->input('pic');
        $doctor->ph_number = $request->input('ph_number');

        if ($doctor->save()) {
            return new DoctorResource($doctor);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get doctor
        $doctor = Doctor::findOrFail($id);

        // Return single as a doctor
        return new DoctorResource($doctor);
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

        // return Doctor::find($id)->update($request->all());
        // // Validating the Input
        // $validator = Validator::make($request->all(), [
        //     'first_name' => 'required|string|max:50',
        //     'second_name' => 'required|string|max:50',
        //     'email' => 'required|string|email|max:50|unique:users',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json($validator->errors()->toJson(), 400);
        // }

        //
        $doctor =  Doctor::findOrFail($id);
        // return $request->all();

        $doctor->first_name = $request->input('first_name');
        $doctor->second_name = $request->input('second_name');
        $doctor->third_name = $request->input('third_name');
        $doctor->specialty = $request->input('specialty');
        $doctor->email = $request->input('email');
        $doctor->age = $request->input('age');
        $doctor->gender = $request->input('gender');
        $doctor->dob = $request->input('dob');
        $doctor->address = $request->input('address');
        $doctor->pic = $request->input('pic');
        $doctor->ph_number = $request->input('ph_number');

        if ($doctor->save()) {
            return new DoctorResource($doctor);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get doctor
        $doctor = Doctor::findOrFail($id);

        if ($doctor->delete()) {
            return new DoctorResource($doctor);
        }
    }
}
