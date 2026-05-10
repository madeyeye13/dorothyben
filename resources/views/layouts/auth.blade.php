<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Dorothy & Ben</title>
    <link rel="icon" href="{{ asset('images/D&B.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body style="margin:0; padding:0; background:#0D0D0D;">

<div style="display:flex; min-height:100vh;">

    {{-- LEFT: Couple image panel (desktop only) --}}
    <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative flex-col items-center justify-center"
         style="background: #0D0D0D; background-image: url('{{ \App\Models\SiteSetting::get('hero_image') ? asset('storage/'.\App\Models\SiteSetting::get('hero_image')) : asset('images/hero-default.jpg') }}'); background-size: cover; background-position: center;">
        <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(0,0,0,0.72) 0%,rgba(0,0,0,0.5) 100%);"></div>
        <div style="position:relative;z-index:2;text-align:center;padding:3rem;">
            <p style="font-family:'DM Sans',sans-serif;font-size:0.75rem;letter-spacing:0.2em;text-transform:uppercase;color:rgba(255,255,255,0.5);margin-bottom:1.5rem;">
                Admin Portal
            </p>
            <h1 style="font-family:'Playfair Display',serif;font-size:clamp(2.5rem,5vw,4rem);font-weight:400;color:#fff;line-height:1.1;margin:0;">
                Dorothy
                <em style="color:#C9A84C;">&</em>
                Ben
            </h1>
            <p style="font-family:'DM Sans',sans-serif;color:rgba(255,255,255,0.5);font-size:0.8125rem;letter-spacing:0.15em;text-transform:uppercase;margin-top:1rem;">
                {{ config('wedding.wedding_date') }}
            </p>
            <div style="width:40px;height:1px;background:rgba(201,168,76,0.4);margin:2rem auto;"></div>
            <p style="color:rgba(255,255,255,0.35);font-size:0.8125rem;">
                {{ config('wedding.hashtag') }}
            </p>
        </div>
    </div>

    {{-- RIGHT: Login form --}}
    <div class="flex-1 flex items-center justify-center p-8 lg:p-12"
         style="background:#FAF7F2; min-height:100vh;">
        <div style="width:100%;max-width:400px;">
            {{-- Mobile logo only --}}
            <div class="lg:hidden text-center mb-10">
                <img src="{{ asset('images/D&B.png') }}" alt="D&B" class="h-12 mx-auto mb-3" onerror="this.style.display='none'">
                <p style="font-family:'Playfair Display',serif;font-size:1.5rem;color:#0D0D0D;">Dorothy & Ben</p>
            </div>

            <p style="font-family:'DM Sans',sans-serif;font-size:0.75rem;letter-spacing:0.15em;text-transform:uppercase;color:#C9A84C;margin-bottom:0.5rem;">
                Admin Portal
            </p>
            <h2 style="font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:400;color:#0D0D0D;margin:0 0 2rem;">
                Sign In
            </h2>

            {{ $slot }}
        </div>
    </div>

</div>

@livewireScripts
</body>
</html>
