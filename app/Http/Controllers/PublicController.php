<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    // Show the landing page
    public function index()
    {
        return view('welcome');
    }

    // Process the 6-character code
    public function accessCode(Request $request)
    {
        // Force the input to be exactly 6 characters
        $request->validate([
            'access_code' => 'required|string|size:6',
        ]);

        $code = strtoupper($request->access_code);

        // TODO: In the future, we will query the database here to find the Test Session
        // $session = TestSession::where('code', $code)->first();

        // For testing right now, let's create a fake successful code: "DEMO12"
        if ($code === 'DEMO12') {
            // If correct, redirect them to a placeholder test page
            return redirect()->route('test.placeholder', ['code' => $code]);
        }

        // If the code doesn't exist, send them back with an error
        return back()->withErrors(['access_code' => 'Invalid or expired code. Please try again.']);
    }
}