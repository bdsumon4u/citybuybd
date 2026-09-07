<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In &mdash; {{ $settings->invoice_brand_name ?? config('app.name', 'CityBuyBD') }}</title>

    @if(!empty($settings?->favicon))
        <link rel="icon" type="image/x-icon" href="{{ asset('backend/img/' . $settings->favicon) }}">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-glow: rgba(79, 70, 229, 0.25);
            --primary-light: #eef2ff;
            --dark-bg: #0b0f19;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --border-focus: #6366f1;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --danger-border: #fecaca;
            --success: #10b981;
            --success-light: #ecfdf5;
            --radius-lg: 16px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .login-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
            overflow: hidden;
        }

        /* ===== BRAND / VISUAL HERO (LEFT) ===== */
        .brand-hero {
            position: relative;
            flex: 1.1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 56px;
            background: linear-gradient(145deg, #0b0f19 0%, #0f172a 50%, #1e1b4b 100%);
            color: #ffffff;
            overflow: hidden;
        }

        /* Ambient Glow & Grid background */
        .hero-bg-glow {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 1;
        }

        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.45;
            animation: floatGlow 14s infinite alternate ease-in-out;
        }

        .glow-orb-1 {
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
            top: -100px;
            left: -100px;
        }

        .glow-orb-2 {
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, #ec4899 0%, rgba(236, 72, 153, 0) 70%);
            bottom: -80px;
            right: -60px;
            animation-delay: -5s;
        }

        .glow-orb-3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #06b6d4 0%, rgba(6, 182, 212, 0) 70%);
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -9s;
        }

        .hero-grid-pattern {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(circle at center, black 40%, transparent 80%);
            -webkit-mask-image: radial-gradient(circle at center, black 40%, transparent 80%);
        }

        @keyframes floatGlow {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(30px) scale(1.08); }
            100% { transform: translateY(-20px) scale(0.95); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .brand-top {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: var(--radius-md);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            color: #ffffff;
            transition: var(--transition);
        }

        .brand-logo-wrap:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }

        .brand-logo-img {
            max-height: 38px;
            max-width: 150px;
            object-fit: contain;
        }

        .brand-name-fallback {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-badge {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 4px 10px;
            border-radius: 20px;
            background: rgba(99, 102, 241, 0.2);
            color: #a5b4fc;
            border: 1px solid rgba(99, 102, 241, 0.4);
        }

        .hero-center {
            margin: auto 0;
            padding: 48px 0;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            margin-bottom: 24px;
            color: #e2e8f0;
        }

        .hero-tag-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 10px #10b981;
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.85); }
        }

        .hero-title {
            font-size: clamp(2rem, 3.2vw, 2.75rem);
            font-weight: 800;
            line-height: 1.18;
            letter-spacing: -0.03em;
            margin-bottom: 18px;
        }

        .hero-title-highlight {
            background: linear-gradient(135deg, #a5b4fc 0%, #6366f1 50%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.05rem;
            line-height: 1.6;
            color: #94a3b8;
            max-width: 480px;
            margin-bottom: 36px;
        }

        /* Features List */
        .hero-features {
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-width: 460px;
        }

        .feature-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-md);
            backdrop-filter: blur(12px);
            transition: var(--transition);
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.18);
            transform: translateX(4px);
        }

        .feature-icon-box {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background: rgba(99, 102, 241, 0.18);
            border: 1px solid rgba(99, 102, 241, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a5b4fc;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .feature-text h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: #f1f5f9;
            margin-bottom: 2px;
        }

        .feature-text p {
            font-size: 0.82rem;
            color: #94a3b8;
        }

        .hero-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #64748b;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .hero-footer a {
            color: #94a3b8;
            text-decoration: none;
            transition: var(--transition);
        }

        .hero-footer a:hover {
            color: #ffffff;
        }

        /* ===== AUTH FORM SECTION (RIGHT) ===== */
        .auth-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px clamp(24px, 5vw, 64px);
            background: #ffffff;
            position: relative;
        }

        .auth-topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            width: 100%;
        }

        .store-pill-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            padding: 9px 18px;
            border-radius: 30px;
            border: 1px solid var(--border-color);
            background: #ffffff;
            transition: var(--transition);
        }

        .store-pill-link:hover {
            color: var(--primary);
            border-color: #cbd5e1;
            background: var(--primary-light);
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.1);
        }

        .store-pill-link svg {
            transition: transform 0.2s ease;
        }

        .store-pill-link:hover svg {
            transform: translateX(3px);
        }

        .auth-card-wrap {
            width: 100%;
            max-width: 440px;
            margin: auto;
            padding: 24px 0;
        }

        /* Mobile Brand Display */
        .mobile-brand {
            display: none;
            margin-bottom: 24px;
            text-align: center;
        }

        .auth-header {
            margin-bottom: 32px;
        }

        .auth-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.025em;
            margin-bottom: 8px;
        }

        .auth-desc {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Alert notifications */
        .alert-box {
            padding: 14px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 0.9rem;
            line-height: 1.45;
            animation: fadeInDown 0.3s ease-out;
        }

        .alert-danger {
            background-color: var(--danger-light);
            border: 1px solid var(--danger-border);
            color: #991b1b;
        }

        .alert-success {
            background-color: var(--success-light);
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .alert-icon {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-list {
            margin: 6px 0 0 18px;
            padding: 0;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: #94a3b8;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .form-input {
            width: 100%;
            height: 50px;
            padding: 0 16px 0 46px;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 500;
            color: #0f172a;
            background-color: #f8fafc;
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-md);
            outline: none;
            transition: var(--transition);
        }

        .form-input.has-toggle {
            padding-right: 48px;
        }

        .form-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .form-input:focus {
            background-color: #ffffff;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 4px var(--primary-glow);
        }

        .input-wrapper:focus-within .input-icon {
            color: var(--primary);
        }

        .form-input.is-invalid {
            border-color: var(--danger);
            background-color: #fffafb;
        }

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            padding: 8px;
            color: #94a3b8;
            cursor: pointer;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .password-toggle-btn:hover {
            color: #334155;
            background-color: #e2e8f0;
        }

        /* Form Row (Remember & Forgot) */
        .form-options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 26px;
            font-size: 0.88rem;
        }

        .custom-checkbox {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            user-select: none;
            color: #475569;
            font-weight: 500;
        }

        .custom-checkbox input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border: 1.5px solid #cbd5e1;
            border-radius: 5px;
            background-color: #ffffff;
            cursor: pointer;
            display: grid;
            place-content: center;
            transition: var(--transition);
            margin: 0;
        }

        .custom-checkbox input[type="checkbox"]:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .custom-checkbox input[type="checkbox"]:checked::before {
            content: "";
            width: 9px;
            height: 5px;
            border-left: 2px solid white;
            border-bottom: 2px solid white;
            transform: rotate(-45deg) translate(1px, -1px);
        }

        .custom-checkbox input[type="checkbox"]:focus {
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .forgot-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #ffffff;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
            transform: translateY(-1px);
        }

        .submit-btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
        }

        .submit-btn svg {
            transition: transform 0.2s ease;
        }

        .submit-btn:hover svg {
            transform: translateX(4px);
        }

        .submit-btn:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
        }

        .btn-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .submit-btn.is-loading .btn-text,
        .submit-btn.is-loading .btn-icon {
            display: none;
        }

        .submit-btn.is-loading .btn-spinner {
            display: inline-block;
        }

        /* Footer */
        .auth-footer {
            text-align: center;
            font-size: 0.85rem;
            color: #94a3b8;
            padding-top: 24px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 992px) {
            .brand-hero {
                display: none;
            }

            .mobile-brand {
                display: block;
            }

            .auth-container {
                padding: 32px 24px;
                min-height: 100vh;
                justify-content: center;
            }

            .auth-topbar {
                position: absolute;
                top: 24px;
                right: 24px;
            }
        }

        @media (max-width: 480px) {
            .auth-topbar {
                position: static;
                margin-bottom: 20px;
                justify-content: center;
            }

            .auth-title {
                font-size: 1.55rem;
            }

            .form-options-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
</head>
<body>

<div class="login-layout">
    <!-- LEFT PANEL: Modern Hero & Brand Showcase -->
    <aside class="brand-hero">
        <div class="hero-bg-glow">
            <div class="glow-orb glow-orb-1"></div>
            <div class="glow-orb glow-orb-2"></div>
            <div class="glow-orb glow-orb-3"></div>
            <div class="hero-grid-pattern"></div>
        </div>

        <div class="hero-content">
            <div class="brand-top">
                <a href="{{ route('homepage') }}" class="brand-logo-wrap" title="{{ $settings->invoice_brand_name ?? config('app.name', 'CityBuyBD') }}">
                    @if(!empty($settings?->logo))
                        <img src="{{ asset('backend/img/' . $settings->logo) }}" alt="Logo" class="brand-logo-img">
                    @else
                        <span class="brand-name-fallback">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            {{ $settings->invoice_brand_name ?? config('app.name', 'CityBuyBD') }}
                        </span>
                    @endif
                </a>
                <span class="brand-badge">Admin Portal</span>
            </div>
        </div>

        <div class="hero-content hero-center">
            <div class="hero-tag">
                <span class="hero-tag-dot"></span>
                <span>Next-Gen Commerce System</span>
            </div>

            <h1 class="hero-title">
                Smart Operations.<br>
                <span class="hero-title-highlight">Seamless Control.</span>
            </h1>

            <p class="hero-subtitle">
                Access your centralized ecommerce dashboard to monitor live sales, track inventory, dispatch shipments, and accelerate customer growth.
            </p>

            <div class="hero-features">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <h4>Real-Time Order Engine</h4>
                        <p>Instant parcel tracking, courier APIs & bulk status updates</p>
                    </div>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <h4>Role-Based Protection</h4>
                        <p>Enterprise multi-tenant security with granular permissions</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-content hero-footer">
            <span>&copy; {{ date('Y') }} {{ $settings->invoice_brand_name ?? config('app.name', 'CityBuyBD') }}. All rights reserved.</span>
            <a href="{{ route('homepage') }}" target="_blank">Storefront &rarr;</a>
        </div>
    </aside>

    <!-- RIGHT PANEL: Authentication Form -->
    <main class="auth-container">
        <header class="auth-topbar">
            <a href="{{ route('homepage') }}" class="store-pill-link" target="_blank">
                <span>Go to Store</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                </svg>
            </a>
        </header>

        <div class="auth-card-wrap">
            <!-- Mobile Brand Logo -->
            <div class="mobile-brand">
                <a href="{{ route('homepage') }}" style="text-decoration: none; display: inline-block;">
                    @if(!empty($settings?->logo))
                        <img src="{{ asset('backend/img/' . $settings->logo) }}" alt="Logo" style="max-height: 44px; max-width: 180px; object-fit: contain;">
                    @else
                        <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a;">{{ $settings->invoice_brand_name ?? config('app.name', 'CityBuyBD') }}</h2>
                    @endif
                </a>
            </div>

            <div class="auth-header">
                <h2 class="auth-title">Welcome back</h2>
                <p class="auth-desc">Sign in with your credentials to access the management portal.</p>
            </div>

            <!-- Session Status Alert -->
            @if(session('status'))
                <div class="alert-box alert-success" role="alert">
                    <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            <!-- Validation Errors Alert -->
            @if ($errors->any())
                <div class="alert-box alert-danger" role="alert">
                    <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div>
                        <strong>Authentication failed:</strong>
                        <ul class="alert-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form id="loginForm" action="{{ Route::has('login.store') ? route('login.store') : route('login') }}" method="POST" autocomplete="on">
                @csrf

                <!-- Email Input -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                            placeholder="name@company.com" 
                            required 
                            autofocus 
                            autocomplete="username"
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input has-toggle {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                            placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" 
                            required 
                            autocomplete="current-password"
                        >
                        <button type="button" class="password-toggle-btn" id="togglePasswordBtn" aria-label="Toggle password visibility">
                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg id="eyeOffIcon" style="display: none;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember & Forgot Password -->
                <div class="form-options-row">
                    <label class="custom-checkbox" for="remember">
                        <input type="checkbox" name="remember" id="remember" checked>
                        <span>Remember me</span>
                    </label>

                    @if(false && Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn" id="submitBtn">
                    <span class="btn-text">Sign In to Dashboard</span>
                    <svg class="btn-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                    <span class="btn-spinner"></span>
                </button>
            </form>
        </div>

        <footer class="auth-footer">
            <span>Protected by enterprise-grade session encryption.</span>
        </footer>
    </main>
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');

        // Toggle password visibility
        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                
                if (isPassword) {
                    eyeIcon.style.display = 'none';
                    eyeOffIcon.style.display = 'block';
                } else {
                    eyeIcon.style.display = 'block';
                    eyeOffIcon.style.display = 'none';
                }
            });
        }

        // Form submit loading indicator
        if (loginForm && submitBtn) {
            loginForm.addEventListener('submit', function () {
                submitBtn.classList.add('is-loading');
                submitBtn.setAttribute('disabled', 'disabled');
            });
        }
    });
</script>

</body>
</html>
