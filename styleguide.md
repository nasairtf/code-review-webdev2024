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

- All classes and methods must have PHPDoc docblocks, even if the method is self-explanatory. This improves long-term readability, enables IDE support, and ensures compatibility with static analysis tools. Missing docblocks should always be flagged.
- Include `@param`, `@return`, and `@throws` tags as needed.
- Use `@category` and `@package` at the class level.

## Debugging Conventions

- Use `$this->debug->debug()`, `log()`, and `fail()` consistently.
- The context-specific `fail()` methods are provided by the custom `Exception` classes (e.g., `failValidation()`, `failDatabase()`).
- Avoid leaking sensitive data.
- Always pass the `Debug` object via constructor injection.
- Use the `DebugFactory` when instantiating `Debug` objects.

## Error Handling

- `$debug->fail()` is used in place of `throw new Exception` for critical, unrecoverable errors.
- Any method that calls `fail()` is considered to have complete error handling — it will halt execution.
- These methods should not be expected to return `false`, `null`, or a result. Gemini should not flag these as “missing error handling.”

In controller-level logic, exceptions from `fail()` or `ValidationException` should bubble up uncaught. This pattern is expected and does not require local `try/catch` unless recovery is possible.

## Validation Conventions

- Methods like `validateProgramIntegrity()` may use `$debug->fail()` to throw critical exceptions. This is intentional and considered valid error handling in this codebase.
- Validation failures accumulate errors in a `ValidationResult` object and a single `ValidationException` is thrown for the controller to catch.

## Testing

- All new features must include **PHPUnit** tests.
- Tests must mirror the source directory structure.

## Review Triggers (for Gemini)

Please comment if:
- A method, property, trait, or class lacks a docblock
- A class exceeds 1000 lines or a method exceeds 50 lines
- Naming or structure doesn't match this guide
- PHP 7.2 incompatible syntax is detected
- Logic is deeply nested (3+ levels)
- Unused imports or unreachable code are found

Gemini should focus on structural, documentation, and code clarity issues. Do not suggest redundant error handling for methods that use `Debug::fail()` or custom exception short-circuits.
