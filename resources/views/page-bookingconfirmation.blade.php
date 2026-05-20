@extends('layouts.app')

@section('content')
<div class="dailyve-booking-page min-h-screen bg-slate-50/50 pb-20 pt-10">
    <div class="mx-auto max-w-7xl px-4">
        @php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $tickets = $_SESSION['tickets'] ?? [];
            
            // Fix: Deeply decode tickets if they were sent as JSON strings
            foreach ($tickets as &$ticket) {
                if (isset($ticket['selectedSeats']) && is_string($ticket['selectedSeats'])) {
                    $ticket['selectedSeats'] = json_decode($ticket['selectedSeats'], true);
                }
                if (!isset($ticket['selectedSeats']) || !is_array($ticket['selectedSeats'])) {
                    $ticket['selectedSeats'] = [];
                }

                if (isset($ticket['pickupPoint']) && is_string($ticket['pickupPoint'])) {
                    $ticket['pickupPoint'] = json_decode($ticket['pickupPoint'], true);
                }
                if (!isset($ticket['pickupPoint']) || !is_array($ticket['pickupPoint'])) {
                    $ticket['pickupPoint'] = [];
                }

                if (isset($ticket['dropoffPoint']) && is_string($ticket['dropoffPoint'])) {
                    $ticket['dropoffPoint'] = json_decode($ticket['dropoffPoint'], true);
                }
                if (!isset($ticket['dropoffPoint']) || !is_array($ticket['dropoffPoint'])) {
                    $ticket['dropoffPoint'] = [];
                }

                if (isset($ticket['seatsAndInfoData']) && is_string($ticket['seatsAndInfoData'])) {
                    $ticket['seatsAndInfoData'] = json_decode($ticket['seatsAndInfoData'], true);
                }
                if (!isset($ticket['seatsAndInfoData']) || !is_array($ticket['seatsAndInfoData'])) {
                    $ticket['seatsAndInfoData'] = [];
                }
            }
            unset($ticket);
            
            $collab_guest_name = $tickets[0]['collabGuestName'] ?? null;
            $collab_guest_phone = $tickets[0]['collabGuestPhone'] ?? null;
        @endphp

        @if (!empty($tickets))
            <div class="mb-10">
                <h1 class="font-display text-4xl font-bold tracking-tight text-slate-900">Xác nhận <span class="text-primary">đặt vé</span></h1>
                <p class="mt-2 font-bold text-slate-400">Vui lòng kiểm tra lại thông tin chuyến đi và điền thông tin liên hệ.</p>
            </div>

            <div class="grid gap-10 lg:grid-cols-[1fr_400px]">
                {{-- Left Column: Contact Form --}}
                <div class="space-y-8">
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                        <h3 class="flex items-center gap-3 font-display text-2xl font-bold text-slate-900">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            Thông tin liên hệ
                        </h3>
                        
                        <div class="mt-10 grid gap-8 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-slate-400">Họ và tên *</label>
                                <input
                                    type="text" 
                                    name="customer-name"
                                    placeholder="Nguyễn Văn A"
                                    value="{{ $collab_guest_name }}"
                                    {{ $collab_guest_name ? 'readonly' : '' }}
                                    class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 font-medium text-slate-700 outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-slate-400">Số điện thoại *</label>
                                <input
                                    type="tel" 
                                    name="customer-phone"
                                    placeholder="09xx xxx xxx"
                                    value="{{ $collab_guest_phone }}"
                                    {{ $collab_guest_phone ? 'readonly' : '' }}
                                    class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 font-medium text-slate-700 outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"
                                />
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-slate-400">Email *</label>
                                <input
                                    type="email" 
                                    name="customer-email"
                                    placeholder="email@example.com"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 font-medium text-slate-700 outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"
                                />
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-slate-400">Ghi chú</label>
                                <textarea
                                    name="customer-note"
                                    rows="3"
                                    placeholder="Ví dụ: Đón tôi tại cổng số 1..."
                                    class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 font-medium text-slate-700 outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"
                                ></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center gap-4 rounded-xl border border-blue-100 bg-blue-50/40 p-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary text-white">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <p class="text-xs font-bold leading-relaxed text-primary-dark">
                                Dailyve cam kết bảo mật thông tin cá nhân. Số điện thoại và email chỉ dùng để gửi thông tin đơn hàng và liên hệ khi cần thiết.
                            </p>
                        </div>
                    </section>

                    <div class="hidden lg:block">
                        <button id="btn_payment_desktop" class="group flex w-full items-center justify-center gap-3 rounded-lg bg-primary py-4 text-base font-semibold text-white shadow-sm transition-all hover:bg-primary-active active:scale-95">
                            TIẾP TỤC THANH TOÁN
                            <i class="fas fa-arrow-right transition-transform group-hover:translate-x-2"></i>
                        </button>
                        <p class="mt-4 text-center text-sm font-bold text-slate-400 italic">
                            Bạn sẽ sớm nhận được biển số xe và số điện thoại tài xế sau khi hoàn tất.
                        </p>
                    </div>
                </div>

                {{-- Right Column: Trip Summary --}}
                <aside class="space-y-6">
                    <div class="sticky top-24 space-y-6">
                        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="bg-slate-50/80 px-8 py-6">
                                <h3 class="font-display text-lg font-bold text-slate-900">Chi tiết chuyến đi</h3>
                            </div>
                            
                            <div class="p-8 space-y-10">
                                @foreach ($tickets as $key => $ticket)
                                    <div class="relative space-y-6">
                                        @if (count($tickets) > 1)
                                        <div class="inline-block rounded-md bg-primary/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-wide text-primary">
                                                {{ $key === 0 ? 'Chiều đi' : 'Chiều về' }}
                                            </div>
                                        @endif

                                        <div class="flex items-center gap-4">
                                            <div class="h-16 w-16 overflow-hidden rounded-2xl border border-slate-50 bg-slate-50 p-1">
                                                <img src="{{ $ticket['seatsAndInfoData']['company_logo'] ?? 'https://static.vexere.com/production/images/1584418537685.jpeg' }}" class="h-full w-full object-contain" alt="Bus Company">
                                            </div>
                                            <div>
                                                <div class="font-display text-lg font-bold text-slate-900">{{ $ticket['seatsAndInfoData']['company_name'] ?? 'Nhà xe' }}</div>
                                                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $ticket['seatsAndInfoData']['name'] ?? 'Limousine' }}</div>
                                            </div>
                                        </div>

                                        <div class="space-y-6 border-l-2 border-dashed border-slate-100 pl-8">
                                            <div class="relative">
                                                <div class="absolute -left-[37px] top-1.5 h-4 w-4 rounded-full border-2 border-white bg-primary shadow-sm"></div>
                                                <div class="font-display text-xl font-bold text-slate-900">
                                                    {{ isset($ticket['pickupPoint']['real_time']) ? convertDateTimeToHour($ticket['pickupPoint']['real_time']) : (isset($ticket['transferPickupPoint']['real_time']) ? convertDateTimeToHour($ticket['transferPickupPoint']['real_time']) : '') }}
                                                </div>
                                                <div class="mt-1 font-bold text-slate-700">{{ $ticket['pickupPoint']['name'] ?? ($ticket['transferPickupPoint']['name'] ?? 'Điểm đón') }}</div>
                                                <div class="mt-1 text-xs text-slate-400">{{ $ticket['pickupPointMoreDesc'] ?? ($ticket['pickupPoint']['address'] ?? ($ticket['transferPickupPoint']['address'] ?? '')) }}</div>
                                            </div>

                                            <div class="relative">
                                                <div class="absolute -left-[37px] top-1.5 h-4 w-4 rounded-full border-2 border-white bg-danger shadow-sm"></div>
                                                <div class="font-display text-xl font-bold text-slate-900">
                                                    {{ isset($ticket['dropoffPoint']['real_time']) ? convertDateTimeToHour($ticket['dropoffPoint']['real_time']) : (isset($ticket['transferDropoffPoint']['real_time']) ? convertDateTimeToHour($ticket['transferDropoffPoint']['real_time']) : '') }}
                                                </div>
                                                <div class="mt-1 font-bold text-slate-700">{{ $ticket['dropoffPoint']['name'] ?? ($ticket['transferDropoffPoint']['name'] ?? 'Điểm trả') }}</div>
                                                <div class="mt-1 text-xs text-slate-400">{{ $ticket['dropoffPointMoreDesc'] ?? ($ticket['dropoffPoint']['address'] ?? ($ticket['transferDropoffPoint']['address'] ?? '')) }}</div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3 rounded-lg bg-slate-50 p-4">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-slate-400 shadow-sm">
                                                <i class="fas fa-couch"></i>
                                            </div>
                                            <div>
                                                <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Số ghế đã chọn</div>
                                                <div class="text-sm font-bold text-slate-900">
                                                    {{ count($ticket['selectedSeats']) }} Ghế ({{ implode(', ', array_column($ticket['selectedSeats'], 'seatCode')) }})
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if (!$loop->last)
                                        <hr class="border-slate-50">
                                    @endif
                                @endforeach
                            </div>

                            <div class="bg-primary/5 p-8">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-slate-500 uppercase tracking-widest">Tổng cộng</span>
                                    <span class="font-display text-3xl font-bold text-primary-dark">
                                        {{ caculatorPriceTotal(true)['total_price'] }}
                                    </span>
                                </div>
                            </div>
                        </section>

                        <div class="lg:hidden">
                            <button id="btn_payment_mobile" class="flex w-full items-center justify-center gap-4 rounded-3xl bg-success py-5 text-lg font-bold text-white shadow-xl shadow-success/20">
                                TIẾP TỤC THANH TOÁN
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-white py-24 text-center shadow-sm">
                <div class="mb-8 flex h-32 w-32 items-center justify-center rounded-full bg-slate-50 text-5xl text-slate-200">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2 class="font-display text-3xl font-bold text-slate-900">Giỏ hàng trống</h2>
                <p class="mt-4 max-w-sm font-bold text-slate-400">Chúng tôi không tìm thấy thông tin đơn hàng của bạn. Có thể phiên làm việc đã hết hạn.</p>
                <a href="/" class="mt-10 rounded-2xl bg-primary px-10 py-4 text-sm font-bold text-white shadow-lg shadow-primary/20 transition-all hover:opacity-90 active:scale-95">
                    QUAY LẠI TRANG CHỦ
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btnPayment = document.querySelectorAll('#btn_payment_desktop, #btn_payment_mobile');
        
        btnPayment.forEach(btn => {
            btn.addEventListener('click', function() {
                const name = document.querySelector('input[name="customer-name"]');
                const phone = document.querySelector('input[name="customer-phone"]');
                const email = document.querySelector('input[name="customer-email"]');
                const note = document.querySelector('textarea[name="customer-note"]').value;

                if (!name.value.trim()) {
                    name.focus();
                    return alert('Vui lòng nhập họ và tên');
                }
                if (!phone.value.trim()) {
                    phone.focus();
                    return alert('Vui lòng nhập số điện thoại');
                }
                if (!email.value.trim() || !email.value.includes('@')) {
                    email.focus();
                    return alert('Vui lòng nhập email hợp lệ');
                }

                const tickets = {!! json_encode($tickets) !!};
                const departure_dates = tickets.map(t => {
                    const info = t.seatsAndInfoData || {};
                    const date = info.departure_date || t.departure_date || '';
                    const time = info.departure_time || t.departure_time || '';
                    return time + ' ' + date;
                });

                const payload = {
                    name: name.value,
                    phone: phone.value,
                    email: email.value,
                    contributor_code: '', // Can add if needed
                    user_id: window.generic_data?.user_id || 0,
                    note: note,
                    departure_dates: departure_dates
                };

                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i> ĐANG XỬ LÝ...';

                fetch('/wp-json/api/v1/booking', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        const data = res.data || {};
                        const journeyId = data.journey_group_id || "";
                        if (journeyId) {
                            window.location.href = `/payment-method?code=${encodeURIComponent(journeyId)}`;
                        } else {
                            alert('Đặt vé thành công nhưng thiếu mã booking. Vui lòng liên hệ hotline.');
                            this.disabled = false;
                            this.innerHTML = 'TIẾP TỤC THANH TOÁN <i class="fas fa-arrow-right ml-2"></i>';
                        }
                    } else {
                        alert(res.message || res.data?.message || 'Có lỗi xảy ra khi đặt vé.');
                        this.disabled = false;
                        this.innerHTML = 'TIẾP TỤC THANH TOÁN <i class="fas fa-arrow-right ml-2"></i>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Lỗi kết nối máy chủ.');
                    this.disabled = false;
                    this.innerHTML = 'TIẾP TỤC THANH TOÁN <i class="fas fa-arrow-right ml-2"></i>';
                });
            });
        });
    });
</script>
@endsection
