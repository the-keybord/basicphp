<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.questions.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Add New Question') }}
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

                <form action="{{ route('admin.questions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Primary Subcategory -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Primary Subcategory <span class="text-red-500">*</span></label>
                            <select name="primary_subcategory_id" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" required>
                                <option value="" disabled selected>Select Primary Subcategory...</option>
                                @foreach($categories as $category)
                                    <optgroup label="{{ ucfirst($category->name) }}">
                                        @foreach($category->subcategories as $sub)
                                            <option value="{{ $sub->id }}" {{ old('primary_subcategory_id') == $sub->id ? 'selected' : '' }}>
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
                                            <option value="{{ $sub->id }}" {{ old('secondary_subcategory_id') == $sub->id ? 'selected' : '' }}>
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
                                <option value="singleselect" {{ old('question_type') == 'singleselect' ? 'selected' : '' }}>Single Select</option>
                                <option value="multiselect" {{ old('question_type', 'multiselect') == 'multiselect' ? 'selected' : '' }}>Multi-Select</option>
                                <option value="dropdown" {{ old('question_type') == 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                <option value="drag_and_drop" {{ old('question_type') == 'drag_and_drop' ? 'selected' : '' }}>Drag & Drop</option>
                                <option value="truefalse" {{ old('question_type') == 'truefalse' ? 'selected' : '' }}>True/False</option>
                            </select>
                        </div>

                        <!-- Correct Answer String -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Correct Answer String <span class="text-red-500">*</span></label>
                            <input type="text" name="correct_answer_string" value="{{ old('correct_answer_string') }}" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5" placeholder="e.g. A, B or True or DDL:Data Definition Language" required>
                            <p class="mt-1 text-xs text-gray-500">The exact correct answer(s). Format depends on the question type. Keep this outside of the XML content.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- XML Content Textarea -->
                        <div class="lg:col-span-2 space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">XML Content <span class="text-red-500">*</span></label>
                            <textarea id="xml_content" name="xml_content" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 font-mono text-sm p-3 h-96 transition duration-150" placeholder="<question>...</question>" required>{{ old('xml_content') }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">Write XML syntax defining text and choice options. You can insert images by dragging them into the uploader panel on the right or pasting them directly anywhere.</p>
                        </div>

                        <!-- Side Utilities Panel -->
                        <div class="space-y-6">
                            <!-- Image Uploader Panel -->
                            <div class="bg-gray-50 border border-gray-150 rounded-xl p-5 space-y-4">
                                <h4 class="font-bold text-sm text-gray-800 uppercase tracking-wider">Image Uploader</h4>
                                <p class="text-xs text-gray-500">Upload images to storage, then insert them anywhere inside the question XML.</p>
                                
                                <!-- Drag and Drop Box -->
                                <div id="image-drop-zone" class="border-2 border-dashed border-gray-300 hover:border-blue-500 hover:bg-blue-50/30 rounded-xl p-6 text-center cursor-pointer transition select-none">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="text-xs font-bold text-gray-700 block">Drag & drop or paste image</span>
                                    <span class="text-xxs text-gray-400 mt-1 block">Or click to select image file</span>
                                </div>
                                <input type="file" id="image-upload-field" class="hidden" accept="image/*" onchange="uploadImageFiles(this.files)">

                                <!-- Uploaded Images List -->
                                <div id="uploaded-images-library" class="hidden space-y-2">
                                    <h5 class="font-bold text-xs text-gray-500 uppercase tracking-wide">Uploaded Images</h5>
                                    <div id="images-container" class="grid grid-cols-2 gap-2">
                                        <!-- Thumbnails inserted here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Interactive Help Panel -->
                            <div class="bg-gray-50 border border-gray-150 rounded-xl p-5 space-y-3">
                                <h4 class="font-bold text-sm text-gray-800 uppercase tracking-wider">XML Templates</h4>
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
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.questions.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg shadow-sm transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Save Question
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- JS for dynamic XML templating and AJAX image uploading -->
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
            const dropzone = document.getElementById('image-drop-zone');
            const fileInput = document.getElementById('image-upload-field');

            // Trigger file dialog
            dropzone.addEventListener('click', () => fileInput.click());

            // Drag and drop events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.add('border-blue-500', 'bg-blue-50/50');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.remove('border-blue-500', 'bg-blue-50/50');
                }, false);
            });

            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                if (dt.files && dt.files.length) {
                    uploadImageFiles(dt.files);
                }
            });

            // Clipboard Paste handler
            window.addEventListener('paste', (e) => {
                const items = (e.clipboardData || e.originalEvent.clipboardData).items;
                for (let index in items) {
                    const item = items[index];
                    if (item.kind === 'file' && item.type.startsWith('image/')) {
                        const blob = item.getAsFile();
                        uploadImageFiles([blob]);
                    }
                }
            });
        });

        function uploadImageFiles(files) {
            const container = document.getElementById('images-container');
            document.getElementById('uploaded-images-library').classList.remove('hidden');

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (!file.type.startsWith('image/')) continue;

                const tempId = 'temp-' + Date.now() + '-' + i;
                const tempCard = document.createElement('div');
                tempCard.id = tempId;
                tempCard.className = 'relative border border-gray-200 rounded-lg p-2 bg-white flex flex-col items-center justify-center h-20 overflow-hidden';
                tempCard.innerHTML = `
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
                    <span class="text-xxs text-gray-500 mt-1 truncate max-w-full px-1">${file.name}</span>
                `;
                container.appendChild(tempCard);

                const formData = new FormData();
                formData.append('image', file);

                fetch("{{ route('admin.questions.upload-image') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Upload failed');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const card = document.getElementById(tempId);
                        card.className = 'relative border border-gray-200 rounded-lg p-1 bg-white hover:shadow-sm transition flex flex-col group';
                        card.innerHTML = `
                            <div class="h-12 w-full rounded overflow-hidden bg-gray-100 flex items-center justify-center relative">
                                <img src="${data.url}" class="h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                    <button type="button" onclick="insertImageToken('${data.filename}')" class="bg-blue-600 hover:bg-blue-700 text-white text-xxs font-bold py-0.5 px-1.5 rounded shadow transition">
                                        Insert
                                    </button>
                                </div>
                            </div>
                            <span class="text-xxs text-gray-400 truncate max-w-full text-center mt-1 select-all font-mono" title="${data.filename}">${data.filename}</span>
                        `;
                    } else {
                        throw new Error(data.message || 'Upload failed');
                    }
                })
                .catch(error => {
                    console.error(error);
                    const card = document.getElementById(tempId);
                    card.innerHTML = `<span class="text-red-500 text-xxs font-bold">Error</span>`;
                    setTimeout(() => card.remove(), 3000);
                });
            }
        }

        function insertImageToken(filename) {
            const textarea = document.getElementById('xml_content');
            const token = `@img('${filename}')`;
            insertAtCursor(textarea, token);
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