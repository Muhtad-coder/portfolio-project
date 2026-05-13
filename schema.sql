-- ============================================================
-- Portfolio Database Schema (Updated)
-- ============================================================
-- This file sets up the complete database with tables for
-- projects, contact messages, and admin users.
-- Run this file once in phpMyAdmin or MySQL CLI

-- ============================================================
-- Table: projects
-- ============================================================
-- Stores all portfolio projects
CREATE TABLE IF NOT EXISTS projects (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(150)  NOT NULL,
    description TEXT          NOT NULL,
    language    VARCHAR(150)  NOT NULL,
    status      ENUM('live', 'wip', 'planned') NOT NULL DEFAULT 'planned',
    link        VARCHAR(255)  DEFAULT '#',
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- ============================================================
-- Table: contacts
-- ============================================================
-- Stores all contact form submissions
CREATE TABLE IF NOT EXISTS contacts (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    reason     VARCHAR(50)  DEFAULT '',
    message    TEXT         NOT NULL,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
);

-- ============================================================
-- Table: admin_users
-- ============================================================
-- Stores admin credentials with bcrypt hashed passwords
CREATE TABLE IF NOT EXISTS admin_users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    last_login    TIMESTAMP    DEFAULT NULL
);

-- ============================================================
-- SEED DATA: Sample Projects
-- ============================================================
-- These 3 projects will appear immediately on your portfolio
INSERT INTO projects (title, description, language, status, link) VALUES
(
    'Personal Portfolio',
    'My personal portfolio website built from scratch for the Internet & Web Programming course. Features animations, responsive layout, dark/light mode toggle, and dynamic JavaScript rendering with AJAX data loading.',
    'HTML / CSS / JavaScript',
    'live',
    '#'
),
(
    'RAG AI Assistant',
    'Retrieval-Augmented Generation (RAG) AI assistant combining information retrieval techniques with generative AI to provide context-aware, fact-based answers from a custom knowledge base. Built with modern FastAPI backend.',
    'Python / FastAPI / LLM',
    'wip',
    '#'
),
(
    'Email Automation System',
    'Advanced email automation system that streamlines receiving, analyzing, and forwarding emails with minimal human intervention. Features intelligent filtering, auto-categorization, and integration with multiple email providers.',
    'Python / FastAPI / Supabase',
    'wip',
    '#'
);

-- ============================================================
-- SEED DATA: Admin User
-- ============================================================
-- IMPORTANT: Replace the password hash below!
-- 
-- To generate a new bcrypt hash, run this command:
--    php -r "echo password_hash('your-strong-password-here', PASSWORD_BCRYPT);"
-- 
-- Then replace the placeholder hash below with your generated hash.
-- 
-- Example: If your password is "SecurePass123!", the command gives:
--    $2y$10$abcd1234efgh5678ijkl9012mnopqrst.uvwxyzABCDEFGHIJKL
-- 
-- Copy that entire hash and paste it below replacing REPLACEME_...

INSERT INTO admin_users (username, password_hash) VALUES
('admin', '$2y$10$JVQRbd7NFMGad3ZraG84a.0oLKRo9eLZMfUVKdb6yB0goWQJgznJG');

-- ============================================================
-- IMPORTANT SECURITY NOTES
-- ============================================================
-- ⚠️  Before deploying publicly:
-- 
-- 1. REPLACE THE ADMIN PASSWORD HASH
--    - Use the PHP command above to generate a real bcrypt hash
--    - Never commit a default password to version control
--    - NEVER store plaintext passwords in the database
-- 
-- 2. ADD THIS TO .gitignore:
--    db.php
--    .env
--    *.log
-- 
-- 3. SECURE YOUR DATABASE CREDENTIALS
--    - Don't use 'root' / 'root' in production
--    - Use environment variables for credentials
--    - Restrict database user permissions
-- 
-- 4. ENABLE HTTPS
--    - Always use SSL/TLS in production
--    - Redirect HTTP to HTTPS
--    - Set secure cookie flags
-- 
-- 5. IMPLEMENT RATE LIMITING
--    - Prevent brute-force login attempts
--    - Rate limit contact form submissions
--    - Implement CSRF protection
-- 
-- 6. REGULAR BACKUPS
--    - Schedule automatic database backups
--    - Test restore procedures
--    - Keep backup files secure
-- ============================================================
