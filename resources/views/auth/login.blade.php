<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Velocitron — Sign In</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&family=Material+Symbols+Outlined:wght,FILL@300,0&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #060d1f;
            overflow: hidden;
            position: relative;
        }

        /* ── Animated mesh background ── */
        .bg-mesh {
            position: fixed;
            inset: 0;
            z-index: 0;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: drift 12s ease-in-out infinite alternate;
        }

        .orb-1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(33,112,228,0.35) 0%, transparent 70%);
            top: -200px; left: -150px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(99,57,234,0.25) 0%, transparent 70%);
            bottom: -150px; right: -100px;
            animation-delay: -4s;
        }

        .orb-3 {
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(0,180,255,0.15) 0%, transparent 70%);
            top: 40%; left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -8s;
        }

        @keyframes drift {
            0%   { transform: translate(0, 0) scale(1); }
            50%  { transform: translate(30px, -40px) scale(1.05); }
            100% { transform: translate(-20px, 20px) scale(0.95); }
        }

        /* ── Grid overlay ── */
        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* ── Card ── */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 0 20px;
            animation: fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .glass-card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 40px;
            box-shadow:
                0 0 0 1px rgba(33,112,228,0.1),
                0 32px 64px rgba(0,0,0,0.4),
                inset 0 1px 0 rgba(255,255,255,0.08);
        }

        /* ── Logo ── */
        .logo-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 32px;
        }

        .logo-mark {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, #2170e4, #4f8ff0);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.1), 0 8px 24px rgba(33,112,228,0.4);
            position: relative;
            overflow: hidden;
        }

        .logo-mark::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 60%);
        }

        .logo-mark svg {
            width: 22px; height: 22px;
            fill: white;
            position: relative;
            z-index: 1;
        }

        .logo-name {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .logo-name span {
            color: #4f8ff0;
        }

        .logo-tagline {
            font-size: 12px;
            color: rgba(255,255,255,0.35);
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        /* ── Heading ── */
        .heading {
            margin-bottom: 28px;
        }

        .heading h2 {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
            margin-bottom: 6px;
        }

        .heading p {
            font-size: 13.5px;
            color: rgba(255,255,255,0.4);
            line-height: 1.5;
        }

        /* ── Form fields ── */
        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            margin-bottom: 8px;
        }

        .field input {
            width: 100%;
            padding: 11px 14px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }

        .field input::placeholder { color: rgba(255,255,255,0.2); }

        .field input:focus {
            border-color: rgba(33,112,228,0.6);
            background: rgba(33,112,228,0.08);
            box-shadow: 0 0 0 3px rgba(33,112,228,0.15);
        }

        .field-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .field-row label { margin-bottom: 0; }

        .forgot {
            font-size: 12px;
            color: #4f8ff0;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .forgot:hover { opacity: 0.7; }

        /* ── Error ── */
        .error-box {
            background: rgba(186,26,26,0.12);
            border: 1px solid rgba(186,26,26,0.3);
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #ff8a8a;
        }

        /* ── Remember ── */
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            margin-top: 4px;
        }

        .remember input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #2170e4;
            cursor: pointer;
        }

        .remember span {
            font-size: 13px;
            color: rgba(255,255,255,0.4);
        }

        /* ── Button ── */
        .btn-signin {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #2170e4 0%, #1a5bc0 100%);
            border: none;
            border-radius: 11px;
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.2px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 4px 20px rgba(33,112,228,0.4);
        }

        .btn-signin::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .btn-signin:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(33,112,228,0.5);
        }

        .btn-signin:hover::before { opacity: 1; }
        .btn-signin:active { transform: translateY(0); }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.08);
        }

        .divider-text {
            font-size: 12px;
            color: rgba(255,255,255,0.25);
        }

        /* ── Footer ── */
        .card-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 13px;
            color: rgba(255,255,255,0.3);
        }

        .card-footer a {
            color: #4f8ff0;
            font-weight: 600;
            text-decoration: none;
        }

        .card-footer a:hover { text-decoration: underline; }

        /* ── Bottom badge ── */
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            font-size: 11px;
            color: rgba(255,255,255,0.2);
            letter-spacing: 0.3px;
        }

        .security-badge .material-symbols-outlined {
            font-size: 14px;
            color: rgba(255,255,255,0.2);
        }
    </style>
</head>

<body>

    <!-- Background -->
    <div class="bg-mesh">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>
    <div class="bg-grid"></div>

    <!-- Login Card -->
    <div class="login-wrapper">
        <div class="glass-card">

            <!-- Logo -->
            <div class="logo-area">
                <div class="logo-mark">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                </div>
                <div class="logo-name">VELOCI<span>TRON</span></div>
                <div class="logo-tagline">ENTERPRISE BUSINESS INTELLIGENCE</div>
            </div>

            <!-- Heading -->
            <div class="heading">
                <h2>Welcome back</h2>
                <p>Sign in to access your intelligence workspace.</p>
            </div>

            <!-- Errors -->
            @if ($errors->any())
                <div class="error-box">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="you@company.com" required autocomplete="email">
                </div>

                <div class="field">
                    <div class="field-row">
                        <label>Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>
                        @endif
                    </div>
                    <input type="password" name="password"
                        placeholder="••••••••" required autocomplete="current-password">
                </div>

                <div class="remember">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Keep me signed in</span>
                </div>

                <button type="submit" class="btn-signin">Sign In</button>

            </form>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">VELOCITRON v2.0</span>
                <div class="divider-line"></div>
            </div>

            <div class="card-footer">
                Don't have an account?
                <a href="{{ route('register') }}">Request access</a>
            </div>

        </div>

        <div class="security-badge">
            <span class="material-symbols-outlined">lock</span>
            Secured with enterprise-grade encryption
        </div>
    </div>

</body>
</html>