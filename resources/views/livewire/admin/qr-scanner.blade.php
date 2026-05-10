<div>
    <div class="mb-6 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">QR Check-in Scanner</h2>
            <p class="text-sm mt-1" style="color: var(--color-muted);">
                Scan a guest QR code — their details appear here without leaving this page.
            </p>
        </div>
        @if($hasPin)
        <div x-data="{ pinOk: sessionStorage.getItem('venue_pin_verified') === '1' }"
             @pinVerified.window="pinOk = true; sessionStorage.setItem('venue_pin_verified', '1');">
            <span style="font-size:11px;padding:4px 10px;border:1px solid;"
                  :style="pinOk
                      ? 'color:#059669;border-color:#a7f3d0;background:#f0fdf4;'
                      : 'color:#dc2626;border-color:#fca5a5;background:#fff5f5;'"
                  x-text="pinOk ? '🔓 PIN verified for this session' : '🔒 PIN not verified'">
            </span>
        </div>
        @endif
    </div>

    <div class="max-w-lg mx-auto"
         x-data="qrScanner()"
         x-init="init()"
         @resumeScanning.window="resumeScanning()"
         @pinVerified.window="sessionStorage.setItem('venue_pin_verified', '1')">

        {{-- PIN Setup Modal (shown once per session if PIN is set) --}}
        @if($hasPin && $showPinSetup)
        @php $alreadyVerifiedClient = false; // server doesn't know, JS will handle @endphp
        <div x-data="{ show: sessionStorage.getItem('venue_pin_verified') !== '1' }"
             x-show="show"
             x-cloak
             @pinVerified.window="show = false"
             style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;">
            <div style="background:#fff;max-width:360px;width:100%;padding:2rem;text-align:center;">
                <div style="width:48px;height:48px;background:rgba(201,168,76,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <svg style="width:22px;height:22px;color:var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 style="font-family:var(--font-serif);font-size:1.25rem;margin-bottom:0.5rem;">Venue Staff PIN</h3>
                <p style="color:var(--color-muted);font-size:0.875rem;margin-bottom:1.25rem;">
                    Enter the venue PIN once. It will be remembered for this browser session — you won't be asked again.
                </p>
                <input wire:model="pin"
                       type="password"
                       inputmode="numeric"
                       placeholder="Enter PIN"
                       autofocus
                       style="width:100%;padding:12px;border:1px solid {{ $pinError ? '#ef4444' : 'var(--color-border)' }};text-align:center;font-size:1.5rem;letter-spacing:0.4em;margin-bottom:8px;outline:none;"
                       wire:keydown.enter="verifyPin">
                @if($pinError)
                <p style="color:#ef4444;font-size:12px;margin-bottom:8px;">Incorrect PIN. Please try again.</p>
                @endif
                <button wire:click="verifyPin" class="btn-gold w-full">Confirm PIN</button>
                <p style="font-size:11px;color:var(--color-muted);margin-top:12px;">
                    PIN is stored only for this session and cleared when you close the tab.
                </p>
            </div>
        </div>
        @endif

        {{-- Scanner method badge --}}
        <div x-show="method" x-cloak
             style="font-size:11px;text-align:center;margin-bottom:8px;padding:3px 0;"
             :style="method === 'native' ? 'color:#059669;' : 'color:var(--color-gold);'"
             x-text="method === 'native' ? '✓ Native camera scanner (fast)' : '✓ jsQR scanner'">
        </div>

        {{-- Start button --}}
        <div x-show="!cameraActive" class="admin-card border border-[var(--color-border)] bg-white p-8 text-center mb-4">
            <div style="width:64px;height:64px;border:1px solid var(--color-border);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                <svg style="width:28px;height:28px;color:var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 style="font-family:var(--font-serif);font-size:1.25rem;margin-bottom:0.5rem;">Ready to Scan</h3>
            <p style="color:var(--color-muted);font-size:0.875rem;margin-bottom:1.5rem;line-height:1.6;">
                Guest details appear as a popup — no page navigation needed.
            </p>
            <button @click="startCamera()" class="btn-gold" style="min-width:160px;">
                Start Camera
            </button>
            <p x-show="errorMsg" x-cloak x-text="errorMsg"
               style="color:#ef4444;font-size:12px;margin-top:12px;line-height:1.6;"></p>
        </div>

        {{-- Camera view --}}
        <div x-show="cameraActive" x-cloak class="mb-4">
            <div style="position:relative;background:#000;overflow:hidden;border:1px solid var(--color-border);">
                <video x-ref="video" autoplay playsinline muted
                       style="width:100%;display:block;max-height:380px;object-fit:cover;"></video>
                <canvas x-ref="canvas" style="display:none;"></canvas>

                {{-- Aim frame --}}
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;">
                    <div style="width:200px;height:200px;position:relative;">
                        <div style="position:absolute;top:0;left:0;width:28px;height:28px;border-top:3px solid #C9A84C;border-left:3px solid #C9A84C;"></div>
                        <div style="position:absolute;top:0;right:0;width:28px;height:28px;border-top:3px solid #C9A84C;border-right:3px solid #C9A84C;"></div>
                        <div style="position:absolute;bottom:0;left:0;width:28px;height:28px;border-bottom:3px solid #C9A84C;border-left:3px solid #C9A84C;"></div>
                        <div style="position:absolute;bottom:0;right:0;width:28px;height:28px;border-bottom:3px solid #C9A84C;border-right:3px solid #C9A84C;"></div>
                        <div style="position:absolute;left:0;right:0;height:2px;background:linear-gradient(to right,transparent,#C9A84C,transparent);animation:scanline 2s ease-in-out infinite;"></div>
                    </div>
                </div>

                {{-- Status --}}
                <div style="position:absolute;bottom:8px;left:0;right:0;text-align:center;">
                    <span style="background:rgba(0,0,0,0.6);color:#fff;font-size:11px;padding:3px 12px;letter-spacing:0.08em;">
                        POINT AT GUEST QR CODE
                    </span>
                </div>

                {{-- Green flash on detect --}}
                <div x-show="flashGreen" x-cloak
                     style="position:absolute;inset:0;background:rgba(201,168,76,0.2);display:flex;align-items:center;justify-content:center;">
                    <div style="background:var(--color-gold);border-radius:50%;width:56px;height:56px;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:28px;height:28px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:8px;margin-top:8px;">
                <button @click="switchCamera()"
                        style="flex:1;padding:9px;border:1px solid var(--color-border);background:#fff;font-size:12px;cursor:pointer;">
                    🔄 Flip Camera
                </button>
                <button @click="stopCamera()"
                        style="flex:1;padding:9px;border:1px solid #fca5a5;background:#fff5f5;color:#dc2626;font-size:12px;cursor:pointer;">
                    Stop
                </button>
            </div>
        </div>

        {{-- Tips --}}
        <div class="admin-card border border-[var(--color-border)] bg-white p-4">
            <ul style="font-size:13px;color:var(--color-muted);line-height:2;list-style:none;padding:0;margin:0;">
                <li>📱 Hold 15–25cm from the QR code</li>
                <li>💡 Ensure QR code is well lit</li>
                <li>🔄 Use Flip Camera if wrong camera opens</li>
                <li>✅ Guest popup appears — no page reload needed</li>
            </ul>
        </div>
    </div>

    {{-- ── Guest Modal ── --}}
    @if($showModal)
    <div style="position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:9000;display:flex;align-items:center;justify-content:center;padding:1rem;"
         wire:click.self="closeModal">
        <div style="background:#fff;max-width:400px;width:100%;max-height:90vh;overflow-y:auto;position:relative;">

            {{-- Close --}}
            <button wire:click="closeModal"
                    style="position:absolute;top:12px;right:12px;background:none;border:none;cursor:pointer;color:var(--color-muted);z-index:1;">
                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            @if($notFound)
            {{-- Not found --}}
            <div style="padding:2.5rem;text-align:center;">
                <div style="width:52px;height:52px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <svg style="width:26px;height:26px;color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h3 style="font-family:var(--font-serif);font-size:1.25rem;color:#991b1b;margin-bottom:0.5rem;">Guest Not Found</h3>
                <p style="color:#b91c1c;font-size:0.875rem;margin-bottom:1.5rem;">
                    This QR code is not valid or the guest declined their RSVP.
                </p>
                <button wire:click="closeModal" class="btn-outline-gold text-xs">Close & Scan Again</button>
            </div>

            @else
            {{-- Guest found --}}
            {{-- Status header --}}
            @if($checkedIn)
            <div style="background:var(--color-gold);padding:10px 20px;display:flex;align-items:center;gap:8px;">
                <svg style="width:16px;height:16px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                <p style="color:#fff;font-size:12px;font-weight:500;letter-spacing:0.08em;text-transform:uppercase;margin:0;">
                    Checked In — {{ now()->format('g:i A') }}
                </p>
            </div>
            @elseif($alreadyUsed)
            <div style="background:#fef3c7;padding:10px 20px;display:flex;align-items:center;gap:8px;">
                <svg style="width:16px;height:16px;color:#92400e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>
                </svg>
                <p style="color:#92400e;font-size:12px;font-weight:500;letter-spacing:0.08em;text-transform:uppercase;margin:0;">
                    Already Checked In — {{ $guest?->qr_used_at?->format('g:i A') }}
                </p>
            </div>
            @else
            <div style="background:var(--color-obsidian);padding:10px 20px;display:flex;align-items:center;justify-content:space-between;">
                <p style="color:rgba(255,255,255,0.6);font-size:11px;letter-spacing:0.1em;text-transform:uppercase;margin:0;">Guest Found</p>
                <p style="color:var(--color-gold);font-size:11px;margin:0;">{{ config('wedding.wedding_date') }}</p>
            </div>
            @endif

            {{-- Guest details --}}
            <div style="padding:1.5rem;text-align:center;">
                <h3 style="font-family:var(--font-serif);font-size:1.75rem;color:var(--color-obsidian);margin:0 0 0.25rem;">
                    {{ $guest?->full_name }}
                </h3>
                <p style="color:var(--color-muted);font-size:0.875rem;margin-bottom:1rem;">
                    {{ $guest?->relationship_label }}
                </p>

                @if($guest?->companions->count())
                <div style="border:1px solid var(--color-border);padding:0.875rem;margin-bottom:1rem;text-align:left;">
                    <p style="font-size:10px;letter-spacing:0.12em;text-transform:uppercase;color:var(--color-muted);margin:0 0 6px;">
                        Plus {{ $guest->companions->count() }} guest(s)
                    </p>
                    @foreach($guest->companions as $comp)
                    <div style="display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid var(--color-border);"
                         class="last:border-0">
                        <p style="font-size:0.875rem;font-weight:500;margin:0;">{{ $comp->name }}</p>
                        @if($comp->relation)
                        <p style="font-size:0.75rem;color:var(--color-muted);margin:0;">{{ $comp->relation }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Already used notice --}}
                @if($alreadyUsed && !$checkedIn)
                <div style="background:#fef3c7;border:1px solid #fde68a;padding:10px 14px;margin-bottom:1rem;">
                    <p style="font-size:12px;color:#92400e;margin:0;">
                        This guest was already checked in at {{ $guest?->qr_used_at?->format('g:i A') }}.
                        Verify their identity manually if needed.
                    </p>
                </div>
                @endif

                {{-- Check in button --}}
                @if(!$checkedIn)
                <button wire:click="checkIn"
                        class="btn-gold w-full"
                        style="margin-bottom:0.75rem;padding:14px;">
                    @if($alreadyUsed)
                    ✓ Re-check In Guest
                    @else
                    ✓ Check In This Guest
                    @endif
                </button>
                @else
                <div style="background:rgba(201,168,76,0.08);border:1px solid rgba(201,168,76,0.3);padding:12px;margin-bottom:1rem;">
                    <p style="font-size:13px;color:var(--color-gold-dark);margin:0;">✓ Guest admitted at {{ now()->format('g:i A') }}</p>
                </div>
                @endif

                <button wire:click="closeModal"
                        class="btn-outline-gold w-full text-xs">
                    Close & Scan Next Guest
                </button>
            </div>
            @endif
        </div>
    </div>
    @endif

    <style>
    @keyframes scanline {
        0%   { top: 0; }
        50%  { top: calc(100% - 2px); }
        100% { top: 0; }
    }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsqr/1.4.0/jsQR.min.js" crossorigin="anonymous"></script>
    <script>
    function qrScanner() {
        return {
            cameraActive: false,
            stream:       null,
            scanning:     false,
            scanInterval: null,
            facingMode:   'environment',
            errorMsg:     '',
            method:       '',
            flashGreen:   false,

            init() {
                if ('BarcodeDetector' in window) {
                    this.method = 'native';
                } else {
                    this.method = 'jsqr';
                }
            },

            async startCamera() {
                this.errorMsg = '';
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: { ideal: this.facingMode }, width: { ideal: 1280 }, height: { ideal: 720 } },
                        audio: false,
                    });
                } catch(e) {
                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                    } catch(e2) {
                        this.errorMsg = e.name === 'NotAllowedError'
                            ? 'Camera permission denied. Please allow camera access and reload.'
                            : 'Camera error: ' + e.message;
                        return;
                    }
                }

                const video = this.$refs.video;
                video.srcObject = this.stream;
                video.onloadedmetadata = () => {
                    video.play().then(() => {
                        this.cameraActive = true;
                        this.scanning     = true;
                        setTimeout(() => this.beginScan(), 800);
                    });
                };
            },

            beginScan() {
                if (!this.scanning) return;
                if (this.method === 'native') {
                    this.scanNative();
                } else {
                    this.scanJsQR();
                }
            },

            async scanNative() {
                let detector;
                try {
                    detector = new BarcodeDetector({ formats: ['qr_code'] });
                } catch(e) {
                    this.method = 'jsqr';
                    this.scanJsQR();
                    return;
                }

                const video = this.$refs.video;
                const loop  = async () => {
                    if (!this.scanning || !this.cameraActive) return;
                    try {
                        const codes = await detector.detect(video);
                        if (codes.length > 0) {
                            this.onDetected(codes[0].rawValue);
                            return;
                        }
                    } catch(e) {}
                    requestAnimationFrame(loop);
                };
                requestAnimationFrame(loop);
            },

            scanJsQR() {
                const video  = this.$refs.video;
                const canvas = this.$refs.canvas;
                if (typeof jsQR === 'undefined') {
                    this.errorMsg = 'Scanner library failed to load. Please refresh.';
                    return;
                }
                if (this.scanInterval) clearInterval(this.scanInterval);
                this.scanInterval = setInterval(() => {
                    if (!this.scanning || !video || video.readyState < 2 || video.videoWidth === 0) return;
                    canvas.width  = video.videoWidth;
                    canvas.height = video.videoHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0);
                    try {
                        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const code    = jsQR(imgData.data, imgData.width, imgData.height, { inversionAttempts: 'attemptBoth' });
                        if (code && code.data) this.onDetected(code.data);
                    } catch(e) {}
                }, 150);
            },

            onDetected(data) {
                // Pause scanning while modal is open
                this.scanning = false;
                if (this.scanInterval) { clearInterval(this.scanInterval); this.scanInterval = null; }

                const match = data.match(/\/verify\/([a-zA-Z0-9]+)/);
                if (match) {
                    this.flashGreen = true;
                    setTimeout(() => { this.flashGreen = false; }, 600);
                    // Send token to Livewire — modal opens
                    this.$wire.processToken(match[1]);
                } else {
                    // Not our QR — resume scanning after brief pause
                    setTimeout(() => {
                        this.scanning = true;
                        this.beginScan();
                    }, 1000);
                }
            },

            resumeScanning() {
                // Called when modal closes
                this.scanning = true;
                this.beginScan();
            },

            stopCamera() {
                this.scanning = false;
                if (this.stream) { this.stream.getTracks().forEach(t => t.stop()); this.stream = null; }
                if (this.scanInterval) { clearInterval(this.scanInterval); this.scanInterval = null; }
                this.cameraActive = false;
                this.flashGreen   = false;
            },

            async switchCamera() {
                this.facingMode = this.facingMode === 'environment' ? 'user' : 'environment';
                this.stopCamera();
                await new Promise(r => setTimeout(r, 300));
                this.startCamera();
            },
        }
    }
    </script>
</div>