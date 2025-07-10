# README.md — App/core/htmlbuilder

This directory provides a modular, layered system for building HTML structures across the IRTF application. It is composed of two main components:

- **Element Builders**: Focused on generating basic HTML elements (inputs, buttons, tables, etc.) with optional formatting.
- **Composite Builder**: Provides reusable high-level UI components by orchestrating multiple builder classes via traits.

---

## 1. ELEMENT BUILDER CLASSES

The `HtmlBuilder` class acts as a unified interface for base-level HTML generation. It wraps a series of specialized builder classes:

- `BaseHtmlBuilder`: Generic HTML wrappers like `<span>`, `<p>`, `<label>`, and form elements.
- `ButtonBuilder`: `<button>` and `<input type="submit|reset">` generators.
- `CheckboxBuilder`: Standalone and grouped checkbox inputs.
- `PulldownBuilder`: `<select>` builders for years, months, programs, etc.
- `RadioBuilder`: Radio inputs, grouped and labeled.
- `TableBuilder`: Utility for creating structured and stylized HTML tables.
- `TextBuilder`: Inputs for text, password, email, hidden, number, etc.
- `HtmlBuildUtility`: Handles output formatting, escaping, and tag attributes.

The `HtmlBuilder` class composes these via traits and provides a single, extensible API for base-level rendering.

---

## 2. COMPOSITE BUILDER CLASS

The `CompositeBuilder` class provides the public interface for generating complex HTML layouts using:

- `FormElementsBuilder`
- `TableLayoutBuilder`
- `LayoutBuilder`

These are injected into `CompositeBuilder` and segmented by trait into:

- `CompositeBuilderFormElementsBuilderTrait`
- `CompositeBuilderTableLayoutBuilderTrait`
- `CompositeBuilderLayoutBuilderTrait`

Each trait wraps logic from its respective builder, promoting code reuse and consistency.

---

## TRAIT COMPOSITION EXAMPLE

```php
use HtmlBuilderTextBuilderTrait;
use HtmlBuilderButtonBuilderTrait;
use CompositeBuilderFormElementsBuilderTrait;
```

Traits wrap methods from builder classes, maintaining clear separation between concerns while offering unified access.

---

## FORMATTED VS UNFORMATTED OUTPUT

All builder classes accept a `$formatOutput` flag. When set to `true`, the output includes line breaks and indentation. This is useful for debugging or generating readable source; otherwise, compact output is produced.

---

## DEVELOPMENT NOTE

When adding a new rendering method:

1. Implement the logic in the appropriate builder class.
2. Wrap that logic in a corresponding trait used by `HtmlBuilder` or `CompositeBuilder`.
3. Ensure it is tested both with and without `$formatOutput`.
