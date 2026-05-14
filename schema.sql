-- Table: projects

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


-- Table: contacts

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


-- Table: admin_users

-- Stores admin credentials with bcrypt hashed passwords
CREATE TABLE IF NOT EXISTS admin_users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    last_login    TIMESTAMP    DEFAULT NULL
);


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



INSERT INTO admin_users (username, password_hash) VALUES
('admin', '$2y$10$JVQRbd7NFMGad3ZraG84a.0oLKRo9eLZMfUVKdb6yB0goWQJgznJG');


