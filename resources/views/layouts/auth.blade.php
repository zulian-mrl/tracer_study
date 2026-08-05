<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tracer Study')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @php
        $warnaLatar = \App\Models\Setting::get('login_warna_latar', '#0f172a');
        $warnaLatar2 = \App\Models\Setting::get('login_warna_latar2', '#1e1b4b');
        $warnaAksen = \App\Models\Setting::get('login_warna_aksen', '#fbbf24');
        $hexToRgba = function ($hex, $alpha) {
            $hex = ltrim((string) $hex, '#');
            if (strlen($hex) === 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            return "rgba($r, $g, $b, $alpha)";
        };
    @endphp
    <style>
        :root {
            --log-latar: {{ $warnaLatar }};
            --log-latar2: {{ $warnaLatar2 }};
            --log-aksen: {{ $warnaAksen }};
        }
        body {
            background: linear-gradient(135deg, var(--log-latar) 0%, var(--log-latar2) 50%, var(--log-latar) 100%);
            background-attachment: fixed;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(circle at 20% 25%, {{ $hexToRgba($warnaAksen, 0.16) }}, transparent 42%),
                radial-gradient(circle at 80% 20%, rgba(56, 189, 248, 0.18), transparent 42%),
                radial-gradient(circle at 50% 90%, rgba(168, 85, 247, 0.15), transparent 48%);
        }

        .glass {
            background: linear-gradient(160deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.78));
            backdrop-filter: blur(12px);
            border: 1px solid rgba(148, 163, 184, 0.2);
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

        /* Kartu login melayang (3D) tanpa membuat teks miring/blur */
        .float-3d {
            animation: float3d 5s ease-in-out infinite;
            filter: drop-shadow(0 28px 40px rgba(0, 0, 0, 0.55));
        }
        @keyframes float3d {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .lock-3d {
            display: inline-block;
            text-shadow: 0 8px 22px {{ $hexToRgba($warnaAksen, 0.55) }}, 0 2px 6px rgba(0, 0, 0, 0.6);
            transform: perspective(600px) rotateX(8deg) rotateY(-8deg);
            filter: drop-shadow(0 10px 12px rgba(0, 0, 0, 0.5));
        }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }
        .fade-up { animation: fadeUp .6s ease both; }

        .input-3d {
            transition: box-shadow .2s ease, border-color .2s ease, transform .2s ease;
        }
        .input-3d:focus {
            transform: translateY(-1px);
            border-color: var(--log-aksen);
            box-shadow: 0 0 0 3px {{ $hexToRgba($warnaAksen, 0.25) }}, 0 10px 20px -8px rgba(0, 0, 0, 0.6);
        }

        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 6px; }
    </style>
</head>
<body class="text-white font-sans min-h-screen flex items-center justify-center p-4">

    @yield('content')

    @yield('script')
</body>
</html>
