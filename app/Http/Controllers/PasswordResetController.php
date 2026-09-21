<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email',
        ]);

        // Send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return redirect('/')->with('success', 'If your email is registered, a password reset link has been sent to your email address.');

        // Check the status and return appropriate response
        // if ($status === Password::RESET_LINK_SENT) {
        //     return back()->with('status', __($status));
        // } else {
        //     return back()->withErrors(['email' => __($status)]);
        // }
    }
}
