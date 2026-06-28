<?php
// image_picker_component.php
if (!isset($question_id)) {
    die("Error: question_id not set for image picker.");
}
?>

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Add Images
    </label>
    <div id="image-dropzone" 
         class="w-full p-6 border-2 border-dashed rounded-lg text-center text-gray-500 cursor-pointer hover:border-blue-400">
        <p>Drag & drop images here, or paste from clipboard</p>
        <p class="text-xs mt-2">Images will be saved in <code>/app/questions/<?php echo htmlspecialchars($question_id); ?></code></p>
    </div>
    <div id="image-preview" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const dropzone = document.getElementById("image-dropzone");
    const preview = document.getElementById("image-preview");

    function uploadFile(file) {
        const formData = new FormData();
        formData.append("image", file);
        formData.append("question_id", "<?php echo $question_id; ?>");

        fetch("/admin/upload_image.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const wrapper = document.createElement("div");
                wrapper.classList.add("flex", "flex-col", "items-center", "space-y-2");

                const img = document.createElement("img");
                img.src = data.path;
                img.classList.add("w-40", "h-40", "object-contain", "border", "rounded");

                const pathText = document.createElement("code");
                pathText.textContent = "<img src='"+data.path+"'>";
                pathText.classList.add("text-xs", "bg-gray-100", "px-2", "py-1", "rounded");

                pathText.addEventListener("click", () => {
    navigator.clipboard.writeText(pathText.textContent).then(() => {
        // Optional feedback
        pathText.classList.add("bg-green-100");
        setTimeout(() => pathText.classList.remove("bg-green-100"), 800);
    }).catch(err => {
        console.error("Clipboard copy failed:", err);
    });
});

                wrapper.appendChild(img);
                wrapper.appendChild(pathText);

                preview.appendChild(wrapper);
            } else {
                alert("Upload failed: " + data.error);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Upload error.");
        });
    }

    // Handle drag & drop
    dropzone.addEventListener("dragover", e => {
        e.preventDefault();
        dropzone.classList.add("border-blue-400", "bg-blue-50");
    });

    dropzone.addEventListener("dragleave", e => {
        dropzone.classList.remove("border-blue-400", "bg-blue-50");
    });

    dropzone.addEventListener("drop", e => {
        e.preventDefault();
        dropzone.classList.remove("border-blue-400", "bg-blue-50");
        [...e.dataTransfer.files].forEach(uploadFile);
    });

    // Handle paste
    document.addEventListener("paste", e => {
        for (const item of e.clipboardData.items) {
            if (item.type.startsWith("image/")) {
                const file = item.getAsFile();
                uploadFile(file);
            }
        }
    });
});
</script>
