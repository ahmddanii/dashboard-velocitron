# Dashboard Velocitron

Dashboard Velocitron is a comprehensive web-based platform built with Laravel 11. It is designed for financial and analytical data management, offering a Decision Support System (DSS) with predictive analytics, transaction request workflows, and role-based access control.

## 🌟 Key Features

- **Decision Support System (DSS) & Analytics:** Predictive data analysis and interactive charts using Chart.js.
- **Role-Based Access Control (RBAC):** Secure access levels powered by `spatie/laravel-permission` (e.g., Head Analytics, Financial Controller, standard users).
- **Transaction Request Workflow:** Complete approval system for transactions (create, review, approve, reject, cancel).
- **Data Management:** Export and Import transactions with downloadable templates.
- **User Management:** Manage application users and assign appropriate roles.
- **Modern UI:** Built using Tailwind CSS and Alpine.js for a responsive and dynamic user experience.

## 🛠️ Technology Stack

- **Backend:** Laravel 11, PHP 8.4
- **Frontend:** Tailwind CSS, Alpine.js, Blade Templates, Vite
- **Data Visualization:** Chart.js
- **HTTP Client (Frontend):** Axios
- **Database:** MySQL / SQLite (configured via `.env`)

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.4
- Composer
- Node.js & npm
- Database Server (MySQL, PostgreSQL, or SQLite)

### Installation

1. **Clone the repository (if applicable):**
   ```bash
   git clone <repository-url>
   cd uasprototype
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Frontend dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   Copy the `.env.example` file to `.env` and configure your database credentials and other environment variables.
   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Run Migrations & Seeders:**
   Prepare your database schema and seed initial data (like roles and default users).
   ```bash
   php artisan migrate --seed
   ```

7. **Compile Frontend Assets:**
   ```bash
   npm run build
   # or for development:
   npm run dev
   ```

8. **Start the Development Server:**
   ```bash
   php artisan serve
   ```

You can now access the application at `http://localhost:8000`.

## 🛡️ Roles & Permissions

This application uses specific roles to restrict access to sensitive areas:
- **Head Analytics:** Full access to user management, DSS, analytics export, and transaction predictive tools.
- **Financial Controller:** Access to DSS and advanced analytics alongside the Head Analytics role.
- **Standard User:** Basic dashboard access, profile management, and ability to create transaction requests.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
