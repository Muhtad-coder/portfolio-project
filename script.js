console.log("=== SYSTEM BOOT INITIATED ===");

const studentName  = "Muhtad Haseeb Mustapha";
const studentTitle = "Backend Developer";

console.log("Name loaded:", studentName);
console.log("Title loaded:", studentTitle);


// Add or remove skills — they appear as glowing tags on the page automatically
const mySkills = [
    "Python",
    "FastAPI",
    "PostgreSQL",
    "Git & GitHub",
    "Problem Solving",
];

console.log("Skills array loaded. Total skills:", mySkills.length);


const hackerProfile = {
    alias: "DevCore",
    level: "3rd Year",
    isActive: true
};

console.log("Profile object:", hackerProfile);


console.log("Incoming connection from: " + hackerProfile.alias);
console.log("Second skill in my array: " + mySkills[1]);


console.log("=== SYSTEM BOOT COMPLETE ===");


/**
 * ⚠️ INSTRUCTOR'S CORE SYSTEM FUNCTION — DO NOT MODIFY
 *
 * Uses your variables above to inject data into the HTML automatically.
 */
document.addEventListener('DOMContentLoaded', () => {
    try {
        // Theme mode toggle with persistence
        const themeToggle = document.getElementById('theme-toggle');
        const savedTheme = localStorage.getItem('theme');

        if (savedTheme === 'light') {
            document.body.classList.add('light-mode');
        }

        const updateThemeButtonText = () => {
            if (!themeToggle) return;
            const isLightMode = document.body.classList.contains('light-mode');
            themeToggle.innerText = isLightMode ? 'Switch to Dark' : 'Switch to Light';
        };

        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.body.classList.toggle('light-mode');
                const isLightMode = document.body.classList.contains('light-mode');
                localStorage.setItem('theme', isLightMode ? 'light' : 'dark');
                updateThemeButtonText();
            });
        }
        updateThemeButtonText();

        // Name, title, and footer are rendered server-side by PHP (index.php $config).
        // To update them, edit the $config array at the top of index.php.

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

        // Load projects from the database via AJAX
        loadProjects();

    } catch (error) {
        console.warn("System Initialization Incomplete: Finish your JS tasks to unlock full portfolio features!", error);
    }
});


// Load projects from the database via AJAX
function loadProjects() {
    const container = document.getElementById('projects-container');
    if (!container) return;

    fetch('get_projects.php')
        .then(res => res.json())
        .then(projects => {
            if (!projects.length) {
                container.innerHTML = '<p style="color:var(--text-body-panel)">No projects found.</p>';
                return;
            }

            container.innerHTML = '';
            projects.forEach(p => {
                const statusClass = p.status === 'live' ? 'status-live' : p.status === 'wip' ? 'status-wip' : 'status-planned';
                const statusLabel = p.status === 'live' ? '● Live' : p.status === 'wip' ? '● In Progress' : '● Planned';

                // Build card with DOM methods (avoids XSS from unsanitized DB content)
                const card = document.createElement('div');
                card.className = 'project-card';

                const header = document.createElement('div');
                header.className = 'project-header';

                const status = document.createElement('span');
                status.className = 'project-status ' + statusClass;
                status.textContent = statusLabel;

                const lang = document.createElement('span');
                lang.className = 'project-lang';
                lang.textContent = p.language;

                header.appendChild(status);
                header.appendChild(lang);

                const title = document.createElement('h3');
                title.textContent = p.title;

                const desc = document.createElement('p');
                desc.textContent = p.description;

                const link = document.createElement('a');
                // Only allow http/https URLs — blocks javascript: and data: injection
                link.href = /^https?:\/\//i.test(p.link) ? p.link : '#';
                link.className = 'project-link';
                link.textContent = 'View Project →';

                card.appendChild(header);
                card.appendChild(title);
                card.appendChild(desc);
                card.appendChild(link);
                container.appendChild(card);
            });
        })
        .catch(() => {
            container.innerHTML = '<p style="color:#ff6b6b">Failed to load projects.</p>';
        });
}

/**
 * Contact form handler — validates, then sends via AJAX to contact.php
 */
function handleFormSubmit(event) {
    event.preventDefault();

    const name     = document.getElementById('fname').value.trim();
    const email    = document.getElementById('femail').value.trim();
    const reason   = document.getElementById('reason').value;
    const message  = document.getElementById('fmessage').value.trim();
    const feedback = document.getElementById('form-feedback');

    // Client-side validation
    if (!name || !email || !message) {
        feedback.style.color = '#ff6b6b';
        feedback.innerText = '⚠ Please fill in your name, email, and message.';
        return;
    }

    const formData = new FormData();
    formData.append('name',    name);
    formData.append('email',   email);
    formData.append('reason',  reason);
    formData.append('message', message);

    fetch('contact.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                feedback.style.color = 'var(--accent)';
                feedback.innerText = `✔ Message received, ${name}! I'll get back to you soon.`;
                document.getElementById('contactForm').reset();
            } else {
                feedback.style.color = '#ff6b6b';
                feedback.innerText = '⚠ ' + (data.error || 'Something went wrong.');
            }
        })
        .catch(() => {
            feedback.style.color = '#ff6b6b';
            feedback.innerText = '⚠ Could not send message. Please try again.';
        });
}
