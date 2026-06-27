<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.questions.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Edit Question #' . $question->id) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold text-red-800">There were errors with your submission</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.questions.update', $question) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Primary Subcategory -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Primary Subcategory <span class="text-red-500">*</span></label>
                            <select name="primary_subcategory_id" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" required>
                                <option value="" disabled>Select Primary Subcategory...</option>
                                @foreach($categories as $category)
                                    <optgroup label="{{ ucfirst($category->name) }}">
                                        @foreach($category->subcategories as $sub)
                                            <option value="{{ $sub->id }}" {{ old('primary_subcategory_id', $question->primary_subcategory_id) == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Every question must have a primary subcategory.</p>
                        </div>

                        <!-- Secondary Subcategory -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Secondary Subcategory <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <select name="secondary_subcategory_id" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5">
                                <option value="">None / No Secondary Subcategory</option>
                                @foreach($categories as $category)
                                    <optgroup label="{{ ucfirst($category->name) }}">
                                        @foreach($category->subcategories as $sub)
                                            <option value="{{ $sub->id }}" {{ old('secondary_subcategory_id', $question->secondary_subcategory_id) == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Choose an additional category/subcategory mapping if applicable.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Question Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Question Type <span class="text-red-500">*</span></label>
                            <select id="question_type" name="question_type" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" required>
                                <option value="singleselect" {{ old('question_type', $question->question_type) == 'singleselect' ? 'selected' : '' }}>Single Select</option>
                                <option value="multiselect" {{ old('question_type', $question->question_type) == 'multiselect' ? 'selected' : '' }}>Multi-Select</option>
                                <option value="dropdown" {{ old('question_type', $question->question_type) == 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                <option value="drag_and_drop" {{ old('question_type', $question->question_type) == 'drag_and_drop' ? 'selected' : '' }}>Drag & Drop</option>
                                <option value="truefalse" {{ old('question_type', $question->question_type) == 'truefalse' ? 'selected' : '' }}>True/False</option>
                            </select>
                        </div>

                        <!-- Correct Answer String -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Correct Answer String <span class="text-red-500">*</span></label>
                            <input type="text" name="correct_answer_string" value="{{ old('correct_answer_string', $question->correct_answer_string) }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" placeholder="e.g. A, B or True or DDL:Data Definition Language" required>
                            <p class="mt-1 text-xs text-gray-500">The exact correct answer(s). Format depends on the question type. Keep this outside of the XML content.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- XML Content Textarea -->
                        <div class="lg:col-span-2">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-semibold text-gray-700">XML Content <span class="text-red-500">*</span></label>
                                <div class="flex items-center space-x-2">
                                    <button type="button" onclick="document.getElementById('image_upload_input').click()" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg shadow-sm transition">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Insert Image (Drop / Paste / Select)
                                    </button>
                                    <input type="file" id="image_upload_input" class="hidden" accept="image/*" onchange="handleImageFileSelect(this)">
                                </div>
                            </div>
                            <textarea id="xml_content" name="xml_content" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 font-mono text-sm p-3 h-96 transition duration-150" placeholder="<question>...</question>" required>{{ old('xml_content', $question->xml_content) }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">Write XML syntax defining text and choice options. You can drag and drop images, paste them from clipboard, or click 'Insert Image' above. They will be added as tags and saved on the server automatically.</p>
                        </div>

                        <!-- Interactive Help Panel -->
                        <div class="bg-gray-50 border border-gray-150 rounded-xl p-5 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-sm text-gray-800 uppercase tracking-wider mb-3">XML Code Templates</h4>
                                <p class="text-xs text-gray-600 mb-4">Click below to copy/load the syntax template for your chosen question type.</p>
                                
                                <div class="space-y-2">
                                    <button type="button" onclick="loadTemplate('singleselect')" class="w-full text-left bg-white hover:bg-indigo-50 border border-gray-200 text-xs font-semibold py-2 px-3 rounded-lg text-indigo-700 transition">
                                        Single Select Template
                                    </button>
                                    <button type="button" onclick="loadTemplate('multiselect')" class="w-full text-left bg-white hover:bg-purple-50 border border-gray-200 text-xs font-semibold py-2 px-3 rounded-lg text-purple-700 transition">
                                        Multi-Select Template
                                    </button>
                                    <button type="button" onclick="loadTemplate('dropdown')" class="w-full text-left bg-white hover:bg-blue-50 border border-gray-200 text-xs font-semibold py-2 px-3 rounded-lg text-blue-700 transition">
                                        Dropdown Template
                                    </button>
                                    <button type="button" onclick="loadTemplate('drag_and_drop')" class="w-full text-left bg-white hover:bg-amber-50 border border-gray-200 text-xs font-semibold py-2 px-3 rounded-lg text-amber-700 transition">
                                        Drag & Drop (Matching) Template
                                    </button>
                                    <button type="button" onclick="loadTemplate('truefalse')" class="w-full text-left bg-white hover:bg-teal-50 border border-gray-200 text-xs font-semibold py-2 px-3 rounded-lg text-teal-700 transition">
                                        True / False Template
                                    </button>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <h5 class="font-semibold text-xs text-gray-700 mb-1">Image Reference Shortcut</h5>
                                <p class="text-xs text-gray-500">Insert custom images in question text or option tags via the asset shortcut format: <code class="bg-gray-200 px-1 py-0.5 rounded font-mono font-bold text-gray-800">@img('filename.png')</code></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.questions.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg shadow-sm transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- JS for dynamic XML templating and image uploading -->
    <script>
        const templates = {
            singleselect: `<question>\n    <text>What is the default port for PostgreSQL databases?</text>\n    <option>3306</option>\n    <option>5432</option>\n    <option>1433</option>\n    <option>1521</option>\n</question>`,
            multiselect: `<question>\n    <text>Select all properties of a primary key:</text>\n    <option>Must contain unique values</option>\n    <option>Cannot contain NULL values</option>\n    <option>A table can have multiple primary keys</option>\n    <option>Speeds up query operations</option>\n</question>`,
            dropdown: `<question>\n    <text>To filter the results of a GROUP BY operation, you should use the _____ clause.</text>\n    <option>WHERE</option>\n    <option>HAVING</option>\n    <option>FILTER</option>\n</question>`,
            drag_and_drop: `<question>\n    <text>Match these SQL commands with their category:</text>\n    <option>\n        <left>SELECT</left>\n        <right>DML (Data Manipulation)</right>\n    </option>\n    <option>\n        <left>CREATE TABLE</left>\n        <right>DDL (Data Definition)</right>\n    </option>\n    <option>\n        <left>GRANT</left>\n        <right>DCL (Data Control)</right>\n    </option>\n</question>`,
            truefalse: `<question>\n    <text>A Foreign Key constraint must always reference a Primary Key in another table.</text>\n    <option>True</option>\n    <option>False</option>\n</question>`
        };

        function loadTemplate(type) {
            if (confirm("Loading this template will overwrite the XML Content area. Proceed?")) {
                document.getElementById('xml_content').value = templates[type];
                document.getElementById('question_type').value = type;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const textarea = document.getElementById('xml_content');

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                textarea.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            // Highlight textarea on drag over
            ['dragenter', 'dragover'].forEach(eventName => {
                textarea.addEventListener(eventName, () => {
                    textarea.classList.add('border-blue-500', 'bg-blue-50/10', 'ring-2', 'ring-blue-200');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                textarea.addEventListener(eventName, () => {
                    textarea.classList.remove('border-blue-500', 'bg-blue-50/10', 'ring-2', 'ring-blue-200');
                }, false);
            });

            // Handle drop
            textarea.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                if (dt.files && dt.files.length) {
                    handleImageFiles(dt.files);
                }
            });

            // Clipboard Paste handler
            textarea.addEventListener('paste', (e) => {
                const items = (e.clipboardData || e.originalEvent.clipboardData).items;
                for (let index in items) {
                    const item = items[index];
                    if (item.kind === 'file' && item.type.startsWith('image/')) {
                        const blob = item.getAsFile();
                        handleImageFiles([blob]);
                    }
                }
            });
        });

        function handleImageFileSelect(input) {
            if (input.files && input.files.length) {
                handleImageFiles(input.files);
                input.value = ''; // Reset input selection
            }
        }

        function handleImageFiles(files) {
            const textarea = document.getElementById('xml_content');
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (!file.type.startsWith('image/')) continue;

                const reader = new FileReader();
                reader.onload = function(event) {
                    const base64Data = event.target.result;
                    const imgTag = `<img src="${base64Data}" />`;
                    insertAtCursor(textarea, imgTag);
                };
                reader.readAsDataURL(file);
            }
        }

        function insertAtCursor(myField, myValue) {
            if (myField.selectionStart || myField.selectionStart == '0') {
                const startPos = myField.selectionStart;
                const endPos = myField.selectionEnd;
                myField.value = myField.value.substring(0, startPos)
                    + myValue
                    + myField.value.substring(endPos, myField.value.length);
                myField.focus();
                myField.selectionStart = startPos + myValue.length;
                myField.selectionEnd = startPos + myValue.length;
            } else {
                myField.value += myValue;
                myField.focus();
            }
        }
    </script>
</x-app-layout>
