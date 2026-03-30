@php($status = $coupon->derivedStatus())
<span class="coupon-status coupon-status--{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
