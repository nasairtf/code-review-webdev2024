# Coding Style Guide (Summary for Code Review Tools)

This project follows the detailed rules in [CONTRIBUTING.md](./CONTRIBUTING.md), including:

## Core Coding Rules

- **PSR-12 compliance** is mandatory.
- **PHP 7.2** compatibility is required.
- Enable `declare(strict_types=1);` at the top of each file.
- **Indentation**: 4 spaces, no tabs.
- **Line length**: 120 characters max.
- Use **single quotes** for strings unless interpolation is needed.

## Class Structure

1. Properties
2. Constructor
3. Abstract Methods
4. Public Methods
5. Protected Methods
6. Private Methods

## Naming Conventions

- **Classes**: PascalCase
- **Methods & Properties**: camelCase
- **Constants**: UPPER_SNAKE_CASE
- **Files**: Match class name exactly (e.g., `ScheduleManager.php`)

## Documentation

- All **public classes and methods** must have PHPDoc blocks.
- Include `@param`, `@return`, and `@throws` tags as needed.
- Use `@category` and `@package` at the class level.

## Debugging Conventions

- Use `$this->debug->debug()`, `log()`, and `fail()` consistently.
- Avoid leaking sensitive data.
- Always pass the `Debug` object via constructor injection.

## Testing

- All new features must include **PHPUnit** tests.
- Tests must mirror the source directory structure.

## Review Triggers (for Gemini)

Please comment if:
- A public method lacks a docblock
- A class exceeds 1000 lines or a method exceeds 50 lines
- Naming or structure doesn't match this guide
- PHP 7.2 incompatible syntax is detected
- Logic is deeply nested (3+ levels)
- Unused imports or unreachable code are found
