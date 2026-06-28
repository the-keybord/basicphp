<?php
if (defined('DEMO_MODE')) {

    return;
}
?>

<style>
    .selected {
        background-color: #3b82f6; /* Corresponds to Tailwind's bg-blue-500 */
        color: white;
        border-color: #3b82f6; /* Corresponds to Tailwind's border-blue-500 */
    }
    pre {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 15px;
  line-height: 1.6;
  color: #222;
  background: #f7f7f7;
  padding: 1em 1.2em;
  border-radius: 8px;
  white-space: pre-wrap; /* allows wrapping instead of scrolling */
  word-break: break-word;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
</style>
<script src="https://cdn.tailwindcss.com"></script>
<script>
{
    const component = document.currentScript.parentNode;
    const responseDiv = component.querySelector('.response');
    const container = component.querySelector('.options-container');

    const options = Array.from(container.querySelectorAll('.option'));
    
    for (let i = options.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [options[i], options[j]] = [options[j], options[i]];
  }
    container.innerHTML = '';
    options.forEach(option => container.appendChild(option));



    for (const btn of component.querySelectorAll('.option')) {
        btn.classList.add('border', 'p-3' ,'cursor-pointer', 'rounded');
    }
    responseDiv.classList.add('hidden');

    const updateResponseArea = () => {

        const selectedButton = component.querySelector('.option.selected');
            
        let responseText = selectedButton ? selectedButton.textContent.trim() : 'No option selected';
        responseDiv.innerHTML = responseText;
    };

    component.addEventListener('click', (event) => {
        const clickedButton = event.target.closest('.option');

        if (!clickedButton) {
            return;
        }

        const sentenceItem = clickedButton.closest('.options-container');
        
        let currentSelected = sentenceItem.querySelector('.option.selected');

        if (currentSelected) {
            currentSelected.classList.remove('selected');
        }

        clickedButton.classList.add('selected');

        updateResponseArea();
    });

    updateResponseArea();
}
</script>