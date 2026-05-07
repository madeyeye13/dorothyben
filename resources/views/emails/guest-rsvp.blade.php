<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RSVP Confirmation — Dorothy & Ben</title>
<style>
  body { margin: 0; padding: 0; background: #FAF7F2; font-family: 'Georgia', serif; }
  .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border: 1px solid #E2D9C8; }
  .header { background: #0D0D0D; padding: 40px 48px; text-align: center; }
  .header h1 { color: #C9A84C; font-size: 2rem; margin: 0; font-weight: 400; letter-spacing: 0.04em; }
  .header p { color: rgba(255,255,255,0.5); font-size: 0.8125rem; margin: 8px 0 0; letter-spacing: 0.12em; font-family: 'Arial', sans-serif; text-transform: uppercase; }
  .body { padding: 48px; }
  .eyebrow { font-family: Arial, sans-serif; font-size: 0.75rem; letter-spacing: 0.15em; text-transform: uppercase; color: #C9A84C; margin-bottom: 12px; }
  .greeting { font-size: 1.5rem; color: #0D0D0D; margin: 0 0 20px; font-weight: 400; }
  p { font-family: Arial, sans-serif; font-size: 0.9375rem; color: #3a3a3a; line-height: 1.8; margin: 0 0 16px; }
  .detail-box { background: #FAF7F2; border: 1px solid #E2D9C8; padding: 24px; margin: 24px 0; }
  .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #E2D9C8; font-family: Arial, sans-serif; font-size: 0.875rem; }
  .detail-row:last-child { border-bottom: none; }
  .detail-label { color: #6B6B6B; }
  .detail-value { color: #0D0D0D; font-weight: 500; text-align: right; }
  .qr-section { text-align: center; margin: 32px 0; padding: 32px; border: 1px solid #E2D9C8; background: #fff; }
  .qr-section p { font-size: 0.8125rem; color: #6B6B6B; margin: 0; }
  .note-box { background: rgba(201,168,76,0.06); border-left: 3px solid #C9A84C; padding: 16px 20px; margin: 24px 0; font-family: Arial, sans-serif; font-size: 0.875rem; color: #3a3a3a; }
  .footer { background: #0D0D0D; padding: 32px 48px; text-align: center; }
  .footer p { color: rgba(255,255,255,0.4); font-family: Arial, sans-serif; font-size: 0.75rem; margin: 0; line-height: 1.8; }
  .footer .hashtag { color: #C9A84C; font-size: 0.875rem; margin-bottom: 8px; }
  .companions-list { font-family: Arial, sans-serif; font-size: 0.875rem; color: #3a3a3a; }
  .companions-list li { padding: 4px 0; }
</style>
</head>
<body>
<div class="wrapper">

  {{-- Header --}}
  <div class="header">
    <h1>Dorothy & Ben</h1>
    <p>{{ config('wedding.wedding_date') }} · {{ config('wedding.general_location') }}</p>
  </div>

  {{-- Body --}}
  <div class="body">
    <p class="eyebrow">
      @if($type === 'attending') RSVP Confirmed
      @elseif($type === 'not_attending') We'll Miss You
      @elseif($type === 'updated_attending') RSVP Updated — We're Glad!
      @elseif($type === 'updated_not_attending') RSVP Updated
      @endif
    </p>

    <h2 class="greeting">
      @if(in_array($type, ['attending', 'updated_attending'])) We can't wait to see you!
      @else We'll miss you, {{ explode(' ', $guest->full_name)[0] }}.
      @endif
    </h2>

    @if(in_array($type, ['attending', 'updated_attending']))
      @if($type === 'updated_attending')
      <p>We are so glad you have decided to celebrate our special day with us! Your RSVP has been updated and we look forward to sharing this moment with you.</p>
      @else
      <p>Thank you for confirming your attendance at our wedding. We are so excited to celebrate this beautiful milestone with you!</p>
      @endif

      {{-- Guest Details --}}
      <div class="detail-box">
        <div class="detail-row">
          <span class="detail-label">Guest Name</span>
          <span class="detail-value">{{ $guest->full_name }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Email</span>
          <span class="detail-value">{{ $guest->email }}</span>
        </div>
        @if($guest->phone)
        <div class="detail-row">
          <span class="detail-label">Phone</span>
          <span class="detail-value">{{ $guest->phone }}</span>
        </div>
        @endif
        @if($guest->companions->count())
        <div class="detail-row">
          <span class="detail-label">Plus Guest(s)</span>
          <span class="detail-value">
            @foreach($guest->companions as $comp)
              {{ $comp->name }}{{ $comp->relation ? ' (' . $comp->relation . ')' : '' }}@if(!$loop->last)<br>@endif
            @endforeach
          </span>
        </div>
        @endif
      </div>

      {{-- Event Details --}}
      <div class="note-box">
        <strong>Event Details</strong><br>
        🕙 Church: {{ config('wedding.church.name') }} — {{ config('wedding.church.time') }}<br>
        🕑 Reception: {{ config('wedding.reception.name') }} — {{ config('wedding.reception.time') }} (Doors: {{ config('wedding.reception.doors') }})<br>
        📍 {{ config('wedding.general_location') }}
      </div>

      {{-- QR Code --}}
      @if($guest->qr_token)
      <div class="qr-section">
        <p style="font-family: Georgia; font-size: 1rem; color: #0D0D0D; margin-bottom: 16px;">Your Entry Pass</p>
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(180)->generate(route('verify', ['token' => $guest->qr_token])) !!}
        <p style="margin-top: 16px;">Please present this QR code at the venue entrance for check-in.</p>
        <p style="font-size: 0.75rem; margin-top: 8px; color: #aaa;">Token: {{ $guest->qr_token }}</p>
      </div>
      @endif

    @else
      {{-- Not Attending --}}
      @if($type === 'updated_not_attending')
      <p>We understand and hope everything is well. Thank you for letting us know and for the love you've shown. Your QR code has been deactivated.</p>
      @else
      <p>We understand, and we'll be thinking of you on our special day. Your love and support mean everything to us even from a distance.</p>
      @endif
      <p>If your plans change, you can always edit your RSVP at any time by visiting our wedding website.</p>
    @endif

    <p>With love and gratitude,<br><strong>Dorothy & Ben</strong></p>
    <p style="font-size: 0.8125rem; color: #C9A84C;">{{ config('wedding.hashtag') }}</p>
  </div>

  {{-- Footer --}}
  <div class="footer">
    <p class="hashtag">{{ config('wedding.hashtag') }}</p>
    <p>Dorothy & Ben Wedding · {{ config('wedding.wedding_date') }}</p>
    <p style="margin-top: 8px;">Website created with love by Bezalel Koncept · Event Planner: PK Events</p>
  </div>

</div>
</body>
</html>
