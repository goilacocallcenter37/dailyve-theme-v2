<?php



/*************** DAILYVE ***************/

function gt_search_filter($query)

{

	// 	global $wp_query;

	// 	if ($query->is_search)

	// 		if($_REQUEST['s']){

	// 			$wp_query->query_vars['s'] = $_REQUEST['s'];

	// 		}

	print_r($query);

	return $query;

}

// add_filter('pre_get_posts','gt_search_filter');

// add_action( 'pre_get_posts', 'print_query' );

function print_query()

{

	print_r($GLOBALS['wp_query']->query);

	echo "<hr>";

	print_r($GLOBALS['wp_query']->query_vars);

	echo "<hr>";

}

add_filter('rank_math/frontend/description', function ($description) {

	global $wp;

	if (is_page(299) || is_page(303) || is_page(301)) {

		$hientai = $wp->request;

		$hientai = explode('-', $hientai);

		$hientai = $hientai[count($hientai) - 1];

		$hientai = explode('.', $hientai);

		$hientai = $hientai[0];

		$hientai = explode('t', $hientai);

		if (count($hientai) == 2 && $hientai[0] && $hientai[1]) {

			$args = array(

				'post_type'  => 'mota_header',

				'meta_query' => array(

					array(

						'key'     => 'tuyen_duong_from',

						'value'   => $hientai[0],

					),

					array(

						'key'     => 'tuyen_duong_to',

						'value'   => $hientai[1],

					),

				),

			);

			$the_query = new WP_Query($args);

			if ($the_query->have_posts()) {

				while ($the_query->have_posts()) {

					$the_query->the_post();

					$descTmp = get_the_excerpt();

					if ($descTmp) {

						$description = $descTmp;

					}

				}

			}

			wp_reset_postdata();

		}

		return $description;

	}

	return $description;

});

add_filter('rank_math/frontend/title', function ($title) {

	global $wp;

	if (is_page(299) || is_page(303) || is_page(301)) {

		$hientai = $wp->request;

		$hientai = explode('-', $hientai);

		$companyId = 0;

		

		if (is_page(301)) {

			$companyId = $hientai[count($hientai) - 2];

			if (is_numeric($companyId)) {

				$companyId = intval($companyId);

			} else {

				$companyId = 0;

			}

		}



		$flag = false;



		$companyName = '';

		$hientai = $hientai[count($hientai) - 1];

		$hientai = explode('.', $hientai);

		$hientai = $hientai[0];

		$hientai = explode('t', $hientai);

		if (count($hientai) == 2 && $hientai[0] && $hientai[1]) {

			if ($companyId > 0) {

				$args = array(

					'numberposts' => 1,

					'post_type' => 'tuyen-duong-nha-xe',

					'meta_query' => array(

						'relation' => 'AND',

						array(

							'key' => 'tuyenduongnhaxefrom',

							'value' => $hientai[0]

						),

						array(

							'key' => 'tuyenduongnhaxeto',

							'value' => $hientai[1]

						),

						array(

							'key' => 'companyid',

							'value' => $companyId

						)

					)

				);

			} else {

				if (is_page(299)) {

					$args = array(

						'numberposts' => 1,

						'post_type' => 'tuyen-duong',

						'meta_query' => array(

							'relation' => 'AND',

							array(

								'key' => 'tuyenduongfrom',

								'value' => $hientai[0]

							),

							array(

								'key' => 'tuyenduongto',

								'value' => $hientai[1]

							)

						)

					);

				}



				if (is_page(303)) {

					$args = array(

						'numberposts' => 1,

						'post_type' => 'limousine',

						'meta_query' => array(

							'relation' => 'AND',

							array(

								'key' => 'tuyenduongfrom',

								'value' => $hientai[0]

							),

							array(

								'key' => 'tuyenduongto',

								'value' => $hientai[1]

							)

						)

					);

				}

			}



			$baiviet = get_posts($args);

			if (count($baiviet) == 1) {

				return $baiviet[0]->post_title;

			} else {

				if ($hientai[0] == 29) {

					$batdau['name'] = 'Sài Gòn';

				} else {

					$batdau = findObjectById($hientai[0]);

				}

				if ($hientai[1] == 29) {

					$ketthuc['name'] = 'Sài Gòn';

				} else {

					$ketthuc = findObjectById($hientai[1]);

				}

				if (is_page(301)) {

					if ($companyId > 0) {

						$company = findCompanyById($companyId);

						$companyName = $company['name'];

						$flag = true;

					}

				} else {

					$flag = false;

					if (is_page(299)) {

						$isLimousine = false;

					} else {

						$isLimousine = true;

					}

				}



				if (is_array($batdau) && is_array($ketthuc)) {

					return $flag == true ? 'Đặt vé xe ' . $companyName . ' từ ' . $batdau['name'] . ' đi ' . $ketthuc['name'] . ' - Dailyve' : 'Đặt vé xe ' . ($isLimousine == true ? 'limousine ' : '') . 'từ ' . $batdau['name'] . ' đi ' . $ketthuc['name'] . ' - Dailyve';

				}

			}

		}

	}

	return $title;

});



function cammedia_rewrite_url()

{

	$page_id = 299;

	add_rewrite_rule('^ve-xe-khach-tu-.*', 'index.php?page_id=' . $page_id, 'top');

}

// add_action('init', 'cammedia_rewrite_url');



function cammedia_rewrite_url_limousine()

{

	$page_id = 303;

	add_rewrite_rule('^ve-xe-limousine-tu-([^/]+)/?$', 'index.php?page_id=' . $page_id, 'top');

}



// add_action('init', 'cammedia_rewrite_url_limousine');



function cammedia_rewrite_url_limousine_gnd()

{

	$page_id = 303;

	add_rewrite_rule('^ve-xe-giuong-nam-doi-tu-([^/]+)/?$', 'index.php?page_id=' . $page_id, 'top');

}



// add_action('init', 'cammedia_rewrite_url_limousine_gnd');



function cammedia_rewrite_url_limousine_gn()

{

	$page_id = 303;

	add_rewrite_rule('^ve-xe-ghe-ngoi-tu-([^/]+)/?$', 'index.php?page_id=' . $page_id, 'top');

}



// add_action('init', 'cammedia_rewrite_url_limousine_gn');



function cammedia_rewrite_url_nha_xe()

{

	$page_id = 301;

	add_rewrite_rule('^ve-xe-khach-.*', 'index.php?page_id=' . $page_id, 'top');

}

// add_action('init', 'cammedia_rewrite_url_nha_xe');

/*add_filter( 'pre_get_document_title', 'cammedia_filter_wp_title', 9999 );

function cammedia_filter_wp_title($title) {

	global $wp, $dulieuTuyenduong;

	if (is_page(299)) {

		$hientai = $wp->request;

		$hientai = explode('-', $hientai);

		$hientai = $hientai[count($hientai) - 1];

		$hientai = explode('.', $hientai);

		$hientai = $hientai[0];

		$hientai = explode('t', $hientai);

		if (count($hientai)==2 && $hientai[0] && $hientai[1]) {

			$batdau = findObjectById($hientai[0]);

			$ketthuc = findObjectById($hientai[1]);

			if (is_array($batdau) && is_array($ketthuc)) {

				return 'Đặt vé xe từ '.$batdau['name'].' đi '.$ketthuc['name'].' - Dailyve';

			}

		}

		return $title;

	} else {

		return $title;

	}

}*/

function findObjectById($id)
{
	global $dulieuTuyenduong;

	foreach ($dulieuTuyenduong as $element) {
		if ($id == $element['id']) {
			return $element;
		}
	}

	return false;
}



function vietnamese_string_to_slug($title)

{

	$replacement = '-';

	$map = array();

	$quotedReplacement = preg_quote($replacement, '/');

	$default = array(

		'/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|å/' => 'a',

		'/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|ë/' => 'e',

		'/ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ|î/' => 'i',

		'/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|ø/' => 'o',

		'/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|ů|û/' => 'u',

		'/ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ/' => 'y',

		'/đ|Đ/' => 'd',

		'/ç/' => 'c',

		'/ñ/' => 'n',

		'/ä|æ/' => 'ae',

		'/ö/' => 'oe',

		'/ü/' => 'ue',

		'/Ä/' => 'Ae',

		'/Ü/' => 'Ue',

		'/Ö/' => 'Oe',

		'/ß/' => 'ss',

		'/[^\s\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',

		'/\\s+/' => $replacement,

		sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',

	);

	$title = urldecode($title);

	$map = array_merge($map, $default);

	return strtolower(preg_replace(array_keys($map), array_values($map), $title));

}



function cammedia_page_template_redirect()

{

	global $wp;

	if ($wp->request == 'dat-ve-truc-tuyen') {

		$dulieu = parse_url($_SERVER['REQUEST_URI']);

		$truyvan = $dulieu['query'];

		parse_str($truyvan, $output);



		$nameFrom = $output['nameFrom'];

		$batdau = $output['from'];

		$nameFromSlug = vietnamese_string_to_slug($nameFrom);

		if ($nameFromSlug == 'ho-chi-minh') {

			$nameFromSlug = 'sai-gon';

		}



		$nameTo = $output['nameTo'];

		$ketthuc = $output['to'];

		$nameToSlug = vietnamese_string_to_slug($nameTo);

		if ($nameToSlug == 'ho-chi-minh') {

			$nameToSlug = 'sai-gon';

		}



		$ngay = $output['departDate'];

		$ngayVe = isset($output['returnDate']) ? $output['returnDate'] : '';

		$bookingRequestId = isset($output['bookingrequestid']) ? $output['bookingrequestid'] : '';
		$collabguestname = isset($output['collab-guest-name']) ? $output['collab-guest-name'] : '';
		$collabguestphone = isset($output['collab-guest-phone']) ? $output['collab-guest-phone'] : '';



		if(!empty($ngayVe)) {

			$url = '/ve-xe-khach-tu-' . $nameFromSlug . '-di-' . $nameToSlug . '-' . $batdau . 't' . $ketthuc . '.html?date=' . $ngay . '&returnDate=' . $ngayVe;

		} else {

			$url = '/ve-xe-khach-tu-' . $nameFromSlug . '-di-' . $nameToSlug . '-' . $batdau . 't' . $ketthuc . '.html?date=' . $ngay;

		}

		if(!empty($bookingRequestId)) {
			$url .= '&bookingrequestid=' . $bookingRequestId . '&collabguestname=' . $collabguestname . '&collabguestphone=' . $collabguestphone;
		}

		wp_redirect(home_url($url));

		exit();

	}

}

// add_action('template_redirect', 'cammedia_page_template_redirect');





function cammedia_page_template_redirect_nha_xe()

{

	global $wp;

	if ($wp->request == 'dat-ve-theo-nha-xe') {

		$dulieu = parse_url($_SERVER['REQUEST_URI']);

		$truyvan = $dulieu['query'];

		parse_str($truyvan, $output);



		$companyId = $output['companyId'];

		$company = findCompanyById($companyId);

		$companyNameSlug = vietnamese_string_to_slug($company['name']);



		$nameFrom = $output['nameFrom'];

		$batdau = $output['from'];

		$nameFromSlug = vietnamese_string_to_slug($nameFrom);

		if ($nameFromSlug == 'ho-chi-minh') {

			$nameFromSlug = 'sai-gon';

		}



		$nameTo = $output['nameTo'];

		$ketthuc = $output['to'];

		$nameToSlug = vietnamese_string_to_slug($nameTo);

		if ($nameToSlug == 'ho-chi-minh') {

			$nameToSlug = 'sai-gon';

		}



		$ngay = $output['departDate'];



		$url = '/ve-xe-khach-' . $companyNameSlug . '-tu-' . $nameFromSlug . '-di-' . $nameToSlug . '-' . $companyId . '-' . $batdau . 't' . $ketthuc . '.html?date=' . $ngay;



		wp_redirect(home_url($url));

		exit();

	}

}

// add_action('template_redirect', 'cammedia_page_template_redirect_nha_xe');





function dailyve_page_template_redirect()

{

	global $wp;

	if ($wp->request == 'dat-ve-limousine-truc-tuyen') {

		$dulieu = parse_url($_SERVER['REQUEST_URI']);

		$truyvan = $dulieu['query'];

		parse_str($truyvan, $output);



		$nameFrom = $output['nameFrom'];

		$seatType = isset($output['seatType']) ? $output['seatType'] : 0;

		$batdau = $output['from'];

		$nameFromSlug = vietnamese_string_to_slug($nameFrom);

		if ($nameFromSlug == 'ho-chi-minh') {

			$nameFromSlug = 'sai-gon';

		}



		$nameTo = $output['nameTo'];

		$ketthuc = $output['to'];

		$nameToSlug = vietnamese_string_to_slug($nameTo);

		if ($nameToSlug == 'ho-chi-minh') {

			$nameToSlug = 'sai-gon';

		}



		switch ($seatType) {

			case 0:

				$seatText = 'limousine';

				break;

			case 7:

				$seatText = 'giuong-nam-doi';

				break;

			case 1:

				$seatText = 'ghe-ngoi';

				break;

			default:

				$seatText = 'limousine';

				break;

		}



		$ngay = $output['departDate'];

		$ngayVe = $output['returnDate'];



		if(!empty($ngayVe)) {

			$url = '/ve-xe-' . $seatText . '-tu-' . $nameFromSlug . '-di-' . $nameToSlug . '-' . $seatType . '-' . $batdau . 't' . $ketthuc . '.html?date=' . $ngay . '&returnDate=' . $ngayVe;

		} else {

			$url = '/ve-xe-' . $seatText . '-tu-' . $nameFromSlug . '-di-' . $nameToSlug . '-' . $seatType . '-' . $batdau . 't' . $ketthuc . '.html?date=' . $ngay;

		}



		wp_redirect(home_url($url));

		exit();

	}

}

// add_action('template_redirect', 'dailyve_page_template_redirect');



function cammedia_disable_canonical_redirects($redirect_url, $requested_url)

{

	$dulieu = parse_url($requested_url);

	$truyvan = str_replace('/', '', $dulieu['path']);



	if (preg_match('/^ve-xe-khach-tu-.*/', $truyvan)) {

		return $requested_url;

	}



	if (preg_match('/^ve-xe-khach-.*/', $truyvan)) {

		return $requested_url;

	}



	if (preg_match('/^ve-xe-limousine-tu-.*/', $truyvan)) {

		return $requested_url;

	}



	return $redirect_url;

}



// add_action('redirect_canonical', 'cammedia_disable_canonical_redirects', 10, 2);





function cammedia_new_rel_canonical($canonical)

{

	if (is_page(299) || is_page(301) || is_page(303)) {

		global $wp;

		return home_url($wp->request);

	} else {

		return $canonical;

	}

}

// add_filter('rank_math/frontend/canonical', 'cammedia_new_rel_canonical');



include 'datatuyenduong.php';

include 'datacompany.php';

include 'baiviettuyenduong.php';

include 'baiviettuyenduonglimousine.php';

// include 'baiviettuyenduongnhaxe.php';

// include 'mota_header.php';

include 'kiemtrave.php';

// add_action('after_setup_theme', function() {

// 	$file = get_stylesheet_directory() . '/cammedia/kiemtrave.php';

// 	if(!file_exists($file)) {

// 		include_once ABSPATH . 'wp-admin/includes/file.php';

// 		\WP_Filesystem();

// 		global $wp_filesystem;

// // 		wp_mkdir_p(get_stylesheet_directory() . '/cammedia');

// 		$wp_filesystem->put_contents($file, '', FS_CHMOD_FILE);

// 	}

// });

/*************** DALYVE ***************/

