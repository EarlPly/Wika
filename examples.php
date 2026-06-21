<?php
// WIKODE BULLETPROOF UI TRANSPILER
function patakbuhinAngWikodeUI($code) {
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

        // --- ALL 6 MINI APP UI GENERATORS --- //
        
        // 1. FORECAST
        if (preg_match('/^app_forecast\((.*?),\s*(.*?),\s*(.*?)\)$/', $l)) {
            $l = preg_replace('/^app_forecast\((.*?),\s*(.*?),\s*(.*?)\)$/', 'echo "<div class=\'bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden w-full max-w-md mx-auto\'><div class=\'bg-indigo-600 p-4 text-white text-center font-bold uppercase tracking-widest text-sm\'>Sales Prediction Dashboard</div><div class=\'p-8 flex items-center justify-between\'><div class=\'text-center\'><p class=\'text-gray-400 font-bold text-xs uppercase\'>Actual</p><p class=\'text-3xl font-black text-gray-800\'>".$1."</p></div><div class=\'text-indigo-200\'><i class=\'ti ti-arrow-right text-3xl\'></i></div><div class=\'text-center\'><p class=\'text-gray-400 font-bold text-xs uppercase\'>Predicted</p><p class=\'text-3xl font-black text-indigo-600\'>".$2."</p></div></div><div class=\'bg-indigo-50 p-4 text-center border-t border-indigo-100\'><span class=\'badge badge-primary badge-lg\'>Accuracy: ".$3."%</span></div></div>";', $l);
        }
        // 2. TIMERS
        if (preg_match('/^app_timer\((.*?),\s*(.*?)\)$/', $l)) {
            $l = preg_replace('/^app_timer\((.*?),\s*(.*?)\)$/', 'echo "<div class=\'bg-gray-900 rounded-3xl shadow-2xl p-8 border-4 border-gray-800 text-center relative overflow-hidden w-full max-w-sm mx-auto\'><div class=\'absolute -top-10 -right-10 w-32 h-32 bg-pink-500 rounded-full blur-3xl opacity-20\'></div><h3 class=\'text-pink-500 font-bold uppercase tracking-widest text-sm mb-6\'>".$1."</h3><div class=\'w-48 h-48 mx-auto border-8 border-gray-800 rounded-full flex items-center justify-center bg-gray-950 shadow-inner\'><span id=\'timer-val\' class=\'text-6xl font-black text-white font-mono\'>".$2."</span></div><script>let t = parseInt(document.getElementById(\'timer-val\').innerText); let intv = setInterval(()=>{ t--; document.getElementById(\'timer-val\').innerText = t; if(t<=0){clearInterval(intv); document.getElementById(\'timer-val\').innerText=\'0\'; document.getElementById(\'timer-val\').classList.add(\'text-red-500\');} }, 1000);</script></div>";', $l);
        }
        // 3. PAYROLL
        if (preg_match('/^app_payroll\((.*?),\s*(.*?),\s*(.*?),\s*(.*?)\)$/', $l)) {
            $l = preg_replace('/^app_payroll\((.*?),\s*(.*?),\s*(.*?),\s*(.*?)\)$/', 'echo "<div class=\'bg-[#f4f1ea] rounded-xl shadow-xl p-8 border border-gray-300 font-mono text-gray-800 max-w-sm mx-auto relative\'><div class=\'border-b-2 border-dashed border-gray-400 pb-4 mb-4 text-center\'><i class=\'ti ti-receipt text-3xl mb-2 text-gray-500\'></i><h3 class=\'font-black text-xl uppercase tracking-widest\'>Payslip</h3></div><div class=\'space-y-2 mb-6\'><div class=\'flex justify-between\'><span>Employee:</span><span class=\'font-bold\'>".$1."</span></div><div class=\'flex justify-between\'><span>Gross Pay:</span><span class=\'font-bold\'>₱".$2."</span></div><div class=\'flex justify-between text-red-600\'><span>Tax Deduction:</span><span class=\'font-bold\'>-₱".$3."</span></div></div><div class=\'border-t-2 border-gray-800 pt-4 flex justify-between items-center bg-gray-800 text-white p-3 rounded\'><span class=\'font-bold uppercase\'>Net Salary</span><span class=\'text-2xl font-black\'>₱".$4."</span></div></div>";', $l);
        }
        // 4. POS
        if (preg_match('/^app_pos\((.*?),\s*(.*?),\s*(.*?),\s*(.*?)\)$/', $l)) {
            $l = preg_replace('/^app_pos\((.*?),\s*(.*?),\s*(.*?),\s*(.*?)\)$/', 'echo "<div class=\'bg-white rounded-2xl shadow-xl border border-gray-200 p-6 max-w-sm mx-auto\'><div class=\'flex items-center gap-3 border-b border-gray-100 pb-4 mb-4\'><div class=\'w-10 h-10 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center\'><i class=\'ti ti-building-store text-xl\'></i></div><h3 class=\'font-bold text-gray-800 uppercase tracking-wider\'>POS Register</h3></div><div class=\'space-y-3 mb-6\'><div class=\'flex justify-between text-gray-600\'><span>Item:</span><span class=\'font-medium\'>".$1."</span></div><div class=\'flex justify-between text-gray-600\'><span>Price:</span><span class=\'font-medium\'>₱".$2."</span></div><div class=\'flex justify-between text-gray-600\'><span>Qty:</span><span class=\'font-medium\'>x".$3."</span></div></div><div class=\'bg-amber-50 p-4 rounded-xl flex justify-between items-center border border-amber-100\'><span class=\'font-bold text-amber-800 uppercase\'>Total Due</span><span class=\'text-2xl font-black text-amber-600\'>₱".$4."</span></div></div>";', $l);
        }
        // 5. INVENTORY ROQ
        if (preg_match('/^app_inventory\((.*?),\s*(.*?),\s*(.*?)\)$/', $l)) {
            $l = preg_replace('/^app_inventory\((.*?),\s*(.*?),\s*(.*?)\)$/', 'echo "<div class=\'bg-slate-800 rounded-2xl shadow-2xl p-6 text-white max-w-sm mx-auto border border-slate-700\'><h3 class=\'text-slate-400 font-bold uppercase tracking-widest text-xs mb-4 flex items-center gap-2\'><i class=\'ti ti-packages\'></i> Inventory Module</h3><div class=\'text-3xl font-black mb-1\'>".$1."</div><div class=\'text-slate-300 mb-6 text-sm\'>Current Stock: <span class=\'font-bold text-white\'>".$2." units</span></div><div class=\'bg-slate-900 rounded-xl p-4 border border-slate-700 flex justify-between items-center\'><span class=\'text-emerald-400 font-bold uppercase text-xs tracking-wider\'>Optimal ROQ</span><span class=\'text-2xl font-black text-emerald-400\'>".$3."</span></div></div>";', $l);
        }
        // 6. AUTH
        if (preg_match('/^app_auth\((.*?),\s*(.*?),\s*(.*?)\)$/', $l)) {
            $l = preg_replace('/^app_auth\((.*?),\s*(.*?),\s*(.*?)\)$/', 'echo "<div class=\'bg-white rounded-2xl shadow-xl border border-gray-200 p-8 text-center max-w-sm mx-auto\'><div class=\'w-20 h-20 mx-auto bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-4xl mb-4\'><i class=\'ti ti-user-shield\'></i></div><h2 class=\'text-2xl font-black text-gray-800\'>".$1."</h2><p class=\'text-gray-500 font-medium mb-6 uppercase tracking-widest text-xs\'>".$2."</p><div class=\'bg-".($3 == "Access Granted" ? "emerald" : "red")."-100 text-".($3 == "Access Granted" ? "emerald" : "red")."-700 py-3 rounded-lg font-bold uppercase tracking-wider border border-".($3 == "Access Granted" ? "emerald" : "red")."-200\'>".$3."</div></div>";', $l);
        }

        if (!str_ends_with($l, ';') && !str_ends_with($l, '{') && !str_ends_with($l, '}') && !str_starts_with($l, 'echo')) { $l .= ';'; }
        $php_code .= $l . "\n";
    }

    $open_braces = substr_count($php_code, '{');
    $close_braces = substr_count($php_code, '}');
    for ($i = 0; $i < ($open_braces - $close_braces); $i++) { $php_code .= "}\n"; }

    ob_start();
    try { eval($php_code); } catch (Throwable $e) { echo "<div class='bg-red-50 text-red-500 p-6 rounded-xl border-2 border-red-200 font-bold'>Runtime Error: Pakisuri ang iyong algorithm at indentasyon.</div>"; }
    return ob_get_clean();
}

// THE 6 APP CODES
$code_forecast = "# Forecasting Module\nbilang actual = 14500\nbilang predicted = 13800\nnumero accuracy = (predicted / actual) * 100\n\napp_forecast(actual, predicted, round(accuracy, 2))";
$code_timer = "# Countdown Module\nteksto kaganapan = \"Launch Timer\"\nbilang segundo = 15\n\napp_timer(kaganapan, segundo)";
$code_payroll = "# Tax & Payroll System\nteksto emp = \"A. Makayan\"\nnumero sahod = 45000\nnumero tax = 0\n\nkung sahod > 30000:\n    tax = sahod * 0.15\nkundi:\n    tax = 0\nwakas\n\nnumero net = sahod - tax\n\napp_payroll(emp, sahod, tax, net)";
$code_pos = "# Point of Sale Logic\nteksto item = \"Mechanical Keyboard\"\nnumero price = 2500.50\nbilang qty = 2\n\nnumero total = price * qty\n\napp_pos(item, price, qty, total)";
$code_inventory = "# Warehouse ROQ Calculator\nteksto item = \"Frozen Meat Box\"\nbilang current_stock = 45\nbilang annual_demand = 5000\nnumero ordering_cost = 50\nnumero holding_cost = 2.5\n\n# Kalkulahin ang ROQ\nnumero roq = sqrt((2 * annual_demand * ordering_cost) / holding_cost)\n\napp_inventory(item, current_stock, round(roq))";
$code_auth = "# Security Authentication\nteksto user = \"Earl John\"\nteksto role = \"Admin\"\nteksto status = \"\"\n\nkung role == \"Admin\":\n    status = \"Access Granted\"\nkundi:\n    status = \"Access Denied\"\nwakas\n\napp_auth(user, role, status)";

$current_code = $code_forecast;
$output = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_code = $_POST['code'];
    $output = patakbuhinAngWikodeUI($current_code);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wikode | App Library Demos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.0/dist/full.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#4f46e5', secondary: '#ec4899' } } } }</script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans flex flex-col min-h-screen">
    
    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="index.php" class="flex items-center gap-2 group">
                <div class="bg-primary text-white p-1.5 rounded-lg"><i class="ti ti-code text-xl"></i></div>
                <h1 class="text-2xl font-black tracking-tight text-gray-900">Wikode<span class="text-primary">.</span></h1>
            </a>
            <div class="flex items-center gap-2 bg-gray-100/50 p-1 rounded-full border border-gray-200 shadow-inner">
                <a href="index.php" class="px-6 py-2 text-gray-500 hover:text-primary rounded-full font-bold text-sm transition">Studio</a>
                <a href="examples.php" class="px-6 py-2 bg-white text-primary shadow-sm rounded-full font-bold text-sm transition">App Demos</a>
                <a href="docs.php" class="px-6 py-2 text-gray-500 hover:text-primary rounded-full font-bold text-sm transition">Manual & Guide</a>
            </div>
            <div class="hidden md:block w-24"></div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-6 py-12">
        
        <div class="text-center mb-8">
            <h2 class="text-4xl font-extrabold text-gray-900">App Component Library</h2>
            <p class="text-gray-500 mt-2">Click a module icon below to load its algorithmic code into the Wikode engine.</p>
        </div>
        
        <div class="grid grid-cols-3 md:grid-cols-6 gap-4 max-w-4xl mx-auto mb-12">
            <button onclick="loadApp('forecast')" class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl group-hover:bg-indigo-600 group-hover:text-white transition"><i class="ti ti-chart-bar"></i></div><span class="text-xs font-bold text-gray-600">Forecast</span>
            </button>
            <button onclick="loadApp('timer')" class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-2xl group-hover:bg-pink-600 group-hover:text-white transition"><i class="ti ti-clock"></i></div><span class="text-xs font-bold text-gray-600">Timers</span>
            </button>
            <button onclick="loadApp('payroll')" class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl group-hover:bg-emerald-600 group-hover:text-white transition"><i class="ti ti-receipt"></i></div><span class="text-xs font-bold text-gray-600">Payroll</span>
            </button>
            <button onclick="loadApp('pos')" class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl group-hover:bg-amber-600 group-hover:text-white transition"><i class="ti ti-building-store"></i></div><span class="text-xs font-bold text-gray-600">POS Reg.</span>
            </button>
            <button onclick="loadApp('inventory')" class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-2xl group-hover:bg-slate-600 group-hover:text-white transition"><i class="ti ti-packages"></i></div><span class="text-xs font-bold text-gray-600">Inventory</span>
            </button>
            <button onclick="loadApp('auth')" class="bg-white p-4 rounded-2xl shadow-md border border-gray-200 hover:-translate-y-2 hover:shadow-lg transition flex flex-col items-center justify-center gap-2 group">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition"><i class="ti ti-user-shield"></i></div><span class="text-xs font-bold text-gray-600">Security</span>
            </button>
        </div>

        <div class="card bg-white shadow-2xl overflow-hidden border border-gray-200 flex flex-col lg:flex-row max-w-6xl mx-auto">
            <div class="flex-1 bg-gray-900 flex flex-col w-full lg:w-1/2">
                <div class="bg-gray-800 px-6 py-4 border-b border-gray-700 font-bold text-white flex justify-between items-center">
                    <span class="flex items-center gap-2"><i class="ti ti-terminal-2 text-primary"></i> App Logic Configurator</span>
                </div>
                <form method="POST" id="demoForm" class="flex flex-col flex-grow">
                    <textarea id="codeArea" name="code" class="w-full flex-grow min-h-[400px] bg-transparent text-indigo-200 font-mono text-sm p-6 focus:outline-none resize-none leading-loose"><?php echo htmlspecialchars($current_code); ?></textarea>
                    <div class="p-4 bg-gray-950 flex justify-end"><button type="submit" class="btn btn-primary rounded flex items-center gap-2">Build UI Component <i class="ti ti-hammer"></i></button></div>
                </form>
            </div>
            <div class="flex-1 bg-gray-100 p-8 flex flex-col justify-center border-l border-gray-200 min-h-[400px] relative w-full lg:w-1/2">
                <h3 class="absolute top-4 left-6 text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2"><i class="ti ti-browser"></i> Rendered Application</h3>
                <div class="mt-8 w-full flex justify-center">
                    <?php echo $output ?: '<div class="text-center border-2 border-dashed border-gray-300 rounded-xl p-10 text-gray-400 font-bold w-full">I-click ang "Build UI Component" upang i-compile.</div>'; ?>
                </div>
            </div>
        </div>
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
        const apps = {
            'forecast': `<?php echo str_replace("\n", "\\n", addslashes($code_forecast)); ?>`,
            'timer': `<?php echo str_replace("\n", "\\n", addslashes($code_timer)); ?>`,
            'payroll': `<?php echo str_replace("\n", "\\n", addslashes($code_payroll)); ?>`,
            'pos': `<?php echo str_replace("\n", "\\n", addslashes($code_pos)); ?>`,
            'inventory': `<?php echo str_replace("\n", "\\n", addslashes($code_inventory)); ?>`,
            'auth': `<?php echo str_replace("\n", "\\n", addslashes($code_auth)); ?>`
        };
        function loadApp(name) { document.getElementById('codeArea').value = apps[name]; }
    </script>
</body>
</html>