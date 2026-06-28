<?php
if (defined('DEMO_MODE')) {

    return;
}
?>
<style>
    .selected {
        background-color: #3b82f6; /* Tailwind bg-blue-500 */
        color: white;
        border-color: #3b82f6; /* Tailwind border-blue-500 */
    }
    .selected:hover {
        background-color: #2563eb; /* Tailwind bg-blue-600 */
        border-color: #2563eb; /* Tailwind border-blue-600 */
    }
    pre {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 15px;
        line-height: 1.6;
        color: #222;
        background: #f7f7f7;
        padding: 1em 1.2em;
        border-radius: 8px;
        white-space: pre-wrap;
        word-break: break-word;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
</style>
<script src="https://cdn.tailwindcss.com"></script>
<script>
{
    const component = document.currentScript.parentNode;
    const responseDiv = component.querySelector('.response');

    // Build the question layout
    component.querySelectorAll('.option').forEach((el, index) => {
        const text = el.querySelector('code')?.textContent.trim() || '';

        const newDiv = document.createElement('div');
        newDiv.className = 'sentence-item flex items-center justify-between p-3 border bg-gray-50 rounded-lg mb-2';
        newDiv.setAttribute('data-sentence-id', index);

        newDiv.innerHTML = `
            <p class='flex-grow mr-4 text-gray-800'>${text}</p>
            <div class='flex-shrink-0 space-x-2'>
                <button class='btn-tf py-1 px-4 rounded-md border border-gray-300 bg-white hover:bg-gray-100 focus:outline-none' data-value='true'>True</button>
                <button class='btn-tf py-1 px-4 rounded-md border border-gray-300 bg-white hover:bg-gray-100 focus:outline-none' data-value='false'>False</button>
            </div>
        `;

        el.replaceWith(newDiv);
    });

    // Handle True/False button clicks
    component.querySelectorAll('.btn-tf').forEach(btn => {
        btn.addEventListener('click', () => {
            const parent = btn.closest('.sentence-item');
            const sentenceId = parent.getAttribute('data-sentence-id');
            const value = btn.getAttribute('data-value');

            // Remove previous selection styling
            parent.querySelectorAll('.btn-tf').forEach(b => b.classList.remove('selected'));

            // Apply selection style
            btn.classList.add('selected');

            // Update the response element
            const responses = {};

            // Collect all selected answers
            component.querySelectorAll('.sentence-item').forEach(item => {
                const id = item.getAttribute('data-sentence-id');
                const selected = item.querySelector('.btn-tf.selected');
                if (selected) {
                    responses[id] = selected.getAttribute('data-value');
                }
            });

            // Display JSON in the .response element
            if (responseDiv) {
                responseDiv.textContent = JSON.stringify(responses, null, 2);
            }
        });
    });
}
</script>
