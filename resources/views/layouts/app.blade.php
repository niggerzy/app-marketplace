<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Marketplace')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --bg-body: #0f172a; --bg-card: #1e293b; --bg-nav: rgba(15,23,42,0.85);
            --text-main: #f8fafc; --text-muted: #94a3b8; --primary: #6366f1;
            --accent: #06b6d4; --danger: #ef4444; --success: #22c55e;
            --glass: rgba(255,255,255,0.05); --border: rgba(255,255,255,0.1);
            --shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
            --radius-lg: 16px; --radius-md: 10px; --container: 1200px;
        }
        [data-theme="light"] {
            --bg-body: #f1f5f9; --bg-card: #ffffff; --bg-nav: rgba(255,255,255,0.85);
            --text-main: #0f172a; --text-muted: #64748b; --border: rgba(0,0,0,0.1);
            --glass: rgba(0,0,0,0.03); --shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; transition: background 0.3s, color 0.3s; }
        body { font-family: 'Outfit', sans-serif; background: var(--bg-body); color: var(--text-main); overflow-x: hidden; }
        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        img { max-width: 100%; display: block; }
        button { cursor: pointer; border: none; font-family: inherit; background: none; }
        input, select, textarea { font-family: inherit; }
        .container { max-width: var(--container); margin: 0 auto; padding: 0 20px; }
        .btn { padding: 10px 24px; border-radius: 50px; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--accent)); color: white; box-shadow: 0 4px 15px rgba(99,102,241,0.4); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,0.6); }
        .btn-outline { border: 2px solid var(--border); background: transparent; color: var(--text-main); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-danger { background: rgba(239,68,68,0.1); color: var(--danger); border: 1px solid var(--danger); }
        .btn-danger:hover { background: var(--danger); color: white; }
        .btn-sm { padding: 6px 14px; font-size: 0.9rem; }
        .badge { padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        nav { position: fixed; top: 0; width: 100%; z-index: 100; backdrop-filter: blur(10px); background: var(--bg-nav); border-bottom: 1px solid var(--border); height: 70px; display: flex; align-items: center; }
        .nav-content { display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 0 20px; }
        .logo { font-size: 1.5rem; font-weight: 700; background: linear-gradient(to right, var(--primary), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .nav-links { display: flex; gap: 15px; align-items: center; }
        .nav-item { cursor: pointer; font-weight: 500; display: flex; align-items: center; gap: 5px; white-space: nowrap; color: var(--text-muted); transition: color 0.2s; }
        .nav-item:hover { color: var(--primary); }
        .nav-item.active { color: var(--primary); }
        .icon-badge-wrapper { position: relative; }
        .counter-badge { position: absolute; top: -5px; right: -8px; background: var(--danger); color: white; font-size: 0.6rem; width: 16px; height: 16px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; }
        main { padding-top: 90px; min-height: 100vh; }
        .page-container { max-width: var(--container); margin: 0 auto; padding: 20px; }
        .card { background: var(--bg-card); border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 24px; }
        .input-modern { background: var(--bg-card); border: 1px solid var(--border); padding: 12px 16px; border-radius: var(--radius-md); color: var(--text-main); outline: none; width: 100%; transition: border-color 0.3s; font-size: 0.95rem; }
        .input-modern:focus { border-color: var(--primary); }
        .select-modern { background: var(--bg-card); border: 1px solid var(--border); padding: 10px 16px; border-radius: 50px; color: var(--text-main); outline: none; cursor: pointer; }
        #toast-container { position: fixed; bottom: 20px; right: 20px; z-index: 1000; display: flex; flex-direction: column; gap: 10px; }
        .toast { background: var(--bg-card); border-left: 4px solid var(--primary); padding: 15px 20px; border-radius: var(--radius-md); box-shadow: var(--shadow); display: flex; align-items: center; gap: 10px; animation: slideIn 0.3s forwards; color: var(--text-main); border: 1px solid var(--border); }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp 0.5s ease; }
        .status-pending { background: rgba(234,179,8,0.15); color: #eab308; }
        .status-confirmed { background: rgba(99,102,241,0.15); color: #6366f1; }
        .status-processing { background: rgba(168,85,247,0.15); color: #a855f7; }
        .status-shipped { background: rgba(6,182,212,0.15); color: #06b6d4; }
        .status-completed { background: rgba(34,197,94,0.15); color: #22c55e; }
        .status-cancelled { background: rgba(239,68,68,0.15); color: #ef4444; }
        footer { border-top: 1px solid var(--border); padding: 30px 0; margin-top: 60px; color: var(--text-muted); text-align: center; font-size: 0.9rem; }
        @media (max-width: 768px) {
            .nav-links .nav-text { display: none; }
            .page-container { padding: 15px; }
        }
    </style>
    @yield('styles')
</head>
<body>
<nav>
    <div class="nav-content">
        <a href="/" class="logo">⚡ Marketplace</a>
        <div class="nav-links">
            <a href="/products" class="nav-item">
                <i class="fas fa-store"></i><span class="nav-text">Produk</span>
            </a>
            @auth
                <a href="/my-orders" class="nav-item">
                    <i class="fas fa-box"></i><span class="nav-text">Pesanan</span>
                </a>
                <a href="/orders/checkout" class="nav-item">
                    <div class="icon-badge-wrapper">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="counter-badge" id="cart-count">0</span>
                    </div>
                    <span class="nav-text">Cart</span>
                </a>
                @if(auth()->user()->role === 'admin')
                    <a href="/admin" class="nav-item" style="color: var(--accent)">
                        <i class="fas fa-shield-halved"></i><span class="nav-text">Admin</span>
                    </a>
                @endif
                <form method="POST" action="/logout" style="display:inline">
                    @csrf
                    <button class="btn btn-sm btn-danger" style="border-radius:50px">
                        <i class="fas fa-sign-out-alt"></i><span class="nav-text">Logout</span>
                    </button>
                </form>
            @else
                <a href="/login" class="nav-item"><i class="fas fa-sign-in-alt"></i><span class="nav-text">Login</span></a>
                <a href="/register" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i><span class="nav-text">Register</span></a>
            @endauth
        </div>
    </div>
</nav>

<main>
    <div class="page-container">
        @yield('content')
    </div>
</main>

<footer>
    <p>© {{ date('Y') }} Marketplace · Built with Laravel</p>
</footer>

<div id="toast-container"></div>

<script>
function showToast(msg, type = 'info') {
    const colors = { success: '#22c55e', error: '#ef4444', info: '#6366f1' };
    const el = document.createElement('div');
    el.className = 'toast';
    el.style.borderLeftColor = colors[type] || colors.info;
    el.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}" style="color:${colors[type]}"></i> ${msg}`;
    document.getElementById('toast-container').appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

// Cart count badge
function updateCartBadge() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const count = cart.reduce((a, b) => a + b.quantity, 0);
    const badge = document.getElementById('cart-count');
    if (badge) badge.textContent = count;
}
updateCartBadge();

// Theme toggle
const savedTheme = localStorage.getItem('theme') || 'dark';
if (savedTheme === 'light') document.documentElement.setAttribute('data-theme', 'light');
</script>
@yield('scripts')
</body>
</html>
