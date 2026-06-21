<?php
$current_phase = isset($_GET['phase']) ? (int)$_GET['phase'] : 1;
if ($current_phase < 1) $current_phase = 1;
if ($current_phase > 8) $current_phase = 8;

// Graphic Data Type Builder for Phase 7
$data_types_html = "
<div class='grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 mb-6'>
    <div class='bg-blue-50 border border-blue-200 p-6 rounded-2xl flex items-start gap-4 shadow-sm'>
        <div class='w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl font-bold shrink-0'>123</div>
        <div><h4 class='font-black text-blue-900 text-lg'>bilang <span class='text-xs text-blue-500 font-normal uppercase tracking-widest ml-2'>Integer</span></h4><p class='text-sm text-blue-700 mt-1'>Used for whole numbers only. Perfect for loop counters or exact quantities (e.g., Inventory counts).</p></div>
    </div>
    <div class='bg-emerald-50 border border-emerald-200 p-6 rounded-2xl flex items-start gap-4 shadow-sm'>
        <div class='w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl font-bold shrink-0'>.99</div>
        <div><h4 class='font-black text-emerald-900 text-lg'>numero <span class='text-xs text-emerald-500 font-normal uppercase tracking-widest ml-2'>Float</span></h4><p class='text-sm text-emerald-700 mt-1'>Used for decimal values. Essential for financial calculations, ROQ formulas, and algorithm outputs.</p></div>
    </div>
    <div class='bg-purple-50 border border-purple-200 p-6 rounded-2xl flex items-start gap-4 shadow-sm'>
        <div class='w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-xl font-bold shrink-0'>Abc</div>
        <div><h4 class='font-black text-purple-900 text-lg'>teksto <span class='text-xs text-purple-500 font-normal uppercase tracking-widest ml-2'>String</span></h4><p class='text-sm text-purple-700 mt-1'>Used for words or characters. Must be enclosed in double quotes. Used heavily for Dashboard Titles.</p></div>
    </div>
    <div class='bg-amber-50 border border-amber-200 p-6 rounded-2xl flex items-start gap-4 shadow-sm'>
        <div class='w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xl font-bold shrink-0'>T/F</div>
        <div><h4 class='font-black text-amber-900 text-lg'>sapat / mali <span class='text-xs text-amber-500 font-normal uppercase tracking-widest ml-2'>Boolean</span></h4><p class='text-sm text-amber-700 mt-1'>Used for logical true or false states. The backbone of all conditional routing and security loops.</p></div>
    </div>
</div>";

$phases_data = [
    1 => [
        "title" => "Phase 1: Language Design",
        "purpose" => "To engineer a high-performance, structurally typed programming language tailored specifically for developers by utilizing Pythonic indentation and bridging cognitive gaps with native terminology.",
        "spec" => "The core architectural decision of Wikode v2.0 was the complete removal of C-style syntactic boilerplate. We eliminated semicolons (;) as line terminators and removed curly braces ({}) entirely. Instead, the basic program structure relies on clean line breaks and precise indentation to define scope.",
        "guide" => "When designing a new programming language, the very first step is identifying the 'Why'. By removing the strict punctuation rules of Java/C++ and replacing them with a Python-like structure, Wikode allows the programmer to write code that reads almost like a natural sentence. When you want to start a block of code—such as an if statement or a loop—you simply end the declaration with a <strong>colon ( : )</strong> and indent the next line.",
        "quiz" => ["code" => "kung edad > 18 {input}\n    isulat('Sapat')", "blank" => ":", "placeholder" => "symbol", "desc" => "What symbol is used at the end of a condition to start a block of code?"]
    ],
    2 => [
        "title" => "Phase 2: Lexical Analysis",
        "purpose" => "The tokenizer functions as the primary gatekeeper of the language, scanning the raw input code as strings and identifying native data keywords before reading their assigned values.",
        "spec" => "During this phase, the compiler groups raw characters into meaningful 'Tokens'. These are explicitly categorized into:<br><ul class='list-disc ml-6 mt-2 text-gray-600 font-medium'><li><strong>Identifiers</strong> (custom names defined by the user)</li><li><strong>Keywords</strong> (reserved system words that trigger functions)</li><li><strong>Operators</strong> (+, -, =, / for math and assignment)</li><li><strong>Literals</strong> (raw data, like strings or numbers)</li></ul>",
        "guide" => "Imagine the Lexical Analyzer as a highly efficient reading machine. When you type `isulat(\"Hello\")`, the machine reads the characters, checks its internal dictionary, and realizes 'Ah! This is the reserved Keyword for the print function'. This lexical mapping is crucial because it ensures that native words are securely translated. Thus, the keyword to output data is always <strong>isulat</strong>.",
        "quiz" => ["code" => "{input}('Kamusta Mundo')", "blank" => "isulat", "placeholder" => "keyword", "desc" => "What is the keyword to print a literal string to the screen?"]
    ],
    3 => [
        "title" => "Phase 3: Syntax Analysis",
        "purpose" => "This phase constructs the Abstract Syntax Tree (AST), validating the overall program structure to ensure that the previously identified lexical tokens are arranged in a logical, compilable order.",
        "spec" => "While Lexical Analysis checks the vocabulary, Syntax Analysis checks the grammar. The grammar rules of Wikode strictly dictate that data assignments must flow from right to left (the evaluated value on the right is assigned to the identifier on the left).",
        "guide" => "Even if all the words (tokens) in your code are correct, they must be in the right order. For example, '20 = edad bilang' is lexically valid but syntactically broken. Syntax analysis ensures that when you assign a variable, you place the identifier first, followed by the <strong>equals sign ( = )</strong> operator, followed by the value.",
        "quiz" => ["code" => "bilang edad {input} 20", "blank" => "=", "placeholder" => "operator", "desc" => "What operator is used to assign a value to an identifier?"]
    ],
    4 => [
        "title" => "Phase 4: Scope and Binding",
        "purpose" => "To autonomously manage variable declarations, dictate memory lifecycle, and allocate memory dynamically during the runtime of the application.",
        "spec" => "Scope determines where a variable 'lives' and is accessible. In Wikode, Scope is determined purely by indentation depth. Binding is the process of attaching a variable name to an actual physical memory address. Wikode utilizes dynamic runtime binding.",
        "guide" => "When you declare a variable, the computer needs to carve out a specific block of RAM to store that data. If you want to declare a general variable without specifying its exact data size immediately, you use the generic keyword <strong>baryabol</strong>. This allows the engine to loosely bind the memory until it processes the data inside it.",
        "quiz" => ["code" => "{input} resulta = 100", "blank" => "baryabol", "placeholder" => "keyword", "desc" => "What is the generic keyword to declare a variable?"]
    ],
    5 => [
        "title" => "Phase 5: Semantic Analysis",
        "purpose" => "Acts as the logical guardian of the execution engine, preventing fatal data manipulation errors by checking the logical context and meaning behind the syntax.",
        "spec" => "The Semantic Analyzer performs strict Type Checking prior to execution. While the syntax might be perfectly valid, attempting to subtract a string literal ('teksto') from an integer identifier ('bilang') will trigger a semantic halt.",
        "guide" => "Semantic analysis catches human logic errors before they crash the server. Imagine you have a variable holding a name, and another holding a salary. Dividing a salary by a name is valid grammar, but the Semantic Analyzer will catch it as mathematically impossible. Therefore, utilizing specific types like <strong>numero</strong> for decimal/float numbers is critical.",
        "quiz" => ["code" => "{input} presyo = 99.50", "blank" => "numero", "placeholder" => "keyword", "desc" => "What keyword is used to declare a variable containing a decimal/float?"]
    ],
    6 => [
        "title" => "Phase 6: Control Flow",
        "purpose" => "Dictates the logical branching, decision-making, and looping mechanics of algorithms, allowing the program to react dynamically to different data states.",
        "spec" => "Control flow is implemented natively. Conditional statements are executed using the <strong>kung</strong> (if), <strong>kundi_kung</strong> (else if), and <strong>kundi</strong> (else) keywords. Iteration utilizes the <strong>para_sa</strong> and <strong>habang</strong> keywords.",
        "guide" => "A program without control flow is just a basic calculator. Control flow gives your application intelligence. It allows the software to evaluate real-time data and make decisions. To execute code only when a specific condition is met, you must initialize the statement with the <strong>kung</strong> keyword.",
        "quiz" => ["code" => "{input} puntos == 100:\n    isulat('Panalo')", "blank" => "kung", "placeholder" => "keyword", "desc" => "What keyword is used to create an 'if' conditional statement?"]
    ],
    7 => [
        "title" => "Phase 7: Data Types",
        "purpose" => "To standardize variables into strict native classifications, ensuring that the backend engine knows exactly how to format and allocate memory for different data structures.",
        "spec" => "Wikode maps standard C-science data concepts to highly intuitive equivalents. The engine handles implicit conversion when combining these types for UI output. $data_types_html",
        "guide" => "Choosing the right data type is crucial for system optimization. If you are tracking inventory quantities, you cannot have half an item, so you use 'bilang' (Integer). If you are preparing data to be displayed on a website (like an employee name), you must declare it as <strong>teksto</strong> (String) so the UI compiler knows to render it as readable text.",
        "quiz" => ["code" => "{input} pangalan = 'Juan Dela Cruz'", "blank" => "teksto", "placeholder" => "keyword", "desc" => "What keyword is used to declare a String data type?"]
    ],
    8 => [
        "title" => "Phase 8: Object-Oriented Features",
        "purpose" => "To enable enterprise-level software architecture by allowing developers to encapsulate data and functions into reusable blueprints, known as classes.",
        "spec" => "Object-Oriented Programming (OOP) in Wikode is implemented via the <strong>klase</strong> keyword, which defines the blueprint and properties of a structural object.",
        "guide" => "As applications grow into massive systems, writing linear, top-to-bottom code becomes impossible to maintain. OOP solves this by wrapping variables and functions into single 'Objects'. Think of a 'klase' (Class) as a blueprint. To actually use it, you must build the object in memory. You do this by creating a <strong>bagong</strong> (new) instance of that blueprint.",
        "quiz" => ["code" => "baryabol admin = {input} User()", "blank" => "bagong", "placeholder" => "keyword", "desc" => "What keyword is used to create a new instance (object) of a class?"]
    ]
];

$phase = $phases_data[$current_phase];
$next_phase = $current_phase < 8 ? $current_phase + 1 : 8;
$prev_phase = $current_phase > 1 ? $current_phase - 1 : 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wikode | Comprehensive Manual</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.0/dist/full.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#4f46e5', secondary: '#ec4899' } } } }</script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col">

    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm shrink-0">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="index.php" class="flex items-center gap-2 group cursor-pointer">
                <div class="bg-primary text-white p-1.5 rounded-lg"><i class="ti ti-code text-xl"></i></div>
                <h1 class="text-2xl font-black tracking-tight text-gray-900">Wikode<span class="text-primary">.</span></h1>
            </a>
            <div class="flex items-center gap-2 bg-gray-100/50 p-1 rounded-full border border-gray-200 shadow-inner">
                <a href="index.php" class="px-6 py-2 text-gray-500 hover:text-primary rounded-full font-bold text-sm transition">Studio</a>
                <a href="examples.php" class="px-6 py-2 text-gray-500 hover:text-primary rounded-full font-bold text-sm transition">App Demos</a>
                <a href="docs.php" class="px-6 py-2 bg-white text-primary shadow-sm rounded-full font-bold text-sm transition">Manual & Guide</a>
            </div>
            <div class="hidden md:block w-24"></div>
        </div>
    </nav>

    <div class="container mx-auto px-6 py-12 flex-1 flex flex-col lg:flex-row gap-12">
        
        <aside class="w-full lg:w-64 shrink-0 border-r border-gray-200 pr-6">
            <h4 class="font-bold text-gray-900 mb-4 uppercase text-xs tracking-widest flex items-center gap-2"><i class="ti ti-book"></i> Modules</h4>
            <ul class="space-y-1">
                <?php for($i = 1; $i <= 8; $i++): ?>
                    <li>
                        <a href="docs.php?phase=<?php echo $i; ?>" 
                           class="block px-4 py-3 rounded-xl text-sm font-semibold transition-colors flex justify-between items-center <?php echo ($current_phase == $i) ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:bg-indigo-50 hover:text-primary'; ?>">
                            Phase <?php echo $i; ?>
                            <?php if($current_phase == $i): ?><i class="ti ti-chevron-right text-white"></i><?php endif; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </aside>

        <main class="flex-1 flex flex-col">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-10 lg:p-16 relative overflow-hidden flex-grow">
                
                <span class="badge badge-primary badge-outline text-xs font-bold mb-4 uppercase tracking-wide">System Architecture Reference</span>
                <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-10"><?php echo htmlspecialchars($phase['title']); ?></h1>
                
                <div class="space-y-10 mb-12">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 border-b-2 border-indigo-100 pb-2 mb-4 flex items-center gap-2"><i class="ti ti-target text-primary"></i> 1. Purpose & Implementation</h3>
                        <p class="text-gray-600 leading-relaxed text-lg text-justify"><?php echo htmlspecialchars($phase['purpose']); ?></p>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 border-b-2 border-pink-100 pb-2 mb-4 flex items-center gap-2"><i class="ti ti-list-check text-secondary"></i> 2. Technical Specifications</h3>
                        <p class="text-gray-600 leading-relaxed text-lg text-justify"><?php echo $phase['spec']; ?></p>
                    </div>
                    
                    <div class="bg-indigo-50 border-l-4 border-primary p-8 rounded-r-2xl shadow-sm">
                        <h3 class="text-xl font-bold text-indigo-900 mb-3 flex items-center gap-2"><i class="ti ti-bulb text-yellow-500 text-2xl"></i> Conceptual Guide</h3>
                        <p class="text-indigo-800 text-lg leading-relaxed text-justify"><?php echo $phase['guide']; ?></p>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-2xl p-8 lg:p-10 border border-gray-800 shadow-2xl relative mt-16">
                    <div class="absolute top-0 right-8 transform -translate-y-1/2 bg-secondary text-white px-6 py-2 rounded-full text-xs font-black uppercase shadow-lg tracking-widest">Knowledge Check</div>
                    <h4 class="text-white font-bold text-xl mb-2"><?php echo $phase['quiz']['desc']; ?></h4>
                    <p class="text-gray-400 text-sm mb-6">Complete this logic gate correctly to unlock the next documentation phase. Read the <strong>Conceptual Guide</strong> above if you are stuck.</p>
                    
                    <div class="bg-[#1e1e1e] p-8 rounded-xl font-mono text-lg text-gray-300 border border-gray-700 shadow-inner overflow-x-auto whitespace-pre leading-loose">
                        <?php 
                            // This guarantees the input field stays perfectly inline with the code without breaking the layout.
                            $htmlInput = '<input type="text" id="quizInput" class="bg-gray-800 text-white px-2 py-0.5 rounded border border-gray-600 focus:border-primary focus:ring-1 focus:ring-primary w-24 text-center mx-1 inline-block font-bold" placeholder="'.$phase['quiz']['placeholder'].'">';
                            echo str_replace('{input}', $htmlInput, $phase['quiz']['code']);
                        ?>
                    </div>

                    <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                        <button onclick="checkAnswer()" class="btn btn-primary btn-lg rounded w-full md:w-auto text-white shadow-lg">Submit Code Evaluation</button>
                        <span id="quizFeedback" class="font-bold hidden items-center gap-2 text-lg"></span>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center">
                <?php if($current_phase > 1): ?>
                    <a href="docs.php?phase=<?php echo $prev_phase; ?>" class="btn btn-outline border-gray-300 text-gray-600 rounded px-8 flex items-center gap-2"><i class="ti ti-arrow-left"></i> Previous Phase</a>
                <?php else: ?>
                    <div></div> <?php endif; ?>

                <?php if($current_phase < 8): ?>
                    <a href="docs.php?phase=<?php echo $next_phase; ?>" id="nextBtn" class="btn btn-primary rounded px-10 flex items-center gap-2 pointer-events-none opacity-50 transition-all duration-500">Proceed to Phase <?php echo $next_phase; ?> <i class="ti ti-arrow-right"></i></a>
                <?php else: ?>
                    <a href="index.php" id="nextBtn" class="btn btn-success text-white rounded px-10 flex items-center gap-2 pointer-events-none opacity-50 transition-all duration-500">Complete Tutorial <i class="ti ti-check"></i></a>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <footer class="bg-gray-900 text-gray-300 py-12 border-t-4 border-primary mt-auto">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h2 class="text-2xl font-black text-white flex items-center gap-2"><i class="ti ti-code text-primary"></i> Wikode Tech.</h2>
                <p class="text-sm text-gray-500 mt-2">Pioneering structural language for the Philippines.</p>
            </div>
            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 border-b border-gray-700 pb-2">Core Development Team</h3>
                <ul class="text-sm space-y-2">
                    <li class="flex justify-between gap-8"><span class="font-bold text-primary">Leader:</span> <span class="text-white">Naranja, Earl John</span></li>
                    <li class="flex justify-between gap-8"><span class="font-bold text-gray-400">Member 1:</span> <span class="text-gray-200">Tan, Raphael</span></li>
                    <li class="flex justify-between gap-8"><span class="font-bold text-gray-400">Member 2:</span> <span class="text-gray-200">Makayan, Amorsolo</span></li>
                    <li class="flex justify-between gap-8"><span class="font-bold text-gray-400">Member 3:</span> <span class="text-gray-200">Elona, Alexandra</span></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        const correctAnswer = "<?php echo addslashes($phase['quiz']['blank']); ?>".toLowerCase().trim();
        const inputField = document.getElementById('quizInput');
        const feedback = document.getElementById('quizFeedback');
        const nextBtn = document.getElementById('nextBtn');

        function checkAnswer() {
            const userAnswer = inputField.value.toLowerCase().trim();
            feedback.classList.remove('hidden');
            
            if (userAnswer === correctAnswer) {
                feedback.innerHTML = '<i class="ti ti-circle-check text-3xl"></i> Logic Validated. You may proceed.';
                feedback.className = "font-bold flex items-center gap-2 text-emerald-400 text-xl";
                inputField.classList.remove('border-red-500');
                inputField.classList.add('border-emerald-500', 'bg-emerald-50', 'text-emerald-900');
                
                nextBtn.classList.remove('pointer-events-none', 'opacity-50');
                nextBtn.classList.add('animate-pulse', 'shadow-[0_0_20px_rgba(79,70,229,0.5)]');
            } else {
                feedback.innerHTML = '<i class="ti ti-circle-x text-3xl"></i> Logic Failed. Subukan ulit.';
                feedback.className = "font-bold flex items-center gap-2 text-red-400 text-xl";
                inputField.classList.add('border-red-500');
            }
        }

        inputField.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                checkAnswer();
            }
        });
    </script>
</body>
</html>