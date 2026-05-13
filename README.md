# Personal Portfolio Website
**Muhtad Haseeb Mustapha** — Software Engineering Student, Halic University

---

## Project Overview

This is a personal portfolio website built from scratch for the Internet & Web Programming course. It serves as a live showcase of my projects, skills, and background, with a fully functional admin panel to manage content dynamically — no manual code edits required to add or update projects.

The site is deployed and accessible at:
**https://muhtad-portfolio.infinityfreeapp.com**

---

## Technologies Used

| Layer | Technology |
|---|---|
| Structure | PHP 8 |
| Styling | CSS3 (custom, no frameworks) |
| Interactivity | Vanilla JavaScript (ES6+) |
| Backend | PHP with MySQLi |
| Database | MySQL |
| Local Development | MAMP (Apache + PHP + MySQL) |
| Production Hosting | InfinityFree |

No external CSS frameworks (e.g. Bootstrap) or JavaScript libraries were used — everything was written by hand.

---

## Features

- **Dark / Light mode toggle** — persists across page reloads using localStorage
- **Animated UI** — glowing orb background, typed console boot sequence, skill bar animations
- **Dynamic project loading** — projects are fetched from the database via AJAX and rendered on the page without a full reload
- **Contact form** — submissions are saved to the database and validated on both client and server side
- **Admin dashboard** — password-protected panel to add, edit, and delete projects
- **Brute force protection** — login is locked for 15 minutes after 5 failed attempts
- **Responsive layout** — works on desktop and mobile

---

## How It Was Built

### 1. Frontend

The frontend is a single-page layout built in `index.php`. Personal details (name, bio, links, education) are defined in a PHP `$config` array at the top of the file and injected into the HTML — making it easy to update without touching the markup.

Skills are rendered dynamically from a JavaScript array in `script.js` using DOM manipulation, with animated progress bars triggered on scroll using the `IntersectionObserver` API.

A console boot sequence runs on page load, simulating a system startup — implemented purely with JavaScript `setTimeout` calls.

### 2. Backend & Database

The database has three tables:

- `projects` — stores portfolio projects (title, description, language, status, link)
- `contacts` — stores contact form submissions
- `admin_users` — stores admin credentials with bcrypt-hashed passwords

Projects are served via `get_projects.php`, which returns a JSON response fetched by the frontend using the `fetch()` API. This keeps the page fast and avoids full reloads.

The contact form posts to `contact.php`, which validates input server-side (including email format with `filter_var`) and inserts the data using a prepared statement to prevent SQL injection.

### 3. Admin Panel

The admin panel (`admin_login.php`, `admin_dashboard.php`) is protected by PHP session-based authentication. Passwords are verified using `password_verify()` against a bcrypt hash stored in the database. A rate limiter blocks the login form for 15 minutes after 5 consecutive failed attempts.

From the dashboard, the admin can add, edit, and delete projects. All admin API actions are handled through `admin_api.php` using POST requests.

### 4. Security Measures

- All database queries use prepared statements (`bind_param`) — no raw user input in SQL
- Passwords stored as bcrypt hashes — never in plaintext
- `config.php` (database credentials) is excluded from version control via `.gitignore`
- Direct browser access to `config.php` is blocked via `.htaccess`
- Output is escaped with `htmlspecialchars()` where displayed in HTML

### 5. Deployment

The site is hosted on InfinityFree free shared hosting. Files were uploaded via FTP and the database was imported through phpMyAdmin. The database credentials are stored in a separate `config.php` file not committed to the repository.

---

## File Structure

```
Portfolio/
├── index.php            # Main page + PHP config
├── style.css            # All styles (dark/light mode)
├── script.js            # Skills, project loading, contact form
├── db.php               # Database connection
├── config.php           # Database credentials (not in git)
├── get_projects.php     # AJAX endpoint — returns projects as JSON
├── contact.php          # AJAX endpoint — saves contact form to DB
├── admin_login.php      # Admin login page + brute force limiter
├── admin_dashboard.php  # Admin project management UI
├── admin_api.php        # Admin CRUD actions
├── admin_logout.php     # Session destroy
├── admin_style.css      # Admin panel styles
├── schema.sql           # Database schema + seed data
└── profile.png          # Profile photo
```
