<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ClinicPro — Mot de passe oublié</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * { -webkit-font-smoothing: antialiased; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ── Background — identical to login ── */
        .page-bg {
            min-height: 100vh;
            background:
                radial-gradient(ellipse 70% 55% at 15% 10%,  rgba(52,168,140,.22) 0%, transparent 60%),
                radial-gradient(ellipse 55% 45% at 85% 85%,  rgba(30,120,100,.18) 0%, transparent 60%),
                radial-gradient(ellipse 45% 40% at 55% 45%,  rgba(100,195,175,.12) 0%, transparent 60%),
                #edf7f4;
        }
        .page-bg::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(40,140,115,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(40,140,115,.06) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .blob { position:fixed; border-radius:50%; filter:blur(70px); pointer-events:none; z-index:0; }

        /* ── Card ── */
        .card {
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(52,168,140,.15);
            border-radius: 24px;
            box-shadow: 0 8px 40px rgba(20,90,75,.10), 0 1px 4px rgba(20,90,75,.06);
        }

        /* ── Logo box ── */
        .logo-box {
            width:64px; height:64px; border-radius:18px;
            background: linear-gradient(135deg, #34a88c 0%, #1a7260 100%);
            box-shadow: 0 8px 24px rgba(26,114,96,.35);
            display:flex; align-items:center; justify-content:center;
        }

        /* ── Input field ── */
        .field {
            width:100%; padding:12px 12px 12px 42px;
            border:1.5px solid rgba(52,168,140,.25); border-radius:12px;
            background:#f4faf8; color:#133c35; font-size:14px;
            font-family:'Plus Jakarta Sans',sans-serif; font-weight:500;
            transition:border-color .2s, box-shadow .2s, background .2s; outline:none;
        }
        .field::placeholder { color:#95c4b8; }
        .field:focus { border-color:#34a88c; box-shadow:0 0 0 3px rgba(52,168,140,.14); background:#fff; }

        .field-icon {
            position:absolute; left:13px; top:50%; transform:translateY(-50%);
            color:#5db8a0; pointer-events:none; width:18px; height:18px;
        }

        /* ── Primary button ── */
        .btn-primary {
            width:100%; padding:14px; border-radius:12px; border:none; cursor:pointer;
            font-family:'Plus Jakarta Sans',sans-serif; font-size:15px; font-weight:600;
            color:#fff; letter-spacing:.02em;
            background:linear-gradient(135deg, #34a88c 0%, #1a7260 100%);
            box-shadow:0 4px 16px rgba(26,114,96,.38);
            transition:transform .2s, box-shadow .2s;
            display:flex; align-items:center; justify-content:center; gap:8px;
        }
        .btn-primary:hover  { transform:translateY(-1px); box-shadow:0 6px 22px rgba(26,114,96,.45); }
        .btn-primary:active { transform:translateY(0);    box-shadow:0 2px 8px rgba(26,114,96,.30); }

        /* ── Ghost/back button ── */
        .btn-ghost {
            width:100%; padding:13px; border-radius:12px; cursor:pointer;
            font-family:'Plus Jakarta Sans',sans-serif; font-size:14px; font-weight:600;
            color:#34a88c; background:transparent;
            border:1.5px solid rgba(52,168,140,.3);
            transition:all .2s;
            display:flex; align-items:center; justify-content:center; gap:8px;
            text-decoration:none;
        }
        .btn-ghost:hover { background:rgba(52,168,140,.06); border-color:#34a88c; }
        .btn-ghost:active { background:rgba(52,168,140,.10); }

        /* ── Error message ── */
        .err { color:#dc2626; font-size:12px; margin-top:6px; display:flex; align-items:center; gap:4px; }

        /* ── Success alert ── */
        .alert-success {
            background:#f0faf7;
            border:1.5px solid rgba(52,168,140,.35);
            border-radius:14px;
            padding:16px 18px;
            margin-bottom:22px;
            display:flex; gap:14px; align-items:flex-start;
        }
        .alert-success-icon {
            width:38px; height:38px; border-radius:50%; flex-shrink:0;
            background:rgba(52,168,140,.15);
            display:flex; align-items:center; justify-content:center;
        }

        /* ── Decorative crosses ── */
        .deco-cross { position:fixed; pointer-events:none; opacity:.09; }

        /* ── Online dot ── */
        .online-dot {
            width:14px; height:14px; background:#4ade80; border:2.5px solid #fff;
            border-radius:50%; position:absolute; top:-3px; right:-3px;
            animation:pulse-dot 2.5s ease-in-out infinite;
        }

        /* ── Hint box ── */
        .hint-box {
            background: rgba(52,168,140,.06);
            border: 1px solid rgba(52,168,140,.15);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        /* ── Animations ── */
        @keyframes floatA { 0%,100%{transform:translateY(0)}   50%{transform:translateY(-14px)} }
        @keyframes floatB { 0%,100%{transform:translateY(-8px)} 50%{transform:translateY(8px)}  }
        @keyframes slideUp { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }
        @keyframes pulse-dot {
            0%,100%{ box-shadow:0 0 0 3px rgba(74,222,128,.25); }
            50%    { box-shadow:0 0 0 6px rgba(74,222,128,.10); }
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .float-a { animation:floatA 7s ease-in-out infinite; }
        .float-b { animation:floatB 9s ease-in-out infinite; }

        .su1 { opacity:0; animation:slideUp .55s cubic-bezier(.16,1,.3,1) .08s forwards; }
        .su2 { opacity:0; animation:slideUp .55s cubic-bezier(.16,1,.3,1) .18s forwards; }
        .su3 { opacity:0; animation:slideUp .55s cubic-bezier(.16,1,.3,1) .28s forwards; }
        .su4 { opacity:0; animation:slideUp .55s cubic-bezier(.16,1,.3,1) .36s forwards; }
        .su5 { opacity:0; animation:slideUp .55s cubic-bezier(.16,1,.3,1) .44s forwards; }
        .su6 { opacity:0; animation:slideUp .55s cubic-bezier(.16,1,.3,1) .52s forwards; }

        /* Loading spinner on submit */
        .spinner {
            display:none;
            width:18px; height:18px;
            border:2.5px solid rgba(255,255,255,.4);
            border-top-color:#fff;
            border-radius:50%;
            animation:spin .7s linear infinite;
        }
        .btn-primary.loading .btn-icon { display:none; }
        .btn-primary.loading .spinner  { display:block; }
        .btn-primary.loading .btn-text { opacity:.8; }
    </style>
</head>

<body class="page-bg flex items-center justify-center p-4 relative">

    {{-- Blobs --}}
    <div class="blob float-a" style="width:320px;height:320px;top:-80px;left:-80px;background:rgba(52,168,140,.20);"></div>
    <div class="blob float-b" style="width:280px;height:280px;bottom:-60px;right:-60px;background:rgba(26,114,96,.18);"></div>
    <div class="blob float-a" style="width:180px;height:180px;top:45%;left:5%;background:rgba(100,195,175,.15);animation-delay:-4s;"></div>

    {{-- Decorative crosses --}}
    <div class="deco-cross" style="top:24px;right:28px;">
        <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
            <rect x="19" y="3"  width="18" height="50" rx="4" fill="#1a7260"/>
            <rect x="3"  y="19" width="50" height="18" rx="4" fill="#1a7260"/>
        </svg>
    </div>
    <div class="deco-cross" style="bottom:40px;left:32px;">
        <svg width="36" height="36" viewBox="0 0 56 56" fill="none">
            <rect x="19" y="3"  width="18" height="50" rx="4" fill="#34a88c"/>
            <rect x="3"  y="19" width="50" height="18" rx="4" fill="#34a88c"/>
        </svg>
    </div>

    {{-- ── Main wrapper ── --}}
    <div style="width:100%;max-width:420px;position:relative;z-index:10;">

        {{-- ── Logo & brand — same as login ── --}}
        <div class="su1" style="text-align:center;margin-bottom:28px;">
            <div style="display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
                <div style="position:relative;">
                    <div class="logo-box">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <rect x="11" y="2"  width="10" height="28" rx="3" fill="white"/>
                            <rect x="2"  y="11" width="28" height="10" rx="3" fill="white"/>
                        </svg>
                    </div>
                    <span class="online-dot"></span>
                </div>
            </div>
            <h1 style="font-size:30px;font-weight:800;color:#133c35;letter-spacing:-1px;margin:0 0 4px;line-height:1;">
                Clinic<span style="color:#34a88c;">Pro</span>
            </h1>
            <p style="font-size:11px;font-weight:600;color:#5db8a0;letter-spacing:.12em;text-transform:uppercase;margin:0;">
                Cabinet Médical · Portail Sécurisé
            </p>
        </div>

        {{-- ── Card ── --}}
        <div class="card" style="padding:36px 32px;">

            {{-- ── Card title — same accent bar style as login ── --}}
            <div class="su2" style="margin-bottom:26px;padding-bottom:20px;border-bottom:1px solid rgba(52,168,140,.12);">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:4px;height:28px;border-radius:4px;background:linear-gradient(180deg,#34a88c,#1a7260);flex-shrink:0;"></div>
                    <div>
                        <h2 style="font-size:22px;font-weight:800;color:#133c35;margin:0;letter-spacing:-.5px;line-height:1;">
                            MOT DE PASSE OUBLIÉ
                        </h2>
                        <p style="font-size:12px;color:#7bbfb0;margin:4px 0 0;font-weight:500;">
                            Récupérez votre accès en quelques secondes
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Success message (shown after email sent) ── --}}
            @if (session('status'))
                <div class="alert-success su2">
                    <div class="alert-success-icon">
                        <svg width="20" height="20" fill="none" stroke="#34a88c" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:13.5px;font-weight:700;color:#1a7260;margin:0 0 3px;">Email envoyé avec succès !</p>
                        <p style="font-size:12.5px;color:#5a9e8e;margin:0;line-height:1.55;">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- ── Info hint box ── --}}
            <div class="hint-box su3">
                <svg style="flex-shrink:0;margin-top:1px;" width="18" height="18" fill="none" stroke="#34a88c" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                </svg>
                <p style="font-size:12.5px;color:#4a9080;margin:0;line-height:1.6;font-weight:500;">
                    Saisissez l'adresse email associée à votre compte. Nous vous enverrons un lien sécurisé pour réinitialiser votre mot de passe.
                </p>
            </div>

            {{-- ── Form ── --}}
            <form method="POST" action="" id="resetForm">
                @csrf

                {{-- Email field --}}
                <div class="su3" style="margin-bottom:22px;">
                    <label for="email" style="display:block;font-size:11px;font-weight:700;color:#2e7a6a;letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px;">
                        Adresse email
                    </label>
                    <div style="position:relative;">
                        <svg class="field-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                        <input
                            id="email" type="email" name="email"
                            value="{{ old('email') }}"
                            required autofocus autocomplete="email"
                            placeholder="docteur@clinicpro.ma"
                            class="field"
                        >
                    </div>
                    @error('email')
                        <div class="err">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Divider --}}
                <div style="height:1px;background:linear-gradient(90deg,transparent,rgba(52,168,140,.2),transparent);margin-bottom:20px;" class="su4"></div>

                {{-- ── Send button ── --}}
                <div class="su4" style="margin-bottom:12px;">
                    <button type="submit" class="btn-primary" id="submitBtn" onclick="handleSubmit(this)">
                        <span class="btn-icon">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                            </svg>
                        </span>
                        <div class="spinner"></div>
                        <span class="btn-text">Envoyer le lien de réinitialisation</span>
                    </button>
                </div>
            </form>

            {{-- ── Back to login button ── --}}
            <div class="su5">
                <a href="" class="btn-ghost">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Retour à la connexion
                </a>
            </div>

        </div>

        {{-- ── Security badge — same as login ── --}}
        <div class="su6" style="text-align:center;margin-top:20px;">
            <div style="display:inline-flex;align-items:center;gap:7px;font-size:11.5px;color:#90b8b0;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#5db8a0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
                Lien sécurisé · Expire après 60 minutes
            </div>
        </div>

    </div>

    <script>
        function handleSubmit(btn) {
            // Small delay to show loading state before form submits
            setTimeout(() => {
                btn.classList.add('loading');
                btn.disabled = true;
            }, 10);
        }
    </script>
</body>
</html>
