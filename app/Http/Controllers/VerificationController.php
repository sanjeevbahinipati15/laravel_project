<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class VerificationController extends Controller
{
    public function submit(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'aadhar_no' => 'required|numeric|digits:12|unique:users,aadhar_no',
            'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pan_no' => 'required|string|size:10|unique:users,pan_no',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

         User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'aadhar_no' => $request->input('aadhar_no'),
            'aadhar_image' => $request->file('aadhar_image') ? $request->file('aadhar_image')->store('aadhar_images', 'public') : null,
            'pan_no' => $request->input('pan_no'),
            'pan_image' => $request->file('pan_image') ? $request->file('pan_image')->store('pan_images', 'public') : null,
        ]);





        // Process the data (e.g., save to database, send email, etc.)
        // For demonstration, we'll just return a success response
        //return response()->json(['message' => 'Form submitted successfully!'], 201);

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }
}
