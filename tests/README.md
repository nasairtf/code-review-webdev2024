# Testing for the IRTF WebDev2024 Codebase

---

1. **Available Tools**:
    - PHPUnit
    - Mockery
    - PrivatePropertyTrait
        - getPrivateProperty()
        - setPrivateProperty()

2. **Debug Class**:
    - Ensure the Debug class instances' debugMode is set to false.
    - If debugMode cannot be set to false, ensure debugLevel is set to 0.
    - Debug throws Exceptions in fail().
    - Debug writes to error_log in log() if debugLevel is >0.
    - Debug echos output if debugMode is true.
    - Ensure unit tests take this into consideration.
    - Mocking Debug or disabling debugMode/debugLevel ensures no test interference.

---

### Revision History
| Version | Date       | Author      | Description                       |
|---------|------------|-------------|-----------------------------------|
| 1.0     | 2024-12-10 | Miranda H-O | Initial draft of readme.md        |
| 1.1     | YYYY-MM-DD | Author Name | Description of changes            |
