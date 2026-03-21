// profile.js

const uploadBtn = document.getElementById('uploadBtn');
const avatarInput = document.getElementById('avatarInput');
const profileImg = document.getElementById('profileImg');

// Open file picker when clicking the camera button
if (uploadBtn) {
    uploadBtn.addEventListener('click', function (e) {
        e.preventDefault();
        avatarInput.click();
    });
}

// Preview the selected image
if (avatarInput) {
    avatarInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file');
            return;
        }

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            return;
        }

        // Preview the image
        const reader = new FileReader();
        reader.onload = function (event) {
            profileImg.src = event.target.result;
        };
        reader.readAsDataURL(file);

        // TODO: Upload to server via AJAX/fetch
        // uploadProfilePicture(file);
    });
}

// Placeholder function for uploading to server
function uploadProfilePicture(file) {
    const formData = new FormData();
    formData.append('profile_picture', file);

    fetch('upload_profile_picture.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Profile picture updated successfully');
        } else {
            alert('Upload failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        alert('An error occurred during upload');
    });
}