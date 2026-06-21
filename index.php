<?php
function patakbuhinAngWikode($code) {
    if (empty(trim($code))) return "";
    preg_match_all('/(?:bilang|numero|teksto|sapat)\s+([a-zA-Z_]\w*)/', $code, $matches);
    $variables = array_unique($matches[1]);
    
    $php_code = "";
    $lines = explode("\n", $code);
    foreach ($lines as $l) {
        $l = trim($l);
        if (empty($l) || str_starts_with($l, '#')) continue;
        
        foreach ($variables as $v) {
            $l = preg_replace('/\b' . $v . '\b/', '$' . $v, $l);
            $l = str_replace('$$', '$', $l);
        }
        
        $l = preg_replace('/^(bilang|numero|teksto|sapat)\s+\$([a-zA-Z_]\w*)\s*=\s*(.*)$/', '$$2 = $3;', $l);
        if (preg_match('/^kung\s+(.*?):$/', $l, $m)) { $l = "if (" . $m[1] . ") {"; }
        if (preg_match('/^kundi_kung\s+(.*?):$/', $l, $m)) { $l = "} elseif (" . $m[1] . ") {"; }
        if (preg_match('/^kundi:$/', $l)) { $l = "} else {"; }
        if ($l === 'wakas') $l = "}";
        if (preg_match('/^isulat\((.*?)\)$/', $l, $m)) { $l = "echo " . $m[1] . " . '<br>';"; }
        
        if (!str_ends_with($l, ';') && !str_ends_with($l, '{') && !str_ends_with($l, '}') && !str_starts_with($l, 'echo')) {
            $l .= ';';
        }
        $php_code .= $l . "\n";
    }

    $open_braces = substr_count($php_code, '{');
    $close_braces = substr_count($php_code, '}');
    for ($i = 0; $i < ($open_braces - $close_braces); $i++) { $php_code .= "}\n"; }

    ob_start();
    try { eval($php_code); } catch (Throwable $e) { echo "<span class='text-red-500 font-bold'>Syntax Error:</span> Pakisuri ang logic at structure."; }
    return ob_get_clean();
}

$output_main = ""; $code_main = "# Tukuyin ang mga baryabol\nteksto kumpanya = \"Wikode Tech\"\nbilang taon = 2026\n\n# Pythonic If Statement\nkung taon == 2026:\n    isulat(\"Tumatakbo ang system ng \" . kumpanya)\nwakas";
if ($_SERVER['REQUEST_METHOD'] === 'POST') { $code_main = $_POST['code']; $output_main = patakbuhinAngWikode($code_main); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wikode | The Native Syntax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.0/dist/full.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#4f46e5', secondary: '#ec4899' } } } }</script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen overflow-x-hidden">

    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="index.php" class="flex items-center gap-2 group cursor-pointer">
                <div class="bg-primary text-white p-1.5 rounded-lg group-hover:bg-secondary transition-colors"><i class="ti ti-code text-xl"></i></div>
                <h1 class="text-2xl font-black tracking-tight text-gray-900">Wikode<span class="text-primary group-hover:text-secondary transition-colors">.</span></h1>
            </a>
            <div class="flex items-center gap-2 bg-gray-100/50 p-1 rounded-full border border-gray-200 shadow-inner">
                <a href="index.php" class="px-6 py-2 bg-white text-primary shadow-sm rounded-full font-bold text-sm transition"><i class="ti ti-terminal-2"></i> Studio</a>
                <a href="examples.php" class="px-6 py-2 text-gray-500 hover:text-primary rounded-full font-bold text-sm transition"><i class="ti ti-apps"></i> App Demos</a>
                <a href="docs.php" class="px-6 py-2 text-gray-500 hover:text-primary rounded-full font-bold text-sm transition"><i class="ti ti-book"></i> Manual & Guide</a>
            </div>
            <div class="hidden md:block w-24"></div>
        </div>
    </nav>

    <main class="flex-grow">
        <section class="relative bg-white overflow-hidden border-b border-gray-100 pb-16">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-gradient-to-tr from-indigo-100/50 to-pink-50/50 rounded-full blur-3xl -z-10"></div>
            <div class="container mx-auto px-6 py-16">
                <div class="flex flex-col-reverse lg:flex-row items-start gap-12">
                    
                    <div class="flex-1 space-y-6 pt-10 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-sm font-semibold border border-indigo-100 shadow-sm">
                            <span class="animate-pulse w-2 h-2 bg-indigo-500 rounded-full"></span> Developed at FEU Tech.
                        </div>
                        <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight text-gray-900 tracking-tight">
                            Code at the speed of <br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Thought.</span>
                        </h1>
                        <p class="text-lg text-gray-500 max-w-lg mx-auto lg:mx-0">
                            Wikode is a structural, Pythonic programming language engineered for Data Science and Logic. No semicolons. No complex boilerplate. Just pure algorithms.
                        </p>
                        <div class="flex justify-center lg:justify-start items-center gap-4 pt-4">
                            <a href="docs.php" class="btn btn-primary px-8 rounded-full shadow-lg shadow-indigo-200 hover:scale-105 transition-transform">Start Learning</a>
                            <a href="examples.php" class="btn btn-outline border-gray-200 text-gray-600 rounded-full px-8 hover:bg-gray-50 transition-colors">Run App Demos</a>
                        </div>
                    </div>
                    
                    <div class="flex-1 w-full max-w-2xl relative mt-12 lg:mt-0">
                        <div class="absolute -top-12 right-4 bg-white px-6 py-3 rounded-t-2xl shadow-xl border-t border-l border-r border-gray-200 text-sm font-bold text-primary flex items-center gap-2 transform transition-all z-10">
                            <i class="ti ti-bulb text-yellow-400 text-xl animate-pulse"></i> <span id="assistant-text">Magsimula sa pag-type ng baryabol...</span>
                        </div>
                        <div class="relative bg-gray-900 shadow-2xl rounded-2xl overflow-hidden border-4 border-white">
                            <div class="bg-gray-800 px-4 py-3 border-b border-gray-700 flex justify-between items-center">
                                <div class="flex gap-2"><div class="w-3 h-3 rounded-full bg-red-500"></div><div class="w-3 h-3 rounded-full bg-yellow-500"></div><div class="w-3 h-3 rounded-full bg-green-500"></div></div>
                                <span class="text-xs font-mono text-gray-400">sandbox.wk</span>
                            </div>
                            <form method="POST" class="flex flex-col">
                                <textarea id="ide-input" name="code" class="w-full h-64 bg-transparent text-indigo-200 font-mono text-sm p-6 focus:outline-none resize-none leading-loose"><?php echo htmlspecialchars($code_main); ?></textarea>
                                <div class="bg-gray-950 p-4 border-t border-gray-800 flex justify-end">
                                    <button type="submit" class="btn btn-sm btn-primary rounded flex items-center gap-1 hover:scale-105 transition-transform"><i class="ti ti-player-play"></i> Compile Code</button>
                                </div>
                            </form>
                            <?php if($output_main): ?>
                            <div class="bg-gray-950 p-6 border-t border-gray-800 text-emerald-400 font-mono text-sm">
                                <div class="text-xs text-gray-500 mb-2 uppercase tracking-wider">Terminal Output</div>
                                <?php echo $output_main; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-gray-900 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-1/2 h-full bg-primary/10 -skew-x-12 transform translate-x-12"></div>
            <div class="container mx-auto px-6 relative z-10 flex flex-col lg:flex-row items-center gap-16">
                <div class="flex-1">
                    <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&q=80&w=800" class="rounded-2xl shadow-2xl border-4 border-gray-800 transform -rotate-2 hover:rotate-0 transition duration-500">
                </div>
                <div class="flex-1 space-y-6">
                    <h2 class="text-secondary font-bold uppercase tracking-widest text-sm">Feature Spotlight</h2>
                    <h3 class="text-4xl lg:text-5xl font-extrabold leading-tight">The Live IDE Assistant.</h3>
                    <p class="text-gray-400 text-lg leading-relaxed">
                        Wikode isn't just a language; it's a teaching environment. As you type in the Studio, our built-in lexical parser reads your keystrokes in real-time, offering native Tagalog hints to guide your logic formatting and syntax structure before you even hit compile.
                    </p>
                    <ul class="space-y-4 mt-8">
                        <li class="flex items-center gap-4 text-gray-300 font-medium"><div class="w-10 h-10 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center"><i class="ti ti-brain"></i></div> Real-time Syntax Prediction</li>
                        <li class="flex items-center gap-4 text-gray-300 font-medium"><div class="w-10 h-10 rounded-full bg-pink-500/20 text-pink-400 flex items-center justify-center"><i class="ti ti-shield-check"></i></div> Instant Semantic Error Catching</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="py-24 bg-gray-50 border-t border-gray-200">
            <div class="container mx-auto px-6 max-w-6xl">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-extrabold text-gray-900">Engineered for Data & Systems</h2>
                    <p class="text-gray-500 mt-4 text-lg">Wikode handles advanced backend logic effortlessly.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="card bg-white p-8 border border-gray-100 shadow-xl hover:-translate-y-2 transition-transform">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-primary flex items-center justify-center text-2xl mb-6"><i class="ti ti-cpu"></i></div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Lexical Parsing</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Native tokenizer maps Tagalog keywords directly to the abstract syntax tree for blazing fast execution.</p>
                    </div>
                    <div class="card bg-white p-8 border border-gray-100 shadow-xl hover:-translate-y-2 transition-transform">
                        <div class="w-14 h-14 rounded-2xl bg-pink-50 text-secondary flex items-center justify-center text-2xl mb-6"><i class="ti ti-box-model"></i></div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Dynamic Binding</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Variables bind to memory instantly at runtime, meaning you never have to pre-allocate RAM sizes manually.</p>
                    </div>
                    <div class="card bg-white p-8 border border-gray-100 shadow-xl hover:-translate-y-2 transition-transform">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-2xl mb-6"><i class="ti ti-shield-check"></i></div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Semantic Checks</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Strict type-checking acts as a safeguard, preventing fatal mathematical operations on 'teksto' fields.</p>
                    </div>
                    <div class="card bg-white p-8 border border-gray-100 shadow-xl hover:-translate-y-2 transition-transform">
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl mb-6"><i class="ti ti-chart-pie"></i></div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Data Science Built</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Designed natively to handle predictive modeling, hybrid architectures, and sales forecasting algorithms.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

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
        const editor = document.getElementById('ide-input');
        const span = document.getElementById('assistant-text');

        editor.addEventListener('keyup', () => {
            const lines = editor.value.split('\n');
            const currentLine = lines[lines.length - 1].trim();

            if (currentLine.startsWith('bilang') || currentLine.startsWith('numero')) {
                span.innerText = "Guide: Idagdag ang pangalan ng baryabol at value (Hal: bilang edad = 18)";
            } else if (currentLine.startsWith('teksto')) {
                span.innerText = "Guide: Gumamit ng quotes para sa string literal (Hal: teksto ngalan = \"Juan\")";
            } else if (currentLine.startsWith('kung')) {
                span.innerText = "Guide: Tapusin ang kondisyon gamit ang colon ( : ) at mag-indent sa susunod na linya.";
            } else if (currentLine.startsWith('isulat')) {
                span.innerText = "Guide: Ilagay sa loob ng parenthesis ang ipi-print (Hal: isulat(edad))";
            } else {
                span.innerText = "Patuloy sa pag-code... Ang parser ay nagmamasid.";
            }
        });
    </script>
</body>
</html>