# Wikode — The Filipino Programming Language

> **Programming Languages Final Project** | FEU Institute of Technology

Wikode is a **structurally-typed, Pythonic programming language** built with Filipino/Tagalog keywords. It is designed as a teaching language that demonstrates all key phases of compiler and interpreter design — from lexical tokenization to semantic type-checking — through a real, browser-based IDE.

---

## 👥 Group Members

| Role     | Name                  |
|----------|-----------------------|
| Leader   | Naranja, Earl John    |
| Member 1 | Tan, Raphael          |
| Member 2 | Makayan, Amorsolo     |
| Member 3 | Elona, Alexandra      |

---

## 🎯 Purpose of Wikode

Wikode bridges the gap between **computer science theory** and **intuitive learning** by replacing abstract English syntax with native Filipino/Tagalog equivalents. The goal is to make programming concepts more accessible while simultaneously implementing a complete, multi-phase compiler pipeline that satisfies formal Programming Languages course requirements.

---

## 🔤 Supported Wikode Syntax

### Data Types & Variable Declarations

```wikode
bilang edad = 18           # Integer
numero presyo = 99.50      # Float / Decimal
teksto pangalan = "Juan"   # String (must use quotes)
sapat aktibo = tama        # Boolean (tama = true, mali = false)
```

### Printing Output

```wikode
isulat("Hello, World!")
isulat(pangalan)
isulat("Presyo: " . presyo)
```

### Conditional Statements

```wikode
kung edad >= 18:
    isulat("Adult")
kundi_kung edad >= 13:
    isulat("Teenager")
kundi:
    isulat("Minor")
wakas
```

### While Loop (`habang`)

```wikode
bilang i = 1
habang i <= 5:
    isulat(i)
    i = i + 1
wakas
```

### For Loop (`para_sa`)

```wikode
para_sa i mula 1 hanggang 5:
    isulat(i)
wakas
```

### Object-Oriented Programming

```wikode
klase Produkto:
    publiko teksto pangalan = "Keyboard"
    pribado numero presyo = 2500.50
wakas

bagong Produkto item
isulat("Object created")
```

### Comments

```wikode
# This is a comment — it is ignored by the compiler
```

---

## 📦 Sample Wikode Program

```wikode
# Student Grade Checker
teksto pangalan = "Maria"
bilang puntos = 85
teksto grado = ""

kung puntos >= 90:
    grado = "Excellent"
kundi_kung puntos >= 75:
    grado = "Passed"
kundi:
    grado = "Failed"
wakas

isulat("Student: " . pangalan)
isulat("Score: " . puntos)
isulat("Grade: " . grado)
```

---

## ⚙️ Compiler Phases Implemented

| Phase | Name                    | Description |
|-------|-------------------------|-------------|
| 1     | **Lexical Analysis**    | Tokenizes raw Wikode source into typed tokens (KEYWORD, IDENTIFIER, OPERATOR, INTEGER_LITERAL, FLOAT_LITERAL, STRING_LITERAL, BOOLEAN_LITERAL, SYMBOL) |
| 2     | **Syntax Analysis**     | Validates grammar rules — variable declaration format, colon placement on control structures, `wakas` matching, `isulat()` format |
| 3     | **Semantic Analysis**   | Type compatibility checks (`bilang` ← int only, `teksto` ← quoted string only, etc.), variable scope validation, undeclared variable detection, redeclaration detection. Builds a symbol table. |
| 4     | **Names, Scope & Binding** | Symbol table tracks all declared variable names → types. Scope is determined by indentation depth. Dynamic runtime binding via PHP eval. |
| 5     | **Control Flow**        | `kung` (if), `kundi_kung` (else if), `kundi` (else), `habang` (while loop), `para_sa` (for loop) |
| 6     | **Data Types**          | Four native types: `bilang` (int), `numero` (float), `teksto` (string), `sapat` (boolean) |
| 7     | **OOP Features**        | `klase` (class), `publiko`/`pribado` access modifiers, `bagong` (object instantiation) |
| 8     | **Execution Output**    | Only executes transpiled PHP if all previous phases pass. Output captured via `ob_start()` and rendered in the terminal panel. |

---

## 🏃 How to Run Locally (XAMPP)

### Requirements
- [XAMPP](https://www.apachefriends.org/) with PHP 8.1+
- A modern browser

### Steps

1. **Clone or copy** the project into your XAMPP `htdocs` folder:
   ```
   C:\xampp\htdocs\Wika\
   ```

2. **Start Apache** from the XAMPP Control Panel.

3. **Open your browser** and navigate to:
   ```
   http://localhost/Wika/
   ```

4. The **Studio (index.php)** will load. Type Wikode code and click **"Compile & Analyze"** to see the full compiler pipeline output.

### File Structure

```
Wika/
├── index.php          # Studio — main IDE with compiler panel
├── examples.php       # App Demos (Forecast, Payroll, POS, etc.)
├── docs.php           # Interactive Language Manual (8 Phases)
├── wikode_engine.php  # Centralized compiler engine (all phases)
├── Dockerfile         # Docker deployment config
└── README.md          # This file
```

---

## 🚀 How to Deploy

### Option 1: Render (Docker)

The project includes a `Dockerfile`. To deploy on [Render](https://render.com):

1. Push the project to a GitHub repository.
2. Create a new **Web Service** on Render.
3. Set the **Docker** runtime.
4. Point it to your repository — Render will use the `Dockerfile` automatically.
5. Set the port to **80** in Render's environment settings.

### Option 2: Any PHP Host

Upload all `.php` files to any web host supporting **PHP 8.1+**. No database required — Wikode runs entirely in-memory.

---

## 🏗️ Architecture Overview

```
User Code (Wikode Source)
        │
        ▼
wikode_engine.php
        │
        ├── wikode_lex()           → Token Stream
        ├── wikode_syntax_check()  → Grammar Validation
        ├── wikode_semantic_check()→ Symbol Table + Type Checks
        ├── wikode_transpile()     → PHP Source Code
        └── wikode_execute()       → Captured Output
                │
                ▼
        wikode_render_compiler_panel()
                │
                ▼
        HTML 4-Panel Compiler Report
```

---

## 🛡️ Error Handling

- **Syntax errors** are shown immediately — execution is blocked.
- **Semantic errors** (type mismatches, undeclared variables) are shown — execution is blocked.
- **Runtime errors** from the PHP eval are caught via `Throwable` and displayed safely.
- All output is `htmlspecialchars()`-escaped to prevent XSS.

---

*Wikode v2.0 — FEU Institute of Technology | Programming Languages Final Project*
