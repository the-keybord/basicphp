@extends('layouts.app') @section('content')
<div class="container">
    <h2>Question Database</h2>
    <a href="{{ route('admin.questions.create') }}" class="btn btn-primary mb-3">Add New Question</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Subcategory</th>
                <th>Answer</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
            <tr>
                <td>{{ $question->id }}</td>
                <td>{{ $question->question_type }}</td>
                <td>{{ $question->subcategory }}</td>
                <td>{{ $question->correct_answer_string }}</td>
                <td>{{ $question->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection