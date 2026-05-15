<div>
    {{-- Header --}}
    <div class="text-center py-20 px-6" style="background: var(--color-obsidian);">
        <p class="section-eyebrow mb-3" style="color: var(--color-gold-light);">Bless the Couple</p>
        <h1 class="section-title" style="color: #fff;">Gift Us</h1>
        <p style="color:rgba(255,255,255,0.55);font-size:0.9375rem;margin-top:1rem;max-width:480px;margin-left:auto;margin-right:auto;line-height:1.8;">
            Your presence on our special day is the greatest gift of all.
            But if you'd like to bless us further, we are deeply grateful. 💛
        </p>
    </div>

    <section class="py-16 px-6" style="background: var(--color-ivory);">
        <div class="max-w-2xl mx-auto">

            @if($accounts->count())
            {{-- Bank Accounts --}}
            <div class="mb-12">
                <div class="flex items-center gap-4 mb-6">
                    <div style="width:36px;height:36px;background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:16px;height:16px;color:var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 style="font-family:var(--font-serif);font-size:1.25rem;margin:0;">Bank Transfer</h2>
                        <p style="color:var(--color-muted);font-size:0.875rem;margin:0;">Copy account number and transfer directly</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($accounts->groupBy('currency') as $currency => $currAccounts)
                    <p class="text-xs uppercase tracking-widest flex items-center gap-3 mb-3" style="color:var(--color-gold);">
                        <span>
                            @if($currency === 'NGN') 🇳🇬 Nigerian Naira
                            @elseif($currency === 'USD') 🇺🇸 US Dollar
                            @elseif($currency === 'GBP') 🇬🇧 British Pound
                            @else {{ $currency }}
                            @endif
                        </span>
                        <span class="flex-1 h-px" style="background:var(--color-border);"></span>
                    </p>

                    @foreach($currAccounts as $account)
                    <div class="bg-white border border-[var(--color-border)] p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <p class="font-medium text-sm mb-0.5">{{ $account->bank_name }}</p>
                                <p style="color:var(--color-muted);font-size:0.875rem;">{{ $account->account_name }}</p>
                                @if($account->account_number)
                                <p style="font-family:var(--font-serif);font-size:1.75rem;letter-spacing:0.06em;color:var(--color-obsidian);margin:0.5rem 0 0;">
                                    {{ $account->account_number }}
                                </p>
                                @endif
                                @if($account->sort_code)
                                <p class="text-xs mt-1" style="color:var(--color-muted);">Sort Code: {{ $account->sort_code }}</p>
                                @endif
                                @if($account->routing_number)
                                <p class="text-xs" style="color:var(--color-muted);">Routing: {{ $account->routing_number }}</p>
                                @endif
                            </div>
                            @if($account->account_number)
                            <button onclick="copyToClipboard('{{ $account->account_number }}')"
                                    title="Copy account number"
                                    style="border:1px solid var(--color-border);padding:10px;background:none;cursor:pointer;flex-shrink:0;transition:border-color 0.2s;"
                                    onmouseover="this.style.borderColor='var(--color-gold)'"
                                    onmouseout="this.style.borderColor='var(--color-border)'">
                                <svg style="width:16px;height:16px;color:var(--color-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Payment Links --}}
            @if($paymentLinks->count())
            <div class="mb-12">
                <div class="flex items-center gap-4 mb-6">
                    <div style="width:36px;height:36px;background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg style="width:16px;height:16px;color:var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <div>
                        <h2 style="font-family:var(--font-serif);font-size:1.25rem;margin:0;">Send Online</h2>
                        <p style="color:var(--color-muted);font-size:0.875rem;margin:0;">Quick one-tap payment links</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($paymentLinks as $link)
                    <div class="bg-white border border-[var(--color-border)] p-6 flex items-center justify-between gap-4 flex-wrap">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                @if($link->currency_tag)
                                <span style="font-size:11px;padding:2px 8px;border:1px solid var(--color-gold);color:var(--color-gold);letter-spacing:0.08em;">
                                    {{ $link->currency_tag }}
                                </span>
                                @endif
                                <p class="font-medium text-sm">{{ $link->title }}</p>
                            </div>
                            @if($link->description)
                            <p style="color:var(--color-muted);font-size:0.875rem;">{{ $link->description }}</p>
                            @endif
                        </div>
                        <a href="{{ $link->url }}" target="_blank" rel="noopener"
                           class="btn-gold text-xs py-3 px-6 inline-flex items-center gap-2 shrink-0">
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            Send Gift
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Empty state --}}
            @if(!$accounts->count() && !$paymentLinks->count())
            <div class="text-center py-16" style="color:var(--color-muted);">
                <p class="text-sm">Gift details coming soon.</p>
            </div>
            @endif

            {{-- Thank you note --}}
            <div class="text-center mt-8 pt-8 border-t border-[var(--color-border)]">
                <p style="font-family:var(--font-serif);font-size:1.125rem;color:var(--color-obsidian);margin-bottom:0.5rem;">
                    Thank you for your love and generosity.
                </p>
                <p style="color:var(--color-muted);font-size:0.875rem;">— Dorothy & Ben</p>
            </div>

        </div>
    </section>

    <script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Account number copied!', type: 'success' } }));
        }).catch(() => {
            // Fallback
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Copied!', type: 'success' } }));
        });
    }
    </script>
</div>