function shuffleArray(array) {
    let arr = array.slice(); // clone the array if you don't want to modify original
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1)); // random index from 0 to i
        [arr[i], arr[j]] = [arr[j], arr[i]]; // swap elements
    }
    return arr;
}


function renderRadioSingle(parentSelector, options, name) {
    
    const parent = document.querySelector(parentSelector);
    if (!parent) return;

    const container = document.createElement("div");
    container.className = "answersContainer";
    parent.appendChild(container);

    options = shuffleArray(options);
    

    options.forEach((option, index) => {
    const label = document.createElement("label");
    label.style.display = "block";

    const radio = document.createElement("input");
    radio.type = "radio";
    radio.name = name;
    radio.value = option;
    radio.id = `${name}_${index}`;

    // add the click handler
    radio.onclick = function () {
        changeText(radio);
    };

    label.appendChild(radio);
    label.appendChild(document.createTextNode(option));
    container.appendChild(label);
});

}

// Optional: get selected value
function getSelectedRadio(name) {
    const radios = document.getElementsByName(name);
    for (let radio of radios) if (radio.checked) return radio.value;
    return null;
}

function changeText(button) {
      // find the parent container
      var parent = button.parentElement;
      var parent = parent.parentElement;
      var parent = parent.parentElement;
      var parent = parent.parentElement;

      // find the target div inside the parent
      const targetDiv = parent.querySelector(".response");

      // change its text
      targetDiv.textContent = "New text!";
      console.log("Text changed!");
    }
