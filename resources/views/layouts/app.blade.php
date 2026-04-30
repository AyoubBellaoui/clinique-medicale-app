<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ClinicPro – @yield('title', 'Tableau de bord')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&family=fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    @stack('styles')

    <style>
        /* ─── Reset & Base ─── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        * { -webkit-font-smoothing: antialiased; }
        html, body { height: 100%; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            background: #f0f7f5;
            color: #133c35;
            overflow-x: hidden;
        }
        button { font-family: inherit; }
        a { text-decoration: none; color: inherit; }
        .serif { font-family: 'Fraunces', serif; font-variation-settings: "opsz" 144; letter-spacing: -.02em; }

        /* ─── Tokens ─── */
        :root {
            --teal-50:  #f0faf7;
            --teal-100: #d9f2ea;
            --teal-200: #b3e5d5;
            --teal-300: #7ecab5;
            --teal-400: #34a88c;
            --teal-500: #2e9278;
            --teal-600: #1a7260;
            --teal-700: #155c4e;
            --teal-800: #133c35;
            --teal-900: #0d2922;
            --ink: #133c35;
            --muted: #7bbfb0;
            --soft: #95c4b8;
            --sidebar-w: 264px;
            --sidebar-w-collapsed: 76px;
            --header-h: 72px;
            --ring: 0 0 0 3px rgba(52,168,140,.15);
            --shadow-sm: 0 1px 2px rgba(20,90,75,.06);
            --shadow: 0 2px 12px rgba(20,90,75,.06), 0 1px 3px rgba(20,90,75,.04);
            --shadow-lg: 0 10px 30px rgba(20,90,75,.12), 0 4px 10px rgba(20,90,75,.06);
        }

        /* ─── Sidebar ─── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: linear-gradient(175deg, #0d2922 0%, #133c35 40%, #1a5446 100%);
            display: flex; flex-direction: column;
            z-index: 100;
            transition: width .3s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }
        .sidebar::before {
            content: ''; position: absolute; inset: 0;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(52,168,140,.14) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(52,168,140,.09) 0%, transparent 50%);
            pointer-events: none;
        }
        .sidebar::after {
            content: ''; position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }
        .sidebar.collapsed { width: var(--sidebar-w-collapsed); }
        .sidebar.collapsed .sidebar-logo-text,
        .sidebar.collapsed .nav-section-label,
        .sidebar.collapsed .nav-label,
        .sidebar.collapsed .nav-badge,
        .sidebar.collapsed .sidebar-user-info,
        .sidebar.collapsed .sidebar-user form { display: none; }
        .sidebar.collapsed .sidebar-logo { justify-content: center; padding: 24px 12px 20px; }
        .sidebar.collapsed .nav-item { justify-content: center; padding: 12px; }
        .sidebar.collapsed .nav-item.active::before { display: none; }
        .sidebar.collapsed .sidebar-user { justify-content: center; padding: 10px; }

        .sidebar-logo {
            padding: 22px 20px 18px; display: flex; align-items: center; gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            position: relative; z-index: 1;
        }
        .sidebar-logo-icon {
            width: 42px; height: 42px; border-radius: 13px;
            background: linear-gradient(135deg, var(--teal-300), var(--teal-500) 60%, var(--teal-700));
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 16px rgba(52,168,140,.45), inset 0 1px 0 rgba(255,255,255,.25);
            flex-shrink: 0;
        }
        .sidebar-logo-text { line-height: 1; white-space: nowrap; }
        .sidebar-logo-text h1 { font-size: 19px; font-weight: 800; color: #fff; letter-spacing: -.5px; }
        .sidebar-logo-text span { font-size: 10px; color: rgba(255,255,255,.5); font-weight: 500; letter-spacing: .08em; text-transform: uppercase; }

        .sidebar-nav { flex: 1; padding: 14px 12px; overflow-y: auto; position: relative; z-index: 1; }
        .sidebar-nav::-webkit-scrollbar { width: 0; }

        .nav-section-label {
            font-size: 9.5px; font-weight: 700;
            color: rgba(255,255,255,.32); letter-spacing: .14em; text-transform: uppercase;
            padding: 0 10px; margin: 18px 0 6px;
        }
        .nav-section-label:first-child { margin-top: 4px; }

        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 12px; border-radius: 11px; cursor: pointer;
            transition: all .2s; color: rgba(255,255,255,.62);
            font-size: 13.5px; font-weight: 500;
            text-decoration: none; margin-bottom: 3px;
            position: relative; white-space: nowrap;
        }
        .nav-item:hover { background: rgba(255,255,255,.07); color: rgba(255,255,255,.95); }
        .nav-item.active {
            background: linear-gradient(135deg, rgba(52,168,140,.28), rgba(52,168,140,.14));
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(52,168,140,.35), 0 0 18px rgba(52,168,140,.15);
        }
        .nav-item.active .nav-icon { color: var(--teal-300); }
        .nav-item .nav-icon { width: 20px; height: 20px; flex-shrink: 0; }
        .nav-item.active::before {
            content: ''; position: absolute; left: -12px; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 22px; background: var(--teal-300);
            border-radius: 0 3px 3px 0;
            box-shadow: 0 0 12px var(--teal-300);
        }
        .nav-badge {
            margin-left: auto;
            background: rgba(52,168,140,.22); color: var(--teal-200);
            font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px;
            line-height: 1.4; border: 1px solid rgba(52,168,140,.3);
        }
        .nav-badge.warn { background: rgba(245,158,11,.2); color: #fbbf24; border-color: rgba(245,158,11,.3); }

        .sidebar-footer { padding: 14px 12px; border-top: 1px solid rgba(255,255,255,.07); position: relative; z-index: 1; }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 11px; background: rgba(255,255,255,.05);
            cursor: pointer; transition: background .2s;
        }
        .sidebar-user:hover { background: rgba(255,255,255,.09); }
        .sidebar-avatar {
            width: 36px; height: 36px; border-radius: 11px;
            background: linear-gradient(135deg, var(--teal-300), var(--teal-600));
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0;
            box-shadow: 0 3px 8px rgba(52,168,140,.35);
        }
        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-info p { font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,.92); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-info span { font-size: 10.5px; color: rgba(255,255,255,.42); }
        .logout-btn {
            background: none; border: none; cursor: pointer;
            color: rgba(255,255,255,.4); padding: 4px; border-radius: 6px; transition: color .2s;
        }
        .logout-btn:hover { color: #f87171; }

        /* ─── Main ─── */
        .main {
            margin-left: var(--sidebar-w);
            min-height: 100vh; display: flex; flex-direction: column;
            transition: margin-left .3s cubic-bezier(.4,0,.2,1);
        }
        .sidebar.collapsed ~ .main { margin-left: var(--sidebar-w-collapsed); }

        /* ─── Header ─── */
        .header {
            height: var(--header-h);
            background: rgba(255,255,255,.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(52,168,140,.1);
            display: flex; align-items: center;
            padding: 0 28px; gap: 14px;
            position: sticky; top: 0; z-index: 50;
            box-shadow: 0 1px 8px rgba(20,90,75,.04);
        }
        .sidebar-toggle {
            width: 38px; height: 38px; border-radius: 10px;
            background: var(--teal-50); border: 1.5px solid rgba(52,168,140,.15);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--teal-600); transition: all .2s;
        }
        .sidebar-toggle:hover { background: var(--teal-100); border-color: var(--teal-400); }
        .header-title { flex: 1; min-width: 0; }
        .header-title h2 { font-size: 19px; font-weight: 700; color: var(--teal-800); letter-spacing: -.3px; font-family: 'Fraunces', serif; font-variation-settings: "opsz" 144; }
        .header-title p { font-size: 12.5px; color: var(--muted); font-weight: 500; margin-top: 1px; }

        .header-clock {
            display: flex; flex-direction: column; align-items: flex-end;
            padding: 0 8px; border-right: 1px solid rgba(52,168,140,.12); margin-right: 8px; padding-right: 20px;
        }
        .header-clock .time { font-size: 14px; font-weight: 700; color: var(--teal-700); font-variant-numeric: tabular-nums; }
        .header-clock .date { font-size: 11px; color: var(--muted); font-weight: 500; }

        .header-search {
            display: flex; align-items: center; gap: 8px;
            background: var(--teal-50);
            border: 1.5px solid rgba(52,168,140,.2);
            border-radius: 10px; padding: 8px 14px;
            width: 280px; transition: all .2s; position: relative;
        }
        .header-search:focus-within { border-color: var(--teal-400); box-shadow: var(--ring); background: #fff; }
        .header-search input { border: none; outline: none; background: none; font-size: 13px; font-family: inherit; color: var(--teal-800); width: 100%; }
        .header-search input::placeholder { color: var(--soft); }
        .header-search svg { color: var(--muted); flex-shrink: 0; }
        .kbd {
            font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 5px;
            background: #fff; color: var(--muted); border: 1px solid rgba(52,168,140,.2); white-space: nowrap;
        }
        .search-dropdown {
            position: absolute; top: calc(100% + 8px); left: 0; right: 0;
            background: #fff; border: 1px solid rgba(52,168,140,.15);
            border-radius: 12px; box-shadow: var(--shadow-lg);
            max-height: 360px; overflow-y: auto; z-index: 60; display: none;
        }
        .search-dropdown.show { display: block; }

        .header-btn {
            width: 38px; height: 38px; border-radius: 10px;
            border: 1.5px solid rgba(52,168,140,.2); background: var(--teal-50);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--teal-600); transition: all .2s; position: relative;
        }
        .header-btn:hover { background: var(--teal-100); border-color: var(--teal-400); }
        .header-notif::after {
            content: ''; position: absolute; top: 6px; right: 6px;
            width: 9px; height: 9px; background: #ef4444; border-radius: 50%;
            border: 2px solid #fff;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%   { box-shadow: 0 0 0 0 rgba(239,68,68,.5); }
            70%  { box-shadow: 0 0 0 8px rgba(239,68,68,0); }
            100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
        }

        /* ─── Content ─── */
        .content { padding: 28px; flex: 1; }

        /* ─── Stats Cards ─── */
        .stats-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: #fff; border-radius: 16px; padding: 20px;
            border: 1px solid rgba(52,168,140,.1); box-shadow: var(--shadow);
            display: flex; align-items: flex-start; gap: 14px;
            transition: transform .25s cubic-bezier(.4,0,.2,1), box-shadow .25s, border-color .25s;
            cursor: pointer; position: relative; overflow: hidden;
        }
        .stat-card::after {
            content: ''; position: absolute; top: 0; right: 0; width: 60%; height: 100%;
            background: radial-gradient(circle at top right, rgba(52,168,140,.05), transparent 70%);
            pointer-events: none; opacity: 0; transition: opacity .3s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); border-color: rgba(52,168,140,.25); }
        .stat-card:hover::after { opacity: 1; }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .stat-icon.teal   { background: linear-gradient(135deg, rgba(52,168,140,.18), rgba(52,168,140,.08)); color: var(--teal-500); }
        .stat-icon.blue   { background: linear-gradient(135deg, rgba(59,130,246,.18), rgba(59,130,246,.08)); color: #3b82f6; }
        .stat-icon.amber  { background: linear-gradient(135deg, rgba(245,158,11,.18), rgba(245,158,11,.08)); color: #f59e0b; }
        .stat-icon.rose   { background: linear-gradient(135deg, rgba(244,63,94,.18), rgba(244,63,94,.08));  color: #f43f5e; }
        .stat-icon.violet { background: linear-gradient(135deg, rgba(139,92,246,.18), rgba(139,92,246,.08)); color: #8b5cf6; }
        .stat-body { flex: 1; min-width: 0; position: relative; z-index: 1; }
        .stat-value { font-size: 28px; font-weight: 800; color: var(--teal-800); line-height: 1; margin-bottom: 5px; font-variant-numeric: tabular-nums; letter-spacing: -.5px; }
        .stat-label { font-size: 12px; color: var(--muted); font-weight: 500; }
        .stat-trend { font-size: 11px; font-weight: 700; margin-top: 7px; display: flex; align-items: center; gap: 4px; }
        .stat-trend.up   { color: #10b981; }
        .stat-trend.down { color: #f43f5e; }
        .stat-trend.warn { color: #f59e0b; }
        .stat-sparkline { position: absolute; bottom: 0; right: 0; width: 80px; height: 32px; opacity: .5; pointer-events: none; }

        /* ─── Section Title ─── */
        .section-title { display: flex; align-items: center; gap: 10px; }
        .section-title .accent-bar {
            width: 4px; height: 24px; border-radius: 4px;
            background: linear-gradient(180deg, var(--teal-300), var(--teal-600));
        }
        .section-title h3 { font-size: 15.5px; font-weight: 700; color: var(--teal-800); letter-spacing: -.2px; }
        .section-title span { font-size: 12px; color: var(--soft); font-weight: 500; }

        /* ─── Cards ─── */
        .card {
            background: #fff; border-radius: 16px;
            border: 1px solid rgba(52,168,140,.1); box-shadow: var(--shadow);
            overflow: hidden; margin-bottom: 20px;
        }
        .card-header {
            padding: 18px 22px; border-bottom: 1px solid rgba(52,168,140,.08);
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }

        /* ─── Buttons ─── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 16px; border-radius: 10px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            transition: all .2s; border: none; text-decoration: none; white-space: nowrap;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--teal-400), var(--teal-600));
            color: #fff; box-shadow: 0 3px 10px rgba(52,168,140,.35);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(52,168,140,.45); }
        .btn-outline {
            background: var(--teal-50); color: var(--teal-600);
            border: 1.5px solid rgba(52,168,140,.25);
        }
        .btn-outline:hover { background: var(--teal-100); border-color: var(--teal-400); }
        .btn-ghost { background: transparent; color: var(--teal-600); }
        .btn-ghost:hover { background: var(--teal-50); }
        .btn-sm { padding: 7px 13px; font-size: 12px; }
        .btn-icon-only { padding: 8px; }
        .btn-danger { background: rgba(244,63,94,.1); color: #f43f5e; border: 1.5px solid rgba(244,63,94,.2); }
        .btn-danger:hover { background: rgba(244,63,94,.15); }
        .btn-success { background: rgba(16,185,129,.1); color: #059669; border: 1.5px solid rgba(16,185,129,.25); }

        /* ─── Table ─── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: linear-gradient(to bottom, var(--teal-50), #fff 150%); }
        thead th {
            padding: 12px 18px; text-align: left;
            font-size: 10.5px; font-weight: 700; color: var(--teal-600);
            letter-spacing: .08em; text-transform: uppercase;
            white-space: nowrap; border-bottom: 1px solid rgba(52,168,140,.1);
        }
        tbody tr {
            border-top: 1px solid rgba(52,168,140,.06);
            transition: background .15s; cursor: pointer;
        }
        tbody tr:hover { background: #fafffe; }
        tbody td { padding: 13px 18px; font-size: 13.5px; color: var(--teal-800); vertical-align: middle; }
        .table-empty { padding: 40px; text-align: center; color: var(--muted); font-size: 13.5px; }

        /* ─── Badges ─── */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11.5px; font-weight: 600; white-space: nowrap;
        }
        .badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
        .badge-green  { background: rgba(16,185,129,.12); color: #059669; }
        .badge-green::before  { background: #10b981; }
        .badge-amber  { background: rgba(245,158,11,.12); color: #d97706; }
        .badge-amber::before  { background: #f59e0b; }
        .badge-blue   { background: rgba(59,130,246,.12); color: #2563eb; }
        .badge-blue::before   { background: #3b82f6; }
        .badge-rose   { background: rgba(244,63,94,.12); color: #e11d48; }
        .badge-rose::before   { background: #f43f5e; }
        .badge-violet { background: rgba(139,92,246,.12); color: #7c3aed; }
        .badge-violet::before { background: #8b5cf6; }
        .badge-gray   { background: rgba(100,116,139,.1); color: #475569; }
        .badge-gray::before   { background: #94a3b8; }
        .badge-teal   { background: rgba(52,168,140,.12); color: var(--teal-600); }
        .badge-teal::before   { background: var(--teal-400); }

        /* ─── Avatar ─── */
        .avatar-chip { display: flex; align-items: center; gap: 10px; }
        .avatar {
            width: 34px; height: 34px; border-radius: 10px;
            background: linear-gradient(135deg, var(--teal-300), var(--teal-500));
            display: flex; align-items: center; justify-content: center;
            font-size: 11.5px; font-weight: 700; color: #fff;
            flex-shrink: 0; letter-spacing: -.2px;
            box-shadow: 0 2px 6px rgba(52,168,140,.25);
        }
        .avatar.blue   { background: linear-gradient(135deg, #60a5fa, #3b82f6); box-shadow: 0 2px 6px rgba(59,130,246,.25); }
        .avatar.amber  { background: linear-gradient(135deg, #fbbf24, #f59e0b); box-shadow: 0 2px 6px rgba(245,158,11,.25); }
        .avatar.rose   { background: linear-gradient(135deg, #fb7185, #f43f5e); box-shadow: 0 2px 6px rgba(244,63,94,.25); }
        .avatar.violet { background: linear-gradient(135deg, #a78bfa, #7c3aed); box-shadow: 0 2px 6px rgba(139,92,246,.25); }
        .avatar.sm { width: 28px; height: 28px; font-size: 10px; border-radius: 8px; }
        .avatar.lg { width: 44px; height: 44px; font-size: 14px; border-radius: 12px; }
        .avatar-info p { font-size: 13.5px; font-weight: 600; color: var(--teal-800); line-height: 1.2; }
        .avatar-info span { font-size: 11.5px; color: var(--muted); }

        /* ─── Grid layouts ─── */
        .dash-grid-main { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px; }
        .dash-grid-bottom { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }

        /* ─── Chart card ─── */
        .chart-card { padding: 22px; }
        .chart-container { position: relative; height: 260px; margin-top: 16px; }
        .chart-legend {
            display: flex; gap: 16px; flex-wrap: wrap; margin-top: 12px;
            padding-top: 12px; border-top: 1px dashed rgba(52,168,140,.15);
        }
        .legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--teal-700); font-weight: 500; }
        .legend-dot { width: 10px; height: 10px; border-radius: 3px; }

        /* ─── Queue item ─── */
        .queue-item {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 18px; border-bottom: 1px solid rgba(52,168,140,.06);
            transition: background .15s; cursor: pointer;
        }
        .queue-item:last-child { border-bottom: none; }
        .queue-item:hover { background: #fafffe; }
        .queue-number {
            width: 32px; height: 32px; border-radius: 9px;
            background: linear-gradient(135deg, var(--teal-400), var(--teal-600));
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 800; color: #fff; flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(52,168,140,.3);
        }
        .queue-number.active {
            background: linear-gradient(135deg, #34d399, #059669);
            box-shadow: 0 2px 6px rgba(16,185,129,.4), 0 0 0 3px rgba(16,185,129,.15);
            animation: pulse-ring 2s infinite;
        }
        @keyframes pulse-ring {
            0%   { box-shadow: 0 2px 6px rgba(16,185,129,.4), 0 0 0 0 rgba(16,185,129,.4); }
            70%  { box-shadow: 0 2px 6px rgba(16,185,129,.4), 0 0 0 8px rgba(16,185,129,0); }
            100% { box-shadow: 0 2px 6px rgba(16,185,129,.4), 0 0 0 0 rgba(16,185,129,0); }
        }
        .queue-info { flex: 1; min-width: 0; }
        .queue-info p { font-size: 13px; font-weight: 600; color: var(--teal-800); }
        .queue-info span { font-size: 11.5px; color: var(--muted); }

        /* ─── Activity Feed ─── */
        .activity-item {
            display: flex; gap: 12px;
            padding: 14px 18px; border-bottom: 1px solid rgba(52,168,140,.06);
            transition: background .15s;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item:hover { background: #fafffe; }
        .activity-icon {
            width: 32px; height: 32px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .activity-icon.green  { background: rgba(16,185,129,.1);  color: #059669; }
        .activity-icon.blue   { background: rgba(59,130,246,.1);  color: #2563eb; }
        .activity-icon.amber  { background: rgba(245,158,11,.1);  color: #d97706; }
        .activity-icon.violet { background: rgba(139,92,246,.1);  color: #7c3aed; }
        .activity-info { flex: 1; min-width: 0; }
        .activity-info p { font-size: 13px; color: var(--teal-800); line-height: 1.4; }
        .activity-info p strong { font-weight: 700; }
        .activity-info .time { font-size: 11px; color: var(--muted); font-weight: 500; margin-top: 2px; display: block; }

        /* ─── Tabs ─── */
        .tabs { display: flex; gap: 4px; background: var(--teal-50); padding: 4px; border-radius: 10px; border: 1px solid rgba(52,168,140,.12); }
        .tab {
            padding: 7px 14px; border-radius: 7px;
            font-size: 12.5px; font-weight: 600; color: var(--muted);
            cursor: pointer; transition: all .2s; border: none; background: none; font-family: inherit;
        }
        .tab.active { background: #fff; color: var(--teal-700); box-shadow: 0 1px 4px rgba(20,90,75,.08); }

        /* ─── Pagination ─── */
        .pagination {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 22px; border-top: 1px solid rgba(52,168,140,.08);
        }
        .pagination span { font-size: 12.5px; color: var(--muted); font-weight: 500; }
        .pagination-btns { display: flex; gap: 4px; }
        .page-btn {
            min-width: 32px; height: 32px; padding: 0 10px; border-radius: 8px;
            border: 1.5px solid rgba(52,168,140,.2); background: none;
            font-family: inherit; font-size: 12.5px; font-weight: 600; color: var(--teal-600);
            cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .15s;
        }
        .page-btn:hover:not(:disabled) { background: var(--teal-50); border-color: var(--teal-400); }
        .page-btn.active { background: var(--teal-400); color: #fff; border-color: var(--teal-400); box-shadow: 0 2px 8px rgba(52,168,140,.3); }
        .page-btn:disabled { opacity: .4; cursor: not-allowed; }

        /* ─── Toast ─── */
        .toast-container { position: fixed; top: 90px; right: 28px; z-index: 2000; display: flex; flex-direction: column; gap: 10px; }
        .toast {
            background: #fff; border-radius: 12px; padding: 12px 16px;
            box-shadow: var(--shadow-lg); border-left: 4px solid var(--teal-400);
            display: flex; align-items: center; gap: 12px;
            min-width: 280px; max-width: 400px;
            animation: slideInRight .3s cubic-bezier(.16,1,.3,1);
        }
        .toast.success { border-left-color: #10b981; }
        .toast.error   { border-left-color: #f43f5e; }
        .toast.warn    { border-left-color: #f59e0b; }
        .toast.info    { border-left-color: #3b82f6; }
        .toast-icon { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .toast.success .toast-icon { background: rgba(16,185,129,.12); color: #059669; }
        .toast.error   .toast-icon { background: rgba(244,63,94,.12);  color: #e11d48; }
        .toast.warn    .toast-icon { background: rgba(245,158,11,.12); color: #d97706; }
        .toast.info    .toast-icon { background: rgba(59,130,246,.12); color: #2563eb; }
        .toast-body { flex: 1; font-size: 13px; font-weight: 500; color: var(--teal-800); }
        @keyframes slideInRight { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes slideOutRight { to { opacity: 0; transform: translateX(40px); } }
        .toast.closing { animation: slideOutRight .25s forwards; }

        /* ─── Modal ─── */
        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(13,41,34,.5);
            backdrop-filter: blur(6px); z-index: 1000;
            opacity: 0; pointer-events: none; transition: opacity .25s;
            display: flex; align-items: center; justify-content: center; padding: 24px;
        }
        .modal-backdrop.show { opacity: 1; pointer-events: auto; }
        .modal {
            background: #fff; border-radius: 20px; width: 100%; max-width: 640px;
            max-height: 88vh; overflow: hidden; display: flex; flex-direction: column;
            box-shadow: 0 30px 80px rgba(13,41,34,.3);
            transform: scale(.94) translateY(10px);
            transition: transform .3s cubic-bezier(.16,1,.3,1);
        }
        .modal.lg { max-width: 820px; }
        .modal-backdrop.show .modal { transform: scale(1) translateY(0); }
        .modal-header {
            padding: 20px 24px; display: flex; align-items: flex-start; gap: 16px;
            border-bottom: 1px solid rgba(52,168,140,.08);
        }
        .modal-body { padding: 24px; overflow-y: auto; flex: 1; }
        .modal-footer {
            padding: 16px 24px; display: flex; justify-content: flex-end; gap: 10px;
            border-top: 1px solid rgba(52,168,140,.08); background: var(--teal-50);
        }
        .modal-close {
            margin-left: auto; width: 32px; height: 32px;
            border: none; background: var(--teal-50); border-radius: 8px;
            cursor: pointer; color: var(--teal-600);
            display: flex; align-items: center; justify-content: center; transition: all .15s;
        }
        .modal-close:hover { background: var(--teal-100); transform: rotate(90deg); }

        /* ─── Forms ─── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 7px; }
        .form-group.full { grid-column: 1 / -1; }
        .form-label {
            font-size: 11px; font-weight: 700; color: var(--teal-700);
            letter-spacing: .06em; text-transform: uppercase;
        }
        .form-label .req { color: #e11d48; font-size: 13px; }
        .form-input, .form-select, .form-textarea {
            padding: 11px 14px; border: 1.5px solid rgba(52,168,140,.18);
            border-radius: 10px; background: #fff;
            font-family: inherit; font-size: 14px;
            color: var(--teal-800); outline: none; transition: all .18s; width: 100%;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--teal-400); box-shadow: var(--ring);
        }
        .form-input::placeholder, .form-textarea::placeholder { color: #a8c5bd; }
        .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237bbfb0' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px; }
        .form-textarea { min-height: 90px; resize: vertical; line-height: 1.6; }

        /* ─── Skeleton ─── */
        .skeleton {
            background: linear-gradient(90deg, #e6f2ee 0%, #f0f7f5 50%, #e6f2ee 100%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: 8px;
        }
        @keyframes skeleton-loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        /* ─── Utility ─── */
        .text-mono { font-family: ui-monospace, SFMono-Regular, monospace; font-size: 12.5px; }
        .text-right { text-align: right; }
        .flex { display: flex; }
        .gap-2 { gap: 8px; }
        .items-center { align-items: center; }
        .flex-1 { flex: 1; }
        .hidden { display: none !important; }
        .w-full { width: 100%; }

        /* ─── Responsive ─── */
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .dash-grid-main, .dash-grid-bottom { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main { margin-left: 0 !important; }
            .header { padding: 0 16px; }
            .header-clock, .header-search { display: none; }
            .content { padding: 16px; }
            .stats-grid { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

{{-- ─── SIDEBAR ─── --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <svg width="22" height="22" viewBox="0 0 32 32" fill="none">
                <rect x="11" y="2" width="10" height="28" rx="3" fill="white"/>
                <rect x="2" y="11" width="28" height="10" rx="3" fill="white"/>
            </svg>
        </div>
        <div class="sidebar-logo-text">
            <h1>Clinic<span style="color:#7ecab5;">Pro</span></h1>
            <span>Cabinet Médical</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Principal</div>

        <a href="#" class="nav-item {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
            </svg>
            <span class="nav-label">Tableau de bord</span>
        </a>

        <a href="" class="nav-item {{ request()->routeIs('appointments*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
            </svg>
            <span class="nav-label">Rendez-vous</span>
            <span class="nav-badge">12</span>
        </a>

        <div class="nav-section-label">Gestion</div>

        <a href=" {{ route('patients.index') }}" class="nav-item {{ request()->routeIs('patients*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            </svg>
            <span class="nav-label">Patients</span>
            <span class="nav-badge">0</span>
        </a>

        <a href="#" class="nav-item {{ request()->routeIs('staff*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/>
            </svg>
            <span class="nav-label">Staff Médical</span>
            <span class="nav-badge">0</span>
        </a>

        <a href="#" class="nav-item {{ request()->routeIs('queue*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
            </svg>
            <span class="nav-label">File d'Attente</span>
            <span class="nav-badge warn">0</span>
        </a>

        <a href="#" class="nav-item {{ request()->routeIs('consultations*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
            <span class="nav-label">Consultations</span>
        </a>

        <a href="#" class="nav-item {{ request()->routeIs('prescriptions*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>
            </svg>
            <span class="nav-label">Ordonnances</span>
        </a>

        <a href="#" class="nav-item {{ request()->routeIs('billing*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="nav-label">Factures</span>
            <span class="nav-badge">0</span>
        </a>

        <div class="nav-section-label">Paramètres</div>

        <a href="#" class="nav-item {{ request()->routeIs('account*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="nav-label">Mon Compte</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="sidebar-user-info">
                <p>{{ auth()->user()->name ?? 'Administrateur' }}</p>
                <span>{{ auth()->user()->email ?? '' }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="Déconnexion">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ─── MAIN ─── --}}
<div class="main" id="main">

    {{-- Header --}}
    <header class="header">
        <button class="sidebar-toggle" id="sidebar-toggle" title="Réduire le menu">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
        </button>

        <div class="header-title">
            <h2>@yield('page-title', 'Tableau de bord')</h2>
            <p>@yield('page-subtitle', "Vue d'ensemble de votre cabinet médical")</p>
        </div>

        <div class="header-clock">
            <span class="time" id="clock-time">--:--</span>
            <span class="date" id="clock-date">--</span>
        </div>

        <div class="header-search">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input type="text" id="global-search" placeholder="Rechercher patient, médecin...">
            <span class="kbd">⌘K</span>
            <div class="search-dropdown" id="search-dropdown"></div>
        </div>

        <button class="header-btn header-notif" title="Notifications">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
            </svg>
        </button>

        <button class="header-btn" title="Paramètres">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </button>
    </header>

    {{-- Page content --}}
    <div class="content">
        @yield('content')
    </div>
</div>

{{-- Toast container --}}
<div class="toast-container" id="toast-container"></div>

{{-- Modal container --}}
<div class="modal-backdrop" id="modal-backdrop">
    <div class="modal" id="modal"></div>
</div>

<script>
    // ─── Live Clock ───
    function updateClock() {
        const now = new Date();
        const timeEl = document.getElementById('clock-time');
        const dateEl = document.getElementById('clock-date');
        if (timeEl) timeEl.textContent = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        if (dateEl) dateEl.textContent = now.toLocaleDateString('fr-FR', { weekday: 'short', day: '2-digit', month: 'short' });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // ─── Sidebar Toggle ───
    document.getElementById('sidebar-toggle').addEventListener('click', () => {
        const sb = document.getElementById('sidebar');
        if (window.innerWidth <= 768) {
            sb.classList.toggle('mobile-open');
        } else {
            sb.classList.toggle('collapsed');
        }
    });

    // ─── Modal ───
    function openModal(html, opts = {}) {
        const backdrop = document.getElementById('modal-backdrop');
        const modal    = document.getElementById('modal');
        modal.className = 'modal' + (opts.lg ? ' lg' : '');
        modal.innerHTML = html;
        backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        document.getElementById('modal-backdrop').classList.remove('show');
        document.body.style.overflow = '';
    }
    document.getElementById('modal-backdrop').addEventListener('click', e => {
        if (e.target.id === 'modal-backdrop') closeModal();
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            document.getElementById('global-search').focus();
        }
    });

    // ─── Toast ───
    function toast(msg, type = 'success') {
        const container = document.getElementById('toast-container');
        const el = document.createElement('div');
        el.className = `toast ${type}`;
        const icons = {
            success: '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>',
            error:   '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>',
            warn:    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c-.866 1.5-3.032-1.874-1.948-3.374H2.645c-1.73 0-2.813 1.874-1.948 3.374L13.949 3.378c.866-1.5 3.032-1.5 3.898 0z"/></svg>',
            info:    '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>'
        };
        el.innerHTML = `<div class="toast-icon">${icons[type] || icons.info}</div><div class="toast-body">${msg}</div>`;
        container.appendChild(el);
        setTimeout(() => { el.classList.add('closing'); setTimeout(() => el.remove(), 250); }, 3500);
    }

    // ─── Search close on outside click ───
    document.addEventListener('click', e => {
        if (!e.target.closest('.header-search')) {
            document.getElementById('search-dropdown').classList.remove('show');
        }
    });
</script>

@stack('scripts')
</body>
</html>
