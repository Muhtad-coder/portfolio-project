<?php
session_start();
date_default_timezone_set('Europe/Istanbul');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Session timeout: 30 minutes of inactivity
$timeout = 1800; 
if (isset($_SESSION['login_time']) && time() - $_SESSION['login_time'] > $timeout) {
    session_destroy();
    header('Location: admin_login.php?timeout=1');
    exit;
}
$_SESSION['login_time'] = time(); // Reset timeout on each page load

require_once 'db.php';

$admin_username = htmlspecialchars($_SESSION['admin_username']);

// Fetch statistics for dashboard
$conn = get_db();

// Count projects
$projects_count = $conn->query('SELECT COUNT(*) as count FROM projects')->fetch_assoc()['count'];

// Count unread contacts
$contacts_count = $conn->query('SELECT COUNT(*) as count FROM contacts')->fetch_assoc()['count'];

// Fetch recent contacts
$recent_contacts = $conn->query('SELECT id, name, email, reason, message, created_at FROM contacts ORDER BY created_at DESC LIMIT 5');

// Fetch all projects
$projects = $conn->query('SELECT id, title, description, language, status, link FROM projects ORDER BY created_at DESC');

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Portfolio</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>📊 Control Panel</h2>
                <p class="user-info">Logged in as: <strong><?= $admin_username ?></strong></p>
            </div>

            <nav class="admin-nav">
                <a href="#dashboard" class="nav-link active" onclick="switchTab(event, 'dashboard')">
                    <span class="icon">📈</span> Dashboard
                </a>
                <a href="#projects" class="nav-link" onclick="switchTab(event, 'projects')">
                    <span class="icon">💼</span> Projects (<?= $projects_count ?>)
                </a>
                <a href="#contacts" class="nav-link" onclick="switchTab(event, 'contacts')">
                    <span class="icon">💬</span> Messages (<?= $contacts_count ?>)
                </a>
                <a href="#settings" class="nav-link" onclick="switchTab(event, 'settings')">
                    <span class="icon">⚙️</span> Settings
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="admin_logout.php" class="logout-btn">🚪 Logout</a>
                <a href="index.php" class="portfolio-btn">👁️ View Portfolio</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <!-- DASHBOARD TAB -->
            <section id="dashboard" class="tab-content active">
                <div class="content-header">
                    <h1>Welcome back, <?= $admin_username ?>! 👋</h1>
                    <p class="subtitle">Here's your portfolio overview</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">💼</div>
                        <div class="stat-info">
                            <h3><?= $projects_count ?></h3>
                            <p>Projects</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">💬</div>
                        <div class="stat-info">
                            <h3><?= $contacts_count ?></h3>
                            <p>Messages</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🕐</div>
                        <div class="stat-info">
                            <h3><?= date('H:i') ?></h3>
                            <p>Current Time</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">✅</div>
                        <div class="stat-info">
                            <h3>Active</h3>
                            <p>Session Status</p>
                        </div>
                    </div>
                </div>

                <div class="recent-section">
                    <h2>📬 Recent Messages</h2>
                    <div class="messages-list">
                        <?php if ($recent_contacts && $recent_contacts->num_rows > 0): ?>
                            <?php while ($contact = $recent_contacts->fetch_assoc()): ?>
                                <div class="message-item">
                                    <div class="message-header">
                                        <strong><?= htmlspecialchars($contact['name']) ?></strong>
                                        <span class="message-date"><?= date('M d, Y', strtotime($contact['created_at'])) ?></span>
                                    </div>
                                    <p class="message-email">📧 <?= htmlspecialchars($contact['email']) ?></p>
                                    <?php if ($contact['reason']): ?>
                                        <p class="message-reason"><strong>Reason:</strong> <?= htmlspecialchars($contact['reason']) ?></p>
                                    <?php endif; ?>
                                    <p class="message-text"><?= htmlspecialchars(substr($contact['message'], 0, 100)) ?>...</p>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="empty-state">No messages yet. Check back later!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- PROJECTS TAB -->
            <section id="projects" class="tab-content">
                <div class="content-header">
                    <h1>💼 Manage Projects</h1>
                    <button class="btn-primary" onclick="toggleForm('addProjectForm')">+ Add New Project</button>
                </div>

                <!-- Add/Edit Project Form -->
                <div id="addProjectForm" class="form-panel" style="display:none;">
                    <h3>Add New Project</h3>
                    <form id="projectForm" onsubmit="handleProjectSubmit(event)">
                        <input type="hidden" id="projectId" name="id">
                        
                        <div class="form-group">
                            <label for="projectTitle">Project Title *</label>
                            <input type="text" id="projectTitle" name="title" required placeholder="e.g., RAG AI Assistant">
                        </div>

                        <div class="form-group">
                            <label for="projectDescription">Description *</label>
                            <textarea id="projectDescription" name="description" rows="5" required placeholder="Describe your project..."></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="projectLanguage">Technology Stack *</label>
                                <input type="text" id="projectLanguage" name="language" required placeholder="e.g., Python / FastAPI">
                            </div>

                            <div class="form-group">
                                <label for="projectStatus">Status *</label>
                                <select id="projectStatus" name="status" required>
                                    <option value="">— Select Status —</option>
                                    <option value="live">● Live</option>
                                    <option value="wip">● In Progress</option>
                                    <option value="planned">● Planned</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="projectLink">Project Link (Optional)</label>
                            <input type="url" id="projectLink" name="link" placeholder="https://example.com">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Save Project</button>
                            <button type="button" class="btn-secondary" onclick="toggleForm('addProjectForm')">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- Projects List -->
                <div class="projects-list">
                    <?php if ($projects && $projects->num_rows > 0): ?>
                        <?php while ($project = $projects->fetch_assoc()): ?>
                            <div class="project-item">
                                <div class="project-header">
                                    <h3><?= htmlspecialchars($project['title']) ?></h3>
                                    <span class="project-status status-<?= $project['status'] ?>">
                                        <?= ucfirst($project['status']) ?>
                                    </span>
                                </div>
                                <p class="project-description"><?= htmlspecialchars($project['description']) ?></p>
                                <p class="project-language">🛠️ <?= htmlspecialchars($project['language']) ?></p>
                                <div class="project-actions">
                                    <button class="btn-edit" onclick="editProject(<?= $project['id'] ?>)">✏️ Edit</button>
                                    <button class="btn-delete" onclick="deleteProject(<?= $project['id'] ?>)">🗑️ Delete</button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="empty-state">No projects yet. Create one to get started!</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- CONTACTS TAB -->
            <section id="contacts" class="tab-content">
                <div class="content-header">
                    <h1>💬 Contact Messages</h1>
                </div>

                <div class="contacts-list">
                    <?php 
                    $conn = get_db();
                    $all_contacts = $conn->query('SELECT id, name, email, reason, message, created_at FROM contacts ORDER BY created_at DESC');
                    
                    if ($all_contacts && $all_contacts->num_rows > 0):
                        while ($contact = $all_contacts->fetch_assoc()): 
                    ?>
                        <div class="contact-item">
                            <div class="contact-header">
                                <h3><?= htmlspecialchars($contact['name']) ?></h3>
                                <span class="contact-date"><?= date('M d, Y H:i', strtotime($contact['created_at'])) ?></span>
                            </div>
                            <p class="contact-email">📧 <a href="mailto:<?= htmlspecialchars($contact['email']) ?>"><?= htmlspecialchars($contact['email']) ?></a></p>
                            <?php if ($contact['reason']): ?>
                                <p class="contact-reason"><strong>Reason:</strong> <?= htmlspecialchars($contact['reason']) ?></p>
                            <?php endif; ?>
                            <div class="contact-message">
                                <strong>Message:</strong>
                                <p><?= nl2br(htmlspecialchars($contact['message'])) ?></p>
                            </div>
                            <div class="contact-actions">
                                <button class="btn-delete" onclick="deleteContact(<?= $contact['id'] ?>)">🗑️ Delete</button>
                                <button class="btn-secondary" onclick="replyContact('<?= htmlspecialchars($contact['email']) ?>')">✉️ Reply</button>
                            </div>
                        </div>
                    <?php 
                        endwhile;
                    else: 
                    ?>
                        <p class="empty-state">No messages yet. When visitors contact you, they'll appear here.</p>
                    <?php 
                    endif;
                    $conn->close();
                    ?>
                </div>
            </section>

            <!-- SETTINGS TAB -->
            <section id="settings" class="tab-content">
                <div class="content-header">
                    <h1>⚙️ Settings</h1>
                </div>

                <div class="settings-panel">
                    <div class="setting-item">
                        <h3>Change Admin Password</h3>
                        <p>Update your admin account password for security</p>
                        <button class="btn-secondary" onclick="toggleForm('changePasswordForm')">Change Password</button>
                        
                        <div id="changePasswordForm" class="form-panel" style="display:none; margin-top:1rem;">
                            <form onsubmit="handlePasswordChange(event)">
                                <div class="form-group">
                                    <label for="currentPassword">Current Password</label>
                                    <input type="password" id="currentPassword" name="current_password" required>
                                </div>
                                <div class="form-group">
                                    <label for="newPassword">New Password</label>
                                    <input type="password" id="newPassword" name="new_password" required minlength="8">
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">Confirm Password</label>
                                    <input type="password" id="confirmPassword" name="confirm_password" required minlength="8">
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn-primary">Update Password</button>
                                    <button type="button" class="btn-secondary" onclick="toggleForm('changePasswordForm')">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <hr>

                    <div class="setting-item danger">
                        <h3>🚪 Logout</h3>
                        <p>End your admin session</p>
                        <a href="admin_logout.php" class="btn-delete">Logout</a>
                    </div>

                    <hr>

                    <div class="setting-item">
                        <h3>📋 Session Info</h3>
                        <p><strong>Username:</strong> <?= $admin_username ?></p>
                        <p><strong>Session ID:</strong> <code><?= session_id() ?></code></p>
                        <p><strong>Logged in since:</strong> <?= date('M d, Y H:i:s', $_SESSION['login_time']) ?></p>
                        <p style="color: var(--text-body-panel); font-size: 0.9rem;">💡 Your session will expire after 30 minutes of inactivity for security.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Tab switching
        function switchTab(event, tabName) {
            event.preventDefault();
            
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all nav links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            event.target.closest('.nav-link').classList.add('active');
        }

        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';

            // Reset form if showing
            if (form.style.display !== 'none' && form.querySelector('form')) {
                form.querySelector('form').reset();
                if (formId === 'addProjectForm') {
                    document.getElementById('projectId').value = '';
                }
            }
        }

        // Handle project form submission
        async function handleProjectSubmit(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const projectId = document.getElementById('projectId').value;
            const action = projectId ? 'update' : 'create';
            
            formData.append('action', action);
            
            try {
                const response = await fetch('admin_api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    alert('✅ Project saved successfully!');
                    location.reload();
                } else {
                    alert('❌ Error: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                alert('❌ Failed to save project: ' + error.message);
            }
        }

        // Edit project
        async function editProject(projectId) {
            try {
                const response = await fetch('admin_api.php?action=get&id=' + projectId);
                const data = await response.json();
                
                if (data.success) {
                    const project = data.project;
                    document.getElementById('projectId').value = project.id;
                    document.getElementById('projectTitle').value = project.title;
                    document.getElementById('projectDescription').value = project.description;
                    document.getElementById('projectLanguage').value = project.language;
                    document.getElementById('projectStatus').value = project.status;
                    document.getElementById('projectLink').value = project.link || '';
                    
                    // Scroll to form
                    document.getElementById('addProjectForm').style.display = 'block';
                    document.getElementById('projectTitle').focus();
                } else {
                    alert('❌ Failed to load project');
                }
            } catch (error) {
                alert('❌ Error: ' + error.message);
            }
        }

        // Delete project
        async function deleteProject(projectId) {
            if (!confirm('Are you sure you want to delete this project?')) return;
            
            try {
                const response = await fetch('admin_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=delete&id=' + projectId
                });
                const data = await response.json();
                
                if (data.success) {
                    alert('✅ Project deleted!');
                    location.reload();
                } else {
                    alert('❌ Error: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                alert('❌ Failed to delete: ' + error.message);
            }
        }

        // Delete contact
        async function deleteContact(contactId) {
            if (!confirm('Are you sure you want to delete this message?')) return;
            
            try {
                const response = await fetch('admin_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=delete_contact&id=' + contactId
                });
                const data = await response.json();
                
                if (data.success) {
                    alert('✅ Message deleted!');
                    location.reload();
                } else {
                    alert('❌ Error: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                alert('❌ Failed to delete: ' + error.message);
            }
        }

        // Reply to contact (open mail client)
        function replyContact(email) {
            window.location.href = 'mailto:' + email + '?subject=Re: Your Portfolio Message';
        }

        // Handle password change
        async function handlePasswordChange(event) {
            event.preventDefault();
            
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword !== confirmPassword) {
                alert('❌ Passwords do not match!');
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'change_password');
            formData.append('current_password', document.getElementById('currentPassword').value);
            formData.append('new_password', newPassword);
            
            try {
                const response = await fetch('admin_api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    alert('✅ Password changed successfully!');
                    event.target.reset();
                    toggleForm('changePasswordForm');
                } else {
                    alert('❌ Error: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                alert('❌ Failed: ' + error.message);
            }
        }
    </script>
</body>
</html>
