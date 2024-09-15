-   API Documentation
-   This API provides endpoints for user authentication, user management, customer management, category management,
-   product management, invoice management, and generating sales reports.
-   ** Key Features **

    -   Backend Calculations For Sales + Invoice
    -   valid - invalid JWT tokens is plus point instead of just removing the token from cookie
    -   Secure Password reset process
    -   More over its Multi Vendor POS System


-   ** Requirements **

    -   PHP 8.2 >= as its Laravel 11

-   ** Installation **

    -   Clone the repository
    -   Run `composer install`
    -   Run `npm install`
    -   Run `cp .env.example .env`
    -   Run `php artisan key:generate`
    -   Set your database credentials in the `.env` file
    -   Set your mail credentials in the `.env` file
    -   Run `php artisan migrate`
    -   Run `php artisan serve` to start the test server

-   Endpoints:
-   note : most of the request requires the id & email from the token set on the cookie
-   Note: except for register, login, send-otp, verify-otp, all other endpoints require a valid JWT token in the cookie, named `token`.
