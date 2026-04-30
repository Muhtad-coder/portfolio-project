/**
 * 🚀 WEEK 5: THE MATRIX OF JAVASCRIPT!
 *
 * HTML is the skeleton. CSS is the skin and clothes.
 * JavaScript is the BRAIN. It makes the web page alive!
 */

console.log("=== SYSTEM BOOT INITIATED ===");

// ==========================================
// TASK 7: Variables & Data Types
// ==========================================
const studentName  = "Muhtad Haseeb Mustapha";           // 👈 Replace with your real name
const studentTitle = "Backend Developer"; // 👈 Replace with your dream job

console.log("Name loaded:", studentName);
console.log("Title loaded:", studentTitle);


// ==========================================
// TASK 8: Arrays (Lists of information)
// ==========================================
// Add or remove skills — they appear as glowing tags on the page automatically!
const mySkills = [
    "Python",
    "FastAPI",
    "PostgreSQL",
    "Git & GitHub",
    "Problem Solving",
];

console.log("Skills array loaded. Total skills:", mySkills.length);


// ==========================================
// TASK 9: Objects (Complex data structures)
// ==========================================
const hackerProfile = {
    alias: "DevCore",           // 👈 Your nickname / handle
    level: "3rd Year",                   // 👈 Your academic year or age
    isActive: true
};

console.log("Profile object:", hackerProfile);


// ==========================================
// TASK 10: Accessing Object Properties (Bug Fix)
// ==========================================
// Fixed: using dot notation to access the 'alias' property
console.log("Incoming connection from: " + hackerProfile.alias);


// ==========================================
// TASK 11: Accessing Array Elements
// ==========================================
// We want to access the 2nd skill from your 'mySkills' array and log it.
// Remember, arrays are ZERO-indexed (the first element is at index 0).
// Write a console.log statement extracting and printing the 2nd element of 'mySkills'.
//
// YOUR CODE HERE:
console.log("Second skill in my array: " + mySkills[1]);


console.log("=== SYSTEM BOOT COMPLETE ===");


/**
 * ⚠️ INSTRUCTOR'S CORE SYSTEM FUNCTION — DO NOT MODIFY
 *
 * Uses your variables above to inject data into the HTML automatically.
 */
document.addEventListener('DOMContentLoaded', () => {
    try {
        // Inject Name and Title into the hero section
        if (typeof studentName !== 'undefined') {
            document.getElementById('hero-name').innerText = studentName;
        }
        if (typeof studentTitle !== 'undefined') {
            document.getElementById('hero-title').innerText = studentTitle;
        }

        // Also update the footer name to match
        const footerName = document.getElementById('footer-name');
        if (footerName && typeof studentName !== 'undefined') {
            footerName.innerText = studentName;
        }

        // Inject Skills Array dynamically as glowing tags
        if (typeof mySkills !== 'undefined' && Array.isArray(mySkills)) {
            const skillsContainer = document.getElementById('skills-container');
            mySkills.forEach((skill, index) => {
                const span = document.createElement('span');
                span.className = 'skill-tag';
                span.innerText = skill;
                // Stagger the animation so tags appear one by one
                span.style.animationDelay = (index * 0.1) + 's';
                skillsContainer.appendChild(span);
            });
        }

    } catch (error) {
        console.warn("System Initialization Incomplete: Finish your JS tasks to unlock full portfolio features!", error);
    }
});


/**
 * Contact form handler — gives user feedback without page reload.
 */
function handleFormSubmit(event) {
    event.preventDefault();

    const name     = document.getElementById('fname').value.trim();
    const email    = document.getElementById('femail').value.trim();
    const reason   = document.getElementById('reason').value;
    const feedback = document.getElementById('form-feedback');

    if (!name || !email) {
        feedback.style.color = '#ff6b6b';
        feedback.innerText = '⚠ Please fill in your name and email.';
        return;
    }

    feedback.style.color = 'var(--accent)';
    feedback.innerText = `✔ Message received, ${name}! I'll get back to you soon.`;
    document.getElementById('contactForm').reset();

    console.log("New contact form submission:", { name, email, reason });
}
