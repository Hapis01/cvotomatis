-- Phase 2: Database Schema for CV & Job Application Document Builder
-- Database: cv_builder

CREATE DATABASE IF NOT EXISTS cv_builder;
USE cv_builder;

-- 1. Users Table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. Candidate Profiles Table
CREATE TABLE candidate_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    professional_title VARCHAR(255),
    address TEXT,
    city VARCHAR(100),
    province VARCHAR(100),
    phone VARCHAR(50),
    email VARCHAR(255),
    portfolio_url VARCHAR(255),
    linkedin_url VARCHAR(255),
    github_url VARCHAR(255),
    professional_summary TEXT,
    photo_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. Educations
CREATE TABLE educations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    profile_id BIGINT UNSIGNED NOT NULL,
    institution VARCHAR(255) NOT NULL,
    degree VARCHAR(100),
    major VARCHAR(100),
    city VARCHAR(100),
    start_date DATE,
    end_date DATE,
    gpa VARCHAR(50),
    accreditation VARCHAR(50),
    description TEXT,
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. Experiences
CREATE TABLE experiences (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    profile_id BIGINT UNSIGNED NOT NULL,
    job_title VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    department VARCHAR(100),
    location VARCHAR(100),
    employment_type VARCHAR(50),
    start_date DATE,
    end_date DATE,
    current_job BOOLEAN DEFAULT FALSE,
    description TEXT,
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 5. Experience Bullets
CREATE TABLE experience_bullets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    experience_id BIGINT UNSIGNED NOT NULL,
    bullet_text TEXT NOT NULL,
    order_num INT DEFAULT 0,
    FOREIGN KEY (experience_id) REFERENCES experiences(id) ON DELETE CASCADE
);

-- 6. Organizations
CREATE TABLE organizations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    profile_id BIGINT UNSIGNED NOT NULL,
    organization_name VARCHAR(255) NOT NULL,
    position VARCHAR(100),
    division VARCHAR(100),
    location VARCHAR(100),
    start_date DATE,
    end_date DATE,
    description TEXT,
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (profile_id) REFERENCES candidate_profiles(id) ON DELETE CASCADE
);

-- 7. Organization Bullets
CREATE TABLE organization_bullets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    organization_id BIGINT UNSIGNED NOT NULL,
    bullet_text TEXT NOT NULL,
    order_num INT DEFAULT 0,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
);

-- 8. Projects
CREATE TABLE projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    profile_id BIGINT UNSIGNED NOT NULL,
    project_name VARCHAR(255) NOT NULL,
    role VARCHAR(100),
    organization_company VARCHAR(255),
    start_date DATE,
    end_date DATE,
    project_url VARCHAR(255),
    description TEXT,
    technologies VARCHAR(255),
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (profile_id) REFERENCES candidate_profiles(id) ON DELETE CASCADE
);

-- 9. Project Bullets
CREATE TABLE project_bullets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    bullet_text TEXT NOT NULL,
    order_num INT DEFAULT 0,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

-- 10. Certifications
CREATE TABLE certifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    profile_id BIGINT UNSIGNED NOT NULL,
    certification_name VARCHAR(255) NOT NULL,
    issuer VARCHAR(255),
    issue_date DATE,
    expiration_date DATE,
    credential_id VARCHAR(100),
    credential_url VARCHAR(255),
    description TEXT,
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (profile_id) REFERENCES candidate_profiles(id) ON DELETE CASCADE
);

-- 11. Awards
CREATE TABLE awards (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    profile_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    issuer VARCHAR(255),
    date_awarded DATE,
    description TEXT,
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (profile_id) REFERENCES candidate_profiles(id) ON DELETE CASCADE
);

-- 12. Skill Categories
CREATE TABLE skill_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    profile_id BIGINT UNSIGNED NOT NULL,
    category_name VARCHAR(100) NOT NULL,
    order_num INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (profile_id) REFERENCES candidate_profiles(id) ON DELETE CASCADE
);

-- 13. Skills
CREATE TABLE skills (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    proficiency VARCHAR(50),
    order_num INT DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES skill_categories(id) ON DELETE CASCADE
);

-- 14. Languages
CREATE TABLE languages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    profile_id BIGINT UNSIGNED NOT NULL,
    language_name VARCHAR(100) NOT NULL,
    proficiency VARCHAR(50),
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (profile_id) REFERENCES candidate_profiles(id) ON DELETE CASCADE
);

-- 15. Custom Sections
CREATE TABLE custom_sections (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    profile_id BIGINT UNSIGNED NOT NULL,
    section_name VARCHAR(100) NOT NULL,
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (profile_id) REFERENCES candidate_profiles(id) ON DELETE CASCADE
);

-- 16. Custom Section Items
CREATE TABLE custom_section_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section_id BIGINT UNSIGNED NOT NULL,
    item_title VARCHAR(255) NOT NULL,
    description TEXT,
    order_num INT DEFAULT 0,
    FOREIGN KEY (section_id) REFERENCES custom_sections(id) ON DELETE CASCADE
);

-- 17. Job Applications
CREATE TABLE job_applications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    company_address TEXT,
    position VARCHAR(255) NOT NULL,
    department VARCHAR(100),
    recruiter_name VARCHAR(100),
    recruiter_title VARCHAR(100),
    application_date DATE,
    job_description TEXT,
    job_requirements TEXT,
    custom_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 18. CV Templates
CREATE TABLE cv_templates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 19. Letter Templates
CREATE TABLE letter_templates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 20. CV Documents
CREATE TABLE cv_documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    template_id INT UNSIGNED,
    document_name VARCHAR(255),
    configuration_json JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES job_applications(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES cv_templates(id) ON DELETE SET NULL
);

-- 21. Cover Letters
CREATE TABLE cover_letters (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    template_id INT UNSIGNED,
    document_name VARCHAR(255),
    letter_content TEXT,
    configuration_json JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES job_applications(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES letter_templates(id) ON DELETE SET NULL
);

-- 22. Signatures
CREATE TABLE signatures (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 23. Letterheads
CREATE TABLE letterheads (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    alignment VARCHAR(20) DEFAULT 'center',
    width INT DEFAULT 100,
    height INT DEFAULT 100,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 24. Settings
CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    setting_key VARCHAR(100) NOT NULL,
    setting_value TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY user_setting_unique (user_id, setting_key)
);

-- 25. Vacancy Trackings (Tracking Lamaran Lowongan Kerja)
CREATE TABLE vacancy_trackings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    folder_name VARCHAR(255) NOT NULL,
    job_title VARCHAR(255) NOT NULL,
    company_name VARCHAR(255) DEFAULT NULL,
    applied_date DATE DEFAULT NULL,
    status ENUM('Berhasil Dilamar', 'Tahap Progress', 'Gagal', 'Berhasil') NOT NULL DEFAULT 'Berhasil Dilamar',
    stage VARCHAR(100) DEFAULT 'Administrasi / Screening',
    notes TEXT DEFAULT NULL,
    profile_id BIGINT UNSIGNED DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY user_id (user_id),
    KEY folder_name (folder_name),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Dummy Data for Templates
INSERT INTO cv_templates (name, file_path, description) VALUES 
('Harvard ATS', 'harvard.php', 'Single column, ATS friendly, classical structure.'),
('Harvard Modern', 'harvard-modern.php', 'ATS friendly with modern typography.'),
('Simple ATS', 'simple.php', 'Minimalist, clear, and highly ATS readable.');

INSERT INTO letter_templates (name, file_path, description) VALUES 
('Corporate', 'corporate.php', 'Professional and highly formal.'),
('Modern Minimal', 'modern.php', 'Clean with modern spacing.'),
('Formal Classic', 'formal.php', 'Traditional formal letter structure.'),
('Custom Letterhead', 'custom.php', 'Template with custom uploaded header image.');

-- Dummy Candidate and User
-- password: password (using BCRYPT)
INSERT INTO users (name, email, password) VALUES 
('Muhammad Hafiz Batubara', 'hafiz@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); 

SET @user_id = LAST_INSERT_ID();

INSERT INTO candidate_profiles (user_id, full_name, professional_title, address, city, province, phone, email, portfolio_url, linkedin_url, github_url, professional_summary) VALUES 
(@user_id, 'Muhammad Hafiz Batubara', 'IT Support | Web Developer', 'Jl. Merdeka No. 1', 'Medan', 'Sumatera Utara', '+62 812 3456 7890', 'hafiz@example.com', 'hafiz.com', 'linkedin.com/in/hafiz', 'github.com/hafiz', 'Dedicated IT Support and Web Developer with strong problem solving skills and passion for tech.');
