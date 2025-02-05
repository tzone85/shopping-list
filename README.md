# Shopping List Application

A simple, user-friendly shopping list application built with PHP. Keep track of your shopping items, mark them as complete, and manage your list with ease.

## Features

- Add items with names and quantities
- Mark items as complete/incomplete
- Edit existing items
- Delete items
- Input validation for item names
- Responsive design that works on mobile and desktop

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/tzone85/shopping-list.git
   cd shopping-list
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Create a MySQL database:
   ```sql
   CREATE DATABASE shopping_items;
   ```

4. Copy the `.env.example` file to `.env` and update the database credentials:
   ```bash
   cp .env.example .env
   ```

   Then edit `.env` with your database details:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=shopping_items
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. The application will automatically create the required `shopping_items` table when you first run it.

## Running the Application

1. Start the PHP development server:
   ```bash
   php -S localhost:8000 -t public
   ```

2. Visit `http://localhost:8000` in your web browser

## Usage

1. **Adding Items**:
   - Enter an item name (minimum 2 characters, cannot be numbers only)
   - Set the quantity (minimum 1)
   - Click "Add Item"

2. **Marking Items Complete**:
   - Click the checkbox next to an item to toggle its completion status

3. **Editing Items**:
   - Click the pencil icon to edit an item
   - Update the name and/or quantity
   - Click "Save Changes"

4. **Deleting Items**:
   - Click the trash icon to delete an item
   - Confirm the deletion when prompted

## Directory Structure

```
├── app/
│   ├── Controllers/    # Application controllers
│   ├── Core/           # Framework core files
│   ├── Models/         # Data models
│   └── Views/          # View templates
├── public/            # Public directory (web root)
├── logs/              # Application logs
└── vendor/           # Composer dependencies
```

## Error Handling

Errors are logged to `logs/error-YYYY-MM-DD.log`. Check these logs if you encounter any issues.

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the MIT license.
