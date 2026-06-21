<?php
require_once 'wikode_engine.php';

// THE 6 APP CODES
$code_forecast = "# Forecasting Module\nbilang actual = 14500\nbilang predicted = 13800\nnumero accuracy = (predicted / actual) * 100\n\napp_forecast(actual, predicted, round(accuracy, 2))";
$code_timer = "# Countdown Module\nteksto kaganapan = \"Launch Timer\"\nbilang segundo = 15\n\napp_timer(kaganapan, segundo)";
$code_payroll = "# Tax & Payroll System\nteksto emp = \"A. Makayan\"\nnumero sahod = 45000\nnumero tax = 0\n\nkung sahod > 30000:\n    tax = sahod * 0.15\nkundi:\n    tax = 0\nwakas\n\nnumero net = sahod - tax\n\napp_payroll(emp, sahod, tax, net)";
$code_pos = "# Point of Sale Logic\nteksto item = \"Mechanical Keyboard\"\nnumero price = 2500.50\nbilang qty = 2\n\nnumero total = price * qty\n\napp_pos(item, price, qty, total)";
$code_inventory = "# Warehouse ROQ Calculator\nteksto item = \"Frozen Meat Box\"\nbilang current_stock = 45\nbilang annual_demand = 5000\nnumero ordering_cost = 50\nnumero holding_cost = 2.5\n\n# Kalkulahin ang ROQ\nnumero roq = sqrt((2 * annual_demand * ordering_cost) / holding_cost)\n\napp_inventory(item, current_stock, round(roq))";
$code_auth = "# Security Authentication\nteksto user = \"Earl John\"\nteksto role = \"Admin\"\nteksto status = \"\"\n\nkung role == \"Admin\":\n    status = \"Access Granted\"\nkundi:\n    status = \"Access Denied\"\nwakas\n\napp_auth(user, role, status)";

$current_code = $code_forecast;
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    $current_code = $_POST['code'];
    $result = wikode_compile_and_run($current_code, 'ui');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wikode | App Library Demos</title>
    <meta name="description"
        content="Interactive Wikode app demos: Forecast, Timer, Payroll, POS, Inventory, and Security modules.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.0/dist/full.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#4f46e5', secondary: '#ec4899' } } } }</script>
    <style>
        .compiler-panel details>summary {
            list-style: none;
        }

        .compiler-panel details>summary::-webkit-details-marker {
            display: none;
        }

        .compiler-panel details[open]>summary .ti-chevron-down {
            transform: rotate(180deg);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-sans flex flex-col min-h-screen">

    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="index.php" class="flex items-center gap-2 group">
                <div class="bg-primary text-white p-1.5 rounded-lg"><i class="ti ti-code text-xl"></i></div>
                <h1 class="text-2xl font-black tracking-tight text-gray-900">Wikode<span class="text-primary">.</span>
                </h1>
            </a>
            <div class="flex items-center gap-2 bg-gray-100/50 p-1 rounded-full border border-gray-200 shadow-inner">
                <a href="index.php"
                    class="px-6 py-2 text-gray-500 hover:text-primary rounded-full font-bold text-sm transition">Studio</a>
                <a href="examples.php"
                    class="px-6 py-2 bg-white text-primary shadow-sm rounded-full font-bold text-sm transition">App
                    Demos</a>
                <a href="docs.php"
                    class="px-6 py-2 text-gray-500 hover:text-primary rounded-full font-bold text-sm transition">Manual
                    &amp; Guide</a>
            </div>
            <div class="hidden md:block w-24"></div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-6 py-12">

        <div class="text-center mb-8">
            <h2 class="text-4xl font-extrabold text-gray-900">App Component Library</h2>
            <p class="text-gray-500 mt-2">Click a module icon below to load its algorithmic code into the Wikode engine.
            </p>
        </div>

        <div class="grid grid-cols-3 md:grid-cols-6 gap-4 max-w-4xl mx-auto mb-12">
            <button onclick="loadApp('forecast')"
                class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div
                    class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl group-hover:bg-indigo-600 group-hover:text-white transition">
                    <i class="ti ti-chart-bar"></i>
                </div><span class="text-xs font-bold text-gray-600">Forecast</span>
            </button>
            <button onclick="loadApp('timer')"
                class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div
                    class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-2xl group-hover:bg-pink-600 group-hover:text-white transition">
                    <i class="ti ti-clock"></i>
                </div><span class="text-xs font-bold text-gray-600">Timers</span>
            </button>
            <button onclick="loadApp('payroll')"
                class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl group-hover:bg-emerald-600 group-hover:text-white transition">
                    <i class="ti ti-receipt"></i>
                </div><span class="text-xs font-bold text-gray-600">Payroll</span>
            </button>
            <button onclick="loadApp('pos')"
                class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div
                    class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl group-hover:bg-amber-600 group-hover:text-white transition">
                    <i class="ti ti-building-store"></i>
                </div><span class="text-xs font-bold text-gray-600">POS
                    Reg.</span>
            </button>
            <button onclick="loadApp('inventory')"
                class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div
                    class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-2xl group-hover:bg-slate-600 group-hover:text-white transition">
                    <i class="ti ti-packages"></i>
                </div><span class="text-xs font-bold text-gray-600">Inventory</span>
            </button>
            <button onclick="loadApp('auth')"
                class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div
                    class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition">
                    <i class="ti ti-user-shield"></i>
                </div><span class="text-xs font-bold text-gray-600">Security</span>
            </button>
        </div>

        <div
            class="card bg-white shadow-2xl overflow-hidden border border-gray-200 flex flex-col lg:flex-row max-w-6xl mx-auto">
            <!-- Left: Code Editor -->
            <div class="flex-1 bg-gray-900 flex flex-col w-full lg:w-1/2">
                <div
                    class="bg-gray-800 px-6 py-4 border-b border-gray-700 font-bold text-white flex justify-between items-center">
                    <span class="flex items-center gap-2"><i class="ti ti-terminal-2 text-primary"></i> App Logic
                        Configurator</span>
                </div>
                <form method="POST" id="demoForm" class="flex flex-col flex-grow">
                    <textarea id="codeArea" name="code"
                        class="w-full flex-grow min-h-[400px] bg-transparent text-indigo-200 font-mono text-sm p-6 focus:outline-none resize-none leading-loose"><?php echo htmlspecialchars($current_code); ?></textarea>
                    <div class="p-4 bg-gray-950 flex justify-end"><button type="submit"
                            class="btn btn-primary rounded flex items-center gap-2">Build UI Component <i
                                class="ti ti-hammer"></i></button></div>
                </form>
            </div>

            <!-- Right: Rendered App Output -->
            <div
                class="flex-1 bg-gray-100 flex flex-col border-l border-gray-200 min-h-[400px] relative w-full lg:w-1/2">
                <h3
                    class="px-6 py-4 text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2 border-b border-gray-200">
                    <i class="ti ti-browser"></i> Rendered Application
                </h3>
                <div class="flex-1 p-8 flex items-center justify-center">
                    <?php
                    if ($result !== null && $result['execution'] !== null) {
                        echo $result['execution']['output'] ?: '<div class="text-center border-2 border-dashed border-gray-300 rounded-xl p-10 text-gray-400 font-bold w-full">No UI output generated.</div>';
                    } elseif ($result !== null && !$result['syntax']['ok']) {
                        echo '<div class="text-center border-2 border-dashed border-red-200 rounded-xl p-10 text-red-400 font-bold w-full"><i class="ti ti-alert-triangle text-2xl mb-2 block"></i>Syntax errors detected — see Compiler Report below.</div>';
                    } elseif ($result !== null && !$result['semantic']['ok']) {
                        echo '<div class="text-center border-2 border-dashed border-red-200 rounded-xl p-10 text-red-400 font-bold w-full"><i class="ti ti-shield-x text-2xl mb-2 block"></i>Semantic errors detected — see Compiler Report below.</div>';
                    } else {
                        echo '<div class="text-center border-2 border-dashed border-gray-300 rounded-xl p-10 text-gray-400 font-bold w-full">I-click ang "Build UI Component" upang i-compile.</div>';
                    }
                    ?>
                </div>

                <?php if ($result !== null): ?>
                    <!-- Compiler Report Toggle -->
                    <div class="border-t border-gray-200">
                        <details class="group">
                            <summary
                                class="px-6 py-3 cursor-pointer flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition select-none">
                                <i class="ti ti-cpu text-indigo-500"></i>
                                <span>Compiler Report</span>
                                <span class="ml-auto flex items-center gap-2">
                                    <?php if ($result['syntax']['ok'] && $result['semantic']['ok']): ?>
                                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-700">All
                                            Phases Passed</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">Errors
                                            Found</span>
                                    <?php endif; ?>
                                    <i
                                        class="ti ti-chevron-down text-gray-400 group-open:rotate-180 transition-transform text-xs"></i>
                                </span>
                            </summary>
                            <div class="bg-gray-900 border-t border-gray-200">
                                <?php echo wikode_render_compiler_panel($result); ?>
                            </div>
                        </details>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="bg-gray-900 text-gray-300 py-12 border-t-4 border-primary mt-auto">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h2 class="text-2xl font-black text-white flex items-center gap-2"><i
                        class="ti ti-code text-primary"></i> Wikode Tech.</h2>
                <p class="text-sm text-gray-500 mt-2">Pioneering structural language for the Philippines.</p>
            </div>
            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                <h3
                    class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 border-b border-gray-700 pb-2">
                    Core Development Team</h3>
                <ul class="text-sm space-y-2">
                    <li class="flex justify-between gap-8"><span class="font-bold text-primary">Leader:</span> <span
                            class="text-white">Naranja, Earl John</span></li>
                    <li class="flex justify-between gap-8"><span class="font-bold text-gray-400">Member 1:</span> <span
                            class="text-gray-200">Tan, Raphael</span></li>
                    <li class="flex justify-between gap-8"><span class="font-bold text-gray-400">Member 2:</span> <span
                            class="text-gray-200">Makayan, Amorsolo</span></li>
                    <li class="flex justify-between gap-8"><span class="font-bold text-gray-400">Member 3:</span> <span
                            class="text-gray-200">Elona, Alexandra</span></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        const apps = {
            'forecast': `<?php echo str_replace("\n", "\\n", addslashes($code_forecast)); ?>`,
            'timer': `<?php echo str_replace("\n", "\\n", addslashes($code_timer)); ?>`,
            'payroll': `<?php echo str_replace("\n", "\\n", addslashes($code_payroll)); ?>`,
            'pos': `<?php echo str_replace("\n", "\\n", addslashes($code_pos)); ?>`,
            'inventory': `<?php echo str_replace("\n", "\\n", addslashes($code_inventory)); ?>`,
            'auth': `<?php echo str_replace("\n", "\\n", addslashes($code_auth)); ?>`
        };
        function loadApp(name) {
            document.getElementById('codeArea').value = apps[name];
        }
    </script>
</body>

</html>