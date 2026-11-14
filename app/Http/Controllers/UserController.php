<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{



    public function login(Request $request)
    {
        // 1. Validate input (checking if it's either an email or a string)
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $loginInput = $request->input('login');

        // 2. Determine whether the input is an email or mobile number
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobileno';

        // 3. Find user by email or mobile number
        $user = User::where($field, $loginInput)->first();

        // 4. Check if user exists and password is correct
        if ($user && \Illuminate\Support\Facades\Hash::check($request->input('password'), $user->password)) {

            // Log the user in manually
            Auth::login($user, $request->boolean('remember'));

            $request->session()->regenerate();

            return response()->json([
                'success' => 'Login successful!',
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], 200);
        }

        // Authentication failed
        return response()->json([
            'error' => 'Invalid credentials',
        ], 401);
    }

    public function register(Request $request)
    {

        $eighteenYearsAgo = now()->subYears(18)->format('Y-m-d');

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'mobileno' => 'required|string|digits:10|unique:users,mobileno',
            'dob' => "required|date|before_or_equal:{$eighteenYearsAgo}",            // 'aadhar_no' => 'required|numeric|digits:12|unique:users,aadhar_no',
            // 'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,avif|max:2048',
            // 'pan_no' => 'required|string|size:10|unique:users,pan_no',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,jfif|max:2048',
        ], [
            'dob.before_or_equal' => 'You must be at least 18 years old to register.',
        ]);

         $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'mobileno' => $request->input('mobileno'),
            'dob' => $request->input('dob'),
            // 'aadhar_no' => $request->input('aadhar_no'),
            // 'aadhar_image' => $request->file('aadhar_image') ? $request->file('aadhar_image')->store('aadhar_images', 'public') : null,
            // 'pan_no' => strtoupper($request->input('pan_no')),
            'pan_image' => $request->file('pan_image') ? $request->file('pan_image')->store('pan_images', 'public') : null,
        ]);



        // Process the data (e.g., save to database, send email, etc.)
        // For demonstration, we'll just return a success response
        // return response()->json([
        //     'success' => 'User registered successfully!',
        //     'user' => $user,
        //     'token' => $user->createToken('auth_token')->plainTextToken,
        // ], 201);

        return redirect()->route('/')->with('success', 'Form submitted successfully!');
    }

    public function dashboard(Request $request)
    {
        $users = User::query();

        if(request()->filled('search')) {

            $users->when($request->input('search'), function ($query) use ($request) {
                $sea = trim($request->input('search')); // Trim the search string

                $query->where('name', 'like', '%' . $sea . '%')
                    ->orWhere('email', 'like', '%' . $sea . '%')
                    ->orWhere('aadhar_no', 'like', '%' . $sea . '%')
                    ->orWhere('pan_no', 'like', '%' . $sea . '%');
            });

        }

        $users->when($request->input('filter_document'), function ($query) use ($request) {

            if(request()->input('filter_document') === 'with_document') {
                $query->where('aadhar_image', '!=', null);
                $query->orWhere('pan_image', '!=', null);
            }
            else if(request()->input('filter_document') === 'without_document') {
                $query->where('aadhar_image', '=', null);
                $query->orWhere('pan_image', '=', null);
            }
        });


        $users->when($request->input('sort'), function ($query) use ($request) {

            if(request()->input('sort') === 'name') {
                $query->orderBy('name');
            }
            else if(request()->input('sort') === 'latest') {
                $query->orderBy('created_at', 'desc');
            }
            else if(request()->input('sort') === 'oldest') {
                $query->orderBy('created_at', 'asc');
            }
        });


        // if(request()->filled('sort')) {
        //     $users = $users->orderBy(request()->input('sort'));
        // }

        $users = $users->paginate(15)->withQueryString();

        return view('dashboard', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        //var_dump($user); // Debugging line to check the user data
        return view('edit_user', compact('user'));
    }

    public function update(Request $request, $id)
    {

        $eighteenYearsAgo = now()->subYears(18)->format('Y-m-d');
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            // 'aadhar_no' => 'required|numeric|digits:12|unique:users,aadhar_no,' . $id,
            // 'aadhar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,avif|max:2048',
            // 'pan_no' => 'required|string|size:10|unique:users,pan_no,' . $id,
            'mobileno' => 'required|string|digits:10|unique:users,mobileno,' . $id,
            'dob' => "required|date|before_or_equal:{$eighteenYearsAgo}",
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,jfif|max:2048',
        ]);

        $user = User::findOrFail($id);

        // Update user details
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobileno' => $request->input('mobileno'),
            'dob' => $request->input('dob'),
            // 'aadhar_no' => $request->input('aadhar_no'),
            // 'aadhar_image' => $request->file('aadhar_image') ? $request->file('aadhar_image')->store('aadhar_images', 'public') : $user->aadhar_image,
            // 'pan_no' => strtoupper($request->input('pan_no')),
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

