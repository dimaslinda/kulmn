# Kulmn Barbershop Website

This is the official website for Kulmn Barbershop, built with Laravel.

## Features

- **Modern Design**: A clean and responsive user interface.
- **SEO Optimized**: Implemented Schema.org and Open Graph meta tags for better search engine visibility and social media sharing.
- **Location Finder**: Helps users find the nearest Kulmn Barbershop locations.
- **Owner Profile**: Information about the owner and their expertise.
- **Partnership Program**: Details on how to join as a Kulmn partner.
- **Professional Barber Training**: Information about becoming a professional barber with Kulmn.

## Installation

To set up the project locally, follow these steps:

1.  **Clone the repository:**
    ```bash
    git clone <repository_url>
    cd kulmn
    ```

2.  **Install Composer dependencies:**
    ```bash
    composer install
    ```

3.  **Install Node.js dependencies:**
    ```bash
    npm install
    ```

4.  **Copy the environment file:**
    ```bash
    cp .env.example .env
    ```

5.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

6.  **Configure your database** in the `.env` file.

7.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```

8.  **Seed the database (optional):**
    ```bash
    php artisan db:seed
    ```

9.  **Run Vite for asset compilation:**
    ```bash
    npm run dev
    ```

10. **Start the Laravel development server:**
    ```bash
    php artisan serve
    ```

    The application will be available at `http://127.0.0.1:8000` (or similar).

## Usage

-   **Homepage**: Provides an overview of Kulmn Barbershop, its services, and locations.
-   **Mitra Page**: Information for potential partners.
-   **Academy Page**: Details about barber training programs.

## Contributing

Contributions are welcome! Please fork the repository and submit pull requests.

## License

The Kulmn Barbershop Website is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
