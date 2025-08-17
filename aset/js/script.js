// JavaScript global functions for BelajarDigital

// Toggle password visibility
function togglePasswordVisibility() {
    const passwordField = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');

    if (passwordField && toggleButton) {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            passwordField.type = 'password';
            toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
        }
    }
}

// Event listener for toggle button
if (document.getElementById('togglePassword')) {
    document.getElementById('togglePassword').addEventListener('click', togglePasswordVisibility);
}

// AJAX Functions for Course Enrollment
function enrollCourse(courseId, button) {
    // Disable button and show loading
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

    // Create form data
    const formData = new FormData();
    formData.append('id_kursus', courseId);

    // Send AJAX request
    fetch('aksi/aksi_tambah_kursus_mahasiswa.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(html => {
            // Create modal container if not exists
            let modalContainer = document.getElementById('enrollmentModal');
            if (!modalContainer) {
                modalContainer = document.createElement('div');
                modalContainer.id = 'enrollmentModal';
                modalContainer.className = 'modal fade';
                modalContainer.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Status Pendaftaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="enrollmentResult">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            `;
                document.body.appendChild(modalContainer);
            }

            // Update modal content
            document.getElementById('enrollmentResult').innerHTML = html;

            // Show modal
            const modal = new bootstrap.Modal(modalContainer);
            modal.show();

            // Re-enable button
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-plus"></i> Daftar Kursus';

        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mendaftar kursus');
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-plus"></i> Daftar Kursus';
        });
}

// Function to show success message
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.innerHTML = `
        <i class="fas fa-check-circle"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Insert at top of page
    const container = document.querySelector('.container') || document.body;
    container.insertBefore(alertDiv, container.firstChild);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Function to handle form submission with AJAX
function handleFormSubmit(formId, actionUrl, successCallback) {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');

            // Show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (successCallback) {
                        successCallback(data);
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Submit';
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Submit';
                });
        });
    }
}

// Function to handle user profile dropdown
function initializeUserProfile() {
    // Ensure dropdown works properly
    const dropdownToggle = document.querySelector('[data-bs-toggle="dropdown"]');
    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Force Bootstrap dropdown to work
            const dropdown = new bootstrap.Dropdown(this);
            dropdown.toggle();
        });
    }

    // Handle logout link with confirmation
    const logoutLink = document.querySelector('a[href*="aksi_logout.php"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function (e) {
            e.preventDefault();

            if (confirm('Apakah Anda yakin ingin logout?')) {
                window.location.href = this.href;
            }
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    // Add click handlers for course enrollment buttons
    const enrollButtons = document.querySelectorAll('.enroll-btn');
    enrollButtons.forEach(button => {
        button.addEventListener('click', function () {
            const courseId = this.dataset.courseId;
            enrollCourse(courseId, this);
        });
    });

    // Initialize user profile functionality
    initializeUserProfile();
});
