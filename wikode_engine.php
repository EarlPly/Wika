<?php
// =============================================================================
// WIKODE ENGINE v2.0 — Centralized Compiler Pipeline
// Programming Languages Final Project — FEU Tech
// =============================================================================
// Phases: Lexical Analysis → Syntax Analysis → Semantic Analysis → Execution
// =============================================================================

// ---------------------------------------------------------------------------
// PHASE 1: LEXICAL ANALYSIS — Tokenizer
// ---------------------------------------------------------------------------

function wikode_lex(string $code): array
{
    $tokens = [];
    $keywords = [
        'bilang',
        'numero',
        'teksto',
        'sapat',
        'kung',
        'kundi_kung',
        'kundi',
        'wakas',
        'isulat',
        'habang',
        'para_sa',
        'mula',
        'hanggang',
        'klase',
        'bagong',
        'publiko',
        'pribado',
        'baryabol',
        'tama',
        'mali',
        'app_forecast',
        'app_timer',
        'app_payroll',
        'app_pos',
        'app_inventory',
        'app_auth',
    ];
    $boolean_literals = ['tama', 'mali'];

    $lines = explode("\n", $code);

    foreach ($lines as $line_num => $raw_line) {
        // Strip inline comments
        $line = preg_replace('/#.*$/', '', $raw_line);
        $line = rtrim($line);
        if (empty(trim($line)))
            continue;

        $pos = 0;
        $len = strlen($line);

        while ($pos < $len) {
            // Skip whitespace
            if ($line[$pos] === ' ' || $line[$pos] === "\t") {
                $pos++;
                continue;
            }

            // STRING LITERAL
            if ($line[$pos] === '"') {
                $end = strpos($line, '"', $pos + 1);
                if ($end === false)
                    $end = $len - 1;
                $val = substr($line, $pos, $end - $pos + 1);
                $tokens[] = ['value' => $val, 'type' => 'STRING_LITERAL', 'line' => $line_num + 1];
                $pos = $end + 1;
                continue;
            }

            // SINGLE-QUOTED STRING
            if ($line[$pos] === "'") {
                $end = strpos($line, "'", $pos + 1);
                if ($end === false)
                    $end = $len - 1;
                $val = substr($line, $pos, $end - $pos + 1);
                $tokens[] = ['value' => $val, 'type' => 'STRING_LITERAL', 'line' => $line_num + 1];
                $pos = $end + 1;
                continue;
            }

            // FLOAT / INTEGER LITERAL
            if (ctype_digit($line[$pos])) {
                $num = '';
                $is_float = false;
                while ($pos < $len && (ctype_digit($line[$pos]) || $line[$pos] === '.')) {
                    if ($line[$pos] === '.')
                        $is_float = true;
                    $num .= $line[$pos];
                    $pos++;
                }
                $tokens[] = [
                    'value' => $num,
                    'type' => $is_float ? 'FLOAT_LITERAL' : 'INTEGER_LITERAL',
                    'line' => $line_num + 1
                ];
                continue;
            }

            // OPERATORS (multi-char first)
            $two_char = substr($line, $pos, 2);
            if (in_array($two_char, ['==', '!=', '<=', '>=', '&&', '||'])) {
                $tokens[] = ['value' => $two_char, 'type' => 'OPERATOR', 'line' => $line_num + 1];
                $pos += 2;
                continue;
            }
            if (in_array($line[$pos], ['+', '-', '*', '/', '=', '<', '>', '!', '%'])) {
                $tokens[] = ['value' => $line[$pos], 'type' => 'OPERATOR', 'line' => $line_num + 1];
                $pos++;
                continue;
            }

            // SYMBOLS
            if (in_array($line[$pos], ['(', ')', ',', ':', '.'])) {
                $tokens[] = ['value' => $line[$pos], 'type' => 'SYMBOL', 'line' => $line_num + 1];
                $pos++;
                continue;
            }

            // KEYWORD or IDENTIFIER
            if (ctype_alpha($line[$pos]) || $line[$pos] === '_') {
                $word = '';
                while ($pos < $len && (ctype_alnum($line[$pos]) || $line[$pos] === '_')) {
                    $word .= $line[$pos];
                    $pos++;
                }
                if (in_array($word, $boolean_literals)) {
                    $tokens[] = ['value' => $word, 'type' => 'BOOLEAN_LITERAL', 'line' => $line_num + 1];
                } elseif (in_array($word, $keywords)) {
                    $tokens[] = ['value' => $word, 'type' => 'KEYWORD', 'line' => $line_num + 1];
                } else {
                    $tokens[] = ['value' => $word, 'type' => 'IDENTIFIER', 'line' => $line_num + 1];
                }
                continue;
            }

            // Unknown char — skip
            $pos++;
        }
    }

    return $tokens;
}


// ---------------------------------------------------------------------------
// PHASE 2: SYNTAX ANALYSIS — Grammar Validation
// ---------------------------------------------------------------------------

function wikode_syntax_check(string $code): array
{
    $errors = [];
    $lines = explode("\n", $code);
    $block_depth = 0;
    $block_openers = []; // stack of line numbers that opened blocks

    $type_keywords = ['bilang', 'numero', 'teksto', 'sapat'];
    $block_starters = ['kung', 'kundi_kung', 'kundi', 'habang', 'para_sa', 'klase'];

    foreach ($lines as $idx => $raw_line) {
        $line_num = $idx + 1;
        $line = trim(preg_replace('/#.*$/', '', $raw_line));
        if (empty($line))
            continue;

        $first_word = preg_split('/\s+/', $line)[0] ?? '';

        // ── Variable declaration: must be  TYPE NAME = VALUE
        if (in_array($first_word, $type_keywords)) {
            if (!preg_match('/^(?:bilang|numero|teksto|sapat)\s+[a-zA-Z_]\w*\s*=\s*.+$/', $line)) {
                $errors[] = "Line $line_num: Invalid variable declaration → \"$line\" (expected: TYPE name = value)";
            }
            continue;
        }

        // ── OOP: class property (publiko/pribado)
        if ($first_word === 'publiko' || $first_word === 'pribado') {
            if (!preg_match('/^(?:publiko|pribado)\s+(?:bilang|numero|teksto|sapat)\s+[a-zA-Z_]\w*\s*=\s*.+$/', $line)) {
                $errors[] = "Line $line_num: Invalid class property → \"$line\"";
            }
            continue;
        }

        // ── OOP: klase declaration
        if ($first_word === 'klase') {
            if (!preg_match('/^klase\s+[a-zA-Z_]\w*\s*:$/', $line)) {
                $errors[] = "Line $line_num: Invalid class declaration → \"$line\" (expected: klase ClassName:)";
            }
            $block_depth++;
            $block_openers[] = $line_num;
            continue;
        }

        // ── OOP: bagong (object instantiation)
        if ($first_word === 'bagong') {
            if (!preg_match('/^bagong\s+[a-zA-Z_]\w+\s+[a-zA-Z_]\w*$/', $line)) {
                $errors[] = "Line $line_num: Invalid object creation → \"$line\" (expected: bagong ClassName varName)";
            }
            continue;
        }

        // ── Block starters that need colon
        if (in_array($first_word, ['kung', 'habang'])) {
            if (!preg_match('/^(?:kung|habang)\s+.+:$/', $line)) {
                $errors[] = "Line $line_num: Missing colon ( : ) at end of \"$first_word\" statement → \"$line\"";
            }
            $block_depth++;
            $block_openers[] = $line_num;
            continue;
        }

        if ($first_word === 'kundi_kung') {
            if (!preg_match('/^kundi_kung\s+.+:$/', $line)) {
                $errors[] = "Line $line_num: Missing colon ( : ) at end of \"kundi_kung\" statement → \"$line\"";
            }
            // kundi_kung closes previous if/kung block and opens new elseif block
            if (!empty($block_openers)) {
                array_pop($block_openers);
                $block_depth--;
            }
            $block_depth++;
            $block_openers[] = $line_num;
            continue;
        }

        if ($first_word === 'kundi') {
            if ($line !== 'kundi:') {
                $errors[] = "Line $line_num: \"kundi\" must be exactly \"kundi:\" → \"$line\"";
            }
            // kundi closes previous if block and opens else block — net depth change is 0
            // but we need to track it: pop the last opener, then push this line as new opener
            if (!empty($block_openers)) {
                array_pop($block_openers);
                $block_depth--;
            }
            $block_depth++;
            $block_openers[] = $line_num;
            continue;
        }

        // ── para_sa loop
        if ($first_word === 'para_sa') {
            if (!preg_match('/^para_sa\s+[a-zA-Z_]\w*\s+mula\s+.+\s+hanggang\s+.+:$/', $line)) {
                $errors[] = "Line $line_num: Invalid for-loop syntax → \"$line\" (expected: para_sa VAR mula START hanggang END:)";
            }
            $block_depth++;
            $block_openers[] = $line_num;
            continue;
        }

        // ── wakas
        if ($first_word === 'wakas') {
            if ($block_depth <= 0) {
                $errors[] = "Line $line_num: Misplaced \"wakas\" — no open block to close.";
            } else {
                $block_depth--;
                array_pop($block_openers);
            }
            continue;
        }

        // ── isulat()
        if ($first_word === 'isulat') {
            if (!preg_match('/^isulat\s*\(.*\)$/', $line)) {
                $errors[] = "Line $line_num: Invalid isulat() call → \"$line\" (expected: isulat(value))";
            }
            continue;
        }

        // ── app_* functions (examples page)
        if (str_starts_with($first_word, 'app_')) {
            continue; // allowed
        }

        // ── Assignment (variable = expression, no type keyword)
        if (preg_match('/^[a-zA-Z_]\w*\s*=\s*.+$/', $line)) {
            continue; // valid reassignment
        }

        // ── Anything else that's not a comment is suspicious
        // (we allow it but don't error — could be expressions)
    }

    // Check unclosed blocks
    foreach ($block_openers as $open_line) {
        $errors[] = "Line $open_line: Block opened here was never closed with \"wakas\".";
    }

    return [
        'ok' => empty($errors),
        'errors' => $errors,
    ];
}


// ---------------------------------------------------------------------------
// PHASE 3: SEMANTIC ANALYSIS — Type Checking & Scope
// ---------------------------------------------------------------------------

function wikode_semantic_check(string $code, array $tokens): array
{
    $errors = [];
    $symbols = []; // name → type map (flat scope for now)
    $classes = []; // class name → properties

    $lines = explode("\n", $code);

    // First pass: collect all declared symbols
    foreach ($lines as $idx => $raw_line) {
        $line_num = $idx + 1;
        $line = trim(preg_replace('/#.*$/', '', $raw_line));
        if (empty($line))
            continue;

        // Variable declaration
        if (preg_match('/^(bilang|numero|teksto|sapat)\s+([a-zA-Z_]\w*)\s*=\s*(.+)$/', $line, $m)) {
            $type = $m[1];
            $name = $m[2];
            $value = trim($m[3]);

            // Check for redeclaration
            if (isset($symbols[$name])) {
                $errors[] = "Line $line_num: Variable \"$name\" was already declared as \"{$symbols[$name]}\". Redeclaration is not allowed in the same scope.";
            } else {
                $symbols[$name] = $type;
            }

            // Type compatibility checks
            _wikode_check_type_compatibility($type, $name, $value, $line_num, $errors);
            continue;
        }

        // OOP class declaration
        if (preg_match('/^klase\s+([a-zA-Z_]\w*)\s*:$/', $line, $m)) {
            $class_name = $m[1];
            $classes[$class_name] = ['properties' => []];
            continue;
        }

        // OOP class property (publiko/pribado)
        if (preg_match('/^(publiko|pribado)\s+(bilang|numero|teksto|sapat)\s+([a-zA-Z_]\w*)\s*=\s*(.+)$/', $line, $m)) {
            $access = $m[1];
            $type = $m[2];
            $name = $m[3];
            $value = trim($m[4]);
            _wikode_check_type_compatibility($type, $name, $value, $line_num, $errors);
            continue;
        }

        // OOP instantiation
        if (preg_match('/^bagong\s+([a-zA-Z_]\w+)\s+([a-zA-Z_]\w*)$/', $line, $m)) {
            $class_name = $m[1];
            $var_name = $m[2];
            if (!isset($classes[$class_name])) {
                // May be defined elsewhere — soft warning, not hard error
                // $errors[] = "Line $line_num: Class \"$class_name\" is not declared in this scope.";
            }
            $symbols[$var_name] = 'object:' . $class_name;
            continue;
        }

        // isulat — check that identifiers used are declared
        if (preg_match('/^isulat\s*\((.+)\)$/', $line, $m)) {
            $inner = trim($m[1]);
            // Extract all bare identifiers (not inside quotes, not numbers)
            $candidates = _wikode_extract_identifiers($inner);
            foreach ($candidates as $ident) {
                if (!isset($symbols[$ident])) {
                    $errors[] = "Line $line_num: Identifier \"$ident\" used in isulat() but was never declared.";
                }
            }
            continue;
        }

        // para_sa loop — register loop variable in symbol table
        if (preg_match('/^para_sa\s+([a-zA-Z_]\w*)\s+mula/', $line, $m)) {
            $symbols[$m[1]] = 'bilang'; // loop counter is integer-like
            continue;
        }

        // Re-assignment: check LHS is declared
        if (preg_match('/^([a-zA-Z_]\w*)\s*=\s*(.+)$/', $line, $m)) {
            $name = $m[1];
            $value = trim($m[2]);
            if (!isset($symbols[$name])) {
                // Check if it's a para_sa loop control variable
                $is_loop_var = false;
                foreach ($lines as $other_line) {
                    if (preg_match('/^para_sa\s+' . preg_quote($name, '/') . '\s+mula/', trim($other_line))) {
                        $is_loop_var = true;
                        break;
                    }
                }
                if (!$is_loop_var) {
                    $errors[] = "Line $line_num: Variable \"$name\" assigned without prior declaration. Declare it first (e.g., bilang $name = 0).";
                }
            }
            continue;
        }
    }

    return [
        'ok' => empty($errors),
        'errors' => $errors,
        'symbols' => $symbols,
        'classes' => $classes,
    ];
}

// Helper: check type ↔ value compatibility
function _wikode_check_type_compatibility(string $type, string $name, string $value, int $line_num, array &$errors): void
{
    switch ($type) {
        case 'bilang':
            // Must be an integer literal or integer expression (no quotes, no float)
            if (preg_match('/^".*"$/', $value) || preg_match("/^'.*'$/", $value)) {
                $errors[] = "Line $line_num: Type Error — \"bilang $name\" must hold an integer, but got a string literal: $value";
            } elseif (preg_match('/^\d+\.\d+$/', $value)) {
                $errors[] = "Line $line_num: Type Error — \"bilang $name\" must hold an integer, but got a float: $value. Use \"numero\" instead.";
            }
            break;

        case 'numero':
            // Must be a number (int or float) or numeric expression, not a string
            if (preg_match('/^".*"$/', $value) || preg_match("/^'.*'$/", $value)) {
                $errors[] = "Line $line_num: Type Error — \"numero $name\" must hold a number, but got a string literal: $value";
            }
            break;

        case 'teksto':
            // Must be a string literal (quoted)
            if (!preg_match('/^".*"$/', $value) && !preg_match("/^'.*'$/", $value)) {
                // Allow empty string assignment and concatenation expressions
                if (
                    !str_contains($value, '"') && !str_contains($value, "'")
                    && !str_contains($value, '.')
                    && !preg_match('/^[a-zA-Z_]\w*$/', $value)  // reassignment from var is ok
                ) {
                    $errors[] = "Line $line_num: Type Error — \"teksto $name\" must hold a string (use double quotes), but got: $value";
                }
            }
            break;

        case 'sapat':
            // Must be tama or mali
            if (!in_array(trim($value), ['tama', 'mali', 'true', 'false'])) {
                // Allow variables or expressions that might evaluate to bool
                if (!preg_match('/^[a-zA-Z_]\w*$/', trim($value)) && !str_contains($value, '==') && !str_contains($value, '!') && !str_contains($value, '<') && !str_contains($value, '>')) {
                    $errors[] = "Line $line_num: Type Error — \"sapat $name\" must be \"tama\" or \"mali\", but got: $value";
                }
            }
            break;
    }
}

// Helper: extract bare identifiers from an expression (ignoring string contents)
function _wikode_extract_identifiers(string $expr): array
{
    // Remove string literals
    $cleaned = preg_replace('/"[^"]*"/', '', $expr);
    $cleaned = preg_replace("/'[^']*'/", '', $cleaned);
    // Remove numbers
    $cleaned = preg_replace('/\b\d+(\.\d+)?\b/', '', $cleaned);
    // Find word tokens
    preg_match_all('/\b([a-zA-Z_][a-zA-Z0-9_]*)\b/', $cleaned, $m);
    // Filter out built-in PHP functions and wikode keywords
    $ignore = [
        'round',
        'sqrt',
        'abs',
        'ceil',
        'floor',
        'strlen',
        'strtoupper',
        'strtolower',
        'tama',
        'mali',
        'true',
        'false',
        'null',
        'bilang',
        'numero',
        'teksto',
        'sapat',
        'kung',
        'kundi',
        'wakas',
        'habang',
        'para_sa',
        'mula',
        'hanggang',
        'klase',
        'bagong',
        'publiko',
        'pribado',
        'isulat',
        'baryabol'
    ];
    return array_filter($m[1] ?? [], fn($w) => !in_array($w, $ignore));
}


// ---------------------------------------------------------------------------
// PHASE 4 (Part A): TRANSPILER — Wikode → PHP
// ---------------------------------------------------------------------------

function wikode_transpile(string $code, array $symbols, array $classes, string $mode = 'standard'): string
{
    if (empty(trim($code)))
        return '';

    $php_code = '';
    $lines = explode("\n", $code);

    // Collect all declared variable names for substitution
    $var_names = array_filter(array_keys($symbols), fn($k) => !str_starts_with($k, 'object:'));

    // Also collect para_sa loop variable names
    foreach ($lines as $line) {
        $t = trim($line);
        if (preg_match('/^para_sa\s+([a-zA-Z_]\w*)\s+mula/', $t, $m)) {
            $var_names[] = $m[1];
        }
    }
    $var_names = array_unique($var_names);

    foreach ($lines as $l) {
        $l = trim($l);
        // Skip comments and empty lines
        if (empty($l) || str_starts_with($l, '#'))
            continue;

        // ── OOP class declaration (handle BEFORE variable substitution)
        if (preg_match('/^klase\s+([a-zA-Z_]\w*)\s*:$/', $l, $m)) {
            $php_code .= "class {$m[1]} {\n";
            continue;
        }

        // ── OOP class property (publiko/pribado) — handle BEFORE var substitution
        if (preg_match('/^(publiko|pribado)\s+(bilang|numero|teksto|sapat)\s+([a-zA-Z_]\w*)\s*=\s*(.*)$/', $l, $m)) {
            $access = ($m[1] === 'publiko') ? 'public' : 'private';
            $php_code .= "$access \${$m[3]} = {$m[4]};\n";
            continue;
        }

        // ── OOP object instantiation — handle BEFORE var substitution
        if (preg_match('/^bagong\s+([a-zA-Z_]\w+)\s+([a-zA-Z_]\w*)$/', $l, $m)) {
            $php_code .= "\${$m[2]} = new {$m[1]}();\n";
            continue;
        }

        // ── Substitute variable names with $ prefix
        foreach ($var_names as $v) {
            $l = preg_replace('/\b' . preg_quote($v, '/') . '\b/', '$' . $v, $l);
            $l = str_replace('$$', '$', $l);
        }

        // ── Variable declarations → PHP assignment
        $l = preg_replace(
            '/^(bilang|numero|teksto|sapat)\s+\$([a-zA-Z_]\w*)\s*=\s*(.*)$/',
            '$$2 = $3;',
            $l
        );

        // ── Booleans
        $l = preg_replace('/\btama\b/', 'true', $l);
        $l = preg_replace('/\bmali\b/', 'false', $l);

        // ── Control flow
        if (preg_match('/^kung\s+(.*?):$/', $l, $m)) {
            $l = "if ({$m[1]}) {";
        }
        if (preg_match('/^kundi_kung\s+(.*?):$/', $l, $m)) {
            $l = "} elseif ({$m[1]}) {";
        }
        if (preg_match('/^kundi:$/', $l)) {
            $l = "} else {";
        }

        // ── habang (while loop)
        if (preg_match('/^habang\s+(.*?):$/', $l, $m)) {
            $l = "while ({$m[1]}) {";
        }

        // ── para_sa (for loop)
        if (preg_match('/^para_sa\s+\$([a-zA-Z_]\w*)\s+mula\s+(.+)\s+hanggang\s+(.+):$/', $l, $m)) {
            $var = $m[1];
            $start = trim($m[2]);
            $end = trim($m[3]);
            $l = "for (\${$var} = {$start}; \${$var} <= {$end}; \${$var}++) {";
        }

        // ── wakas
        if (trim($l) === 'wakas') {
            $l = "}";
        }

        // ── isulat (handle concatenation and raw strings)
        if (preg_match('/^isulat\s*\((.*)\)$/s', $l, $m)) {
            $inner = $m[1];
            $l = "echo htmlspecialchars_decode(htmlspecialchars((string)({$inner}), ENT_QUOTES, 'UTF-8')) . '<br>';";
        }

        // ── app_* UI generators (ui mode only)
        if ($mode === 'ui') {
            $l = _wikode_transpile_app_functions($l);
        }

        // ── Add semicolon where needed
        if (
            !str_ends_with($l, ';')
            && !str_ends_with($l, '{')
            && !str_ends_with($l, '}')
            && !str_starts_with(ltrim($l), 'echo')
            && !str_starts_with(ltrim($l), '//')
            && !empty(trim($l))
        ) {
            $l .= ';';
        }

        $php_code .= $l . "\n";
    }

    // Balance braces (safety net)
    $open = substr_count($php_code, '{');
    $close = substr_count($php_code, '}');
    for ($i = 0; $i < ($open - $close); $i++) {
        $php_code .= "}\n";
    }

    return $php_code;
}

// App UI function transpiler (for examples.php)
function _wikode_transpile_app_functions(string $l): string
{
    // 1. FORECAST
    if (preg_match('/^app_forecast\((.*?),\s*(.*?),\s*(.*?)\)$/', $l, $m)) {
        return 'echo "<div class=\'bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden w-full max-w-md mx-auto\'><div class=\'bg-indigo-600 p-4 text-white text-center font-bold uppercase tracking-widest text-sm\'>Sales Prediction Dashboard</div><div class=\'p-8 flex items-center justify-between\'><div class=\'text-center\'><p class=\'text-gray-400 font-bold text-xs uppercase\'>Actual</p><p class=\'text-3xl font-black text-gray-800\'>".' . $m[1] . '."</p></div><div class=\'text-indigo-200\'><i class=\'ti ti-arrow-right text-3xl\'></i></div><div class=\'text-center\'><p class=\'text-gray-400 font-bold text-xs uppercase\'>Predicted</p><p class=\'text-3xl font-black text-indigo-600\'>".' . $m[2] . '."</p></div></div><div class=\'bg-indigo-50 p-4 text-center border-t border-indigo-100\'><span class=\'badge badge-primary badge-lg\'>Accuracy: ".' . $m[3] . '."%</span></div></div>";';
    }
    // 2. TIMER
    if (preg_match('/^app_timer\((.*?),\s*(.*?)\)$/', $l, $m)) {
        $title = $m[1];
        $secs = $m[2];
        // Build the timer HTML separately and return a PHP eval string.
        // The </script> closing tag is split via PHP concat inside the eval string
        // to prevent the browser from misreading the script block closure.
        return 'echo \'<div class="bg-gray-900 rounded-3xl shadow-2xl p-8 border-4 border-gray-800 text-center relative overflow-hidden w-full max-w-sm mx-auto">'
            . '<h3 class="text-pink-500 font-bold uppercase tracking-widest text-sm mb-6">\' . htmlspecialchars((string)(' . $title . ')) . \'</h3>'
            . '<div class="w-48 h-48 mx-auto border-8 border-gray-800 rounded-full flex items-center justify-center bg-gray-950 shadow-inner">'
            . '<span class="wk-timer-val text-6xl font-black text-white font-mono">\' . ((int)(' . $secs . ')) . \'</span>'
            . '</div></div>\';'
            . ' echo \'<sc\' . \'ript>(function(){'
            . 'var el=document.querySelector(".wk-timer-val");'
            . 'if(el){'
            . 'var t=parseInt(el.textContent||"0");'
            . 'var iv=setInterval(function(){t--;el.textContent=t;if(t<=0){clearInterval(iv);el.textContent="0";}},1000);'
            . '}})()</\' . \'script>\';';
    }
    // 3. PAYROLL
    if (preg_match('/^app_payroll\((.*?),\s*(.*?),\s*(.*?),\s*(.*?)\)$/', $l, $m)) {
        return 'echo "<div class=\'bg-[#f4f1ea] rounded-xl shadow-xl p-8 border border-gray-300 font-mono text-gray-800 max-w-sm mx-auto\'><div class=\'border-b-2 border-dashed border-gray-400 pb-4 mb-4 text-center\'><h3 class=\'font-black text-xl uppercase tracking-widest\'>Payslip</h3></div><div class=\'space-y-2 mb-6\'><div class=\'flex justify-between\'><span>Employee:</span><span class=\'font-bold\'>".' . $m[1] . '."</span></div><div class=\'flex justify-between\'><span>Gross Pay:</span><span class=\'font-bold\'>₱".' . $m[2] . '."</span></div><div class=\'flex justify-between text-red-600\'><span>Tax:</span><span class=\'font-bold\'>-₱".' . $m[3] . '."</span></div></div><div class=\'bg-gray-800 text-white p-3 rounded flex justify-between items-center\'><span class=\'font-bold uppercase\'>Net Salary</span><span class=\'text-2xl font-black\'>₱".' . $m[4] . '."</span></div></div>";';
    }
    // 4. POS
    if (preg_match('/^app_pos\((.*?),\s*(.*?),\s*(.*?),\s*(.*?)\)$/', $l, $m)) {
        return 'echo "<div class=\'bg-white rounded-2xl shadow-xl border border-gray-200 p-6 max-w-sm mx-auto\'><div class=\'flex items-center gap-3 border-b border-gray-100 pb-4 mb-4\'><div class=\'w-10 h-10 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center\'><i class=\'ti ti-building-store text-xl\'></i></div><h3 class=\'font-bold text-gray-800 uppercase tracking-wider\'>POS Register</h3></div><div class=\'space-y-3 mb-6\'><div class=\'flex justify-between text-gray-600\'><span>Item:</span><span class=\'font-medium\'>".' . $m[1] . '."</span></div><div class=\'flex justify-between text-gray-600\'><span>Price:</span><span class=\'font-medium\'>₱".' . $m[2] . '."</span></div><div class=\'flex justify-between text-gray-600\'><span>Qty:</span><span class=\'font-medium\'>x".' . $m[3] . '."</span></div></div><div class=\'bg-amber-50 p-4 rounded-xl flex justify-between items-center border border-amber-100\'><span class=\'font-bold text-amber-800 uppercase\'>Total Due</span><span class=\'text-2xl font-black text-amber-600\'>₱".' . $m[4] . '."</span></div></div>";';
    }
    // 5. INVENTORY
    if (preg_match('/^app_inventory\((.*?),\s*(.*?),\s*(.*?)\)$/', $l, $m)) {
        return 'echo "<div class=\'bg-slate-800 rounded-2xl shadow-2xl p-6 text-white max-w-sm mx-auto border border-slate-700\'><h3 class=\'text-slate-400 font-bold uppercase tracking-widest text-xs mb-4\'>Inventory Module</h3><div class=\'text-3xl font-black mb-1\'>".' . $m[1] . '."</div><div class=\'text-slate-300 mb-6 text-sm\'>Current Stock: <span class=\'font-bold text-white\'>".' . $m[2] . '." units</span></div><div class=\'bg-slate-900 rounded-xl p-4 border border-slate-700 flex justify-between items-center\'><span class=\'text-emerald-400 font-bold uppercase text-xs tracking-wider\'>Optimal ROQ</span><span class=\'text-2xl font-black text-emerald-400\'>".' . $m[3] . '."</span></div></div>";';
    }
    // 6. AUTH
    if (preg_match('/^app_auth\((.*?),\s*(.*?),\s*(.*?)\)$/', $l, $m)) {
        $v3 = $m[3];
        return 'echo "<div class=\'bg-white rounded-2xl shadow-xl border border-gray-200 p-8 text-center max-w-sm mx-auto\'><div class=\'w-20 h-20 mx-auto bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-4xl mb-4\'><i class=\'ti ti-user-shield\'></i></div><h2 class=\'text-2xl font-black text-gray-800\'>".' . $m[1] . '."</h2><p class=\'text-gray-500 font-medium mb-6 uppercase tracking-widest text-xs\'>".' . $m[2] . '."</p><div class=\'" . (' . $v3 . ' == "Access Granted" ? "bg-emerald-100 text-emerald-700 border-emerald-200" : "bg-red-100 text-red-700 border-red-200") . " py-3 rounded-lg font-bold uppercase tracking-wider border\'>".' . $v3 . '."</div></div>";';
    }

    return $l;
}


// ---------------------------------------------------------------------------
// PHASE 4 (Part B): EXECUTION
// ---------------------------------------------------------------------------

function wikode_execute(string $php_code): array
{
    ob_start();
    $runtime_error = null;
    try {
        eval ($php_code);
    } catch (Throwable $e) {
        $runtime_error = $e->getMessage();
    }
    $raw_output = ob_get_clean();

    return [
        'output' => $raw_output,
        'error' => $runtime_error,
    ];
}


// ---------------------------------------------------------------------------
// MASTER ORCHESTRATOR
// ---------------------------------------------------------------------------

function wikode_compile_and_run(string $code, string $mode = 'standard'): array
{
    // Phase 1: Lex
    $tokens = wikode_lex($code);

    // Phase 2: Syntax
    $syntax = wikode_syntax_check($code);

    // Phase 3: Semantic
    $semantic = wikode_semantic_check($code, $tokens);

    // Phase 4: Transpile + Execute (only if phases 2 & 3 pass)
    $php_code = null;
    $execution = null;

    if ($syntax['ok'] && $semantic['ok']) {
        $php_code = wikode_transpile($code, $semantic['symbols'], $semantic['classes'], $mode);
        $execution = wikode_execute($php_code);
    }

    return [
        'tokens' => $tokens,
        'syntax' => $syntax,
        'semantic' => $semantic,
        'php_code' => $php_code,
        'execution' => $execution,
        'mode' => $mode,
    ];
}


// ---------------------------------------------------------------------------
// HTML RENDERER — Compiler Panel
// ---------------------------------------------------------------------------

function wikode_render_compiler_panel(array $result): string
{
    $tokens = $result['tokens'];
    $syntax = $result['syntax'];
    $semantic = $result['semantic'];
    $execution = $result['execution'];
    $php_code = $result['php_code'];
    $mode = $result['mode'] ?? 'standard';

    $html = '<div class="mt-0 border-t border-gray-800 compiler-panel">';
    $html .= '<div class="bg-gray-950 px-4 py-2 text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2">';
    $html .= '<i class="ti ti-cpu text-indigo-400"></i> Wikode Compiler Report</div>';

    // ── PHASE 1: LEXICAL ANALYSIS
    $html .= _render_phase_section(
        '1',
        'Lexical Analysis',
        'ti-scan',
        'indigo',
        true, // always ok if we got here
        [],
        _render_token_table($tokens)
    );

    // ── PHASE 2: SYNTAX ANALYSIS
    $html .= _render_phase_section(
        '2',
        'Syntax Analysis',
        'ti-code',
        'violet',
        $syntax['ok'],
        $syntax['errors'],
        $syntax['ok'] ? '<p class="text-emerald-400 font-mono text-sm flex items-center gap-2"><i class="ti ti-circle-check text-lg"></i> Grammar validated — all constructs are well-formed.</p>' : ''
    );

    // ── PHASE 3: SEMANTIC ANALYSIS
    $sem_extra = '';
    if ($semantic['ok'] && !empty($semantic['symbols'])) {
        $sem_extra .= '<div class="mt-3">';
        $sem_extra .= '<p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 flex items-center gap-1"><i class="ti ti-table"></i> Symbol Table</p>';
        $sem_extra .= '<table class="text-xs font-mono w-full border-collapse">';
        $sem_extra .= '<thead><tr class="text-gray-500 border-b border-gray-700">';
        $sem_extra .= '<th class="text-left pb-1 pr-4">Variable</th><th class="text-left pb-1">Type</th></tr></thead><tbody>';
        foreach ($semantic['symbols'] as $name => $type) {
            $badge_color = match (true) {
                $type === 'bilang' => 'bg-blue-900 text-blue-300',
                $type === 'numero' => 'bg-emerald-900 text-emerald-300',
                $type === 'teksto' => 'bg-purple-900 text-purple-300',
                $type === 'sapat' => 'bg-amber-900 text-amber-300',
                str_starts_with($type, 'object') => 'bg-pink-900 text-pink-300',
                default => 'bg-gray-800 text-gray-300',
            };
            $sem_extra .= "<tr class='border-b border-gray-800'>";
            $sem_extra .= "<td class='py-1 pr-4 text-indigo-300'>\$$name</td>";
            $sem_extra .= "<td class='py-1'><span class='px-2 py-0.5 rounded text-xs font-bold $badge_color'>$type</span></td>";
            $sem_extra .= "</tr>";
        }
        if (!empty($semantic['classes'])) {
            foreach ($semantic['classes'] as $cname => $cdata) {
                $sem_extra .= "<tr class='border-b border-gray-800'>";
                $sem_extra .= "<td class='py-1 pr-4 text-pink-300'>class $cname</td>";
                $sem_extra .= "<td class='py-1'><span class='px-2 py-0.5 rounded text-xs font-bold bg-pink-900 text-pink-300'>klase</span></td>";
                $sem_extra .= "</tr>";
            }
        }
        $sem_extra .= '</tbody></table></div>';
    }

    $sem_success_msg = $semantic['ok']
        ? '<p class="text-emerald-400 font-mono text-sm flex items-center gap-2"><i class="ti ti-circle-check text-lg"></i> Type compatibility verified — no semantic violations found.</p>' . $sem_extra
        : $sem_extra;

    $html .= _render_phase_section(
        '3',
        'Semantic Analysis',
        'ti-shield-check',
        'pink',
        $semantic['ok'],
        $semantic['errors'],
        $sem_success_msg
    );

    // ── PHASE 4: EXECUTION OUTPUT
    $exec_content = '';
    if ($execution === null) {
        $exec_content = '<p class="text-amber-400 font-mono text-sm flex items-center gap-2"><i class="ti ti-ban text-lg"></i> Execution skipped — resolve errors in earlier phases first.</p>';
        $exec_ok = false;
    } elseif ($execution['error']) {
        $exec_content = '<p class="text-red-400 font-mono text-sm flex items-center gap-2 mb-2"><i class="ti ti-alert-triangle text-lg"></i> Runtime Error:</p>';
        $exec_content .= '<div class="bg-red-950 border border-red-800 rounded p-3 text-red-300 font-mono text-xs">' . htmlspecialchars($execution['error']) . '</div>';
        $exec_ok = false;
    } else {
        $exec_ok = true;
        $exec_content = '';
        if ($mode === 'standard') {
            // Standard mode: show text output as terminal
            if (!empty(trim(strip_tags($execution['output'])))) {
                $exec_content .= '<div class="font-mono text-sm text-emerald-400 leading-relaxed">' . $execution['output'] . '</div>';
            } else {
                $exec_content .= '<p class="text-gray-500 font-mono text-sm italic">Program ran successfully with no output.</p>';
            }
        } else {
            // UI mode: render the HTML component
            $exec_content .= $execution['output'];
        }
    }

    $html .= _render_phase_section(
        '4',
        'Execution Output',
        'ti-player-play',
        'emerald',
        $exec_ok,
        [],
        $exec_content,
        !$exec_ok && $execution === null
    );

    // ── Generated PHP (collapsible, always show if available)
    if ($php_code !== null) {
        $html .= '<details class="bg-gray-900 border-t border-gray-800">';
        $html .= '<summary class="px-4 py-2 text-xs font-bold text-gray-500 uppercase tracking-widest cursor-pointer flex items-center gap-2 hover:text-gray-300 transition">';
        $html .= '<i class="ti ti-brand-php text-indigo-400"></i> Generated PHP (Transpiled Code)</summary>';
        $html .= '<pre class="bg-gray-950 text-indigo-300 font-mono text-xs p-4 overflow-x-auto leading-relaxed">';
        $html .= htmlspecialchars($php_code, ENT_QUOTES, 'UTF-8');
        $html .= '</pre></details>';
    }

    $html .= '</div>'; // end compiler-panel

    return $html;
}

// Helper: render one phase section
function _render_phase_section(
    string $num,
    string $title,
    string $icon,
    string $color,
    bool $ok,
    array $errors,
    string $extra_html = '',
    bool $skipped = false
): string {
    if ($skipped) {
        $badge = '<span class="px-2 py-0.5 rounded text-xs font-bold bg-amber-900 text-amber-300 flex items-center gap-1"><i class="ti ti-minus"></i> SKIPPED</span>';
        $header_bg = 'bg-gray-900';
    } elseif ($ok) {
        $badge = '<span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-900 text-emerald-300 flex items-center gap-1"><i class="ti ti-check"></i> PASS</span>';
        $header_bg = 'bg-gray-900';
    } else {
        $badge = '<span class="px-2 py-0.5 rounded text-xs font-bold bg-red-900 text-red-300 flex items-center gap-1"><i class="ti ti-x"></i> FAIL (' . count($errors) . ')</span>';
        $header_bg = 'bg-gray-900';
    }

    $icon_color = match ($color) {
        'indigo' => 'text-indigo-400',
        'violet' => 'text-violet-400',
        'pink' => 'text-pink-400',
        'emerald' => 'text-emerald-400',
        default => 'text-gray-400',
    };

    $html = "<details class='bg-gray-900 border-t border-gray-800 group' " . ($ok || $num === '1' ? 'open' : '') . ">";
    $html .= "<summary class='px-4 py-3 cursor-pointer flex items-center gap-3 hover:bg-gray-800 transition select-none'>";
    $html .= "<i class='ti $icon $icon_color text-base'></i>";
    $html .= "<span class='text-xs font-bold text-gray-400 uppercase tracking-widest flex-1'>Phase $num — $title</span>";
    $html .= $badge;
    $html .= "<i class='ti ti-chevron-down text-gray-600 text-xs group-open:rotate-180 transition-transform'></i>";
    $html .= "</summary>";
    $html .= "<div class='px-4 pb-4 pt-1'>";

    if (!empty($errors)) {
        $html .= '<div class="space-y-1 mb-3">';
        foreach ($errors as $err) {
            $html .= '<div class="flex items-start gap-2 text-red-400 font-mono text-xs bg-red-950 border border-red-900 rounded px-3 py-2">';
            $html .= '<i class="ti ti-alert-circle shrink-0 mt-0.5"></i>';
            $html .= '<span>' . htmlspecialchars($err, ENT_QUOTES, 'UTF-8') . '</span>';
            $html .= '</div>';
        }
        $html .= '</div>';
    }

    if (!empty($extra_html)) {
        $html .= $extra_html;
    }

    $html .= "</div></details>";

    return $html;
}

// Helper: render token table
function _render_token_table(array $tokens): string
{
    if (empty($tokens)) {
        return '<p class="text-gray-500 font-mono text-xs italic">No tokens found.</p>';
    }

    $type_colors = [
        'KEYWORD' => 'bg-indigo-900 text-indigo-300',
        'IDENTIFIER' => 'bg-cyan-900 text-cyan-300',
        'OPERATOR' => 'bg-orange-900 text-orange-300',
        'INTEGER_LITERAL' => 'bg-blue-900 text-blue-300',
        'FLOAT_LITERAL' => 'bg-emerald-900 text-emerald-300',
        'STRING_LITERAL' => 'bg-green-900 text-green-300',
        'BOOLEAN_LITERAL' => 'bg-amber-900 text-amber-300',
        'SYMBOL' => 'bg-gray-700 text-gray-300',
    ];

    $html = '<div class="overflow-x-auto max-h-48 overflow-y-auto rounded border border-gray-800">';
    $html .= '<table class="text-xs font-mono w-full border-collapse">';
    $html .= '<thead class="sticky top-0 bg-gray-800 z-10"><tr class="text-gray-400">';
    $html .= '<th class="text-left px-3 py-2 w-8">#</th>';
    $html .= '<th class="text-left px-3 py-2">Token</th>';
    $html .= '<th class="text-left px-3 py-2">Type</th>';
    $html .= '<th class="text-left px-3 py-2">Line</th>';
    $html .= '</tr></thead><tbody>';

    foreach ($tokens as $i => $tok) {
        $tc = $type_colors[$tok['type']] ?? 'bg-gray-700 text-gray-300';
        $val = htmlspecialchars($tok['value'], ENT_QUOTES, 'UTF-8');
        $bg = ($i % 2 === 0) ? 'bg-gray-900' : 'bg-gray-950';
        $html .= "<tr class='$bg border-b border-gray-800'>";
        $html .= "<td class='px-3 py-1 text-gray-600'>" . ($i + 1) . "</td>";
        $html .= "<td class='px-3 py-1 text-gray-200'>$val</td>";
        $html .= "<td class='px-3 py-1'><span class='px-2 py-0.5 rounded text-xs font-bold $tc'>{$tok['type']}</span></td>";
        $html .= "<td class='px-3 py-1 text-gray-500'>{$tok['line']}</td>";
        $html .= "</tr>";
    }

    $html .= '</tbody></table></div>';
    $html .= '<p class="text-gray-600 text-xs mt-1 font-mono">' . count($tokens) . ' tokens identified.</p>';

    return $html;
}
