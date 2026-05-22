<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>404 — Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b1120;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }

        /* Grid */
        .grid-bg {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(37,99,235,0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37,99,235,0.07) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
            z-index: 0;
        }

        /* Glow blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
        }
        .blob-purple {
            width: 500px; height: 500px;
            background: rgba(126,34,206,0.12);
            top: -150px; right: -150px;
            animation: pulse 7s ease-in-out infinite;
        }
        .blob-blue {
            width: 350px; height: 350px;
            background: rgba(37,99,235,0.1);
            bottom: -100px; left: -100px;
            animation: pulse 7s ease-in-out infinite 3.5s;
        }
        @keyframes pulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50%       { opacity: 1;   transform: scale(1.08); }
        }

        /* Card */
        .card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            margin: 0 24px;
            animation: fadeUp 0.65s cubic-bezier(0.16,1,0.3,1) both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0);    }
        }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(30,58,138,0.4);
            border: 1px solid rgba(59,130,246,0.3);
            backdrop-filter: blur(8px);
            margin-bottom: 32px;
        }
        .badge-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #3b82f6;
            animation: blink 1.5s ease-in-out infinite;
        }
        .badge-text {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #93c5fd;
        }
        @keyframes blink {
            0%,100% { opacity: 1; }
            50%      { opacity: 0.25; }
        }

        /* Icon box */
        .icon-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 32px;
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%,100% { transform: translateY(0);   }
            50%     { transform: translateY(-7px); }
        }
        .icon-box {
            width: 112px; height: 112px;
            border-radius: 20px;
            background: rgba(30,58,138,0.2);
            border: 1px solid rgba(59,130,246,0.25);
            backdrop-filter: blur(10px);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .scan-line {
            position: absolute;
            left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, rgba(59,130,246,0.6), transparent);
            animation: scan 2.4s ease-in-out infinite;
        }
        @keyframes scan {
            0%   { top: 0%;   opacity: 0; }
            8%   { opacity: 1; }
            92%  { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        /* Corner decorations */
        .corner { position: absolute; width: 12px; height: 12px; }
        .corner-tl { top: -2px; left: -2px; border-top: 2px solid rgba(59,130,246,0.5); border-left: 2px solid rgba(59,130,246,0.5); border-radius: 3px 0 0 0; }
        .corner-tr { top: -2px; right: -2px; border-top: 2px solid rgba(59,130,246,0.5); border-right: 2px solid rgba(59,130,246,0.5); border-radius: 0 3px 0 0; }
        .corner-bl { bottom: -2px; left: -2px; border-bottom: 2px solid rgba(59,130,246,0.5); border-left: 2px solid rgba(59,130,246,0.5); border-radius: 0 0 0 3px; }
        .corner-br { bottom: -2px; right: -2px; border-bottom: 2px solid rgba(59,130,246,0.5); border-right: 2px solid rgba(59,130,246,0.5); border-radius: 0 0 3px 0; }

        /* Text section */
        .error-num {
            font-size: 64px; font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.04em;
            line-height: 1;
            margin-bottom: 12px;
        }
        .divider-line {
            height: 1px;
            flex: 1;
            background: linear-gradient(90deg, transparent, #334155);
        }
        .divider-line-r {
            background: linear-gradient(90deg, #334155, transparent);
        }
        .error-title {
            font-size: 20px; font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 12px;
        }
        .error-desc {
            font-size: 14px; color: #94a3b8;
            line-height: 1.7;
            max-width: 340px;
            margin: 0 auto;
        }

        /* User info box */
        .info-box {
            background: rgba(15,23,42,0.7);
            border: 1px solid rgba(51,65,85,0.6);
            border-radius: 14px;
            padding: 16px;
            margin: 28px 0;
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .avatar {
            width: 40px; height: 40px; border-radius: 10px;
            background: #1e293b;
            border: 1px solid #334155;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: #94a3b8;
        }
        .user-label {
            font-size: 10px; font-weight: 800;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: #475569; margin-bottom: 2px;
        }
        .user-name {
            font-size: 14px; font-weight: 600; color: #f1f5f9;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 200px;
        }
        .user-email {
            font-size: 12px; color: #64748b;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 200px;
        }

        /* Buttons */
        .btn-row {
            display: flex;
            gap: 10px;
        }
        .btn-primary {
            flex: 1;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 13px 20px;
            background: #2563eb;
            color: #ffffff;
            border: none; border-radius: 12px;
            font-size: 14px; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }
        .btn-primary:hover { background: #1d4ed8; transform: scale(1.02); }
        .btn-primary:active { transform: scale(0.98); }

        .btn-secondary {
            display: flex; align-items: center; justify-content: center;
            padding: 13px 16px;
            background: #1e293b;
            color: #94a3b8;
            border: 1px solid #334155; border-radius: 12px;
            font-size: 14px; font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, color 0.2s, transform 0.15s;
        }
        .btn-secondary:hover { background: #273549; color: #f1f5f9; transform: scale(1.02); }
        .btn-secondary:active { transform: scale(0.98); }

        .footer-note {
            text-align: center;
            font-size: 12px;
            color: #334155;
            margin-top: 24px;
        }
    </style>
</head>
<body>

    <div class="grid-bg"></div>
    <div class="blob blob-purple"></div>
    <div class="blob blob-blue"></div>

    <div class="card">

        {{-- Badge --}}
        <div style="display:flex; justify-content:center;">
            <div class="badge">
                <span class="badge-dot"></span>
                <span class="badge-text">Page Not Found</span>
            </div>
        </div>

        {{-- Icon --}}
        <div style="display:flex; justify-content:center;">
            <div class="icon-wrap">
                <div class="icon-box">
                    <div class="scan-line"></div>
                    <span class="material-symbols-outlined" style="font-size:54px; color:#3b82f6; position:relative; z-index:1; font-variation-settings:'FILL' 1">explore_off</span>
                </div>
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>
            </div>
        </div>

        {{-- 404 Text --}}
        <div style="text-align:center; margin-bottom: 28px;">
            <div style="display:flex; align-items:center; justify-content:center; gap:16px; margin-bottom:12px;">
                <div class="divider-line"></div>
                <span class="error-num">404</span>
                <div class="divider-line divider-line-r"></div>
            </div>
            <h1 class="error-title">Halaman Tidak Ditemukan</h1>
            <p class="error-desc">
                Maaf, halaman yang Anda cari tidak tersedia atau mungkin telah dipindahkan ke alamat lain.
            </p>
        </div>

        {{-- User info --}}
        @auth
        <div class="info-box">
            <div class="avatar">
                <span class="material-symbols-outlined" style="font-size:20px;">person</span>
            </div>
            <div style="flex:1; min-width:0;">
                <p class="user-label">Login sebagai</p>
                <p class="user-name">{{ Auth::user()->name }}</p>
                <p class="user-email">{{ Auth::user()->email }}</p>
            </div>
            @php
                $roleLabels = [
                    'logistics-officer' => ['label' => 'CLO', 'bg' => 'rgba(124,45,18,0.4)', 'color' => '#fb923c', 'border' => 'rgba(194,65,12,0.4)'],
                    'procurement-director' => ['label' => 'Procurement', 'bg' => 'rgba(23,37,84,0.5)', 'color' => '#60a5fa', 'border' => 'rgba(29,78,216,0.4)'],
                    'key-account-manager' => ['label' => 'KAM', 'bg' => 'rgba(8,47,73,0.5)', 'color' => '#22d3ee', 'border' => 'rgba(8,145,178,0.4)'],
                    'head-analytics' => ['label' => 'Analytics', 'bg' => 'rgba(59,7,100,0.5)', 'color' => '#c084fc', 'border' => 'rgba(126,34,206,0.4)'],
                    'financial-controller' => ['label' => 'Finance', 'bg' => 'rgba(5,46,22,0.5)', 'color' => '#4ade80', 'border' => 'rgba(21,128,61,0.4)'],
                ];
                $userRole = Auth::user()->getRoleNames()->first() ?? '';
                $rl = $roleLabels[$userRole] ?? ['label' => 'User', 'bg' => '#1e293b', 'color' => '#94a3b8', 'border' => '#334155'];
            @endphp
            <div style="flex-shrink:0; padding: 4px 10px; border-radius:8px; background:{{ $rl['bg'] }}; color:{{ $rl['color'] }}; border:1px solid {{ $rl['border'] }}; font-size:11px; font-weight:700;">
                {{ $rl['label'] }}
            </div>
        </div>
        @endauth

        {{-- Buttons --}}
        <div class="btn-row">
            <a href="{{ route('dashboard') }}" class="btn-primary">
                <span class="material-symbols-outlined" style="font-size:18px;">dashboard</span>
                Kembali ke Dashboard
            </a>
            @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-secondary">
                    <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn-secondary">
                <span class="material-symbols-outlined" style="font-size:18px;">login</span>
            </a>
            @endauth
        </div>

        <p class="footer-note">Jika Anda merasa ini adalah kesalahan, silakan hubungi tim IT.</p>

    </div>

</body>
</html>
