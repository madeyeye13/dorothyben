<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>New RSVP — {{ $guest->full_name }}</title>
<style>
  body { margin: 0; padding: 0; background: #FAF7F2; font-family: Arial, sans-serif; }
  .wrapper { max-width: 560px; margin: 40px auto; background: #fff; border: 1px solid #E2D9C8; }
  .header { background: #0D0D0D; padding: 32px 40px; }
  .header h1 { color: #C9A84C; font-size: 1.25rem; margin: 0; font-weight: 400; }
  .header p { color: rgba(255,255,255,0.4); font-size: 0.75rem; margin: 6px 0 0; }
  .body { padding: 40px; }
  .badge { display: inline-block; padding: 4px 12px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 16px; }
  .badge.new { background: rgba(16,185,129,0.1); color: #065f46; }
  .badge.updated { background: rgba(245,158,11,0.1); color: #92400e; }
  h2 { font-family: Georgia, serif; font-size: 1.375rem; color: #0D0D0D; margin: 0 0 20px; font-weight: 400; }
  .detail-box { border: 1px solid #E2D9C8; margin: 16px 0; }
  .detail-row { display: flex; padding: 10px 16px; border-bottom: 1px solid #E2D9C8; font-size: 0.875rem; }
  .detail-row:last-child { border-bottom: none; }
  .label { color: #6B6B6B; width: 140px; shrink: 0; }
  .value { color: #0D0D0D; font-weight: 500; flex: 1; }
  .value.attending { color: #059669; }
  .value.not-attending { color: #dc2626; }
  .footer { padding: 24px 40px; border-top: 1px solid #E2D9C8; font-size: 0.8125rem; color: #6B6B6B; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>New RSVP Notification</h1>
    <p>Dorothy & Ben Wedding Admin</p>
  </div>
  <div class="body">
    <span class="badge {{ $action === 'new' ? 'new' : 'updated' }}">
      {{ $action === 'new' ? '✓ New RSVP' : '✎ Updated RSVP' }}
    </span>
    <h2>{{ $guest->full_name }}</h2>

    <div class="detail-box">
      <div class="detail-row">
        <span class="label">Status</span>
        <span class="value {{ $guest->attending === 'yes' ? 'attending' : 'not-attending' }}">
          {{ $guest->attending === 'yes' ? '✓ Attending' : '✕ Not Attending' }}
        </span>
      </div>
      <div class="detail-row">
        <span class="label">Email</span>
        <span class="value">{{ $guest->email }}</span>
      </div>
      @if($guest->phone)
      <div class="detail-row">
        <span class="label">Phone</span>
        <span class="value">{{ $guest->phone }}</span>
      </div>
      @endif
      <div class="detail-row">
        <span class="label">Relationship</span>
        <span class="value">{{ $guest->relationship_label ?: 'Not specified' }}</span>
      </div>
      @if($guest->companions->count())
      <div class="detail-row">
        <span class="label">Plus Guests</span>
        <span class="value">
          @foreach($guest->companions as $comp)
            {{ $comp->name }}{{ $comp->relation ? ' (' . $comp->relation . ')' : '' }}<br>
          @endforeach
        </span>
      </div>
      @endif
      @if($guest->attending === 'no' && $guest->decline_reason)
      <div class="detail-row">
        <span class="label">Reason</span>
        <span class="value" style="color: #6B6B6B;">{{ $guest->decline_reason }}</span>
      </div>
      @endif
      <div class="detail-row">
        <span class="label">Submitted</span>
        <span class="value">{{ $guest->created_at->format('d M Y, g:i A') }}</span>
      </div>
    </div>
  </div>
  <div class="footer">
    View all guests in your <a href="{{ url('/admin/guests') }}" style="color: #C9A84C;">admin dashboard</a>.
  </div>
</div>
</body>
</html>
