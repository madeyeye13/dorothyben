<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>New Wish — {{ $wish->name }}</title>
<style>
  body { margin:0; padding:0; background:#FAF7F2; font-family: Arial, sans-serif; }
  .wrapper { max-width:560px; margin:40px auto; background:#fff; border:1px solid #E2D9C8; }
  .header { background:#0D0D0D; padding:32px 40px; }
  .header h1 { color:#C9A84C; font-size:1.125rem; margin:0; font-weight:400; }
  .header p { color:rgba(255,255,255,0.4); font-size:0.75rem; margin:6px 0 0; }
  .body { padding:40px; }
  h2 { font-family:Georgia,serif; font-size:1.25rem; color:#0D0D0D; margin:0 0 16px; font-weight:400; }
  .message-box { background:#FAF7F2; border-left:3px solid #C9A84C; padding:16px 20px; margin:16px 0; font-size:0.9375rem; color:#3a3a3a; line-height:1.8; }
  .meta { font-size:0.8125rem; color:#6B6B6B; margin-top:8px; }
  .btn { display:inline-block; padding:10px 24px; background:#C9A84C; color:#fff; text-decoration:none; font-size:0.8125rem; letter-spacing:0.08em; text-transform:uppercase; margin-top:20px; }
  .footer { padding:24px 40px; border-top:1px solid #E2D9C8; font-size:0.8125rem; color:#6B6B6B; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>New Wish Received</h1>
    <p>Dorothy & Ben Wedding — Awaiting Your Approval</p>
  </div>
  <div class="body">
    <h2>{{ $wish->name }} left a wish 💛</h2>
    <div class="message-box">"{{ $wish->message }}"</div>
    <p class="meta">Submitted {{ $wish->created_at->format('d M Y, g:i A') }}</p>
    <p style="font-size:0.875rem; color:#3a3a3a; margin-top:16px;">
      This wish is currently <strong>pending approval</strong>. Visit your admin dashboard to approve or delete it.
    </p>
    <a href="{{ url('/admin/wishes') }}" class="btn">Review in Admin →</a>
  </div>
  <div class="footer">Dorothy & Ben Wedding Admin</div>
</div>
</body>
</html>
