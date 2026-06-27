@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add New Question</h2>
    
    <form action="{{ route('admin.questions.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label>Subcategory</label>
            <input type="text" name="subcategory" class="form-control">
        </div>

        <div class="mb-3">
            <label>Question Type</label>
            <select name="question_type" class="form-control" required>
                <option value="multiselect">Multi-Select</option>
                <option value="truefalse">True/False</option>
                <option value="dropdown">Dropdown</option>
                <option value="freeform">Free Form</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Correct Answer String</label>
            <input type="text" name="correct_answer_string" class="form-control">
        </div>

        <div class="mb-3">
            <label>XML Content</label>
            <textarea name="xml_content" class="form-control" rows="10" required placeholder="<question>...</question>"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Save Question</button>
    </form>
</div>
@endsection