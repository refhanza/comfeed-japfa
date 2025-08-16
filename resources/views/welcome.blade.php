<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Comfeed Japfa') }} - Inventory Management</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            backdrop-filter: blur(10px);
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .subtitle {
            color: #666;
            font-size: 1.3rem;
            margin-bottom: 2rem;
            font-weight: 300;
        }
        .success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
            border: 2px solid #b8dacc;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .nav-links {
            margin-top: 2.5rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .nav-links a {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }
        .nav-links a:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.5);
            background: linear-gradient(135deg, #5a67d8 0%, #6b5b95 100%);
        }
        .nav-links a.primary {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.3);
        }
        .nav-links a.primary:hover {
            background: linear-gradient(135deg, #ff5252 0%, #d32f2f 100%);
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.5);
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        .feature {
            background: rgba(255, 255, 255, 0.8);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.1);
        }
        .feature h3 {
            color: #667eea;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        .feature p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }
        .footer-text {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏭 {{ config('app.name', 'Comfeed Japfa') }}</h1>
        <p class="subtitle">Sistem Manajemen Inventori Terpadu</p>
        
        <div class="success">
            ✅ <strong>Laravel berhasil berjalan!</strong><br>
            Framework: Laravel {{ app()->version() }}<br>
            PHP Version: {{ phpversion() }}<br>
            Environment: {{ config('app.env') }}
        </div>

        <div class="features">
            <div class="feature">
                <h3>📦 Manajemen Barang</h3>
                <p>Kelola stok barang dengan mudah dan efisien</p>
            </div>
            <div class="feature">
                <h3>📊 Transaksi</h3>
                <p>Catat transaksi masuk dan keluar barang</p>
            </div>
            <div class="feature">
                <h3>👥 User Management</h3>
                <p>Kelola user dan hak akses sistem</p>
            </div>
            <div class="feature">
                <h3>📈 Dashboard</h3>
                <p>Pantau statistik dan laporan real-time</p>
            </div>
        </div>

        <div class="nav-links">
            <a href="/login">Login</a>
            <a href="/register">Register</a>
            <a href="/dashboard" class="primary">Dashboard</a>
        </div>

        <p class="footer-text">
            Sistem Inventory Management untuk {{ config('app.name', 'Comfeed Japfa') }}<br>
            Solusi terpadu untuk pengelolaan inventori perusahaan
        </p>
    </div>
</body>
</html>