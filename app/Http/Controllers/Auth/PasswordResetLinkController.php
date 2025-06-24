<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        // We have successfully sent a password reset link and will see the
        // message to the user. We do not want to tell the user if the
        // email address is in our database or not and we'll just send
        // the response back to the client and let them know a link was sent.
        $status = Password::broker()->sendResetLink(
            $request->only('username')
        );

        return back()->with('status', __($status));
    }
}