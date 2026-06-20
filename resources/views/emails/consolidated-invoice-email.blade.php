<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Iceberg Dry Ice Invoice</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.5;">
    <p>Dear {{ $customer->name ?? 'Customer' }},</p>

    <p>
        Please find attached your invoice
        <strong>#{{ $invoice->invoice_number ?? '' }}</strong>@if(!empty($invoice->invoice_date)) dated
        {{ \Illuminate\Support\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}@endif
        for a total of <strong>${{ number_format($invoice->total_amount ?? 0, 2) }}</strong>.
    </p>

    <p>If you have any questions about this invoice, simply reply to this email.</p>

    <p>
        Thank you,<br>
        Iceberg Dry Ice
    </p>
</body>
</html>
