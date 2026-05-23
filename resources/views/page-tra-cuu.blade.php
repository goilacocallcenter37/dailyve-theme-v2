@extends('layouts.app')

@section('content')
  <main class="bg-white min-h-[70vh] py-24">
    <section class="mx-auto max-w-3xl px-4">
      <div class="text-center mb-12">
        <h1 class="font-display text-4xl md:text-5xl font-semibold tracking-tighter text-slate-900">Kiểm tra chuyến đi</h1>
        <p class="mt-4 text-base text-slate-500 max-w-xl mx-auto">Theo dõi lịch trình, vị trí ghế và trạng thái thanh toán vé của bạn một cách nhanh chóng và chính xác.</p>
      </div>

      <div class="rounded-xl bg-slate-100 p-8 md:p-10">
        {{-- Booking Lookup Form --}}
        <form id="dailyve-ticket-lookup-form" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label for="phone" class="block text-sm font-semibold text-slate-900 mb-2">Số điện thoại <span class="text-red-500 font-normal">*</span></label>
              <input type="tel" id="phone" name="phone" required
                     class="block w-full h-10 rounded-md border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-900 transition-colors focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                     placeholder="Ví dụ: 0912345678">
            </div>
            <div>
              <label for="code" class="block text-sm font-semibold text-slate-900 mb-2">Mã đặt chỗ <span class="text-red-500 font-normal">*</span></label>
              <input type="text" id="code" name="code" required
                     class="block w-full h-10 rounded-md border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-900 uppercase transition-colors focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                     placeholder="Ví dụ: VEXERE123">
            </div>
          </div>
          <div class="flex justify-center pt-4">
            <button type="submit" id="btn-lookup-submit" class="inline-flex h-10 items-center justify-center rounded-md bg-[#2196F3] px-8 text-sm font-semibold text-white transition-all hover:bg-[#1565C0] focus:outline-none focus:ring-2 focus:ring-[#2196F3] focus:ring-offset-2">
              Tra cứu vé
            </button>
          </div>
        </form>

        {{-- Loading State --}}
        <div id="lookup-loading" class="hidden mt-10 flex flex-col items-center justify-center py-8">
          <svg class="h-6 w-6 animate-spin text-[#2196F3]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>

        {{-- Error State --}}
        <div id="lookup-error" class="hidden mt-8 rounded-lg border border-red-200 bg-red-50 p-4">
          <div class="flex items-start">
            <i class="fas fa-exclamation-circle mt-0.5 text-red-500 text-sm"></i>
            <div class="ml-3">
              <h3 class="text-sm font-semibold text-red-800">Không tìm thấy vé</h3>
              <p id="lookup-error-message" class="mt-1 text-sm text-red-700">Vui lòng kiểm tra lại mã vé hoặc số điện thoại.</p>
            </div>
          </div>
        </div>

        {{-- Result State --}}
        <div id="lookup-result" class="hidden mt-12">
           {{-- Will be populated by JS --}}
        </div>
      </div>
    </section>

    {{-- Support & Guide Section --}}
    <section class="mx-auto max-w-5xl px-4 mt-20">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Feature 1 -->
        <div class="rounded-xl border border-slate-200 bg-white p-8 transition-shadow hover:shadow-md">
          <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-[#2196F3]">
            <i class="fas fa-question-circle text-xl"></i>
          </div>
          <h3 class="mb-3 text-[17px] font-semibold text-slate-900">Lấy mã vé ở đâu?</h3>
          <p class="text-sm text-slate-600 leading-relaxed">Mã đặt chỗ (Ví dụ: <strong>VEXERE123</strong>) được gửi tự động qua Email và tin nhắn SMS số điện thoại của bạn ngay sau khi thanh toán thành công.</p>
        </div>

        <!-- Feature 2 -->
        <div class="rounded-xl border border-slate-200 bg-white p-8 transition-shadow hover:shadow-md">
          <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-[#2196F3]">
            <i class="fas fa-headset text-xl"></i>
          </div>
          <h3 class="mb-3 text-[17px] font-semibold text-slate-900">Hỗ trợ nhanh 24/7</h3>
          <p class="text-sm text-slate-600 leading-relaxed">Bạn cần thay đổi lịch trình, hoàn/hủy vé hoặc khiếu nại dịch vụ? Hãy liên hệ ngay hotline <a href="tel:19001234" class="font-semibold text-[#2196F3] hover:underline">1900 1234</a> để được xử lý.</p>
        </div>

        <!-- Feature 3 -->
        <div class="rounded-xl border border-slate-200 bg-white p-8 transition-shadow hover:shadow-md">
          <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-[#2196F3]">
            <i class="fas fa-shield-alt text-xl"></i>
          </div>
          <h3 class="mb-3 text-[17px] font-semibold text-slate-900">Bảo mật thông tin</h3>
          <p class="text-sm text-slate-600 leading-relaxed">Tất cả thông tin cá nhân, lịch trình chuyến đi và dữ liệu thanh toán của bạn đều được chúng tôi mã hóa và bảo mật tuyệt đối.</p>
        </div>
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('dailyve-ticket-lookup-form');
      const loadingEl = document.getElementById('lookup-loading');
      const errorEl = document.getElementById('lookup-error');
      const errorMsgEl = document.getElementById('lookup-error-message');
      const resultEl = document.getElementById('lookup-result');
      const btnSubmit = document.getElementById('btn-lookup-submit');

      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // UI Reset
        errorEl.classList.add('hidden');
        resultEl.classList.add('hidden');
        loadingEl.classList.remove('hidden');
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-70', 'cursor-not-allowed');

        const formData = new FormData(form);
        formData.append('action', 'kiemtrave');

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(res => {
          loadingEl.classList.add('hidden');
          btnSubmit.disabled = false;
          btnSubmit.classList.remove('opacity-70', 'cursor-not-allowed');

          if (res.success && res.data && res.data.data) {
            renderTicket(res.data.data, formData.get('code'));
          } else {
            showError(res.data && res.data.message ? res.data.message : 'Không tìm thấy vé xe. Vui lòng kiểm tra lại thông tin.');
          }
        })
        .catch(err => {
          console.error(err);
          loadingEl.classList.add('hidden');
          btnSubmit.disabled = false;
          btnSubmit.classList.remove('opacity-70', 'cursor-not-allowed');
          showError('Có lỗi xảy ra trong quá trình kết nối. Vui lòng thử lại sau.');
        });
      });

      function showError(msg) {
        errorMsgEl.textContent = msg;
        errorEl.classList.remove('hidden');
      }

      function formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
      }

      function renderTicket(payload, inputCode) {
        let tickets = Array.isArray(payload.tickets) ? payload.tickets : [];
        
        let passengerName = '';
        if (tickets.length > 0 && tickets[0].passengerName) {
            passengerName = tickets[0].passengerName;
        } else {
            passengerName = payload.customerName || 'Không có tên';
        }

        const customerPhone = payload.customerPhone || form.querySelector('#phone').value;
        const customerEmail = payload.customerEmail || 'Không có email';
        const companyName = payload.companyName || 'Không rõ nhà xe';
        const bookingCode = payload.bookingCode || inputCode;
        
        let finalAmount = parseFloat(payload.finalAmount);
        if (isNaN(finalAmount) || finalAmount <= 0) {
            finalAmount = parseFloat(payload.totalAmount) || 0;
        }
        
        let seatCodes = [];
        let totalFare = 0;
        
        let departurePlace = payload.departurePlace || '';
        let destinationPlace = payload.destinationPlace || '';

        if (!departurePlace && tickets.length > 0) {
           departurePlace = tickets[0].pickupPoint ? tickets[0].pickupPoint.split('|')[0] : '';
        } else if (departurePlace) {
           departurePlace = departurePlace.split('|')[0];
        }

        if (!destinationPlace && tickets.length > 0) {
           destinationPlace = tickets[0].dropoffPoint ? tickets[0].dropoffPoint.split('|')[0] : '';
        } else if (destinationPlace) {
           destinationPlace = destinationPlace.split('|')[0];
        }

        tickets.forEach(t => {
           if (t.seatCode) {
               seatCodes.push(t.seatCode.split('|')[0]);
           }
           totalFare += (parseFloat(t.fare) || 0);
        });

        const seatsStr = seatCodes.length > 0 ? seatCodes.join(', ') : '—';
        if (finalAmount <= 0 && totalFare > 0) {
           finalAmount = totalFare;
        }

        let departureTime = payload.departureTime || '';
        if (departureTime) {
           const parts = departureTime.trim().split(' ');
           departureTime = parts[0] + ' ' + (parts[1] || '');
        }

        let statusRaw = (payload.status || '').toUpperCase();
        const statusDesc = (payload.statusDescription || '').toUpperCase();
        
        // Vexere edge case: status is REFUNDED but description is CANCELED -> treat as CANCELED
        if (statusDesc === 'CANCELED' || statusDesc === 'CANCELLED') {
            statusRaw = 'CANCELED';
        }

        const statusMap = {
            'CONFIRMED': { text: 'Đã xác nhận', color: 'bg-emerald-100 text-emerald-700' },
            'PAID': { text: 'Đã thanh toán', color: 'bg-emerald-100 text-emerald-700' },
            'REFUNDED': { text: 'Đã hoàn tiền', color: 'bg-orange-100 text-orange-700' },
            'CANCELED': { text: 'Đã hủy', color: 'bg-red-100 text-red-700' },
            'CANCELLED': { text: 'Đã hủy', color: 'bg-red-100 text-red-700' },
            'PENDING': { text: 'Chờ xử lý', color: 'bg-amber-100 text-amber-700' },
            'COMPLETED': { text: 'Hoàn thành', color: 'bg-blue-100 text-blue-700' },
            'EXPIRED': { text: 'Hết hạn', color: 'bg-slate-100 text-slate-700' }
        };

        const statusInfo = statusMap[statusRaw] || { text: statusRaw || 'Không xác định', color: 'bg-slate-100 text-slate-700' };

        const html = `
          <div class="relative overflow-hidden rounded-[16px] bg-white shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-slate-200">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 p-6 md:p-8">
              <div class="flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-slate-50 text-slate-900">
                  <i class="fas fa-ticket-alt text-sm"></i>
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-slate-900">${companyName}</h3>
                  <p class="text-sm font-medium text-slate-500 mt-0.5">Mã vé: <span class="uppercase text-slate-900 font-bold">${bookingCode}</span></p>
                </div>
              </div>
              <div class="mt-4 sm:mt-0 flex shrink-0">
                <span class="inline-flex items-center rounded-full px-3 py-1 text-[13px] font-semibold ${statusInfo.color}">
                  ${statusInfo.text}
                </span>
              </div>
            </div>

            <!-- Body -->
            <div class="p-6 md:p-8">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                
                <!-- Hành khách -->
                <div>
                  <h4 class="text-[13px] font-semibold tracking-wide text-slate-500 mb-4 uppercase">Hành khách</h4>
                  <div class="space-y-4">
                    <div>
                      <p class="text-[13px] font-medium text-slate-500 mb-0.5">Họ và tên</p>
                      <p class="text-sm font-semibold text-slate-900">${passengerName}</p>
                    </div>
                    <div>
                      <p class="text-[13px] font-medium text-slate-500 mb-0.5">Số điện thoại</p>
                      <p class="text-sm font-medium text-slate-900">${customerPhone}</p>
                    </div>
                    <div>
                      <p class="text-[13px] font-medium text-slate-500 mb-0.5">Email</p>
                      <p class="text-sm font-medium text-slate-900 break-all">${customerEmail}</p>
                    </div>
                  </div>
                </div>

                <!-- Chuyến đi -->
                <div>
                  <h4 class="text-[13px] font-semibold tracking-wide text-slate-500 mb-4 uppercase">Hành trình</h4>
                  <div class="relative pl-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-px before:bg-slate-200">
                    <div class="relative mb-6">
                      <div class="absolute -left-6 top-1.5 h-2 w-2 rounded-full ring-4 ring-white bg-[#2196F3]"></div>
                      <p class="text-sm font-semibold text-slate-900">${departurePlace || 'Đang cập nhật'}</p>
                      <p class="text-[13px] font-medium text-slate-500 mt-1">${departureTime || '—'}</p>
                    </div>
                    <div class="relative">
                      <div class="absolute -left-6 top-1.5 h-2 w-2 rounded-full ring-4 ring-white bg-red-500"></div>
                      <p class="text-sm font-semibold text-slate-900">${destinationPlace || 'Đang cập nhật'}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer / Payment -->
            <div class="border-t border-slate-100 bg-slate-50 p-6 md:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div>
                <p class="text-[13px] font-medium text-slate-500">Vị trí ghế</p>
                <p class="font-display text-lg font-semibold tracking-tight text-slate-900 mt-0.5">${seatsStr}</p>
              </div>
              <div class="sm:text-right">
                <p class="text-[13px] font-medium text-slate-500">Tổng thanh toán</p>
                <p class="font-display text-2xl font-semibold tracking-tight text-[#2196F3] mt-0.5">${formatMoney(finalAmount)}</p>
              </div>
            </div>
          </div>
        `;

        resultEl.innerHTML = html;
        resultEl.classList.remove('hidden');
        
        // Scroll to result slightly
        setTimeout(() => {
          resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
      }
    });
  </script>
@endsection
