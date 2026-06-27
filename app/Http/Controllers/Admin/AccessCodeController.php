<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccessCodeController extends Controller
{
    public function index()
    {
        $codes = AccessCode::with('test')->latest()->get();
        return view('admin.codes.index', compact('codes'));
    }

    public function create()
    {
        $tests = Test::latest()->get();
        return view('admin.codes.create', compact('tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'test_id' => 'required|exists:tests,id',
            'expires_at' => 'nullable|date|after:now',
        ]);

        // Generate unique 6-character uppercase code
        do {
            $code = strtoupper(Str::random(6));
        } while (AccessCode::where('code', $code)->exists());

        $rules = [
            'mix_questions' => $request->has('rules.mix_questions'),
            'mix_options' => $request->has('rules.mix_options'),
            'hide_after_submit' => $request->has('rules.hide_after_submit'),
            'view_answers_after_submit' => $request->has('rules.view_answers_after_submit'),
            'view_correct_answers' => $request->has('rules.view_correct_answers'),
        ];

        AccessCode::create([
            'code' => $code,
            'test_id' => $request->test_id,
            'expires_at' => $request->expires_at,
            'rules' => $rules,
        ]);

        return redirect()->route('admin.codes.index')
            ->with('success', 'Access Code generated successfully: ' . $code);
    }

    public function destroy(AccessCode $code)
    {
        $code->delete();
        return redirect()->route('admin.codes.index')
            ->with('success', 'Access Code deleted successfully!');
    }
}
