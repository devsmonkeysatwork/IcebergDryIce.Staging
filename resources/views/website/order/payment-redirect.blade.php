@extends('website.layouts.main')



@section('content')

    <style>

        .loading-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 300;
        }

        p {
            font-size: 16px;
            opacity: 0.8;
            margin-bottom: 20px;
        }

        .secure-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(0, 255, 0, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            border: 1px solid rgba(0, 255, 0, 0.3);
        }

        .secure-badge::before {
            content: "🔒";
            margin-right: 8px;
        }

        .dots {
            display: inline-block;
            animation: dots 1.5s infinite;
        }

        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }

        .dots::after {
            content: '';
            animation: dots 1.5s infinite;
        }
    </style>


<div class="loading-container">
    <div class="spinner"></div>
    <h1>Processing Your Order</h1>
    <p>Please wait while we redirect you to secure payment<span class="dots"></span></p>
    <div class="secure-badge">
        Secure Payment Gateway
    </div>
</div>

{{-- Hidden payment form --}}
<form id="paymentForm" method="POST" action="{{ $paymentParams['payment_url'] }}" style="display: none;">
    @csrf
    @foreach($paymentParams as $key => $value)
        @if($key !== 'payment_url' && $value !== null)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit the form after 2 seconds
        setTimeout(function() {
            document.getElementById('paymentForm').submit();
        }, 2000);
    });

    // Fallback: if form doesn't submit automatically, show a manual button
    setTimeout(function() {
        if (document.visibilityState === 'visible') {
            const container = document.querySelector('.loading-container');
            const button = document.createElement('button');
            button.textContent = 'Continue to Payment';
            button.style.cssText = `
                    background: rgba(255, 255, 255, 0.2);
                    border: 1px solid rgba(255, 255, 255, 0.3);
                    color: white;
                    padding: 12px 24px;
                    border-radius: 25px;
                    cursor: pointer;
                    font-size: 16px;
                    margin-top: 20px;
                    transition: all 0.3s ease;
                `;
            button.onmouseover = function() { this.style.background = 'rgba(255, 255, 255, 0.3)'; };
            button.onmouseout = function() { this.style.background = 'rgba(255, 255, 255, 0.2)'; };
            button.onclick = function() { document.getElementById('paymentForm').submit(); };
            container.appendChild(button);
        }
    }, 5000);
</script>

@endsection

