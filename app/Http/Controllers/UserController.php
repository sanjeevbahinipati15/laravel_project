<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {

            $request->session()->regenerate();
            // Authentication successful
            return redirect()->intended('/dashboard')->with('success', 'Login successful!');
        }

        // Authentication failed
        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function register(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'aadhar_no' => 'required|numeric|digits:12|unique:users,aadhar_no',
            'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,avif|max:2048',
            'pan_no' => 'required|string|size:10|unique:users,pan_no',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,jfif|max:2048',
        ]);

         User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'aadhar_no' => $request->input('aadhar_no'),
            'aadhar_image' => $request->file('aadhar_image') ? $request->file('aadhar_image')->store('aadhar_images', 'public') : null,
            'pan_no' => strtoupper($request->input('pan_no')),
            'pan_image' => $request->file('pan_image') ? $request->file('pan_image')->store('pan_images', 'public') : null,
        ]);



        // Process the data (e.g., save to database, send email, etc.)
        // For demonstration, we'll just return a success response
        //return response()->json(['message' => 'Form submitted successfully!'], 201);

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    public function dashboard()
    {
        $users = User::all();
        return view('dashboard', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('edit_user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'aadhar_no' => 'required|numeric|digits:12|unique:users,aadhar_no,' . $id,
            'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,avif|max:2048',
            'pan_no' => 'required|string|size:10|unique:users,pan_no,' . $id,
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,jfif|max:2048',
        ]);

        $user = User::findOrFail($id);

        // Update user details
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'aadhar_no' => $request->input('aadhar_no'),
            'aadhar_image' => $request->file('aadhar_image') ? $request->file('aadhar_image')->store('aadhar_images', 'public') : $user->aadhar_image,
            'pan_no' => strtoupper($request->input('pan_no')),
            'pan_image' => $request->file('pan_image') ? $request->file('pan_image')->store('pan_images', 'public') : $user->pan_image,
        ]);

        return redirect()->route('dashboard')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if(strtolower($user->name) === 'admin') {
            return redirect('/dashboard')->with('error', 'Admin user cannot be deleted!');
        }

        $user->delete();

        return redirect('/dashboard')->with('success', 'User deleted successfully!');
    }

}

