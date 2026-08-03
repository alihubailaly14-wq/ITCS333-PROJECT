document.addEventListener('DOMContentLoaded', function() {
    
    function showError(inputElement, message) {
        clearError(inputElement); 
        const errorDiv = document.createElement('div');
        errorDiv.classList.add('error-msg', 'js-error-msg'); 
        errorDiv.textContent = message;
        
        if (inputElement.type === 'checkbox') {
            inputElement.parentElement.insertAdjacentElement('afterend', errorDiv);
        } else {
            inputElement.insertAdjacentElement('afterend', errorDiv);
        }
    }

    function clearError(inputElement) {
        let nextEl = inputElement.type === 'checkbox' 
            ? inputElement.parentElement.nextElementSibling 
            : inputElement.nextElementSibling;
            
        if (nextEl && nextEl.classList.contains('js-error-msg')) {
            nextEl.remove();
        }
    }

    // -----------------------------------------
    // 1. REGISTRATION FORM
    // -----------------------------------------
    const regForm = document.querySelector('.Registration form');
    
    if (regForm) {
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const cpassword = document.getElementById('cpassword');
        const agree = document.getElementById('agree');

        function validateName() {
            if (name.value.trim() === '') {
                showError(name, 'Name cannot be empty.');
                return false;
            }
            clearError(name);
            return true;
        }

        function validateEmail() {
            const emailRegex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
            if (email.value.trim() !== '' && !emailRegex.test(email.value.trim())) {
                showError(email, 'Please enter a valid email address.');
                return false;
            }
            if (email.value.trim() === '') {
                showError(email, 'Email cannot be empty.');
                return false;
            }
            clearError(email);
            return true;
        }

        function validatePassword() {
            if (password.value.length > 0 && password.value.length < 8) {
                showError(password, 'Password must be at least 8 characters long.');
                return false;
            }
            if (password.value.trim() === '') {
                showError(password, 'Password cannot be empty.');
                return false;
            }
            clearError(password);
            
            if (cpassword.value.length > 0) {
                validateCPassword();
            }
            return true;
        }

        function validateCPassword() {
            if (password.value !== cpassword.value) {
                showError(cpassword, 'Passwords do not match.');
                return false;
            }
            clearError(cpassword);
            return true;
        }

        function validateAgree() {
            if (!agree.checked) {
                showError(agree, 'You must agree to the policies.');
                return false;
            }
            clearError(agree);
            return true;
        }

        ['input', 'blur'].forEach(evt => {
            name.addEventListener(evt, validateName);
            email.addEventListener(evt, validateEmail);
            password.addEventListener(evt, validatePassword);
            cpassword.addEventListener(evt, validateCPassword);
        });
        agree.addEventListener('change', validateAgree);

        regForm.addEventListener('submit', function(e) {
            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isPasswordValid = validatePassword();
            const isCPasswordValid = validateCPassword();
            const isAgreeValid = validateAgree();

            if (!isNameValid || !isEmailValid || !isPasswordValid || !isCPasswordValid || !isAgreeValid) {
                e.preventDefault(); 
            }
        });
    }

    // -----------------------------------------
    // 2. LOGIN FORM
    // -----------------------------------------
    const loginForm = document.querySelector('.Login form');
    
    if (loginForm) {
        const loginId = document.getElementById('login_id');
        const password = document.getElementById('password');

        function validateLoginId() {
            if (loginId.value.trim() === '') {
                showError(loginId, 'Please enter your email or username.');
                return false;
            }
            clearError(loginId);
            return true;
        }

        function validateLoginPassword() {
            if (password.value.trim() === '') {
                showError(password, 'Please enter your password.');
                return false;
            }
            clearError(password);
            return true;
        }

        ['input', 'blur'].forEach(evt => {
            loginId.addEventListener(evt, validateLoginId);
            password.addEventListener(evt, validateLoginPassword);
        });

        loginForm.addEventListener('submit', function(e) {
            const isLoginIdValid = validateLoginId();
            const isLoginPasswordValid = validateLoginPassword();

            if (!isLoginIdValid || !isLoginPasswordValid) {
                e.preventDefault();
            }
        });
    }

    // -----------------------------------------
    // 3. PROFILE UPDATE FORM
    // -----------------------------------------
    const profileUpdateForm = document.getElementById('profileUpdateForm');
    const toggleEditBtn = document.getElementById('toggleEditBtn');
    const editFormContainer = document.getElementById('editFormContainer');

    if (toggleEditBtn && editFormContainer) {
        toggleEditBtn.addEventListener('click', function() {
            if (editFormContainer.style.display === 'none') {
                editFormContainer.style.display = 'block';
                toggleEditBtn.textContent = 'Cancel Editing';
            } else {
                editFormContainer.style.display = 'none';
                toggleEditBtn.textContent = 'Edit Profile';
            }
        });
    }

    if (profileUpdateForm) {
        const updateName = document.getElementById('update_name');
        const updateEmail = document.getElementById('update_email');

        function validateUpdateName() {
            if (updateName.value.trim() === '') {
                showError(updateName, 'Name cannot be empty.');
                return false;
            }
            clearError(updateName);
            return true;
        }

        function validateUpdateEmail() {
            const emailRegex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
            if (updateEmail.value.trim() !== '' && !emailRegex.test(updateEmail.value.trim())) {
                showError(updateEmail, 'Please enter a valid email address.');
                return false;
            }
            if (updateEmail.value.trim() === '') {
                showError(updateEmail, 'Email cannot be empty.');
                return false;
            }
            clearError(updateEmail);
            return true;
        }

        ['input', 'blur'].forEach(evt => {
            updateName.addEventListener(evt, validateUpdateName);
            updateEmail.addEventListener(evt, validateUpdateEmail);
        });

        profileUpdateForm.addEventListener('submit', function(e) {
            const isNameValid = validateUpdateName();
            const isEmailValid = validateUpdateEmail();

            if (!isNameValid || !isEmailValid) {
                e.preventDefault(); 
            } else {
                const userConfirmed = confirm('Are you sure to update your information?');
                if (!userConfirmed) {
                    e.preventDefault();
                }
            }
        });
    }

    // -----------------------------------------
    // 4. DELETE POST CONFIRMATION
    // -----------------------------------------
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                // The confirm method shows a yes/no popup and gives you true or false[cite: 1]
                const userConfirmed = confirm('Are you sure you want to delete this post?');
                
                // If they click 'Cancel', prevent the link from opening delete.php
                if (!userConfirmed) {
                    e.preventDefault();
                }
            });
        });
    }
});



const commentBox = document.getElementById('comment');
    const charCounter = document.getElementById('char-counter');
    const postSubmitBtn = document.getElementById('postSubmitBtn');

    if (commentBox && charCounter && postSubmitBtn) {
        const MAX_CHARS = 280;

        commentBox.addEventListener('input', function() {
            const currentLength = commentBox.value.length;
            const remaining = MAX_CHARS - currentLength;

            if (remaining >= 0) {
                // User is under or exactly at the limit
                charCounter.textContent = `${currentLength} / ${MAX_CHARS} characters (${remaining} remaining)`;
                charCounter.style.color = '#737373'; // Default grey
                
                // Enable button and restore normal appearance
                postSubmitBtn.disabled = false;
                postSubmitBtn.style.opacity = '1';
                postSubmitBtn.style.cursor = 'pointer';
            } else {
                // User is over the limit
                // Math.abs() removes the negative sign for a cleaner display
                charCounter.textContent = `${currentLength} / ${MAX_CHARS} characters (${Math.abs(remaining)} over limit)`;
                charCounter.style.color = '#ed4956'; // Error red
                
                // Disable button and grey it out
                postSubmitBtn.disabled = true;
                postSubmitBtn.style.opacity = '0.5';
                postSubmitBtn.style.cursor = 'not-allowed';
            }
        });
    }