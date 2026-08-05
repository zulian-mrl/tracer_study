<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Tracer Study')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            background-attachment: fixed;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(circle at 15% 20%, rgba(251, 191, 36, 0.14), transparent 42%),
                radial-gradient(circle at 85% 8%, rgba(56, 189, 248, 0.16), transparent 42%),
                radial-gradient(circle at 50% 92%, rgba(168, 85, 247, 0.13), transparent 48%);
        }
        .glass {
            background: linear-gradient(160deg, rgba(30, 41, 59, 0.85), rgba(15, 23, 42, 0.72));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.18);
        }
        .grad-text {
            background: linear-gradient(90deg, #fbbf24, #f472b6, #38bdf8, #fbbf24);
            background-size: 300% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: shine 6s linear infinite;
        }
        @keyframes shine { to { background-position: 300% 0; } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        .fade-up { animation: fadeUp .5s ease both; }

        .inp {
            width: 100%;
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgb(71 85 105);
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            color: #fff;
            outline: none;
            color-scheme: dark;
            transition: box-shadow .2s ease, border-color .2s ease, transform .2s ease;
        }
        .inp:focus {
            border-color: #fbbf24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.22), 0 10px 20px -10px rgba(0, 0, 0, 0.6);
            transform: translateY(-1px);
        }
        .inp::placeholder { color: #64748b; }
        select.inp option { background: #1e293b; color: #fff; }
        input[type="color"].inp { height: 42px; padding: 4px; cursor: pointer; }

        .sec-title {
            font-size: 1rem;
            font-weight: 700;
            color: #fbbf24;
            border-left: 4px solid #fbbf24;
            padding-left: 0.75rem;
            margin-bottom: 1rem;
        }
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="text-gray-200 font-sans min-h-screen">

    <nav class="sticky top-0 z-40 border-b border-white/10 bg-slate-900/70 backdrop-blur-lg shadow-2xl">
        <div class="@yield('nav_container', 'max-w-5xl') mx-auto p-4 flex justify-between items-center">
            <h1 class="text-xl font-extrabold tracking-wider grad-text uppercase">@yield('judul')</h1>
            @yield('nav_right')
        </div>
    </nav>

    @yield('content')

    @yield('script')
</body>
</html>
