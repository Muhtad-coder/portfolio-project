<?php

$config = [

    // --- Identity ---
    'name'       => 'Muhtad Haseeb Mustapha',  
    'title'      => 'Backend Developer',        
    'bio'        => 'Welcome to my digital space - where technology, creativity, and problem-solving come together to build meaningful experiences.',

    // --- Profile image ---
   
    'avatar'     => 'profile.png',

    // --- Social / contact links ---
    'email'      => 'salehmuhtad@gmail.com',
    'github'     => 'https://github.com/Muhtad-coder',
    'linkedin'   => 'https://www.linkedin.com/in/muhtad-mustapha-aa0375222/',

    // --- About section ---
    'about_bio'  => 'Hey! I\'m a Software Engineering student passionate about building and innovating impactful solutions. I love turning ideas into reality through clean code and creative design. Currently studying and leveling up my skills every day.',

    // --- About table rows: [ 'Category', 'Details' ] ---
    'about_table' => [
        [ '🎓 Education',   'Halic University'          ],
        [ '📚 Department',  'Software Engineering'      ],
        [ '🗓️ Year',        '3rd Year (2023 - 2026)'    ],
        [ '📍 Location',    'Istanbul / Turkey'         ],
        [ '🌐 Languages',   'English'                   ],
        [ '🎯 Goal',        'Full Stack Developer'      ],
    ],

    // --- Footer ---
    'footer_stack' => 'HTML · CSS · JS · PHP · MySQL',
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($config['name']) ?> — Portfolio</title>
    <link rel="stylesheet" href="style.css?v=readability-2026">
</head>

<body>
    <div class="glow-orb"></div>

    <nav class="main-nav">
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#skills">Skills</a></li>
            <li><a href="#projects">Projects</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <button id="theme-toggle" class="theme-toggle" type="button" aria-label="Toggle light and dark mode">
            Switch to Light
        </button>
    </nav>

    <main>

        <!-- HOME SECTION -->
        <section id="home" class="glass-panel">
            <h1 id="hero-name"><?= htmlspecialchars($config['name']) ?></h1>
            <p id="hero-title" class="subtitle"><?= htmlspecialchars($config['title']) ?></p>

            <div class="profile-container">
                <img
                    src="<?= htmlspecialchars($config['avatar']) ?>"
                    alt="Profile photo of <?= htmlspecialchars($config['name']) ?>"
                    class="profile-pic"
                    onerror="this.src='https://api.dicebear.com/7.x/shapes/svg?seed=devcore&backgroundColor=030014&shapeColor=00f0ff'"
                >
            </div>

            <p class="hero-bio"><?= htmlspecialchars($config['bio']) ?></p>

            <div class="hero-links">
                <a href="<?= htmlspecialchars($config['github']) ?>" target="_blank" class="hero-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
                    GitHub
                </a>
                <a href="<?= htmlspecialchars($config['linkedin']) ?>" target="_blank" class="hero-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    LinkedIn
                </a>
            </div>
        </section>

        <!-- ABOUT SECTION -->
        <section id="about" class="glass-panel">
            <h2>System Specifications (About Me)</h2>

            <p class="about-bio"><?= htmlspecialchars($config['about_bio']) ?></p>

            <table>
                <tr>
                    <th>Category</th>
                    <th>Details</th>
                </tr>
                <?php foreach ($config['about_table'] as [$category, $detail]): ?>
                <tr>
                    <td><?= htmlspecialchars($category) ?></td>
                    <td><?= htmlspecialchars($detail) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <!-- SKILLS SECTION (populated by script.js) -->
        <section id="skills" class="glass-panel">
            <h2>Neural Network (Skills)</h2>
            <div id="skills-container" class="skills-grid">
                <!-- Injected automatically by script.js from my mySkills array -->
            </div>
        </section>

        <!-- PROJECTS SECTION -->
        <section id="projects" class="glass-panel">
            <h2>Active Missions (Projects)</h2>
            <div class="projects-grid" id="projects-container">
                <p style="color:var(--text-body-panel)">Loading projects...</p>
            </div>
        </section>

        <!-- CONTACT SECTION -->
        <section id="contact" class="glass-panel">
            <h2>Establish Connection (Contact)</h2>

            <div class="contact-layout">
                <div class="contact-info">
                    <p class="contact-intro">Have a question, collaboration idea, or just want to say hello? Fill out the form or reach out directly.</p>
                    <div class="contact-links">
                        <a href="mailto:<?= htmlspecialchars($config['email']) ?>" class="contact-link">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <?= htmlspecialchars($config['email']) ?>
                        </a>
                        <a href="<?= htmlspecialchars($config['github']) ?>" target="_blank" class="contact-link">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
                            <?= htmlspecialchars($config['github']) ?>
                        </a>
                        <a href="<?= htmlspecialchars($config['linkedin']) ?>" target="_blank" class="contact-link">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            <?= htmlspecialchars($config['linkedin']) ?>
                        </a>
                    </div>
                </div>

                <form id="contactForm" method="POST" action="contact.php" onsubmit="handleFormSubmit(event)">
                    <div class="form-group">
                        <label for="fname">Name</label>
                        <input type="text" id="fname" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <label for="femail">Email</label>
                        <input type="email" id="femail" name="email" placeholder="your@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason for Contact</label>
                        <select id="reason" name="reason">
                            <option value="">— Select a reason —</option>
                            <option value="collaboration">Collaboration</option>
                            <option value="job">Job / Internship</option>
                            <option value="question">General Question</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fmessage">Message</label>
                        <textarea id="fmessage" name="message" rows="4" placeholder="Write your message here..." required></textarea>
                    </div>
                    <button type="submit">Send Message</button>
                    <p id="form-feedback" class="form-feedback"></p>
                </form>
            </div>
        </section>

    </main>

    <footer>
        <p>Built with <span class="highlight"><?= htmlspecialchars($config['footer_stack']) ?></span> &nbsp;·&nbsp; <span id="footer-name"><?= htmlspecialchars($config['name']) ?></span></p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
