<?php

add_shortcode('kiemtrave_script', 'func_kiemtrave_script');

function func_kiemtrave_script($atts, $content = "")

{

	ob_start();

?>
	<style>
		#kiemtraveForm {
			background-color: #ffffff;
			padding: 20px 40px;
			border-radius: 8px;
			border: 1px solid #E6E6E8;
		}

		#kiemtraveForm table {
			margin: 0px;
		}

		#kiemtraveForm table td {
			border: none;
		}

		#kiemtraveForm table td button {
			margin: 0px;
			padding: 3px 10px !important;
			border-radius: 8px;
			text-transform: none;
			font-size: .9rem;
		}

		#kiemtraveForm table td input {
			border-radius: 8px;
			border: 1px solid #37A3FB;
			background: #ffffff;
			box-shadow: 0 0 0 rgba(1, 116, 229, 0.20);
			padding: 22px 14px;
			font-size: .9rem;
			margin: 5px 0 0 0;
		}

		@media screen and (max-width: 768px) {
			#kiemtraveForm {
				padding: 20px;
			}
		}

		@media screen and (max-width: 549px) {
			#kiemtraveForm input {
				margin-bottom: 0px !important;
			}
		}
	</style>
	<form id="kiemtraveForm">
		<h2 class="title_kiemtrave text-center" style="color: #1B4C85; font-weight: 600; margin-bottom: 0;">Nhập thông tin vé xe</h2>
		<table class="w-30-l w-100 fl pv3 ml6-l form_kiemtrave">
			<tbody>
				<tr>
					<td class="gray pv2">
						<div>
							<label for="phone">Số điện thoại <span style="color: red;">*</span></label>
						</div>
						<input class="w-80-l w-100 pv2 form-input" name="phone" id="phone" type="tel" required placeholder="Số điện thoại (Bắt Buộc)" />
					</td>
				</tr>
				<tr>
					<td class="gray pv2">
						<div>
							<label for="code">Mã vé <span style="color: red;">*</span></label>
						</div>
						<input class="w-80-l w-100 pv2 form-input" name="code" id="code" type="text" required placeholder="Mã Vé" />
					</td>
				</tr>
				<tr>
					<td class="pv2 "><button class="button primary block full-width" type="submit">Kiểm tra vé</button></td>
				</tr>
			</tbody>
		</table>
	</form>

	<script type="text/javascript">
		(function($) {
			$(document).ready(function() {
				$(document).on('submit', 'form#kiemtraveForm', function(e) {
					e.preventDefault();
					var $this = $(this);
					var $inputs = $this.find('input');
					var values = {};
					$inputs.each(function() {
						values[this.name] = $(this).val();
					});
					values['action'] = 'kiemtrave';
					$.ajax({
						type: "post",
						dataType: "json",
						url: '<?php echo admin_url('admin-ajax.php'); ?>',
						data: values,
						context: this,
						beforeSend: function() {
							$('.ketquakiemtrave .col-inner').html('<div class="warrap-loader"><span class="loader"></span></div>');
						},
						success: function(response) {
							if (response.success) {
								$('.ketquakiemtrave .col-inner').html(response.data.html);
							} else {
								var msg = (response.data && response.data.message) ? response.data.message : 'Không tìm thấy vé xe';
								$('.ketquakiemtrave .col-inner').html('<div class="text-center"><span style="color: red;">' + msg + '</span></div>');
							}
						},
						error: function(jqXHR, textStatus, errorThrown) {
							console.log('The following error occured: ' + textStatus, errorThrown);
						}
					});
				});
			})
		})(jQuery)
	</script>
<?php
	return ob_get_clean();
}

add_action('wp_ajax_kiemtrave', 'func_ajax_kiemtrave');
add_action('wp_ajax_nopriv_kiemtrave', 'func_ajax_kiemtrave');

function func_ajax_kiemtrave()
{
	$code  = isset($_POST['code'])  ? trim(sanitize_text_field($_POST['code']))  : '';
	$phone = isset($_POST['phone']) ? trim(sanitize_text_field($_POST['phone'])) : '';

	if (empty($code) || empty($phone)) {
		wp_send_json_error(['message' => 'Vui lòng nhập đầy đủ mã vé và số điện thoại.']);
		return;
	}

	// Auto-detect partner: thử goopay trước, nếu thất bại thử vexere
	$partners      = ['goopay', 'vexere'];
	$payload       = null;
	$partner_found = '';

	foreach ($partners as $partner) {
		$resp = call_api_v2('booking/' . rawurlencode($code), 'GET', ['partner' => $partner]);

		if (is_wp_error($resp)) {
			continue;
		}

		$http_code = wp_remote_retrieve_response_code($resp);
		$body_raw  = wp_remote_retrieve_body($resp);
		$body      = json_decode($body_raw, true);

		if ($http_code !== 200 || empty($body)) {
			continue;
		}

		// Lấy data từ wrapper nếu có
		$data = isset($body['data']) ? $body['data'] : $body;
		if (is_array($data) && isset($data[0]) && is_array($data[0])) {
			$data = $data[0];
		}

		// Kiểm tra có trường đặc trưng thì coi là hợp lệ
		if (!empty($data) && (isset($data['bookingCode']) || isset($data['companyName']) || isset($data['customerPhone']))) {
			$payload       = $data;
			$partner_found = $partner;
			break;
		}
	}

	if (empty($payload)) {
		wp_send_json_error(['message' => 'Không tìm thấy thông tin vé. Vui lòng kiểm tra lại mã vé.']);
		return;
	}

	// Verify số điện thoại khớp với response
	$customer_phone_raw  = (string)(isset($payload['customerPhone']) ? $payload['customerPhone'] : '');
	$customer_phone_norm = preg_replace('/\D/', '', $customer_phone_raw);
	$input_phone_norm    = preg_replace('/\D/', '', $phone);

	// Chuẩn hóa: chuyển đầu 84 -> 0
	$normalize = function ($p) {
		if (substr($p, 0, 2) === '84' && strlen($p) === 11) {
			return '0' . substr($p, 2);
		}
		return $p;
	};

	$customer_phone_norm = $normalize($customer_phone_norm);
	$input_phone_norm    = $normalize($input_phone_norm);

	if (!empty($customer_phone_norm) && $customer_phone_norm !== $input_phone_norm) {
		wp_send_json_error(['message' => 'Số điện thoại không khớp với thông tin vé.']);
		return;
	}

	// ------- Map fields từ response mới -------
	$customer_name     = (string)(isset($payload['customerName'])    ? $payload['customerName']    : '');
	$customer_email    = (string)(isset($payload['customerEmail'])   ? $payload['customerEmail']   : '');
	$company_name      = (string)(isset($payload['companyName'])     ? $payload['companyName']     : '');
	$booking_code      = (string)(isset($payload['bookingCode'])     ? $payload['bookingCode']     : $code);
	$final_amount      = (float)(isset($payload['finalAmount'])      ? $payload['finalAmount']     : (isset($payload['totalAmount']) ? $payload['totalAmount'] : 0));
	$departure_time    = (string)(isset($payload['departureTime'])   ? $payload['departureTime']   : '');
	$departure_place   = (string)(isset($payload['tickets'][0]['pickupPoint'])  ? $payload['tickets'][0]['pickupPoint']  : '');
	$destination_place = (string)(isset($payload['tickets'][0]['dropoffPoint']) ? $payload['tickets'][0]['dropoffPoint'] : '');
	$status_raw        = strtoupper((string)(isset($payload['status']) ? $payload['status'] : ''));

	// Dịch trạng thái sang tiếng Việt
	$status_map = [
		'CONFIRMED' => 'Đã xác nhận',
		'PAID'      => 'Đã thanh toán',
		'REFUNDED' => $partner_found == 'goopay' ? 'Hủy vé hoàn tiền' : 'Đã hủy',
		'CANCELED'  => 'Đã hủy',
		'PENDING'   => 'Chờ xử lý',
		'COMPLETED' => 'Hoàn thành',
		'EXPIRED'   => 'Hết hạn',
		'UNKNOWN'   => $partner_found == 'goopay' ? 'Đã hủy' : 'Không xác định'
	];
	$status_vn    = isset($status_map[$status_raw]) ? $status_map[$status_raw] : $status_raw;
	$status_color = in_array($status_raw, ['CANCELLED', 'CANCELED', 'EXPIRED']) ? '#dc3545' : '#28a745';

	// Ghế ngồi & tổng giá tính từ tickets[]
	$tickets    = (isset($payload['tickets']) && is_array($payload['tickets'])) ? $payload['tickets'] : [];
	$seat_codes = [];
	$total_fare = 0;
	foreach ($tickets as $t) {
		if (!empty($t['seatCode'])) {
			$seat_codes[] = (string)$t['seatCode'];
		}
		$total_fare += (float)(isset($t['fare']) ? $t['fare'] : 0);
	}
	$seats_str = !empty($seat_codes) ? implode(', ', $seat_codes) : '—';

	// Nếu finalAmount = 0 thì sum từ tickets
	if ($final_amount <= 0 && $total_fare > 0) {
		$final_amount = $total_fare;
	}

	// Định dạng giờ khởi hành
	$depart_formatted = '';
	if (!empty($departure_time)) {
		// departureTime từ goopay: "2026-03-13 01:30:00 2026-03-13 01:30:00" → lấy phần đầu
		$depart_parts = explode(' ', trim($departure_time));
		$depart_clean = trim((isset($depart_parts[0]) ? $depart_parts[0] : '') . ' ' . (isset($depart_parts[1]) ? $depart_parts[1] : ''));
		$depart_ts    = strtotime($depart_clean);
		if ($depart_ts) {
			$depart_formatted = date('H:i d/m/Y', $depart_ts);
		} else {
			$depart_formatted = $depart_clean;
		}
	}

	// ------- Build HTML -------
	$html  = '<div class="pt3 fl w-50-l w-50-m w-100">';

	// Thông tin khách hàng
	$html .= '<table class="w-100"><tbody>';
	$html .= '<tr><th class="w-50 tl f4 ttu" style="border-bottom: 0; width: 50%;" rowspan="3">' . esc_html($customer_name) . '</th></tr>';
	$html .= '<tr><td class="gray f6 text-center">Phone: ' . esc_html($customer_phone_raw ?: $phone) . '</td></tr>';
	$html .= '<tr><td class="gray f6 text-center">Email: ' . esc_html($customer_email) . '</td></tr>';
	$html .= '</tbody></table>';
	$html .= '<hr class="ba b--dotted mv2 b--black-20"/>';

	// Chi tiết vé
	$html .= '<table class="ttc f6 w-100"><tbody>';

	// Mã đặt chỗ & nhà xe
	$html .= '<tr>';
	$html .= '<td class="b pb3 w-25 tl">Mã đặt chỗ:</td><td class="pb3 tl ttu">' . esc_html($booking_code) . '</td>';
	$html .= '<td class="b pb3 w-25 tl pl3">Nhà xe:</td><td class="pb3 tl">' . esc_html($company_name) . '</td>';
	$html .= '</tr>';

	// Giờ khởi hành & Tổng tiền
	$html .= '<tr>';
	$html .= '<td class="b pb3 tl">Giờ khởi hành:</td><td class="pb3 tl">' . esc_html($depart_formatted ?: '—') . '</td>';
	$html .= '<td class="b pb3 w-25 tl pl3">Tổng tiền:</td><td class="pb3 tl color-menu">' . number_format($final_amount, 0, ',', '.') . 'đ</td>';
	$html .= '</tr>';

	// Điểm đón & Điểm trả
	$html .= '<tr>';
	$html .= '<td class="b pb3 tl">Điểm đón:</td><td class="pb3 tl">' . esc_html($departure_place ?: '—') . '</td>';
	$html .= '<td class="b pb3 tl pl3">Điểm trả:</td><td class="pb3 tl">' . esc_html($destination_place ?: '—') . '</td>';
	$html .= '</tr>';

	// Ghế & Trạng thái
	$html .= '<tr>';
	$html .= '<td class="b pb3 tl">Vị trí ghế:</td><td class="pb3 tl">' . esc_html($seats_str) . '</td>';
	$html .= '<td class="b pb3 color-all-fare tl pl3">Tình trạng vé:</td>';
	$html .= '<td class="pb3 tl" style="color: ' . $status_color . '; font-weight: 600;">' . esc_html($status_vn) . '</td>';
	$html .= '</tr>';

	$html .= '</tbody></table>';

	// Nút quay lại
	$html .= '<div><table class="w-100"><tbody><tr>';
	$html .= '<td class="w-50" style="width: 50%;"><a href="/" class="ba mt3 br3 b--black-20 w-50-l mb3 w-100 bg-color-login dim fl tc pointer link ttc pv2 button primary">Quay lại trang chủ</a></td>';
	$html .= '<td class="w-50"></td>';
	$html .= '</tr></tbody></table></div>';

	$html .= '</div>';

	wp_send_json_success(['html' => $html, 'data' => $payload]);

	die();
}
