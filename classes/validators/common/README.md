# README.md — App/validators/common

This directory contains all validation-related classes used across the IRTF
application, including utilities, traits, and orchestration layers.

Grouped into:

 - Base Utility Classes (e.g., StringsBaseUtility, IntegersBaseUtility)
 - Composite Utility Classes (e.g., TextCompositeUtility, DateTimeCompositeUtility)
 - A unified API wrapper class (ValidationCore)
 - Trait files that segment ValidationCore by utility category

---

## TRAIT SYSTEM OVERVIEW

The `ValidationCore` class provides a consolidated API surface for all validation
operations. To reduce file size and improve maintainability, its methods are
grouped into traits. Each trait wraps the corresponding utility class.

For example:
- Methods wrapping `TextCompositeUtility` live in `TextCompositeTrait`
- Methods wrapping `TokensBaseUtility` live in `TokensBaseTrait`

These traits are then composed into `ValidationCore` via `use` statements.

---

## DEVELOPMENT NOTE

If you add a new method to any *Utility class*, you MUST also add a corresponding
wrapper method to the appropriate ValidationCore *XXXTrait* file.

This ensures that the `ValidationCore` class stays up-to-date and provides
a stable, consistent interface for all validation operations across the system.

---

## TRAITS INCLUDED (as of current refactor):

- RequiredFieldTrait
- IntegersBaseTrait
- FloatsBaseTrait
- StringsBaseTrait
- SelectionBaseTrait
- DateTimeBaseTrait
- UploadsBaseTrait
- TokensBaseTrait
- NumericCompositeTrait
- TextCompositeTrait
- SelectionCompositeTrait
- DateTimeCompositeTrait
- TokenCompositeTrait
- ConvenienceWrapperTrait
