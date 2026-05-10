<div>
    <div class="mb-6">
        <h2 style="font-family: var(--font-serif); font-size: 1.75rem;">QR Check-in Scanner</h2>
        <p class="text-sm mt-1" style="color: var(--color-muted);">
            Point the camera at a guest's QR code. Check-in is automatic — no typing needed.
        </p>
    </div>

    <div
        x-data="qrScanner()"
        x-init="init()"
        @keydown.escape.window="stopCamera()"
        class="max-w-lg mx-auto"
        data-verify-base="{{ rtrim(url('/verify'), '/') }}"
        x-ref="root"
    >
        {{-- Status Card --}}
        <div x-show="!cameraActive && !result" class="admin-card border border-[var(--color-border)] bg-white p-8 text-center mb-4">
            <div style="width:64px;height:64px;border:1px solid var(--color-border);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;border-radius:50%;">
                <svg style="width:28px;height:28px;color:var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
            </div>
            <h3 style="font-family:var(--font-serif);font-size:1.25rem;margin-bottom:0.5rem;">Ready to Scan</h3>
            <p style="color:var(--color-muted);font-size:0.875rem;margin-bottom:1.5rem;line-height:1.6;">
                Tap the button below to activate your camera and start scanning guest QR codes for check-in.
            </p>
            <button @click="startCamera()" class="btn-gold">
                <svg style="width:16px;height:16px;margin-right:6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Start Camera
            </button>
        </div>

        {{-- Camera View --}}
        <div x-show="cameraActive" x-cloak class="mb-4">
            <div style="position:relative;background:#000;aspect-ratio:1;max-height:360px;overflow:hidden;border:1px solid var(--color-border);">
                <video x-ref="video" autoplay playsinline muted
                       style="width:100%;height:100%;object-fit:cover;"></video>
                <canvas x-ref="canvas" style="display:none;"></canvas>

                {{-- Scan overlay --}}
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;">
                    <div style="width:200px;height:200px;position:relative;">
                        {{-- Corner brackets --}}
                        <div style="position:absolute;top:0;left:0;width:30px;height:30px;border-top:3px solid #C9A84C;border-left:3px solid #C9A84C;"></div>
                        <div style="position:absolute;top:0;right:0;width:30px;height:30px;border-top:3px solid #C9A84C;border-right:3px solid #C9A84C;"></div>
                        <div style="position:absolute;bottom:0;left:0;width:30px;height:30px;border-bottom:3px solid #C9A84C;border-left:3px solid #C9A84C;"></div>
                        <div style="position:absolute;bottom:0;right:0;width:30px;height:30px;border-bottom:3px solid #C9A84C;border-right:3px solid #C9A84C;"></div>
                        {{-- Scanning line animation --}}
                        <div x-ref="scanLine" style="position:absolute;left:0;right:0;top:0;height:2px;background:linear-gradient(to right,transparent,#C9A84C,transparent);animation:scanLine 2s ease-in-out infinite;"></div>
                    </div>
                </div>

                {{-- Status badge --}}
                <div style="position:absolute;bottom:12px;left:0;right:0;text-align:center;">
                    <span style="background:rgba(0,0,0,0.6);color:#fff;font-size:12px;padding:4px 12px;letter-spacing:0.08em;">
                        SCANNING...
                    </span>
                </div>
            </div>

            <div style="display:flex;gap:8px;margin-top:8px;">
                {{-- Switch camera --}}
                <button @click="switchCamera()" style="flex:1;padding:8px;border:1px solid var(--color-border);background:#fff;font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Switch Camera
                </button>
                <button @click="stopCamera()" style="flex:1;padding:8px;border:1px solid #fca5a5;background:#fff5f5;color:#dc2626;font-size:12px;cursor:pointer;">
                    Stop Camera
                </button>
            </div>
        </div>

        {{-- Result (shown briefly then redirects) --}}
        <div x-show="result" x-cloak x-transition class="admin-card border bg-white p-6 text-center"
             :style="result === 'found' ? 'border-color:var(--color-gold);' : 'border-color:#fca5a5;'">
            <div x-show="result === 'found'">
                <div style="width:48px;height:48px;background:rgba(201,168,76,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
                    <svg style="width:24px;height:24px;color:var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p style="font-weight:500;margin-bottom:0.25rem;">QR Code Found!</p>
                <p style="font-size:12px;color:var(--color-muted);">Redirecting to check-in...</p>
            </div>
            <div x-show="result === 'invalid'">
                <div style="width:48px;height:48px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
                    <svg style="width:24px;height:24px;color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p style="font-weight:500;color:#dc2626;margin-bottom:0.25rem;">Not a valid QR code</p>
                <p style="font-size:12px;color:var(--color-muted);">Resuming scan...</p>
            </div>
        </div>

        {{-- Tips --}}
        <div class="admin-card border border-[var(--color-border)] bg-white p-5 mt-4">
            <p class="text-xs font-medium mb-3" style="color:var(--color-muted);text-transform:uppercase;letter-spacing:0.1em;">Tips for best results</p>
            <ul style="font-size:13px;color:var(--color-muted);line-height:2;list-style:none;padding:0;margin:0;">
                <li>📱 Hold the guest's phone about 15–20cm from camera</li>
                <li>💡 Ensure there is enough light on the QR code</li>
                <li>🔄 Use "Switch Camera" to use front or back camera</li>
                <li>✅ Check-in is automatic — no button to press after scan</li>
            </ul>
        </div>
    </div>

    <style>
    @keyframes scanLine {
        0%   { top: 0; opacity: 1; }
        50%  { top: calc(100% - 2px); opacity: 1; }
        100% { top: 0; opacity: 1; }
    }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsqr/1.4.0/jsQR.min.js"></script>
    <script>
    function qrScanner() {
        return {
            cameraActive:  false,
            result:        null,
            stream:        null,
            scanInterval:  null,
            facingMode:    'environment',
            baseUrl:       document.querySelector('[data-verify-base]')?.dataset.verifyBase ?? '',
            venueParam:    '?check_in=1',

            init() {
                // Auto-start camera on desktop if desired
            },

            async startCamera() {
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: this.facingMode, width: { ideal: 1280 }, height: { ideal: 1280 } }
                    });
                    this.$refs.video.srcObject = this.stream;
                    await this.$refs.video.play();
                    this.cameraActive = true;
                    this.startScanning();
                } catch (err) {
                    alert('Camera access denied or not available. Please allow camera access and try again.');
                }
            },

            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(t => t.stop());
                    this.stream = null;
                }
                clearInterval(this.scanInterval);
                this.cameraActive = false;
                this.result       = null;
            },

            async switchCamera() {
                this.facingMode = this.facingMode === 'environment' ? 'user' : 'environment';
                this.stopCamera();
                await this.$nextTick();
                this.startCamera();
            },

            startScanning() {
                this.scanInterval = setInterval(() => {
                    this.scanFrame();
                }, 250); // scan 4 times per second
            },

            scanFrame() {
                const video  = this.$refs.video;
                const canvas = this.$refs.canvas;
                if (!video || video.readyState !== video.HAVE_ENOUGH_DATA) return;

                canvas.width  = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: 'dontInvert',
                });

                if (code) {
                    this.handleQrCode(code.data);
                }
            },

            handleQrCode(data) {
                clearInterval(this.scanInterval); // stop scanning immediately

                // Check if it's one of our verify URLs
                const verifyPattern = /\/verify\/([a-zA-Z0-9]+)/;
                const match = data.match(verifyPattern);

                if (match) {
                    this.result = 'found';
                    const token = match[1];
                    // Redirect to check-in URL after brief pause
                    setTimeout(() => {
                        window.location.href = this.baseUrl + '/' + token + this.venueParam;
                    }, 800);
                } else {
                    // Not our QR code
                    this.result = 'invalid';
                    setTimeout(() => {
                        this.result = null;
                        this.startScanning(); // resume
                    }, 1500);
                }
            }
        }
    }
    </script>
</div>