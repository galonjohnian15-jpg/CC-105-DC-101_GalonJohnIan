function validateForm() {
    // Get form values and trim whitespace
    const first = document.forms["studentForm"]["first_name"].value.trim();
    const last = document.forms["studentForm"]["last_name"].value.trim();
    const email = document.forms["studentForm"]["email"].value.trim();
    const course = document.forms["studentForm"]["course_id"].value;
    
    // Check if fields are empty
    if (first === "" || last === "" || email === "") {
        alert("Please fill in all required fields!");
        return false;
    }
    
    // Validate name length
    if (first.length > 50 || last.length > 50) {
        alert("Names must be less than 50 characters!");
        return false;
    }
    
    // Validate name format (only letters, spaces, hyphens, apostrophes)
    const namePattern = /^[a-zA-Z\s'-]+$/;
    if (!namePattern.test(first)) {
        alert("First name can only contain letters, spaces, hyphens, and apostrophes!");
        return false;
    }
    if (!namePattern.test(last)) {
        alert("Last name can only contain letters, spaces, hyphens, and apostrophes!");
        return false;
    }
    
    // Validate email format
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address!");
        return false;
    }
    
    // Check if course is selected
    if (course === "" || course === null) {
        alert("Please select a course!");
        return false;
    }
    
    return true;
}

// Add real-time validation feedback
document.addEventListener('DOMContentLoaded', function() {
    const form = document.forms["studentForm"];
    
    if (form) {
        // Email validation on blur
        const emailInput = form["email"];
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailPattern.test(this.value)) {
                    this.style.borderColor = '#e74c3c';
                } else {
                    this.style.borderColor = '#ddd';
                }
            });
        }
        
        // Name validation on blur
        const firstNameInput = form["first_name"];
        const lastNameInput = form["last_name"];
        const namePattern = /^[a-zA-Z\s'-]+$/;
        
        if (firstNameInput) {
            firstNameInput.addEventListener('blur', function() {
                if (this.value && !namePattern.test(this.value)) {
                    this.style.borderColor = '#e74c3c';
                } else {
                    this.style.borderColor = '#ddd';
                }
            });
        }
        
        if (lastNameInput) {
            lastNameInput.addEventListener('blur', function() {
                if (this.value && !namePattern.test(this.value)) {
                    this.style.borderColor = '#e74c3c';
                } else {
                    this.style.borderColor = '#ddd';
                }
            });
        }
    }
});