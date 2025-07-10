# README.md — App/transformers/common

This directory contains all transformer-related classes used across the IRTF
application. Transformers handle the conversion of user input or context data
into structured formats suitable for downstream processes such as validation,
storage, or email output.

Grouped into:

 - Utility Classes (e.g., LabelUtility, InstrumentUtility)
 - A unified API wrapper class (TransformerCore)
 - Trait files that segment TransformerCore by utility category

---

## TRAIT SYSTEM OVERVIEW

The `TransformerCore` class provides a consolidated API surface for all transformer
operations. To reduce file size and improve maintainability, its methods are
grouped into traits. Each trait wraps the corresponding utility class.

For example:
- Methods wrapping `LabelUtility` live in `LabelTrait`
- Methods wrapping `InstrumentUtility` live in `InstrumentTrait`

These traits are then composed into `TransformerCore` via `use` statements.

---

## DEVELOPMENT NOTE

If you add a new method to any *Utility class*, you MUST also add a corresponding
wrapper method to the appropriate TransformerCore *XXXTrait* file.

This ensures that the `TransformerCore` class stays up-to-date and provides
a stable, consistent interface for all transformer operations across the system.

---

## TRAITS INCLUDED (as of current refactor):

- LabelTrait
- InstrumentTrait
