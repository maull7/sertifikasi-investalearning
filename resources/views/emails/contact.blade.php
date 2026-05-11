<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #334155; padding: 24px;">
    <h2 style="margin-bottom: 4px;">Pesan Kontak Baru</h2>
    <p style="color: #64748b; margin-top: 0;">Dari landing page Investalearning</p>
    <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 16px 0;">
    <p><strong>Nama:</strong> {{ $senderName }}</p>
    <p><strong>Email:</strong> <a href="mailto:{{ $senderEmail }}">{{ $senderEmail }}</a></p>
    <p><strong>Pesan:</strong></p>
    <p style="background:#f8fafc; border-left:4px solid #6366f1; padding:12px 16px; border-radius:4px;">
        {{ $message }}
    </p>
</body>
</html>
