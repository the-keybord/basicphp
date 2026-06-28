<?php
if (defined('DEMO_MODE')) {
    return;
}
?>
<style>
  .drop-zone {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 80px;
    min-height: 28px;
    border: 2px dashed #94a3b8; /* Tailwind slate-400 */
    border-radius: 6px;
    margin: 0 4px;
    vertical-align: middle;
    background-color: #f8fafc;
    transition: background 0.2s;
  }
  .drop-zone.filled {
    border-style: solid;
    background-color: #e0f2fe; /* Tailwind blue-100 */
  }
  .draggable {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    background-color: #3b82f6;
    color: white;
    border-radius: 6px;
    margin: 4px;
    cursor: grab;
    user-select: none;
    font-size: 14px;
  }
  .draggable.dragging {
    opacity: 0.5;
  }
  .remove-btn {
    cursor: pointer;
    font-weight: bold;
    color: white;
    background: rgba(235, 37, 37, 1);
    border-radius: 50%;
    width: 16px;
    height: 16px;
    line-height: 14px;
    text-align: center;
    font-size: 11px;
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

    // Extract draggable options
    const match = text.match(/\{\{\{([^}]+)\}\}\}/);
    let options = [];
    if (match) {
      options = match[1].split('|||').map(o => o.trim());
      text = text.replace(match[0], ''); // remove the triple-brace part
    }

    // Replace blanks (___) with drop zones
    let blankCounter = 0;
    const html = text.replace(/___+/g, () => {
      const id = blankCounter++;
      return `<span class="drop-zone" data-id="${id}"></span>`;
    });

    const container = document.createElement('div');
    container.className = 'converted-text font-mono text-gray-800 whitespace-pre-wrap';
    container.innerHTML = html;
    pre.replaceWith(container);

    // Create draggable options list
    const dragContainer = document.createElement('div');
    dragContainer.className = 'drag-container mt-3 flex flex-wrap';
    dragContainer.innerHTML = options
      .map(opt => `<div class="draggable" draggable="true" data-value="${opt}">${opt}</div>`)
      .join('');
    container.after(dragContainer);

    let dragged = null;

    // --- Dragging logic ---
    dragContainer.addEventListener('dragstart', e => {
      if (e.target.classList.contains('draggable')) {
        dragged = e.target;
        e.target.classList.add('dragging');
      }
    });

    dragContainer.addEventListener('dragend', e => {
      if (e.target.classList.contains('draggable')) {
        e.target.classList.remove('dragging');
      }
      dragged = null;
    });

    container.addEventListener('dragover', e => e.preventDefault());
    container.addEventListener('drop', e => {
      e.preventDefault();
      if (!dragged) return;

      const zone = e.target.closest('.drop-zone');
      if (!zone) return;

      // Clone the dragged item (reusable)
      const clone = dragged.cloneNode(true);
      clone.classList.remove('dragging');
      clone.draggable = false;

      // Add remove button
      const remove = document.createElement('span');
      remove.textContent = '×';
      remove.className = 'remove-btn';
      remove.onclick = () => {
        clone.remove();
        zone.classList.remove('filled');
        updateResponse();
      };
      clone.appendChild(remove);

      // Clear old item, add new one
      zone.innerHTML = '';
      zone.appendChild(clone);
      zone.classList.add('filled');

      updateResponse();
    });

    // --- Build response JSON ---
    function updateResponse() {
      const answers = {};
      container.querySelectorAll('.drop-zone').forEach(zone => {
        const item = zone.querySelector('.draggable');
        answers[zone.dataset.id] = item ? item.dataset.value : null;
      });
      responseBox.textContent = JSON.stringify(answers, null, 2);
    }

    updateResponse();
  }
}
</script>
