<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt vé thành công</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7fa; color: #334155;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 24px; overflow: hidden; shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #0d2e59; padding: 40px 0;">
                            <img src="https://object.dailyve.com/dailyve/wp-content/uploads/2024/10/DailyVe-12-300x104.png" width="180" alt="Dailyve Logo" style="display: block;">
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 48px 40px;">
                            <h1 style="margin: 0 0 16px; font-size: 28px; font-weight: 800; color: #0d2e59; text-align: center;">Xác nhận đặt vé thành công!</h1>
                            <p style="margin: 0; font-size: 16px; line-height: 24px; text-align: center; color: #64748b;">Chào <strong>{{ $customer_name }}</strong>, cảm ơn bạn đã tin tưởng dịch vụ của Dailyve. Hành trình của bạn đã sẵn sàng!</p>
                            
                            <!-- Booking Card -->
                            <div style="margin-top: 40px; padding: 32px; background-color: #f8fafc; border-radius: 20px; border: 1px solid #e2e8f0;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td style="padding-bottom: 24px; border-bottom: 1px dashed #cbd5e1;">
                                            <div style="text-transform: uppercase; font-size: 10px; font-weight: 800; tracking: 1px; color: #94a3b8; margin-bottom: 4px;">Mã đơn hàng</div>
                                            <div style="font-size: 24px; font-weight: 900; color: #e11d48;">#{{ $booking_code }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 24px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    <td width="50%" style="padding-bottom: 20px;">
                                                        <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Nhà xe</div>
                                                        <div style="font-size: 15px; font-weight: 700; color: #1e293b;">{{ $company }}</div>
                                                    </td>
                                                    <td width="50%" style="padding-bottom: 20px;">
                                                        <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Thời gian đi</div>
                                                        <div style="font-size: 15px; font-weight: 700; color: #1e293b;">{{ $pickup_date }}</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="padding-bottom: 20px;">
                                                        <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Tuyến đường</div>
                                                        <div style="font-size: 15px; font-weight: 700; color: #1e293b;">{{ $route_name }}</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%">
                                                        <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Số ghế</div>
                                                        <div style="font-size: 15px; font-weight: 700; color: #1e293b;">{{ $seats }}</div>
                                                    </td>
                                                    <td width="50%">
                                                        <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Tổng tiền</div>
                                                        <div style="font-size: 15px; font-weight: 900; color: #0d2e59;">{{ number_format((float)$total_price, 0, ',', '.') }}đ</div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div style="margin-top: 40px; text-align: center;">
                                <p style="font-size: 14px; color: #64748b; margin-bottom: 24px;">Vui lòng có mặt tại điểm đón trước 15-30 phút và xuất trình email này để lên xe.</p>
                                <a href="{{ home_url('/tra-cuu-ve') }}" style="display: inline-block; padding: 16px 40px; background-color: #2474e5; color: #ffffff; text-decoration: none; border-radius: 16px; font-weight: 800; font-size: 14px; box-shadow: 0 10px 15px -3px rgba(36, 116, 229, 0.3);">TRA CỨU VÉ CHI TIẾT</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 0 40px 48px; text-align: center;">
                            <div style="padding-top: 32px; border-top: 1px solid #f1f5f9;">
                                <p style="margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #1e293b;">Dailyve - Hành trình vạn dặm</p>
                                <p style="margin: 0; font-size: 12px; color: #94a3b8;">Hotline: 1900 0155 • Email: lienhe@dailyve.com</p>
                            </div>
                        </td>
                    </tr>
                </table>
                
                <p style="margin-top: 32px; font-size: 11px; color: #94a3b8; text-align: center;">
                    Đây là email tự động, vui lòng không trả lời email này.<br>
                    © {{ date('Y') }} Dailyve Co., Ltd. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
