<script>
{
    const component = document.currentScript.parentNode;
    const responseDiv = component.querySelector('.response');

    component.addEventListener('click', (event) => {
        const clickedOption = event.target.closest('.option');
        if (!clickedOption) {
            return;
        }

        const currentSelected = component.querySelector('.option.selected');
        if (currentSelected) {
            currentSelected.classList.remove('selected');
        }

        clickedOption.classList.add('selected');
        const selectedAnswer = clickedOption.textContent.trim();

        // The output no longer mentions an ID
        responseDiv.textContent = selectedAnswer;
    });
}
</script>