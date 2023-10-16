const allowedTypes = ['.pdf', '.jpg', '.jpeg', '.png', '.doc', '.docx', '.rar', '.zip'];

function isFileTypeSupported(file) {
    const fileName = file.name;
    const fileExtension = fileName.substring(fileName.lastIndexOf('.')).toLowerCase();
    return allowedTypes.includes(fileExtension);
}

const fileUploadInput = document.getElementById('file-upload');
const fileUploadContainer = document.getElementById('file-upload-container');

let selectedFiles = new Set(); // Set to store the selected files

fileUploadInput.addEventListener('change', handleFileUpload);
fileUploadContainer.addEventListener('click', removeUploadedFile);

function handleFileUpload() {
    const files = fileUploadInput.files;
    if (files.length > 0) {
        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            // Check if the file is already selected
            if (!selectedFiles.has(file)) {
                // Check if the file type is supported
                if (isFileTypeSupported(file)) {
                    // Add the file to the selected files set
                    selectedFiles.add(file);

                    const fileDisplayContainer = document.createElement('div');
                    fileDisplayContainer.classList.add('file-display-container');

                    const fileDisplayName = document.createElement('span');
                    fileDisplayName.textContent = file.name;
                    fileDisplayContainer.appendChild(fileDisplayName);

                    const removeFileIcon = document.createElement('span');
                    removeFileIcon.classList.add('remove-file-icon');
                    removeFileIcon.innerHTML = '&#10006;';
                    fileDisplayContainer.appendChild(removeFileIcon);

                    fileUploadContainer.appendChild(fileDisplayContainer);
                } else {
                    // Handle the case where the file type is not supported
                    console.log('File type not supported:', file.name);
                }
            }
        }
    }
    fileUploadInput.value = ''; // Reset the input value to allow selecting the same file again
}

function removeUploadedFile(event) {
    if (event.target.classList.contains('remove-file-icon')) {
        const fileDisplayContainer = event.target.parentNode;
        const fileName = fileDisplayContainer.querySelector('span').textContent;

        // Remove the file from the selected files set
        const fileToRemove = Array.from(selectedFiles).find((file) => file.name === fileName);
        if (fileToRemove) {
            selectedFiles.delete(fileToRemove);
        }

        // Remove the file display container from the DOM
        fileDisplayContainer.remove();
    }
}
