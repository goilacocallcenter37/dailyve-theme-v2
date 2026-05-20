@extends('layouts.app')

@section('content')
@php
    $journey_group_id = $_GET['code'] ?? '';
    if (empty($journey_group_id)) {
        echo "<script>window.location.href = '/';</script>";
        exit;
    }

    $existing_post = get_posts([
        'post_type' => 'book-ticket',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'journey_group_id',
                'value' => $journey_group_id,
                'compare' => '=',
            ]
        ],
    ]);

    if (!empty($existing_post)) {
        usort($existing_post, function ($a, $b) {
            $ia = (int)get_post_meta($a->ID, 'journey_ticket_index', true) ?: 9999;
            $ib = (int)get_post_meta($b->ID, 'journey_ticket_index', true) ?: 9999;
            return $ia === $ib ? $a->ID <=> $b->ID : $ia <=> $ib;
        });

        $post_id = $existing_post[0]->ID;
        $post_title = $existing_post[0]->post_title;
        $totalPrice = 0;
        $displayTickets = [];
        
        foreach ($existing_post as $p) {
            $tp = get_post_meta($p->ID, 'total_price', true) ?: get_field('total_price', $p->ID);
            $totalPrice += (float)$tp;
            
            $seatStr = (string)get_post_meta($p->ID, 'seat', true);
            $seatArr = array_values(array_filter(array_map('trim', explode(',', $seatStr))));
            
            $displayTickets[] = [
                'post_id' => $p->ID,
                'from' => get_post_meta($p->ID, 'pickup_name', true),
                'to' => get_post_meta($p->ID, 'dropoff_name', true),
                'fromAddress' => get_post_meta($p->ID, 'pickup_address', true),
                'toAddress' => get_post_meta($p->ID, 'dropoff_address', true),
                'company' => get_post_meta($p->ID, 'company_bus', true),
                'vehicle' => get_post_meta($p->ID, 'vehicle_name', true),
                'pickupDate' => get_post_meta($p->ID, 'pickup_date', true),
                'arrivalDate' => get_post_meta($p->ID, 'arrival_date', true),
                'seatArr' => $seatArr,
                'partner_id' => get_post_meta($p->ID, 'partner_id', true),
            ];
        }

        $paymentStatus = (int)get_post_meta($post_id, 'payment_status', true) ?: 1;
        $paymentContent = get_post_meta($post_id, 'payment_content', true) ?: (get_field('payment_content', $post_id) ?: '');
        $payment_key = $journey_group_id ?: $post_title;

        $post_date_gmt = get_post_time('U', true, $post_id);
        $remaining_seconds = max(0, ($post_date_gmt + 600) - time());
        $contact = [
            'name' => get_post_meta($post_id, 'full_name', true),
            'phone' => get_post_meta($post_id, 'phone', true),
            'email' => get_post_meta($post_id, 'email', true),
        ];
    }
@endphp

<div class="dailyve-payment-page min-h-screen bg-slate-50/50 pb-20 pt-10">
    <div class="mx-auto max-w-7xl px-4">
        @if (!empty($existing_post))
            <div class="mb-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="font-display text-4xl font-black tracking-tight text-slate-900">Thanh toán <span class="text-primary">đơn hàng</span></h1>
                    <p class="mt-2 font-bold text-slate-400">Mã đơn hàng: <span class="text-slate-900">#{{ $payment_key }}</span></p>
                </div>
                @if ($paymentStatus == 1)
                    <div class="inline-flex items-center gap-4 rounded-xl bg-primary px-6 py-3 text-white shadow-sm">
                        <div class="h-10 w-10 animate-pulse rounded-full bg-white/20 p-2">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-black uppercase tracking-widest opacity-70">Thời gian còn lại</div>
                            <div id="time-expired" class="font-display text-2xl font-black tabular-nums">10:00</div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid gap-10 lg:grid-cols-[1fr_400px]">
                {{-- Left: Payment Methods --}}
                <div class="space-y-8">
                    @if ($paymentStatus == 1)
                        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                            <h3 class="flex items-center gap-3 font-display text-2xl font-black text-slate-900">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-success/10 text-success">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                Chọn phương thức thanh toán
                            </h3>

                            <div class="mt-10 space-y-6">
                                {{-- QR Transfer --}}
                                <div class="relative overflow-hidden rounded-2xl border border-primary/30 bg-primary/5 p-6 transition-all">
                                    <div class="absolute right-0 top-0 rounded-bl-3xl bg-primary px-6 py-2 text-[10px] font-black uppercase tracking-widest text-white">
                                        Khuyên dùng
                                    </div>
                                    
                                    <div class="flex flex-col gap-8 md:flex-row">
                                        <div class="flex-1 space-y-6">
                                            <div class="flex items-center gap-4">
                                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white shadow-sm">
                                                    <i class="fas fa-qrcode text-2xl text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="text-xl font-black text-slate-900">Chuyển khoản QR</div>
                                                    <div class="text-xs font-bold text-slate-400">Tự động điền nội dung & số tiền</div>
                                                </div>
                                            </div>

                                            <div class="space-y-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-xs font-bold text-slate-400">Ngân hàng</span>
                                                    <span class="text-sm font-black text-slate-900">MBBank</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-xs font-bold text-slate-400">Chủ tài khoản</span>
                                                    <span class="text-sm font-black text-slate-900 uppercase">DAILYVE CO. LTD</span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs font-bold text-slate-400">Số tài khoản</span>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-sm font-black text-primary">VQRQAAVUO1996</span>
                                                        <button onclick="coppyText('VQRQAAVUO1996')" class="text-slate-300 hover:text-primary"><i class="far fa-copy"></i></button>
                                                    </div>
                                                </div>
                                                <div class="flex justify-between items-center border-t border-slate-50 pt-4">
                                                    <span class="text-xs font-bold text-slate-400">Nội dung CK</span>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-sm font-black text-danger">{{ $paymentContent }}</span>
                                                        <button onclick="coppyText('{{ $paymentContent }}')" class="text-slate-300 hover:text-danger"><i class="far fa-copy"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex shrink-0 flex-col items-center justify-center space-y-4 md:w-48">
                                            <div class="rounded-xl border-2 border-slate-100 bg-white p-3 shadow-sm">
                                                <img src="https://img.vietqr.io/image/MB-VQRQAAVUO1996-qr_only.png?amount={{ (int)$totalPrice }}&addInfo={{ urlencode($paymentContent) }}" class="h-40 w-40" alt="Payment QR">
                                            </div>
                                            <div class="text-center text-[10px] font-black uppercase tracking-widest text-slate-400">
                                                Quét để thanh toán
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @elseif ($paymentStatus == 2)
                        <section class="rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                            <div class="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-full bg-success/10 text-4xl text-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h2 class="font-display text-3xl font-black text-slate-900">Thanh toán thành công!</h2>
                            <p class="mt-4 text-slate-500">Cảm ơn quý khách đã đặt vé. Thông báo xác nhận đã được gửi đến email/SĐT của bạn.</p>
                            <a href="/" class="mt-10 inline-block rounded-lg bg-primary px-8 py-3 text-sm font-semibold text-white shadow-sm">QUAY LẠI TRANG CHỦ</a>
                        </section>
                    @else
                        <section class="rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                            <div class="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-full bg-danger/10 text-4xl text-danger">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <h2 class="font-display text-3xl font-black text-slate-900">Đơn hàng đã bị hủy</h2>
                            <p class="mt-4 text-slate-500">Tiếc quá, đơn hàng của bạn đã quá hạn thanh toán hoặc đã bị hủy.</p>
                            <a href="/" class="mt-10 inline-block rounded-lg bg-primary px-8 py-3 text-sm font-semibold text-white shadow-sm">ĐẶT VÉ MỚI</a>
                        </section>
                    @endif
                </div>

                {{-- Right: Summary --}}
                <aside class="space-y-6">
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="bg-slate-50/80 px-8 py-6">
                            <h3 class="font-display text-lg font-black text-slate-900">Chi tiết đơn hàng</h3>
                        </div>
                        <div class="p-8 space-y-8">
                            <div class="space-y-4">
                                <div class="text-xs font-black uppercase tracking-widest text-slate-400">Thông tin liên hệ</div>
                                <div class="space-y-1">
                                    <div class="font-bold text-slate-900">{{ $contact['name'] }}</div>
                                    <div class="text-sm text-slate-500">{{ $contact['phone'] }} • {{ $contact['email'] }}</div>
                                </div>
                            </div>
                            
                            <hr class="border-slate-50">

                            @foreach ($displayTickets as $key => $item)
                                <div class="space-y-4">
                                    @if (count($displayTickets) > 1)
                                        <div class="inline-block rounded-md bg-primary/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-wide text-primary">
                                            {{ $key === 0 ? 'Chiều đi' : 'Chiều về' }}
                                        </div>
                                    @endif
                                    <div class="font-bold text-slate-900">{{ $item['company'] }}</div>
                                    <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-4 py-2">
                                        <div class="text-center">
                                            <div class="font-display text-lg font-black">{{ convertStringToTimeN($item['pickupDate']) }}</div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $item['from'] }}</div>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <div class="h-0.5 w-8 bg-slate-100"></div>
                                            <i class="fas fa-bus-alt text-[10px] text-slate-300"></i>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-display text-lg font-black">{{ convertStringToTimeN($item['arrivalDate']) }}</div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $item['to'] }}</div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between rounded-xl bg-slate-50 p-3 text-xs">
                                        <span class="font-bold text-slate-400">Số ghế</span>
                                        <span class="font-black text-slate-900">{{ implode(', ', $item['seatArr']) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="bg-primary/5 p-8">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-500 uppercase tracking-widest">Tổng cộng</span>
                                <span class="font-display text-3xl font-black text-primary-dark">
                                    {{ number_format($totalPrice, 0, ',', '.') }}đ
                                </span>
                            </div>
                        </div>
                    </section>
                </aside>
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-white py-24 text-center shadow-sm">
                <div class="mb-8 flex h-32 w-32 items-center justify-center rounded-full bg-slate-50 text-5xl text-slate-200">
                    <i class="fas fa-search"></i>
                </div>
                <h2 class="font-display text-3xl font-black text-slate-900">Không tìm thấy đơn hàng</h2>
                <p class="mt-4 max-w-sm font-bold text-slate-400">Mã đơn hàng không hợp lệ hoặc đã bị xóa khỏi hệ thống.</p>
                <a href="/" class="mt-10 rounded-2xl bg-primary px-10 py-4 text-sm font-black text-white">VỀ TRANG CHỦ</a>
            </div>
        @endif
    </div>
</div>

<script>
    function coppyText(text) {
        navigator.clipboard.writeText(text).then(() => alert('Đã sao chép: ' + text));
    }

    function showExpiredReservationNotice() {
        const pageContainer = document.querySelector('.dailyve-payment-page .max-w-7xl');
        if (!pageContainer) {
            return;
        }

        pageContainer.innerHTML = `
            <div class="flex flex-col items-center justify-center rounded-2xl border border-red-200 bg-white py-20 text-center shadow-sm">
                <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-red-50 text-3xl text-red-500">
                    <i class="fas fa-times-circle" aria-hidden="true"></i>
                </div>
                <h2 class="font-display text-3xl font-black text-slate-900">Vé đã bị hủy giữ chỗ</h2>
                <p class="mt-3 max-w-lg text-sm font-semibold text-slate-500">
                    Thời gian thanh toán đã hết. Vui lòng quay lại trang chủ để tìm và đặt lại chuyến phù hợp.
                </p>
                <a href="/" class="mt-8 inline-flex min-h-10 items-center justify-center rounded-lg bg-primary px-8 py-3 text-sm font-semibold text-white shadow-sm">
                    VỀ TRANG CHỦ
                </a>
            </div>
        `;
    }

    document.addEventListener('DOMContentLoaded', () => {
        @if ($paymentStatus == 1)
            let remaining = {{ (int)$remaining_seconds }};
            const expireAt = Date.now() + remaining * 1000;
            let isExpiredRendered = false;
            let checkStatus = null;
            const timer = setInterval(() => {
                const rem = Math.floor((expireAt - Date.now()) / 1000);
                if (rem <= 0) {
                    clearInterval(timer);
                    document.getElementById('time-expired').innerText = '00:00';
                    if (checkStatus) {
                        clearInterval(checkStatus);
                    }
                    if (!isExpiredRendered) {
                        isExpiredRendered = true;
                        showExpiredReservationNotice();
                    }
                    return;
                }
                const m = Math.floor(rem / 60);
                const s = rem % 60;
                document.getElementById('time-expired').innerText = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
            }, 1000);

            // Polling transaction status
            checkStatus = setInterval(() => {
                const formData = new FormData();
                formData.append('action', 'check_transaction_ticket');
                formData.append('nonce', '{{ wp_create_nonce('ams_vexe_check_transaction') }}');
                formData.append('code', '{{ $payment_key }}');
                formData.append('journey_group_id', '{{ $journey_group_id }}');

                fetch('{{ admin_url('admin-ajax.php') }}', {
                    method: 'POST',
                    body: new URLSearchParams(formData)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success && res.data?.status) {
                        clearInterval(checkStatus);
                        location.reload();
                    }
                })
                .catch(err => console.error(err));
            }, 5000);
        @endif
    });
</script>
@endsection
