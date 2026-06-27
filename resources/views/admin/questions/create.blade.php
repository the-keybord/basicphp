<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Question') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

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

                    <button type="submit" class="btn btn-success mt-4">Save Question</button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>