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
const pre = component.querySelector('pre');
const responseBox = component.querySelector('.response');

if (pre && responseBox) {
  let text = pre.textContent.trim();

  // Replace triple-brace patterns with <select> elements
  let counter = 0;
  const html = text.replace(/\{\{\{([^}]+)\}\}\}/g, (match, options) => {
    const opts = options.split('|||')
      .map(opt => `<option value="${opt.trim()}">${opt.trim()}</option>`)
      .join('');
    const selectId = `dropdown-${counter++}`;
    return `<select data-id="${selectId}" class="inline-select border border-gray-300 rounded px-1 py-0.5 text-sm focus:outline-none focus:ring focus:ring-blue-200">
    <option value="" disabled selected>...</option>${opts}</select>`;
  });

  // Create container for formatted content
  const container = document.createElement('div');
  container.className = 'converted-text font-mono text-gray-800 whitespace-pre-wrap';
  container.innerHTML = html;
  pre.replaceWith(container);

  // Function to collect all current answers and display as JSON
  function updateResponse() {
    const selects = container.querySelectorAll('select');
    const answers = {};
    selects.forEach(sel => {
      answers[sel.dataset.id] = sel.value;
    });
    responseBox.textContent = JSON.stringify(answers, null, 2);
  }

  // Listen to dropdown changes
  container.addEventListener('change', updateResponse);

  // Initialize response once at start
  updateResponse();
}
}

</script>
