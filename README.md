# WooCommerce Migrator

A WP-CLI-based tool to migrate data from various e-commerce platforms to WooCommerce. The initial focus is on migrating products from Shopify.

## Requirements

- PHP 7.4 or higher
- Composer
- WP-CLI

## Installation

1. Clone the repository.
2. Navigate to the `WooCommerce-Migrator` directory.
3. Install the dependencies:
   ```bash
   composer install
   ```

## Development

This project adheres to the WooCommerce and WordPress coding standards.

### Linting & Formatting

- To check for coding standard violations, run the linter:
  ```bash
  composer lint
  ```
- To automatically fix any fixable violations, run the formatter:
  ```bash
  composer format
  ```

### Testing

This project uses PHPUnit for its test suite.

#### Test Environment Setup

Before running the tests for the first time, you need to set up the WordPress test environment and database.

- Run the setup script:
  ```bash
  composer test:setup
  ```

By default, this script will attempt to connect to the database using the username `root` with an empty password. If you need to use different credentials, you can override the defaults using environment variables:

```bash
DB_NAME=your_db_name DB_USER=your_user DB_PASS=your_pass DB_HOST=localhost composer test:setup
```

#### Running Tests

Once the test environment is set up, you can run the test suite:

```bash
composer test
```
