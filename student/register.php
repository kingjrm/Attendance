<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-page">
    <div class="container">
        <div class="logo">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 017.499 22c-1.39 0-2.733-.11-4.049-.326a.988.988 0 01-.394-.233.996.996 0 01-.195-.293A47.416 47.416 0 013.68 10.5c0-.338.045-.677.138-1.006a4.61 4.61 0 01.289-1.225 1.002 1.002 0 01.886-.585c.566.043 1.093.14 1.612.289a47.562 47.562 0 013.966 1.353c.743.318 1.443.707 2.096 1.159a4.611 4.611 0 011.678 2.148c.147.54.303 1.085.459 1.631a60.585 60.585 0 01.394 3.742c0 .754-.044 1.508-.132 2.257a.988.988 0 01-.309.732.996.996 0 01-.886.383 47.503 47.503 0 01-4.049-.326c-.465-.088-.93-.177-1.394-.266a47.416 47.416 0 01-3.732-.895 1.002 1.002 0 01-.686-.949 60.585 60.585 0 01.491-6.347c.285-1.663.715-3.292 1.278-4.896a4.61 4.61 0 012.148-2.5 4.61 4.61 0 012.5-.459c.74.09 1.476.206 2.21.346a47.562 47.562 0 013.966.777c.744.194 1.478.441 2.195.74a4.611 4.611 0 012.5 2.148c.306.74.585 1.488.835 2.244a60.436 60.436 0 01.491 3.742z" fill="#667eea"/>
            </svg>
        </div>
        <h1>Student Registration</h1>
            <form action="../register.php" method="post" class="registration-form" id="registerForm">
                <div class="form-group user">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group email">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group lock">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <button type="button" class="toggle-password" data-target="password">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="#667eea"/>
                        </svg>
                    </button>
                </div>
                <div class="form-group lock">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <button type="button" class="toggle-password" data-target="confirm_password">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="#667eea"/>
                        </svg>
                    </button>
                </div>
                <div class="form-group id">
                    <label for="student_id">Student ID:</label>
                    <input type="text" id="student_id" name="student_id" required>
                </div>
                <div class="form-group checkbox">
                    <label class="checkbox-label">
                        <input type="checkbox" id="agree" name="agree" required>
                        <span class="checkmark"></span>
                        I agree to the <a href="#" id="termsLink">Terms & Conditions</a> and <a href="#" id="privacyLink">Privacy Policy</a>
                    </label>
                </div>
                <button type="submit" class="btn" id="signupBtn" disabled>Sign Up</button>
            </form>
            <div class="login-link">
                <p>Already have an account? <a href="login.php">Sign In</a></p>
            </div>
            <?php if (isset($_GET['message'])): ?>
                <div class="feedback <?php echo strpos($_GET['message'], 'success') !== false ? 'success' : 'error'; ?>" style="display: block; margin-top: 1rem;">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Terms Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Terms & Conditions</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <h3>1. Acceptance of Terms</h3>
                <p>By accessing and using Spacer, you accept and agree to be bound by the terms and provision of this agreement.</p>
                <h3>2. Use License</h3>
                <p>Permission is granted to temporarily use Spacer for personal, non-commercial transitory viewing only.</p>
                <h3>3. Disclaimer</h3>
                <p>The materials on Spacer are provided on an 'as is' basis. Spacer makes no warranties, expressed or implied.</p>
                <h3>4. Limitations</h3>
                <p>In no event shall Spacer be liable for any damages arising out of the use or inability to use the materials.</p>
                <h3>5. Privacy Policy</h3>
                <p>Your privacy is important to us. Please review our Privacy Policy for details.</p>
                <!-- Add more content to make it scrollable -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
            <div class="modal-footer">
                <button class="btn" id="acceptTerms">I Agree</button>
            </div>
        </div>
    </div>

    <!-- Privacy Modal -->
    <div id="privacyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Privacy Policy</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <h3>1. Information We Collect</h3>
                <p>We collect information you provide directly to us, such as when you create an account or use our services.</p>
                <h3>2. How We Use Information</h3>
                <p>We use the information we collect to provide, maintain, and improve our services.</p>
                <h3>3. Information Sharing</h3>
                <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent.</p>
                <h3>4. Data Security</h3>
                <p>We implement appropriate security measures to protect your personal information.</p>
                <h3>5. Contact Us</h3>
                <p>If you have any questions about this Privacy Policy, please contact us.</p>
                <!-- Add more content -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
            <div class="modal-footer">
                <button class="btn" id="acceptPrivacy">I Agree</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const agreeCheckbox = document.getElementById('agree');
            const signupBtn = document.getElementById('signupBtn');
            const termsLink = document.getElementById('termsLink');
            const privacyLink = document.getElementById('privacyLink');
            const termsModal = document.getElementById('termsModal');
            const privacyModal = document.getElementById('privacyModal');
            const modalCloses = document.querySelectorAll('.modal-close');
            const acceptTerms = document.getElementById('acceptTerms');
            const acceptPrivacy = document.getElementById('acceptPrivacy');

            function checkFormValidity() {
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();
                const confirmPassword = document.getElementById('confirm_password').value.trim();
                const studentId = document.getElementById('student_id').value.trim();
                const agreed = agreeCheckbox.checked;

                signupBtn.disabled = !(name && email && password && confirmPassword && studentId && agreed && password === confirmPassword);
            }

            form.addEventListener('input', checkFormValidity);
            agreeCheckbox.addEventListener('change', checkFormValidity);

            termsLink.addEventListener('click', function(e) {
                e.preventDefault();
                termsModal.style.display = 'block';
            });

            privacyLink.addEventListener('click', function(e) {
                e.preventDefault();
                privacyModal.style.display = 'block';
            });

            modalCloses.forEach(close => {
                close.addEventListener('click', function() {
                    termsModal.style.display = 'none';
                    privacyModal.style.display = 'none';
                });
            });

            acceptTerms.addEventListener('click', function() {
                termsModal.style.display = 'none';
                // Could set a flag here if needed
            });

            acceptPrivacy.addEventListener('click', function() {
                privacyModal.style.display = 'none';
                // Could set a flag here
            });

            window.addEventListener('click', function(e) {
                if (e.target === termsModal) {
                    termsModal.style.display = 'none';
                }
                if (e.target === privacyModal) {
                    privacyModal.style.display = 'none';
                }
            });

            form.addEventListener('submit', function(e) {
                if (!agreeCheckbox.checked) {
                    e.preventDefault();
                    alert('Please agree to the Terms & Conditions and Privacy Policy.');
                    return;
                }
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match.');
                    return;
                }
            });

            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    const input = document.getElementById(target);
                    if (input.type === 'password') {
                        input.type = 'text';
                        this.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.99902 3L20.999 21L19.999 22L15.999 18H4.99902C3.89402 18 2.99902 17.105 2.99902 16V6C2.99902 5.46957 3.21002 5.00097 3.58502 4.707L1.99902 3.171L2.99902 3ZM11.999 6C13.0609 6 14.0783 6.42143 14.8284 7.17157C15.5786 7.92172 15.999 8.93913 15.999 10C15.999 10.552 15.551 11 14.999 11C14.447 11 13.999 10.552 13.999 10C13.999 9.46957 13.789 9.00097 13.414 8.707L11.999 7.292C11.999 6.74 11.551 6.292 10.999 6.292L11.999 6ZM11.999 8C12.551 8 12.999 8.448 12.999 9C12.999 9.552 12.551 10 11.999 10C11.447 10 10.999 9.552 10.999 9C10.999 8.448 11.551 8.448 11.999 8.448L11.999 8ZM4.99902 6.414L6.99902 8.414V14H14.585L16.585 16H19.999V6H4.99902V6.414Z" fill="#667eea"/></svg>';
                    } else {
                        input.type = 'password';
                        this.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="#667eea"/></svg>';
                    }
                });
            });
        });
    </script>
</body>
</html>