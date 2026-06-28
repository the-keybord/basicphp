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
            'type' => 'required|string|in:testing,resource',
            'test_id' => 'required_if:type,testing|nullable|exists:tests,id',
            'resource_url' => 'required_if:type,resource|nullable|url',
            'expires_at' => 'nullable|date|after:now',
        ]);

        // Generate unique 6-character uppercase code
        do {
            $code = strtoupper(Str::random(6));
        } while (AccessCode::where('code', $code)->exists());

        $rules = [];
        if ($request->type === 'testing') {
            $rules = [
                'mix_questions' => $request->has('rules.mix_questions'),
                'mix_options' => $request->has('rules.mix_options'),
                'hide_after_submit' => $request->has('rules.hide_after_submit'),
                'view_answers_after_submit' => $request->has('rules.view_answers_after_submit'),
                'view_correct_answers' => $request->has('rules.view_correct_answers'),
            ];
        }

        AccessCode::create([
            'code' => $code,
            'type' => $request->type,
            'test_id' => $request->type === 'testing' ? $request->test_id : null,
            'resource_url' => $request->type === 'resource' ? $request->resource_url : null,
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
