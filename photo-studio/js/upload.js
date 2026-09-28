const input = document.getElementById("photo");
const dropZone = document.getElementById("dropZone");
const selectedFile = document.getElementById("selectedFile");

if (input) {

    input.addEventListener("change", function () {

        if (this.files.length > 0) {

            const file = this.files[0];

            selectedFile.textContent =
                "Selected: " + file.name;

        }

    });

}

if (dropZone) {

    ["dragenter", "dragover"].forEach(eventName => {

        dropZone.addEventListener(
            eventName,
            e => {
                e.preventDefault();
                dropZone.classList.add("dragover");
            }
        );

    });


    ["dragleave", "drop"].forEach(eventName => {

        dropZone.addEventListener(
            eventName,
            e => {
                e.preventDefault();
                dropZone.classList.remove("dragover");
            }
        );

    });


    dropZone.addEventListener("drop", e => {

        const files = e.dataTransfer.files;

        if (files.length > 0) {

            input.files = files;

            selectedFile.textContent =
                "Selected: " + files[0].name;

        }

    });

}