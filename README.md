# CV Automatis (CV & Application Letter Builder)

A native PHP (MVC architecture) web application designed to automatically generate professional, ATS-friendly Curricula Vitae (CV) and tailored Job Application Letters (Surat Lamaran Kerja). Generates pixel-perfect PDF documents powered by Dompdf.

## Features

- **ATS-Friendly CV Templates**:
  - Harvard ATS (Classical single-column, optimized for corporate ATS parsers)
  - Harvard Modern (Clean typography, compact layout)
  - Simple ATS (Minimalist, high readability format)
- **Application Letter Builder**:
  - Corporate, Modern Minimal, Formal Classic, and Custom Letterhead templates
- **High-Fidelity PDF Export**:
  - Pixel-perfect PDF rendering with zero layout shift powered by Dompdf
  - Automated file naming and smart folder routing for job applications
- **Sub-Profile & Tailoring System**:
  - Target-specific candidate profiles (e.g. IT Support, System Analyst, IT Business Analyst, AI Engineer)
  - Multi-category skill, experience, certification, and project management
- **Job Vacancy Matcher & Tracking**:
  - Markdown vacancy parser and status tracking pipeline

## Tech Stack

- **Backend**: Native PHP 8.2+ (Clean MVC architecture)
- **Database**: MySQL / MariaDB
- **Libraries**:
  - `dompdf/dompdf` (^3.0) for high-fidelity PDF rendering
  - `smalot/pdfparser` (^2.12) for parsing PDF files
  - `vlucas/phpdotenv` (^5.6) for environment configuration

## Installation & Setup

1. **Clone repository**:
   ```bash
   git clone https://github.com/Hapis01/cvotomatis.git
   cd cvotomatis
   ```

2. **Install Composer dependencies**:
   ```bash
   composer install
   ```

3. **Configure environment**:
   ```bash
   cp .env.example .env
   ```
   Update `.env` with your database credentials:
   ```env
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cv_builder
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Import Database Schema**:
   Import `database.sql` into MySQL database `cv_builder`:
   ```bash
   mysql -u root -p cv_builder < database.sql
   ```

5. **Run the Application**:
   Using XAMPP (Apache) or PHP built-in server:
   ```bash
   php -S localhost:8000 -t public
   ```
   Or access via XAMPP at `http://localhost/cvotomatis/public/`.

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).
