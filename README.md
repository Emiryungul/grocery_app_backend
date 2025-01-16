# Grocery Application API

Welcome to the Grocery Application API! This project provides a comprehensive backend for managing a grocery application, including user authentication, product management, and order handling.

# Features

- **Authentication**: Powered by Laravel Sanctum for secure and scalable token-based authentication.
- **Product Management**: Add, update, delete, and fetch product information.
- **Categories**: Organize products into categories for easier navigation.
- **Cart Functionality**: Manage user-specific carts with automatic quantity adjustments.
- **Order Management**: Place and manage orders.
- **Favorites**: Allow users to save and manage their favorite products.
- **Secure and Scalable**: Built with Laravel's best practices.

# Installation

## Prerequisites

- PHP >= 8.1
- Composer
- MySQL
- Laravel >= 10

## Steps

1. Clone the repository:

   ```bash
   git clone https://github.com/yourusername/grocery-api.git
   cd grocery-api
   ```

2. Install dependencies:

   ```bash
   composer install
   ```

3. Set up the environment:
   - Copy the `.env.example` file to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update database credentials in the `.env` file:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=grocery_db
     DB_USERNAME=root
     DB_PASSWORD=yourpassword
     ```

4. Generate the application key:

   ```bash
   php artisan key:generate
   ```

5. Run migrations and seed the database:

   ```bash
   php artisan migrate --seed
   ```

6. Start the development server:

   ```bash
   php artisan serve
   ```

   The API will be available at `http://127.0.0.1:8000`.

# Authentication

Laravel Sanctum is used for authentication. To test API endpoints, include the following header in your requests:

```http
Authorization: Bearer {token}
```

# API Endpoints

## Authentication

- **Register**: `POST /api/register`
  - Fields: `name`, `email`, `password`, `password_confirmation`

- **Login**: `POST /api/login`
  - Fields: `email`, `password`

- **Logout**: `POST /api/logout`

## Products

- **List Products**: `GET /api/products`
- **Add Product**: `POST /api/products`
  - Fields: `name`, `price`, `category_id`, `description`, `image`
- **Update Product**: `PUT /api/products/{id}`
- **Delete Product**: `DELETE /api/products/{id}`

## Categories

- **List Categories**: `GET /api/categories`
- **Add Category**: `POST /api/categories`
  - Fields: `name`
- **Update Category**: `PUT /api/categories/{id}`
- **Delete Category**: `DELETE /api/categories/{id}`

## Cart

- **View Cart**: `GET /api/cart`
- **Add to Cart**: `POST /api/cart`
  - Fields: `product_id`, `quantity`
- **Remove from Cart**: `DELETE /api/cart/{id}`

## Favorites

- **View Favorites**: `GET /api/favorites`
- **Add to Favorites**: `POST /api/favorites`
  - Fields: `product_id`
- **Remove from Favorites**: `DELETE /api/favorites/{id}`

## Orders

- **Place Order**: `POST /api/orders`
  - Fields: `cart_id`
- **View Orders**: `GET /api/orders`

# Database File

Include your MySQL dump file (`.sql`) in the `database/` directory for easy setup. Ensure to mention the file path in the `.env` file:

```env
DB_DUMP_FILE=database/grocery_db.sql
```

# License

This project is licensed under the [MIT License](LICENSE).

# Contributing

Pull requests are welcome. For significant changes, please open an issue first to discuss what you would like to change.


