<p align="center">
  <h1 align="center">KANAN</h1>
  <p align="center">Digital System for Asset Control and Allocation</p>
</p>

## 📖 General Description

**Kanan** is a platform developed to transform, optimize, and digitize the critical process of asset control in public institutions. Designed to replace obsolete and error-prone physical records, Kanan offers a completely digital, secure, and auditable workflow for the lifecycle management of assets (laptops, monitors, peripherals, disincorporations, etc.).

## ✨ Key Features

*   🔐 **Role-Based Access Control (RBAC):** Ensures that each user interacts strictly with the modules and sections that correspond to their responsibilities and permissions.
*   📦 **Comprehensive Management (CRUD):** Complete administration of **Categories, Brands, Assets, and Personnel** from an intuitive interface.
*   👤 **Employee Portal:** A dedicated interface for employees to check in real-time the catalog of assets currently assigned to them.
*   🔄 **Assignment & Transfer Module with Traceability:** Detailed tracking of each asset's history, allowing the registration of assignments, reassignments, and returns with total transparency.
*   📄 **PDF Receipts (Non-Repudiation):** Automatic generation of receipts in PDF format to guarantee "non-repudiation" and legally back every administrative movement or change of custody.

## 🛠️ Tech Stack

Kanan was built with a focus on delivering a professional, robust, scalable tool ready for real-world environments:

*   **Backend:** PHP, [Laravel 12](https://laravel.com/)
*   **Frontend:** JavaScript, [Bootstrap](https://getbootstrap.com/)
*   **Database:** Robust MySQL Database
*   **Containers & Development Environment:** Docker + [Laravel Sail](https://laravel.com/docs/sail) (ensuring absolute parity between development and production)
*   **Production Deployment:** Linux Servers (Debian 12)

## 🚀 Installation & Local Development Environment

To ensure parity with the production server, local development is orchestrated through **Docker** using **Laravel Sail**.

1.  **Clone the repository:**
    ```bash
    git clone <REPOSITORY_URL>
    cd siape-gob
    ```

2.  **Environment Variables:**
    Duplicate the example file to configure the environment.
    ```bash
    cp .env.example .env
    ```

3.  **Install Composer Dependencies:**
    You must install PHP dependencies before starting Sail. If you have PHP and Composer installed locally:
    ```bash
    composer install
    ```
    *Alternative using Docker (without local PHP):*
    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php84-composer:latest \
        composer install --ignore-platform-reqs
    ```

4.  **Start Docker Containers (Sail):**
    ```bash
    ./vendor/bin/sail up -d
    ```

5.  **Generate the Application Key:**
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

6.  **Run Migrations and Seeders:**
    Build the MySQL database structure and populate it with initial data (roles, etc.):
    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

7.  **Create Storage Link:**
    Create the symbolic link to make your storage files publicly accessible, which is required for PDFs and assets:
    ```bash
    ./vendor/bin/sail artisan storage:link
    ```

8.  **Access the Application:**
    The application will be available at `http://localhost`.

## 🌍 Production Deployment

The final deployment of **Kanan** was designed and executed on **Linux (Debian 12) servers**. When deploying the system to production, make sure to:
- Properly configure the web server (Nginx / Apache).
- Optimize Laravel caches with `php artisan optimize`.
- Establish strict security restrictions for persistence and the MySQL database.
- Ensure the `storage:link` is properly generated in the production environment.

---
*Developed to optimize administrative workflows and provide total traceability to institutional asset control.*
