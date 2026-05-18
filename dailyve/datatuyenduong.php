<?php
function timTuyenDuongID($id)
{
	global $dulieuTuyenduong;
	foreach ($dulieuTuyenduong as $item) {
		if ($item['id'] == $id) {
			return $item['name'];
		}
	}
	return '';
}

$dulieuTuyenduong = [
	[
		"id" => "28299",
		"area_id" => 65,
		"name" => "Savannakhet - Lào",
		"name_filter" => "savannakhet - lào",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "savannakhet-lào",
		"index" => 1
	],
	[
		"id" => "28291",
		"area_id" => 65,
		"name" => "Pakse - Lào",
		"name_filter" => "pakse - lào",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "pakse-lào",
		"index" => 1
	],
	[
		"id" => "28300",
		"area_id" => 64,
		"name" => "Vientiane - Lào",
		"name_filter" => "vientiane - lào",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "vientiane-lào",
		"index" => 1
	],
	[
		"id" => "528",
		"area_id" => 63,
		"name" => "Vinh",
		"name_filter" => "vinh",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "vinh",
		"index" => 1
	],
	[
		"id" => "24",
		"area_id" => 24,
		"name" => "Hà Nội",
		"name_filter" => "hà nội",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "hànội",
		"index" => 1
	],
	[
		"id" => "27",
		"area_id" => 27,
		"name" => "Hải Phòng",
		"name_filter" => "hải phòng",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "hảiphòng",
		"index" => 1
	],
	[
		"id" => "3",
		"area_id" => 3,
		"name" => "Bắc Giang",
		"name_filter" => "bắc giang",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "bắcgiang",
		"index" => 1
	],
	[
		"id" => "4",
		"area_id" => 4,
		"name" => "Bắc Kạn",
		"name_filter" => "bắc kạn",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "bắckạn",
		"index" => 1
	],
	[
		"id" => "6",
		"area_id" => 6,
		"name" => "Bắc Ninh",
		"name_filter" => "bắc ninh",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "bắcninh",
		"index" => 1
	],
	[
		"id" => "14",
		"area_id" => 14,
		"name" => "Cao Bằng",
		"name_filter" => "cao bằng",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "caobằng",
		"index" => 1
	],
	[
		"id" => "18",
		"area_id" => 18,
		"name" => "Điện Biên",
		"name_filter" => "điện biên",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "điệnbiên",
		"index" => 1
	],
	[
		"id" => "22",
		"area_id" => 22,
		"name" => "Hà Giang",
		"name_filter" => "hà giang",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "hàgiang",
		"index" => 1
	],
	[
		"id" => "23",
		"area_id" => 23,
		"name" => "Hà Nam",
		"name_filter" => "hà nam",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "hànam",
		"index" => 1
	],
	[
		"id" => "26",
		"area_id" => 26,
		"name" => "Hải Dương",
		"name_filter" => "hải dương",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "hảidương",
		"index" => 1
	],
	[
		"id" => "30",
		"area_id" => 30,
		"name" => "Hòa Bình",
		"name_filter" => "hòa bình",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "hòabình",
		"index" => 1
	],
	[
		"id" => "31",
		"area_id" => 31,
		"name" => "Hưng Yên",
		"name_filter" => "hưng yên",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "hưngyên",
		"index" => 1
	],
	[
		"id" => "37",
		"area_id" => 37,
		"name" => "Lạng Sơn",
		"name_filter" => "lạng sơn",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "lạngsơn",
		"index" => 1
	],
	[
		"id" => "38",
		"area_id" => 38,
		"name" => "Lào Cai",
		"name_filter" => "lào cai",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "làocai",
		"index" => 1
	],
	[
		"id" => "40",
		"area_id" => 40,
		"name" => "Nam Định",
		"name_filter" => "nam định",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "namđịnh",
		"index" => 1
	],
	[
		"id" => "42",
		"area_id" => 42,
		"name" => "Ninh Bình",
		"name_filter" => "ninh bình",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "ninhbình",
		"index" => 1
	],
	[
		"id" => "44",
		"area_id" => 44,
		"name" => "Phú Thọ",
		"name_filter" => "phú thọ",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "phúthọ",
		"index" => 1
	],
	[
		"id" => "54",
		"area_id" => 54,
		"name" => "Thái Bình",
		"name_filter" => "thái bình",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "tháibình",
		"index" => 1
	],
	[
		"id" => "60",
		"area_id" => 60,
		"name" => "Tuyên Quang",
		"name_filter" => "tuyên quang",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "tuyênquang",
		"index" => 1
	],
	[
		"id" => "62",
		"area_id" => 62,
		"name" => "Vĩnh Phúc",
		"name_filter" => "vĩnh phúc",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "vĩnhphúc",
		"index" => 1
	],
	[
		"id" => "63",
		"area_id" => 63,
		"name" => "Yên Bái",
		"name_filter" => "yên bái",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "yênbái",
		"index" => 1
	],
	[
		"id" => "15",
		"area_id" => 15,
		"name" => "Đà Nẵng",
		"name_filter" => "đà nẵng",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "đànẵng",
		"index" => 1
	],
	[
		"id" => "8",
		"area_id" => 8,
		"name" => "Bình Định",
		"name_filter" => "bình định",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "11",
		"area_id" => 11,
		"name" => "Bình Thuận",
		"name_filter" => "bình thuận",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "bìnhthuận",
		"index" => 1
	],
	[
		"id" => "16",
		"area_id" => 16,
		"name" => "Đắk Lắk",
		"name_filter" => "đắk lắk",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "đắklắk",
		"index" => 1
	],
	[
		"id" => "17",
		"area_id" => 17,
		"name" => "Đăk Nông",
		"name_filter" => "đăk nông",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "đăknông",
		"index" => 1
	],
	[
		"id" => "21",
		"area_id" => 21,
		"name" => "Gia Lai",
		"name_filter" => "gia lai",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "gialai",
		"index" => 1
	],
	[
		"id" => "25",
		"area_id" => 25,
		"name" => "Hà Tĩnh",
		"name_filter" => "hà tĩnh",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "hàtĩnh",
		"index" => 1
	],
	[
		"id" => "32",
		"area_id" => 32,
		"name" => "Khánh Hòa",
		"name_filter" => "khánh hòa",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "khánhhòa",
		"index" => 1
	],
	[
		"id" => "34",
		"area_id" => 34,
		"name" => "Kon Tum",
		"name_filter" => "kon tum",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "kontum",
		"index" => 1
	],
	[
		"id" => "35",
		"area_id" => 35,
		"name" => "Lai Châu",
		"name_filter" => "lai châu",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "laichâu",
		"index" => 1
	],
	[
		"id" => "36",
		"area_id" => 36,
		"name" => "Lâm Đồng",
		"name_filter" => "lâm đồng",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "lâmđồng",
		"index" => 1
	],
	[
		"id" => "41",
		"area_id" => 41,
		"name" => "Nghệ An",
		"name_filter" => "nghệ an",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "nghệan",
		"index" => 1
	],
	[
		"id" => "43",
		"area_id" => 43,
		"name" => "Ninh Thuận",
		"name_filter" => "ninh thuận",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "ninhthuận",
		"index" => 1
	],
	[
		"id" => "45",
		"area_id" => 45,
		"name" => "Phú Yên",
		"name_filter" => "phú yên",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "phúyên",
		"index" => 1
	],
	[
		"id" => "46",
		"area_id" => 46,
		"name" => "Quảng Bình",
		"name_filter" => "quảng bình",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "quảngbình",
		"index" => 1
	],
	[
		"id" => "47",
		"area_id" => 47,
		"name" => "Quảng Nam",
		"name_filter" => "quảng nam",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "quảngnam",
		"index" => 1
	],
	[
		"id" => "48",
		"area_id" => 48,
		"name" => "Quảng Ngãi",
		"name_filter" => "quảng ngãi",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "quảngngãi",
		"index" => 1
	],
	[
		"id" => "49",
		"area_id" => 49,
		"name" => "Quảng Ninh",
		"name_filter" => "quảng ninh",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "quảngninh",
		"index" => 1
	],
	[
		"id" => "50",
		"area_id" => 50,
		"name" => "Quảng Trị",
		"name_filter" => "quảng trị",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "quảngtrị",
		"index" => 1
	],
	[
		"id" => "52",
		"area_id" => 52,
		"name" => "Sơn La",
		"name_filter" => "sơn la",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "sơnla",
		"index" => 1
	],
	[
		"id" => "55",
		"area_id" => 55,
		"name" => "Thái Nguyên",
		"name_filter" => "thái nguyên",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "tháinguyên",
		"index" => 1
	],
	[
		"id" => "56",
		"area_id" => 56,
		"name" => "Thanh Hóa",
		"name_filter" => "thanh hóa",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "thanhhóa",
		"index" => 1
	],
	[
		"id" => "57",
		"area_id" => 57,
		"name" => "Thừa Thiên-Huế",
		"name_filter" => "thừa thiên-huế",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "thừathiên-huế",
		"index" => 1
	],
	[
		"id" => "29",
		"area_id" => 29,
		"name" => "Hồ Chí Minh",
		"name_filter" => "hồ chí minh",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "hồchíminh",
		"index" => 1
	],
	[
		"id" => "29",
		"area_id" => 29,
		"name" => "Sài Gòn",
		"name_filter" => "sài gòn",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "sàigòn",
		"index" => 1
	],
	[
		"id" => "13",
		"area_id" => 13,
		"name" => "Cần Thơ",
		"name_filter" => "cần thơ",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "cầnthơ",
		"index" => 1
	],
	[
		"id" => "1",
		"area_id" => 1,
		"name" => "An Giang",
		"name_filter" => "an giang",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "angiang",
		"index" => 1
	],
	[
		"id" => "2",
		"area_id" => 2,
		"name" => "Bà Rịa-Vũng Tàu",
		"name_filter" => "bà rịa - vũng tàu",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "bàrịa-vũngtàu",
		"index" => 1
	],
	[
		"id" => "5",
		"area_id" => 5,
		"name" => "Bạc Liêu",
		"name_filter" => "bạc liêu",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "bạcliêu",
		"index" => 1
	],
	[
		"id" => "7",
		"area_id" => 7,
		"name" => "Bến Tre",
		"name_filter" => "bến tre",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "bếntre",
		"index" => 1
	],
	[
		"id" => "9",
		"area_id" => 9,
		"name" => "Bình Dương",
		"name_filter" => "bình dương",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "bìnhdương",
		"index" => 1
	],
	[
		"id" => "10",
		"area_id" => 10,
		"name" => "Bình Phước",
		"name_filter" => "bình phước",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "bìnhphước",
		"index" => 1
	],
	[
		"id" => "12",
		"area_id" => 12,
		"name" => "Cà Mau",
		"name_filter" => "cà mau",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "càmau",
		"index" => 1
	],
	[
		"id" => "19",
		"area_id" => 19,
		"name" => "Đồng Nai",
		"name_filter" => "đồng nai",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "đồngnai",
		"index" => 1
	],
	[
		"id" => "20",
		"area_id" => 20,
		"name" => "Đồng Tháp",
		"name_filter" => "đồng tháp",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "đồngtháp",
		"index" => 1
	],
	[
		"id" => "28",
		"area_id" => 28,
		"name" => "Hậu Giang",
		"name_filter" => "hậu giang",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "hậugiang",
		"index" => 1
	],
	[
		"id" => "33",
		"area_id" => 33,
		"name" => "Kiên Giang",
		"name_filter" => "kiên giang",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "kiêngiang",
		"index" => 1
	],
	[
		"id" => "39",
		"area_id" => 39,
		"name" => "Long An",
		"name_filter" => "long an",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "longan",
		"index" => 1
	],
	[
		"id" => "51",
		"area_id" => 51,
		"name" => "Sóc Trăng",
		"name_filter" => "sóc trăng",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "sóctrăng",
		"index" => 1
	],
	[
		"id" => "53",
		"area_id" => 53,
		"name" => "Tây Ninh",
		"name_filter" => "tây ninh",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "tâyninh",
		"index" => 1
	],
	[
		"id" => "58",
		"area_id" => 58,
		"name" => "Tiền Giang",
		"name_filter" => "tiền giang",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "tiềngiang",
		"index" => 1
	],
	[
		"id" => "59",
		"area_id" => 59,
		"name" => "Trà Vinh",
		"name_filter" => "trà vinh",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "tràvinh",
		"index" => 1
	],
	[
		"id" => "61",
		"area_id" => 61,
		"name" => "Vĩnh Long",
		"name_filter" => "vĩnh long",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "vĩnhlong",
		"index" => 1
	],
	[
		"id" => "49110",
		"area_id" => 49110,
		"name" => "Kampot - Campuchia",
		"name_filter" => "campuchia - kampot",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "campuchia-kampot",
		"index" => 1
	],
	[
		"id" => "49110",
		"area_id" => 49110,
		"name" => "Kampot - Campuchia",
		"name_filter" => "kampot - campuchia",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "kampot-campuchia",
		"index" => 1
	],
	[
		"id" => "28349",
		"area_id" => 65,
		"name" => " Thakhek - Khammuane - Lào",
		"name_filter" => " thakhek - khammuane - lào",
		"category" => "Quận - Huyện",
		"name_nospace" => "thakhek-khammuane-lào",
		"index" => 1
	],
	[
		"id" => "49118",
		"area_id" => 1065,
		"name" => "Phnôm Pênh - Campuchia",
		"name_filter" => "campuchia - phnôm pênh",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "campuchia-phnômpênh",
		"index" => 1
	],
	[
		"id" => "49118",
		"area_id" => 1065,
		"name" => "Phnôm Pênh - Campuchia",
		"name_filter" => "phnôm pênh - campuchia",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "phnômpênh-campuchia",
		"index" => 1
	],
	[
		"id" => "49124",
		"area_id" => 49587,
		"name" => "Sihanoukville - Campuchia",
		"name_filter" => "sihanoukville - campuchia",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "sihanoukville-campuchia",
		"index" => 1
	],
	[
		"id" => "49144",
		"area_id" => 49144,
		"name" => "Battambang - Campuchia",
		"name_filter" => "battambang - campuchia",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "battambang-campuchia",
		"index" => 1
	],
	[
		"id" => "49123",
		"area_id" => 49123,
		"name" => "Siem Reap - Campuchia",
		"name_filter" => "siem reap - campuchia",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "siemreap-campuchia",
		"index" => 1
	],
	[
		"id" => 765,
		"area_id" => 29,
		"name" => "Bến xe Miền Đông",
		"name_filter" => "bến xe miền đông - hồ chí minh",
		"category" => "Bến xe",
		"name_nospace" => "bếnxemiềnđông-hồchíminh",
		"index" => 1
	],
	[
		"id" => 768,
		"area_id" => 29,
		"name" => "Bến xe Miền Tây",
		"name_filter" => "bến xe miền tây - hồ chí minh",
		"category" => "Bến xe",
		"name_nospace" => "bếnxemiềntây-hồchíminh",
		"index" => 1
	],
	[
		"id" => 818,
		"area_id" => 29,
		"name" => "Bến xe An Sương",
		"name_filter" => "bến xe an sương - hồ chí minh",
		"category" => "Bến xe",
		"name_nospace" => "bếnxeansương-hồchíminh",
		"index" => 1
	],
	[
		"id" => 786,
		"area_id" => 24,
		"name" => "Bến xe Mỹ Đình",
		"name_filter" => "bến xe mỹ đình - hà nội",
		"category" => "Bến xe",
		"name_nospace" => "bếnxemỹđình-hànội",
		"index" => 1
	],
	[
		"id" => 930,
		"area_id" => 24,
		"name" => "Bến xe Gia Lâm",
		"name_filter" => "bến xe gia lâm - hà nội",
		"category" => "Bến xe",
		"name_nospace" => "bếnxegialâm-hànội",
		"index" => 1
	],
	[
		"id" => 785,
		"area_id" => 24,
		"name" => "Bến xe Giáp Bát",
		"name_filter" => "bến xe giáp bát - hà nội",
		"category" => "Bến xe",
		"name_nospace" => "bếnxegiápbát-hànội",
		"index" => 1
	],
	[
		"id" => 803,
		"area_id" => 24,
		"name" => "Bến xe Nước Ngầm",
		"name_filter" => "bến xe nước ngầm - hà nội",
		"category" => "Bến xe",
		"name_nospace" => "bếnxenướcngầm-hànội",
		"index" => 1
	],
	[
		"id" => 811,
		"area_id" => 24,
		"name" => "Bến xe Lương Yên",
		"name_filter" => "bến xe lương yên - hà nội",
		"category" => "Bến xe",
		"name_nospace" => "bếnxelươngyên-hànội",
		"index" => 1
	],
	[
		"id" => 966,
		"area_id" => 24,
		"name" => "Bến xe Yên Nghĩa",
		"name_filter" => "bến xe yên nghĩa - hà nội",
		"category" => "Bến xe",
		"name_nospace" => "bếnxeyênnghĩa-hànội",
		"index" => 1
	],
	[
		"id" => 767,
		"area_id" => 15,
		"name" => "Bến xe Trung tâm Đà Nẵng",
		"name_filter" => "bến xe trung tâm - đà nẵng",
		"category" => "Bến xe",
		"name_nospace" => "bếnxetrungtâm-đànẵng",
		"index" => 1
	],
	[
		"id" => 989,
		"area_id" => 15,
		"name" => "Bến xe Khách phía nam Đà Nẵng",
		"name_filter" => "bến xe khách phía nam - đà nẵng",
		"category" => "Bến xe",
		"name_nospace" => "bếnxekháchphíanam-đànẵng",
		"index" => 1
	],
	[
		"id" => 968,
		"area_id" => 15,
		"name" => "Bến xe Khách Đà Nẵng",
		"name_filter" => "bến xe khách - đà nẵng",
		"category" => "Bến xe",
		"name_nospace" => "bếnxekhách-đànẵng",
		"index" => 1
	],
	[
		"id" => 876,
		"area_id" => 13,
		"name" => "Bến xe Cần Thơ",
		"name_filter" => "bến xe - cần thơ",
		"category" => "Bến xe",
		"name_nospace" => "bếnxe-cầnthơ",
		"index" => 1
	],
	[
		"id" => 1243,
		"area_id" => 13,
		"name" => "Bến xe Thạch An",
		"name_filter" => "bến xe thạch an - cần thơ",
		"category" => "Bến xe",
		"name_nospace" => "bếnxethạchan-cầnthơ",
		"index" => 1
	],
	[
		"id" => 1242,
		"area_id" => 13,
		"name" => "Bến xe Thốt Nốt",
		"name_filter" => "bến xe thốt nốt - cần thơ",
		"category" => "Bến xe",
		"name_nospace" => "bếnxethốtnốt-cầnthơ",
		"index" => 1
	],
	[
		"id" => 1235,
		"area_id" => 13,
		"name" => "Bến xe Bình Thủy",
		"name_filter" => "bến xe bình thủy - cần thơ",
		"category" => "Bến xe",
		"name_nospace" => "bếnxebìnhthủy-cầnthơ",
		"index" => 1
	],
	[
		"id" => 1238,
		"area_id" => 13,
		"name" => "Bến xe Phong Điền",
		"name_filter" => "bến xe phong điền - cần thơ",
		"category" => "Bến xe",
		"name_nospace" => "bếnxephongđiền-cầnthơ",
		"index" => 1
	],
	[
		"id" => 1236,
		"area_id" => 13,
		"name" => "Bến xe Khu đô thị nam Cần Thơ",
		"name_filter" => "bến xe khu đô thị nam - cần thơ",
		"category" => "Bến xe",
		"name_nospace" => "bếnxekhuđôthịnam-cầnthơ",
		"index" => 1
	],
	[
		"id" => 1044,
		"area_id" => 13,
		"name" => "Bến xe Cờ Đỏ",
		"name_filter" => "bến xe cờ đỏ - cần thơ",
		"category" => "Bến xe",
		"name_nospace" => "bếnxecờđỏ-cầnthơ",
		"index" => 1
	],
	[
		"id" => 779,
		"area_id" => 13,
		"name" => "Bến xe 91B Cần Thơ",
		"name_filter" => "bến xe 91b - cần thơ",
		"category" => "Bến xe",
		"name_nospace" => "bếnxe91b-cầnthơ",
		"index" => 1
	],
	[
		"id" => 984,
		"area_id" => 13,
		"name" => "Bến xe Tàu Cần Thơ",
		"name_filter" => "bến xe tàu - cần thơ",
		"category" => "Bến xe",
		"name_nospace" => "bếnxetàu-cầnthơ",
		"index" => 1
	],
	[
		"id" => 782,
		"area_id" => 13,
		"name" => "Bến xe Hùng Vương Cần Thơ",
		"name_filter" => "bến xe hùng vương - cần thơ",
		"category" => "Bến xe",
		"name_nospace" => "bếnxehùngvương-cầnthơ",
		"index" => 1
	],
	[
		"id" => 887,
		"area_id" => 13,
		"name" => "Bến xe Ô Môn",
		"name_filter" => "bến xe ô môn - cần thơ",
		"category" => "Bến xe",
		"name_nospace" => "bếnxeômôn-cầnthơ",
		"index" => 1
	],
	[
		"id" => 370,
		"area_id" => 29,
		"name" => "Cần Giờ - Hồ Chí Minh",
		"name_filter" => "cần giờ - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "cầngiờ-hồchíminh",
		"index" => 1
	],
	[
		"id" => "345",
		"area_id" => 27,
		"name" => "An Dương - Hải Phòng",
		"name_filter" => "an dương - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "andương-hảiphòng",
		"index" => 1
	],
	[
		"id" => "346",
		"area_id" => 27,
		"name" => "An Lão - Hải Phòng",
		"name_filter" => "an lão - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "anlão-hảiphòng",
		"index" => 1
	],
	[
		"id" => "347",
		"area_id" => 27,
		"name" => "Bạch Long Vĩ - Hải Phòng",
		"name_filter" => "bạch long vĩ - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "bạchlongvĩ-hảiphòng",
		"index" => 1
	],
	[
		"id" => "348",
		"area_id" => 27,
		"name" => "Cát Hải - Hải Phòng",
		"name_filter" => "cát hải - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "cáthải-hảiphòng",
		"index" => 1
	],
	[
		"id" => "349",
		"area_id" => 27,
		"name" => "Dương Kinh - Hải Phòng",
		"name_filter" => "dương kinh - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "dươngkinh-hảiphòng",
		"index" => 1
	],
	[
		"id" => "350",
		"area_id" => 27,
		"name" => "Đồ Sơn - Hải Phòng",
		"name_filter" => "đồ sơn - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "đồsơn-hảiphòng",
		"index" => 1
	],
	[
		"id" => "351",
		"area_id" => 27,
		"name" => "Hải An - Hải Phòng",
		"name_filter" => "hải an - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "hảian-hảiphòng",
		"index" => 1
	],
	[
		"id" => "352",
		"area_id" => 27,
		"name" => "Hồng Bàng - Hải Phòng",
		"name_filter" => "hồng bàng - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "hồngbàng-hảiphòng",
		"index" => 1
	],
	[
		"id" => "353",
		"area_id" => 27,
		"name" => "Kiến An - Hải Phòng",
		"name_filter" => "kiến an - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "kiếnan-hảiphòng",
		"index" => 1
	],
	[
		"id" => "354",
		"area_id" => 27,
		"name" => "Kiến Thụy - Hải Phòng",
		"name_filter" => "kiến thụy - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "kiếnthụy-hảiphòng",
		"index" => 1
	],
	[
		"id" => "355",
		"area_id" => 27,
		"name" => "Lê Chân - Hải Phòng",
		"name_filter" => "lê chân - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "lêchân-hảiphòng",
		"index" => 1
	],
	[
		"id" => "356",
		"area_id" => 27,
		"name" => "Ngô Quyền - Hải Phòng",
		"name_filter" => "ngô quyền - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "ngôquyền-hảiphòng",
		"index" => 1
	],
	[
		"id" => "357",
		"area_id" => 27,
		"name" => "Thuỷ Nguyên - Hải Phòng",
		"name_filter" => "thuỷ nguyên - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "thuỷnguyên-hảiphòng",
		"index" => 1
	],
	[
		"id" => "358",
		"area_id" => 27,
		"name" => "Tiên Lãng - Hải Phòng",
		"name_filter" => "tiên lãng - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "tiênlãng-hảiphòng",
		"index" => 1
	],
	[
		"id" => "359",
		"area_id" => 27,
		"name" => "Vĩnh Bảo - Hải Phòng",
		"name_filter" => "vĩnh bảo - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhbảo-hảiphòng",
		"index" => 1
	],
	[
		"id" => "64",
		"area_id" => 1,
		"name" => "An Phú - An Giang",
		"name_filter" => "an phú - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "anphú-angiang",
		"index" => 1
	],
	[
		"id" => "65",
		"area_id" => 1,
		"name" => "Châu Đốc - An Giang",
		"name_filter" => "châu đốc - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuđốc-angiang",
		"index" => 1
	],
	[
		"id" => "66",
		"area_id" => 1,
		"name" => "Châu Phú - An Giang",
		"name_filter" => "châu phú - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuphú-angiang",
		"index" => 1
	],
	[
		"id" => "67",
		"area_id" => 1,
		"name" => "Châu Thành - An Giang",
		"name_filter" => "châu thành - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuthành-angiang",
		"index" => 1
	],
	[
		"id" => "68",
		"area_id" => 1,
		"name" => "Chợ Mới - An Giang",
		"name_filter" => "chợ mới - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "chợmới-angiang",
		"index" => 1
	],
	[
		"id" => "69",
		"area_id" => 1,
		"name" => "Long Xuyên - An Giang",
		"name_filter" => "long xuyên - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "longxuyên-angiang",
		"index" => 1
	],
	[
		"id" => "70",
		"area_id" => 1,
		"name" => "Phú Tân - An Giang",
		"name_filter" => "phú tân - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "phútân-angiang",
		"index" => 1
	],
	[
		"id" => "71",
		"area_id" => 1,
		"name" => "Tân Châu - An Giang",
		"name_filter" => "tân châu - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânchâu-angiang",
		"index" => 1
	],
	[
		"id" => "72",
		"area_id" => 1,
		"name" => "Thoại Sơn - An Giang",
		"name_filter" => "thoại sơn - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "thoạisơn-angiang",
		"index" => 1
	],
	[
		"id" => "72",
		"area_id" => 1,
		"name" => "Núi Sập - Thoại Sơn - An Giang",
		"name_filter" => "núi sập - thoại sơn - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "núisập-thoạisơn-angiang",
		"index" => 1
	],
	[
		"id" => "73",
		"area_id" => 1,
		"name" => "Tịnh Biên - An Giang",
		"name_filter" => "tịnh biên - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "tịnhbiên-angiang",
		"index" => 1
	],
	[
		"id" => "73",
		"area_id" => 1,
		"name" => "Chi Lăng - Tịnh Biên - An Giang",
		"name_filter" => "chi lăng - tịnh biên - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "chilăng-tịnhbiên-angiang",
		"index" => 1
	],
	[
		"id" => "73",
		"area_id" => 1,
		"name" => "Nhà Bàng - Tịnh Biên - An Giang",
		"name_filter" => "nhà bàng - tịnh biên - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "nhàbàng-tịnhbiên-angiang",
		"index" => 1
	],
	[
		"id" => "74",
		"area_id" => 1,
		"name" => "Tri Tôn - An Giang",
		"name_filter" => "tri tôn - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "tritôn-angiang",
		"index" => 1
	],
	[
		"id" => "75",
		"area_id" => 2,
		"name" => "Tân Thành - Bà Rịa-Vũng Tàu",
		"name_filter" => "tân thành - bà rịa-vũng tàu",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânthành-bàrịa-vũngtàu",
		"index" => 1
	],
	[
		"id" => "76",
		"area_id" => 2,
		"name" => "Vũng Tàu - Bà Rịa-Vũng Tàu",
		"name_filter" => "vũng tàu - bà rịa-vũng tàu",
		"category" => "Quận - Huyện",
		"name_nospace" => "vũngtàu-bàrịa-vũngtàu",
		"index" => 1
	],
	[
		"id" => "77",
		"area_id" => 2,
		"name" => "Xuyên Mộc - Bà Rịa-Vũng Tàu",
		"name_filter" => "xuyên mộc - bà rịa-vũng tàu",
		"category" => "Quận - Huyện",
		"name_nospace" => "xuyênmộc-bàrịa-vũngtàu",
		"index" => 1
	],
	[
		"id" => "78",
		"area_id" => 2,
		"name" => "Bà Rịa - Bà Rịa-Vũng Tàu",
		"name_filter" => "bà rịa - bà rịa-vũng tàu",
		"category" => "Quận - Huyện",
		"name_nospace" => "bàrịa-bàrịa-vũngtàu",
		"index" => 1
	],
	[
		"id" => "79",
		"area_id" => 2,
		"name" => "Châu Đức - Bà Rịa-Vũng Tàu",
		"name_filter" => "châu đức - bà rịa-vũng tàu",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuđức-bàrịa-vũngtàu",
		"index" => 1
	],
	[
		"id" => "80",
		"area_id" => 2,
		"name" => "Côn Đảo - Bà Rịa-Vũng Tàu",
		"name_filter" => "côn đảo - bà rịa-vũng tàu",
		"category" => "Quận - Huyện",
		"name_nospace" => "cônđảo-bàrịa-vũngtàu",
		"index" => 1
	],
	[
		"id" => "81",
		"area_id" => 2,
		"name" => "Đất Đỏ - Bà Rịa-Vũng Tàu",
		"name_filter" => "đất đỏ - bà rịa-vũng tàu",
		"category" => "Quận - Huyện",
		"name_nospace" => "đấtđỏ-bàrịa-vũngtàu",
		"index" => 1
	],
	[
		"id" => "82",
		"area_id" => 2,
		"name" => "Long Điền - Bà Rịa-Vũng Tàu",
		"name_filter" => "long điền - bà rịa-vũng tàu",
		"category" => "Quận - Huyện",
		"name_nospace" => "longđiền-bàrịa-vũngtàu",
		"index" => 1
	],
	[
		"id" => "101",
		"area_id" => 5,
		"name" => "Bạc Liêu - Bạc Liêu",
		"name_filter" => "bạc liêu - bạc liêu",
		"category" => "Quận - Huyện",
		"name_nospace" => "bạcliêu-bạcliêu",
		"index" => 1
	],
	[
		"id" => "102",
		"area_id" => 5,
		"name" => "Đông Hải - Bạc Liêu",
		"name_filter" => "đông hải - bạc liêu",
		"category" => "Quận - Huyện",
		"name_nospace" => "đônghải-bạcliêu",
		"index" => 1
	],
	[
		"id" => "103",
		"area_id" => 5,
		"name" => "Giá Rai - Bạc Liêu",
		"name_filter" => "giá rai - bạc liêu",
		"category" => "Quận - Huyện",
		"name_nospace" => "giárai-bạcliêu",
		"index" => 1
	],
	[
		"id" => "104",
		"area_id" => 5,
		"name" => "Hoà Bình - Bạc Liêu",
		"name_filter" => "hoà bình - bạc liêu",
		"category" => "Quận - Huyện",
		"name_nospace" => "hoàbình-bạcliêu",
		"index" => 1
	],
	[
		"id" => "105",
		"area_id" => 5,
		"name" => "Hồng Dân - Bạc Liêu",
		"name_filter" => "hồng dân - bạc liêu",
		"category" => "Quận - Huyện",
		"name_nospace" => "hồngdân-bạcliêu",
		"index" => 1
	],
	[
		"id" => "106",
		"area_id" => 5,
		"name" => "Phước Long - Bạc Liêu",
		"name_filter" => "phước long - bạc liêu",
		"category" => "Quận - Huyện",
		"name_nospace" => "phướclong-bạcliêu",
		"index" => 1
	],
	[
		"id" => "107",
		"area_id" => 5,
		"name" => "Vĩnh Lợi - Bạc Liêu",
		"name_filter" => "vĩnh lợi - bạc liêu",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhlợi-bạcliêu",
		"index" => 1
	],
	[
		"id" => "93",
		"area_id" => 4,
		"name" => "Ba Bể - Bắc Kạn",
		"name_filter" => "ba bể - bắc kạn",
		"category" => "Quận - Huyện",
		"name_nospace" => "babể-bắckạn",
		"index" => 1
	],
	[
		"id" => "94",
		"area_id" => 4,
		"name" => "Bạch Thông - Bắc Kạn",
		"name_filter" => "bạch thông - bắc kạn",
		"category" => "Quận - Huyện",
		"name_nospace" => "bạchthông-bắckạn",
		"index" => 1
	],
	[
		"id" => "95",
		"area_id" => 4,
		"name" => "Bắc Kạn - Bắc Kạn",
		"name_filter" => "bắc kạn - bắc kạn",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắckạn-bắckạn",
		"index" => 1
	],
	[
		"id" => "96",
		"area_id" => 4,
		"name" => "Chợ Đồn - Bắc Kạn",
		"name_filter" => "chợ đồn - bắc kạn",
		"category" => "Quận - Huyện",
		"name_nospace" => "chợđồn-bắckạn",
		"index" => 1
	],
	[
		"id" => "97",
		"area_id" => 4,
		"name" => "Chợ Mới - Bắc Kạn",
		"name_filter" => "chợ mới - bắc kạn",
		"category" => "Quận - Huyện",
		"name_nospace" => "chợmới-bắckạn",
		"index" => 1
	],
	[
		"id" => "98",
		"area_id" => 4,
		"name" => "Na Rì - Bắc Kạn",
		"name_filter" => "na rì - bắc kạn",
		"category" => "Quận - Huyện",
		"name_nospace" => "narì-bắckạn",
		"index" => 1
	],
	[
		"id" => "99",
		"area_id" => 4,
		"name" => "Ngân Sơn - Bắc Kạn",
		"name_filter" => "ngân sơn - bắc kạn",
		"category" => "Quận - Huyện",
		"name_nospace" => "ngânsơn-bắckạn",
		"index" => 1
	],
	[
		"id" => "100",
		"area_id" => 4,
		"name" => "Pác Nặm - Bắc Kạn",
		"name_filter" => "pác nặm - bắc kạn",
		"category" => "Quận - Huyện",
		"name_nospace" => "pácnặm-bắckạn",
		"index" => 1
	],
	[
		"id" => "83",
		"area_id" => 3,
		"name" => "Bắc Giang - Bắc Giang",
		"name_filter" => "bắc giang - bắc giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắcgiang-bắcgiang",
		"index" => 1
	],
	[
		"id" => "84",
		"area_id" => 3,
		"name" => "Hiệp Hòa - Bắc Giang",
		"name_filter" => "hiệp hòa - bắc giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "hiệphòa-bắcgiang",
		"index" => 1
	],
	[
		"id" => "85",
		"area_id" => 3,
		"name" => "Lạng Giang - Bắc Giang",
		"name_filter" => "lạng giang - bắc giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "lạnggiang-bắcgiang",
		"index" => 1
	],
	[
		"id" => "86",
		"area_id" => 3,
		"name" => "Lục Nam - Bắc Giang",
		"name_filter" => "lục nam - bắc giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "lụcnam-bắcgiang",
		"index" => 1
	],
	[
		"id" => "87",
		"area_id" => 3,
		"name" => "Lục Ngạn - Bắc Giang",
		"name_filter" => "lục ngạn - bắc giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "lụcngạn-bắcgiang",
		"index" => 1
	],
	[
		"id" => "88",
		"area_id" => 3,
		"name" => "Sơn Động - Bắc Giang",
		"name_filter" => "sơn động - bắc giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "sơnđộng-bắcgiang",
		"index" => 1
	],
	[
		"id" => "89",
		"area_id" => 3,
		"name" => "Tân Yên - Bắc Giang",
		"name_filter" => "tân yên - bắc giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânyên-bắcgiang",
		"index" => 1
	],
	[
		"id" => "90",
		"area_id" => 3,
		"name" => "Việt Yên - Bắc Giang",
		"name_filter" => "việt yên - bắc giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "việtyên-bắcgiang",
		"index" => 1
	],
	[
		"id" => "91",
		"area_id" => 3,
		"name" => "Yên Dũng - Bắc Giang",
		"name_filter" => "yên dũng - bắc giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "yêndũng-bắcgiang",
		"index" => 1
	],
	[
		"id" => "92",
		"area_id" => 3,
		"name" => "Yên Thế - Bắc Giang",
		"name_filter" => "yên thế - bắc giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênthế-bắcgiang",
		"index" => 1
	],
	[
		"id" => "108",
		"area_id" => 6,
		"name" => "Bắc Ninh - Bắc Ninh",
		"name_filter" => "bắc ninh - bắc ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắcninh-bắcninh",
		"index" => 1
	],
	[
		"id" => "109",
		"area_id" => 6,
		"name" => "Gia Bình - Bắc Ninh",
		"name_filter" => "gia bình - bắc ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "giabình-bắcninh",
		"index" => 1
	],
	[
		"id" => "110",
		"area_id" => 6,
		"name" => "Lương Tài - Bắc Ninh",
		"name_filter" => "lương tài - bắc ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "lươngtài-bắcninh",
		"index" => 1
	],
	[
		"id" => "111",
		"area_id" => 6,
		"name" => "Quế Võ - Bắc Ninh",
		"name_filter" => "quế võ - bắc ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quếvõ-bắcninh",
		"index" => 1
	],
	[
		"id" => "112",
		"area_id" => 6,
		"name" => "Thuận Thành - Bắc Ninh",
		"name_filter" => "thuận thành - bắc ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "thuậnthành-bắcninh",
		"index" => 1
	],
	[
		"id" => "113",
		"area_id" => 6,
		"name" => "Tiên Du - Bắc Ninh",
		"name_filter" => "tiên du - bắc ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "tiêndu-bắcninh",
		"index" => 1
	],
	[
		"id" => "114",
		"area_id" => 6,
		"name" => "Từ Sơn - Bắc Ninh",
		"name_filter" => "từ sơn - bắc ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "từsơn-bắcninh",
		"index" => 1
	],
	[
		"id" => "115",
		"area_id" => 6,
		"name" => "Yên Phong - Bắc Ninh",
		"name_filter" => "yên phong - bắc ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênphong-bắcninh",
		"index" => 1
	],
	[
		"id" => "116",
		"area_id" => 7,
		"name" => "Ba Tri - Bến Tre",
		"name_filter" => "ba tri - bến tre",
		"category" => "Quận - Huyện",
		"name_nospace" => "batri-bếntre",
		"index" => 1
	],
	[
		"id" => "117",
		"area_id" => 7,
		"name" => "Bến Tre - Bến Tre",
		"name_filter" => "bến tre - bến tre",
		"category" => "Quận - Huyện",
		"name_nospace" => "bếntre-bếntre",
		"index" => 1
	],
	[
		"id" => "118",
		"area_id" => 7,
		"name" => "Bình Đại - Bến Tre",
		"name_filter" => "bình đại - bến tre",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhđại-bếntre",
		"index" => 1
	],
	[
		"id" => "119",
		"area_id" => 7,
		"name" => "Châu Thành - Bến Tre",
		"name_filter" => "châu thành - bến tre",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuthành-bếntre",
		"index" => 1
	],
	[
		"id" => "120",
		"area_id" => 7,
		"name" => "Chợ Lách - Bến Tre",
		"name_filter" => "chợ lách - bến tre",
		"category" => "Quận - Huyện",
		"name_nospace" => "chợlách-bếntre",
		"index" => 1
	],
	[
		"id" => "121",
		"area_id" => 7,
		"name" => "Giồng Trôm - Bến Tre",
		"name_filter" => "giồng trôm - bến tre",
		"category" => "Quận - Huyện",
		"name_nospace" => "giồngtrôm-bếntre",
		"index" => 1
	],
	[
		"id" => "122",
		"area_id" => 7,
		"name" => "Mỏ Cày Bắc - Bến Tre",
		"name_filter" => "mỏ cày bắc - bến tre",
		"category" => "Quận - Huyện",
		"name_nospace" => "mỏcàybắc-bếntre",
		"index" => 1
	],
	[
		"id" => "123",
		"area_id" => 7,
		"name" => "Mỏ Cày Nam - Bến Tre",
		"name_filter" => "mỏ cày nam - bến tre",
		"category" => "Quận - Huyện",
		"name_nospace" => "mỏcàynam-bếntre",
		"index" => 1
	],
	[
		"id" => "124",
		"area_id" => 7,
		"name" => "Thạnh Phú - Bến Tre",
		"name_filter" => "thạnh phú - bến tre",
		"category" => "Quận - Huyện",
		"name_nospace" => "thạnhphú-bếntre",
		"index" => 1
	],
	[
		"id" => "136",
		"area_id" => 9,
		"name" => "Bến Cát - Bình Dương",
		"name_filter" => "bến cát - bình dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "bếncát-bìnhdương",
		"index" => 1
	],
	[
		"id" => "137",
		"area_id" => 9,
		"name" => "Dầu Tiếng - Bình Dương",
		"name_filter" => "dầu tiếng - bình dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "dầutiếng-bìnhdương",
		"index" => 1
	],
	[
		"id" => "138",
		"area_id" => 9,
		"name" => "Dĩ An - Bình Dương",
		"name_filter" => "dĩ an - bình dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "dĩan-bìnhdương",
		"index" => 1
	],
	[
		"id" => "139",
		"area_id" => 9,
		"name" => "Phú Giáo - Bình Dương",
		"name_filter" => "phú giáo - bình dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúgiáo-bìnhdương",
		"index" => 1
	],
	[
		"id" => "140",
		"area_id" => 9,
		"name" => "Tân Uyên - Bình Dương",
		"name_filter" => "tân uyên - bình dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânuyên-bìnhdương",
		"index" => 1
	],
	[
		"id" => "141",
		"area_id" => 9,
		"name" => "Thủ Dầu Một - Bình Dương",
		"name_filter" => "thủ dầu một - bình dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "thủdầumột-bìnhdương",
		"index" => 1
	],
	[
		"id" => "142",
		"area_id" => 9,
		"name" => "Thuận An - Bình Dương",
		"name_filter" => "thuận an - bình dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "thuậnan-bìnhdương",
		"index" => 1
	],
	[
		"id" => "125",
		"area_id" => 8,
		"name" => "An Lão - Bình Định",
		"name_filter" => "an lão - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "anlão-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "126",
		"area_id" => 8,
		"name" => "An Nhơn - Bình Định",
		"name_filter" => "an nhơn - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "annhơn-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "127",
		"area_id" => 8,
		"name" => "Hoài Ân - Bình Định",
		"name_filter" => "hoài ân - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "hoàiân-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "128",
		"area_id" => 8,
		"name" => "Hoài Nhơn - Bình Định",
		"name_filter" => "hoài nhơn - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "hoàinhơn-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "129",
		"area_id" => 8,
		"name" => "Phù Cát - Bình Định",
		"name_filter" => "phù cát - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "phùcát-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "130",
		"area_id" => 8,
		"name" => "Phù Mỹ - Bình Định",
		"name_filter" => "phù mỹ - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "phùmỹ-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "131",
		"area_id" => 8,
		"name" => "Qui Nhơn - Bình Định",
		"name_filter" => "qui nhơn - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "quinhơn-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "132",
		"area_id" => 8,
		"name" => "Tây Sơn - Bình Định",
		"name_filter" => "tây sơn - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "tâysơn-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "133",
		"area_id" => 8,
		"name" => "Tuy Phước - Bình Định",
		"name_filter" => "tuy phước - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "tuyphước-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "134",
		"area_id" => 8,
		"name" => "Vân Canh - Bình Định",
		"name_filter" => "vân canh - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "vâncanh-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "135",
		"area_id" => 8,
		"name" => "Vĩnh Thạnh - Bình Định",
		"name_filter" => "vĩnh thạnh - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhthạnh-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "143",
		"area_id" => 10,
		"name" => "Bình Long - Bình Phước",
		"name_filter" => "bình long - bình phước",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhlong-bìnhphước",
		"index" => 1
	],
	[
		"id" => "144",
		"area_id" => 10,
		"name" => "Bù Đăng - Bình Phước",
		"name_filter" => "bù đăng - bình phước",
		"category" => "Quận - Huyện",
		"name_nospace" => "bùđăng-bìnhphước",
		"index" => 1
	],
	[
		"id" => "145",
		"area_id" => 10,
		"name" => "Bù Đốp - Bình Phước",
		"name_filter" => "bù đốp - bình phước",
		"category" => "Quận - Huyện",
		"name_nospace" => "bùđốp-bìnhphước",
		"index" => 1
	],
	[
		"id" => "146",
		"area_id" => 10,
		"name" => "Bù Gia Mập - Bình Phước",
		"name_filter" => "bù gia mập - bình phước",
		"category" => "Quận - Huyện",
		"name_nospace" => "bùgiamập-bìnhphước",
		"index" => 1
	],
	[
		"id" => "147",
		"area_id" => 10,
		"name" => "Chơn Thành - Bình Phước",
		"name_filter" => "chơn thành - bình phước",
		"category" => "Quận - Huyện",
		"name_nospace" => "chơnthành-bìnhphước",
		"index" => 1
	],
	[
		"id" => "148",
		"area_id" => 10,
		"name" => "Đồng Phú - Bình Phước",
		"name_filter" => "đồng phú - bình phước",
		"category" => "Quận - Huyện",
		"name_nospace" => "đồngphú-bìnhphước",
		"index" => 1
	],
	[
		"id" => "149",
		"area_id" => 10,
		"name" => "Đồng Xoài - Bình Phước",
		"name_filter" => "đồng xoài - bình phước",
		"category" => "Quận - Huyện",
		"name_nospace" => "đồngxoài-bìnhphước",
		"index" => 1
	],
	[
		"id" => "150",
		"area_id" => 10,
		"name" => "Hớn Quản - Bình Phước",
		"name_filter" => "hớn quản - bình phước",
		"category" => "Quận - Huyện",
		"name_nospace" => "hớnquản-bìnhphước",
		"index" => 1
	],
	[
		"id" => "151",
		"area_id" => 10,
		"name" => "Lộc Ninh - Bình Phước",
		"name_filter" => "lộc ninh - bình phước",
		"category" => "Quận - Huyện",
		"name_nospace" => "lộcninh-bìnhphước",
		"index" => 1
	],
	[
		"id" => "152",
		"area_id" => 10,
		"name" => "Phước Long - Bình Phước",
		"name_filter" => "phước long - bình phước",
		"category" => "Quận - Huyện",
		"name_nospace" => "phướclong-bìnhphước",
		"index" => 1
	],
	[
		"id" => "153",
		"area_id" => 11,
		"name" => "Bắc Bình - Bình Thuận",
		"name_filter" => "bắc bình - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắcbình-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "154",
		"area_id" => 11,
		"name" => "Đức Linh - Bình Thuận",
		"name_filter" => "đức linh - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "đứclinh-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "155",
		"area_id" => 11,
		"name" => "Hàm Tân - Bình Thuận",
		"name_filter" => "hàm tân - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "hàmtân-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "156",
		"area_id" => 11,
		"name" => "Hàm Thuận Bắc - Bình Thuận",
		"name_filter" => "hàm thuận bắc - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "hàmthuậnbắc-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "157",
		"area_id" => 11,
		"name" => "Hàm Thuận Nam - Bình Thuận",
		"name_filter" => "hàm thuận nam - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "hàmthuậnnam-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "158",
		"area_id" => 11,
		"name" => "La Gi - Bình Thuận",
		"name_filter" => "la gi - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "lagi-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "159",
		"area_id" => 11,
		"name" => "Phan Thiết - Bình Thuận",
		"name_filter" => "phan thiết - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "phanthiết-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "28458",
		"area_id" => 11,
		"name" => "Mũi Né - Bình Thuận",
		"name_filter" => "mũi né - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "mũiné-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "114633",
		"area_id" => 11,
		"name" => "Phường Mũi Né - Bình Thuận",
		"name_filter" => "phường mũi né - bình thuận",
		"category" => "Phường - Xã",
		"name_nospace" => "phườngmũiné-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "160",
		"area_id" => 11,
		"name" => "Phú Quý - Bình Thuận",
		"name_filter" => "phú quý - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúquý-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "161",
		"area_id" => 11,
		"name" => "Tánh Linh - Bình Thuận",
		"name_filter" => "tánh linh - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "tánhlinh-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "162",
		"area_id" => 11,
		"name" => "Tuy Phong - Bình Thuận",
		"name_filter" => "tuy phong - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "tuyphong-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "163",
		"area_id" => 12,
		"name" => "Cà Mau - Cà Mau",
		"name_filter" => "cà mau - cà mau",
		"category" => "Quận - Huyện",
		"name_nospace" => "càmau-càmau",
		"index" => 1
	],
	[
		"id" => "164",
		"area_id" => 12,
		"name" => "Cái Nước - Cà Mau",
		"name_filter" => "cái nước - cà mau",
		"category" => "Quận - Huyện",
		"name_nospace" => "cáinước-càmau",
		"index" => 1
	],
	[
		"id" => "165",
		"area_id" => 12,
		"name" => "Đầm Dơi - Cà Mau",
		"name_filter" => "đầm dơi - cà mau",
		"category" => "Quận - Huyện",
		"name_nospace" => "đầmdơi-càmau",
		"index" => 1
	],
	[
		"id" => "166",
		"area_id" => 12,
		"name" => "Năm Căn - Cà Mau",
		"name_filter" => "năm căn - cà mau",
		"category" => "Quận - Huyện",
		"name_nospace" => "nămcăn-càmau",
		"index" => 1
	],
	[
		"id" => "167",
		"area_id" => 12,
		"name" => "Ngọc Hiển - Cà Mau",
		"name_filter" => "ngọc hiển - cà mau",
		"category" => "Quận - Huyện",
		"name_nospace" => "ngọchiển-càmau",
		"index" => 1
	],
	[
		"id" => "168",
		"area_id" => 12,
		"name" => "Phú Tân - Cà Mau",
		"name_filter" => "phú tân - cà mau",
		"category" => "Quận - Huyện",
		"name_nospace" => "phútân-càmau",
		"index" => 1
	],
	[
		"id" => "169",
		"area_id" => 12,
		"name" => "Thới Bình - Cà Mau",
		"name_filter" => "thới bình - cà mau",
		"category" => "Quận - Huyện",
		"name_nospace" => "thớibình-càmau",
		"index" => 1
	],
	[
		"id" => "170",
		"area_id" => 12,
		"name" => "Trần Văn Thời - Cà Mau",
		"name_filter" => "trần văn thời - cà mau",
		"category" => "Quận - Huyện",
		"name_nospace" => "trầnvănthời-càmau",
		"index" => 1
	],
	[
		"id" => "171",
		"area_id" => 12,
		"name" => "U Minh - Cà Mau",
		"name_filter" => "u minh - cà mau",
		"category" => "Quận - Huyện",
		"name_nospace" => "uminh-càmau",
		"index" => 1
	],
	[
		"id" => "181",
		"area_id" => 14,
		"name" => "Bảo Lạc - Cao Bằng",
		"name_filter" => "bảo lạc - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "bảolạc-caobằng",
		"index" => 1
	],
	[
		"id" => "182",
		"area_id" => 14,
		"name" => "Bảo Lâm - Cao Bằng",
		"name_filter" => "bảo lâm - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "bảolâm-caobằng",
		"index" => 1
	],
	[
		"id" => "183",
		"area_id" => 14,
		"name" => "Cao Bằng - Cao Bằng",
		"name_filter" => "cao bằng - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "caobằng-caobằng",
		"index" => 1
	],
	[
		"id" => "184",
		"area_id" => 14,
		"name" => "Hà Quảng - Cao Bằng",
		"name_filter" => "hà quảng - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "hàquảng-caobằng",
		"index" => 1
	],
	[
		"id" => "185",
		"area_id" => 14,
		"name" => "Hạ Lang - Cao Bằng",
		"name_filter" => "hạ lang - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "hạlang-caobằng",
		"index" => 1
	],
	[
		"id" => "186",
		"area_id" => 14,
		"name" => "Hòa An - Cao Bằng",
		"name_filter" => "hòa an - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "hòaan-caobằng",
		"index" => 1
	],
	[
		"id" => "187",
		"area_id" => 14,
		"name" => "Nguyên Bình - Cao Bằng",
		"name_filter" => "nguyên bình - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "nguyênbình-caobằng",
		"index" => 1
	],
	[
		"id" => "188",
		"area_id" => 14,
		"name" => "Phục Hòa - Cao Bằng",
		"name_filter" => "phục hòa - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "phụchòa-caobằng",
		"index" => 1
	],
	[
		"id" => "189",
		"area_id" => 14,
		"name" => "Quảng Uyên - Cao Bằng",
		"name_filter" => "quảng uyên - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảnguyên-caobằng",
		"index" => 1
	],
	[
		"id" => "190",
		"area_id" => 14,
		"name" => "Thạch An - Cao Bằng",
		"name_filter" => "thạch an - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "thạchan-caobằng",
		"index" => 1
	],
	[
		"id" => "191",
		"area_id" => 14,
		"name" => "Thông Nông - Cao Bằng",
		"name_filter" => "thông nông - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "thôngnông-caobằng",
		"index" => 1
	],
	[
		"id" => "192",
		"area_id" => 14,
		"name" => "Trà Lĩnh - Cao Bằng",
		"name_filter" => "trà lĩnh - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "tràlĩnh-caobằng",
		"index" => 1
	],
	[
		"id" => "193",
		"area_id" => 14,
		"name" => "Trùng Khánh - Cao Bằng",
		"name_filter" => "trùng khánh - cao bằng",
		"category" => "Quận - Huyện",
		"name_nospace" => "trùngkhánh-caobằng",
		"index" => 1
	],
	[
		"id" => "202",
		"area_id" => 16,
		"name" => "Buôn Đôn - Đắk Lắk",
		"name_filter" => "buôn đôn - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "buônđôn-đắklắk",
		"index" => 1
	],
	[
		"id" => "203",
		"area_id" => 16,
		"name" => "Buôn Hồ - Đắk Lắk",
		"name_filter" => "buôn hồ - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "buônhồ-đắklắk",
		"index" => 1
	],
	[
		"id" => "204",
		"area_id" => 16,
		"name" => "Buôn Ma Thuột - Đắk Lắk",
		"name_filter" => "buôn ma thuột - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "buônmathuột-đắklắk",
		"index" => 1
	],
	[
		"id" => "205",
		"area_id" => 16,
		"name" => "Cư Kuin - Đắk Lắk",
		"name_filter" => "cư kuin - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "cưkuin-đắklắk",
		"index" => 1
	],
	[
		"id" => "206",
		"area_id" => 16,
		"name" => "Cư M'gar - Đắk Lắk",
		"name_filter" => "cư m'gar - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "cưm'gar-đắklắk",
		"index" => 1
	],
	[
		"id" => "207",
		"area_id" => 16,
		"name" => "Ea H'leo - Đắk Lắk",
		"name_filter" => "ea h'leo - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "eah'leo-đắklắk",
		"index" => 1
	],
	[
		"id" => "208",
		"area_id" => 16,
		"name" => "Ea Kar - Đắk Lắk",
		"name_filter" => "ea kar - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "eakar-đắklắk",
		"index" => 1
	],
	[
		"id" => "209",
		"area_id" => 16,
		"name" => "Ea Súp - Đắk Lắk",
		"name_filter" => "ea súp - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "easúp-đắklắk",
		"index" => 1
	],
	[
		"id" => "210",
		"area_id" => 16,
		"name" => "Krông Ana - Đắk Lắk",
		"name_filter" => "krông ana - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "krôngana-đắklắk",
		"index" => 1
	],
	[
		"id" => "211",
		"area_id" => 16,
		"name" => "Krông Bông - Đắk Lắk",
		"name_filter" => "krông bông - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "krôngbông-đắklắk",
		"index" => 1
	],
	[
		"id" => "212",
		"area_id" => 16,
		"name" => "Krông Búk - Đắk Lắk",
		"name_filter" => "krông búk - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "krôngbúk-đắklắk",
		"index" => 1
	],
	[
		"id" => "213",
		"area_id" => 16,
		"name" => "Krông Năng - Đắk Lắk",
		"name_filter" => "krông năng - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "krôngnăng-đắklắk",
		"index" => 1
	],
	[
		"id" => "214",
		"area_id" => 16,
		"name" => "Krông Pắk - Đắk Lắk",
		"name_filter" => "krông pắk - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "krôngpắk-đắklắk",
		"index" => 1
	],
	[
		"id" => "215",
		"area_id" => 16,
		"name" => "Lắk - Đắk Lắk",
		"name_filter" => "lắk - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "lắk-đắklắk",
		"index" => 1
	],
	[
		"id" => "216",
		"area_id" => 16,
		"name" => "M'Đrăk - Đắk Lắk",
		"name_filter" => "m'đrăk - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "m'đrăk-đắklắk",
		"index" => 1
	],
	[
		"id" => "217",
		"area_id" => 17,
		"name" => "Cư Jút - Đăk Nông",
		"name_filter" => "cư jút - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "cưjút-đăknông",
		"index" => 1
	],
	[
		"id" => "218",
		"area_id" => 17,
		"name" => "Đăk Glong - Đăk Nông",
		"name_filter" => "đăk glong - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "đăkglong-đăknông",
		"index" => 1
	],
	[
		"id" => "218",
		"area_id" => 17,
		"name" => "Quảng Khê - Đăk Glong - Đăk Nông",
		"name_filter" => "quảng khê - đăk glong - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảngkhê-đăkglong-đăknông",
		"index" => 1
	],
	[
		"id" => "218",
		"area_id" => 17,
		"name" => "Quảng Sơn - Đăk Glong - Đăk Nông",
		"name_filter" => "quảng sơn - đăk glong - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảngsơn-đăkglong-đăknông",
		"index" => 1
	],
	[
		"id" => "219",
		"area_id" => 17,
		"name" => "Đăk Mil - Đăk Nông",
		"name_filter" => "đăk mil - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "đăkmil-đăknông",
		"index" => 1
	],
	[
		"id" => "220",
		"area_id" => 17,
		"name" => "Đăk R'Lấp - Đăk Nông",
		"name_filter" => "đăk r'lấp - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "đăkr'lấp-đăknông",
		"index" => 1
	],
	[
		"id" => "220",
		"area_id" => 17,
		"name" => "Kiến Đức - Đăk R'Lấp - Đăk Nông",
		"name_filter" => "kiến đức - đăk r'lấp - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "kiếnđức-đăkr'lấp-đăknông",
		"index" => 1
	],
	[
		"id" => "220",
		"area_id" => 17,
		"name" => "Nhân Cơ - Đăk R'Lấp - Đăk Nông",
		"name_filter" => "nhân cơ - đăk r'lấp - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "nhâncơ-đăkr'lấp-đăknông",
		"index" => 1
	],
	[
		"id" => "221",
		"area_id" => 17,
		"name" => "Đăk Song - Đăk Nông",
		"name_filter" => "đăk song - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "đăksong-đăknông",
		"index" => 1
	],
	[
		"id" => "222",
		"area_id" => 17,
		"name" => "Gia Nghĩa - Đăk Nông",
		"name_filter" => "gia nghĩa - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "gianghĩa-đăknông",
		"index" => 1
	],
	[
		"id" => "223",
		"area_id" => 17,
		"name" => "Krông Nô - Đăk Nông",
		"name_filter" => "krông nô - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "krôngnô-đăknông",
		"index" => 1
	],
	[
		"id" => "224",
		"area_id" => 17,
		"name" => "Tuy Đức - Đăk Nông",
		"name_filter" => "tuy đức - đăk nông",
		"category" => "Quận - Huyện",
		"name_nospace" => "tuyđức-đăknông",
		"index" => 1
	],
	[
		"id" => "225",
		"area_id" => 18,
		"name" => "Điện Biên - Điện Biên",
		"name_filter" => "điện biên - điện biên",
		"category" => "Quận - Huyện",
		"name_nospace" => "điệnbiên-điệnbiên",
		"index" => 1
	],
	[
		"id" => "226",
		"area_id" => 18,
		"name" => "Điện Biên Đông - Điện Biên",
		"name_filter" => "điện biên đông - điện biên",
		"category" => "Quận - Huyện",
		"name_nospace" => "điệnbiênđông-điệnbiên",
		"index" => 1
	],
	[
		"id" => "227",
		"area_id" => 18,
		"name" => "Điện Biên Phủ - Điện Biên",
		"name_filter" => "điện biên phủ - điện biên",
		"category" => "Quận - Huyện",
		"name_nospace" => "điệnbiênphủ-điệnbiên",
		"index" => 1
	],
	[
		"id" => "228",
		"area_id" => 18,
		"name" => "Mường Ảng - Điện Biên",
		"name_filter" => "mường ảng - điện biên",
		"category" => "Quận - Huyện",
		"name_nospace" => "mườngảng-điệnbiên",
		"index" => 1
	],
	[
		"id" => "229",
		"area_id" => 18,
		"name" => "Mường Chà - Điện Biên",
		"name_filter" => "mường chà - điện biên",
		"category" => "Quận - Huyện",
		"name_nospace" => "mườngchà-điệnbiên",
		"index" => 1
	],
	[
		"id" => "230",
		"area_id" => 18,
		"name" => "Mường Lay - Điện Biên",
		"name_filter" => "mường lay - điện biên",
		"category" => "Quận - Huyện",
		"name_nospace" => "mườnglay-điệnbiên",
		"index" => 1
	],
	[
		"id" => "231",
		"area_id" => 18,
		"name" => "Mường Nhé - Điện Biên",
		"name_filter" => "mường nhé - điện biên",
		"category" => "Quận - Huyện",
		"name_nospace" => "mườngnhé-điệnbiên",
		"index" => 1
	],
	[
		"id" => "232",
		"area_id" => 18,
		"name" => "Nậm Pồ - Điện Biên",
		"name_filter" => "nậm pồ - điện biên",
		"category" => "Quận - Huyện",
		"name_nospace" => "nậmpồ-điệnbiên",
		"index" => 1
	],
	[
		"id" => "233",
		"area_id" => 18,
		"name" => "Tủa Chùa - Điện Biên",
		"name_filter" => "tủa chùa - điện biên",
		"category" => "Quận - Huyện",
		"name_nospace" => "tủachùa-điệnbiên",
		"index" => 1
	],
	[
		"id" => "234",
		"area_id" => 18,
		"name" => "Tuần Giáo - Điện Biên",
		"name_filter" => "tuần giáo - điện biên",
		"category" => "Quận - Huyện",
		"name_nospace" => "tuầngiáo-điệnbiên",
		"index" => 1
	],
	[
		"id" => "235",
		"area_id" => 19,
		"name" => "Biên Hòa - Đồng Nai",
		"name_filter" => "biên hòa - đồng nai",
		"category" => "Quận - Huyện",
		"name_nospace" => "biênhòa-đồngnai",
		"index" => 1
	],
	[
		"id" => "236",
		"area_id" => 19,
		"name" => "Cẩm Mỹ - Đồng Nai",
		"name_filter" => "cẩm mỹ - đồng nai",
		"category" => "Quận - Huyện",
		"name_nospace" => "cẩmmỹ-đồngnai",
		"index" => 1
	],
	[
		"id" => "237",
		"area_id" => 19,
		"name" => "Định Quán - Đồng Nai",
		"name_filter" => "định quán - đồng nai",
		"category" => "Quận - Huyện",
		"name_nospace" => "địnhquán-đồngnai",
		"index" => 1
	],
	[
		"id" => "238",
		"area_id" => 19,
		"name" => "Long Khánh - Đồng Nai",
		"name_filter" => "long khánh - đồng nai",
		"category" => "Quận - Huyện",
		"name_nospace" => "longkhánh-đồngnai",
		"index" => 1
	],
	[
		"id" => "239",
		"area_id" => 19,
		"name" => "Long Thành - Đồng Nai",
		"name_filter" => "long thành - đồng nai",
		"category" => "Quận - Huyện",
		"name_nospace" => "longthành-đồngnai",
		"index" => 1
	],
	[
		"id" => "240",
		"area_id" => 19,
		"name" => "Nhơn Trạch - Đồng Nai",
		"name_filter" => "nhơn trạch - đồng nai",
		"category" => "Quận - Huyện",
		"name_nospace" => "nhơntrạch-đồngnai",
		"index" => 1
	],
	[
		"id" => "241",
		"area_id" => 19,
		"name" => "Tân Phú - Đồng Nai",
		"name_filter" => "tân phú - đồng nai",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânphú-đồngnai",
		"index" => 1
	],
	[
		"id" => "242",
		"area_id" => 19,
		"name" => "Thống Nhất - Đồng Nai",
		"name_filter" => "thống nhất - đồng nai",
		"category" => "Quận - Huyện",
		"name_nospace" => "thốngnhất-đồngnai",
		"index" => 1
	],
	[
		"id" => "243",
		"area_id" => 19,
		"name" => "Trảng Bom - Đồng Nai",
		"name_filter" => "trảng bom - đồng nai",
		"category" => "Quận - Huyện",
		"name_nospace" => "trảngbom-đồngnai",
		"index" => 1
	],
	[
		"id" => "244",
		"area_id" => 19,
		"name" => "Vĩnh Cửu - Đồng Nai",
		"name_filter" => "vĩnh cửu - đồng nai",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhcửu-đồngnai",
		"index" => 1
	],
	[
		"id" => "245",
		"area_id" => 19,
		"name" => "Xuân Lộc - Đồng Nai",
		"name_filter" => "xuân lộc - đồng nai",
		"category" => "Quận - Huyện",
		"name_nospace" => "xuânlộc-đồngnai",
		"index" => 1
	],
	[
		"id" => "246",
		"area_id" => 20,
		"name" => "Tp.Cao Lãnh - Đồng Tháp",
		"name_filter" => "tp.cao lãnh - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "tp.caolãnh-đồngtháp",
		"index" => 1
	],
	[
		"id" => "247",
		"area_id" => 20,
		"name" => "H.Cao Lãnh - Đồng Tháp",
		"name_filter" => "h.cao lãnh - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "h.caolãnh-đồngtháp",
		"index" => 1
	],
	[
		"id" => "248",
		"area_id" => 20,
		"name" => "Châu Thành - Đồng Tháp",
		"name_filter" => "châu thành - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuthành-đồngtháp",
		"index" => 1
	],
	[
		"id" => "249",
		"area_id" => 20,
		"name" => "Hồng Ngự - Đồng Tháp",
		"name_filter" => "hồng ngự - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "hồngngự-đồngtháp",
		"index" => 1
	],
	[
		"id" => "250",
		"area_id" => 20,
		"name" => "Hồng Ngự - Đồng Tháp",
		"name_filter" => "hồng ngự - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "hồngngự-đồngtháp",
		"index" => 1
	],
	[
		"id" => "251",
		"area_id" => 20,
		"name" => "Lai Vung - Đồng Tháp",
		"name_filter" => "lai vung - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "laivung-đồngtháp",
		"index" => 1
	],
	[
		"id" => "252",
		"area_id" => 20,
		"name" => "Lấp Vò - Đồng Tháp",
		"name_filter" => "lấp vò - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "lấpvò-đồngtháp",
		"index" => 1
	],
	[
		"id" => "253",
		"area_id" => 20,
		"name" => "Sa Đéc - Đồng Tháp",
		"name_filter" => "sa đéc - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "sađéc-đồngtháp",
		"index" => 1
	],
	[
		"id" => "254",
		"area_id" => 20,
		"name" => "Tam Nông - Đồng Tháp",
		"name_filter" => "tam nông - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "tamnông-đồngtháp",
		"index" => 1
	],
	[
		"id" => "255",
		"area_id" => 20,
		"name" => "Tân Hồng - Đồng Tháp",
		"name_filter" => "tân hồng - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânhồng-đồngtháp",
		"index" => 1
	],
	[
		"id" => "256",
		"area_id" => 20,
		"name" => "Thanh Bình - Đồng Tháp",
		"name_filter" => "thanh bình - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhbình-đồngtháp",
		"index" => 1
	],
	[
		"id" => "257",
		"area_id" => 20,
		"name" => "Tháp Mười - Đồng Tháp",
		"name_filter" => "tháp mười - đồng tháp",
		"category" => "Quận - Huyện",
		"name_nospace" => "thápmười-đồngtháp",
		"index" => 1
	],
	[
		"id" => "258",
		"area_id" => 21,
		"name" => "An Khê - Gia Lai",
		"name_filter" => "an khê - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "ankhê-gialai",
		"index" => 1
	],
	[
		"id" => "259",
		"area_id" => 21,
		"name" => "Ayun Pa - Gia Lai",
		"name_filter" => "ayun pa - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "ayunpa-gialai",
		"index" => 1
	],
	[
		"id" => "260",
		"area_id" => 21,
		"name" => "Chư Păh - Gia Lai",
		"name_filter" => "chư păh - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "chưpăh-gialai",
		"index" => 1
	],
	[
		"id" => "261",
		"area_id" => 21,
		"name" => "Chư Prông - Gia Lai",
		"name_filter" => "chư prông - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "chưprông-gialai",
		"index" => 1
	],
	[
		"id" => "262",
		"area_id" => 21,
		"name" => "Chư Pưh - Gia Lai",
		"name_filter" => "chư pưh - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "chưpưh-gialai",
		"index" => 1
	],
	[
		"id" => "263",
		"area_id" => 21,
		"name" => "Chư Sê - Gia Lai",
		"name_filter" => "chư sê - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "chưsê-gialai",
		"index" => 1
	],
	[
		"id" => "264",
		"area_id" => 21,
		"name" => "Đăk Đoa - Gia Lai",
		"name_filter" => "đăk đoa - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "đăkđoa-gialai",
		"index" => 1
	],
	[
		"id" => "265",
		"area_id" => 21,
		"name" => "Đắk Pơ - Gia Lai",
		"name_filter" => "đắk pơ - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "đắkpơ-gialai",
		"index" => 1
	],
	[
		"id" => "266",
		"area_id" => 21,
		"name" => "Đức Cơ - Gia Lai",
		"name_filter" => "đức cơ - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "đứccơ-gialai",
		"index" => 1
	],
	[
		"id" => "267",
		"area_id" => 21,
		"name" => "Ia Grai - Gia Lai",
		"name_filter" => "ia grai - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "iagrai-gialai",
		"index" => 1
	],
	[
		"id" => "268",
		"area_id" => 21,
		"name" => "Ia Pa - Gia Lai",
		"name_filter" => "ia pa - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "iapa-gialai",
		"index" => 1
	],
	[
		"id" => "269",
		"area_id" => 21,
		"name" => "KBang - Gia Lai",
		"name_filter" => "kbang - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "kbang-gialai",
		"index" => 1
	],
	[
		"id" => "270",
		"area_id" => 21,
		"name" => "Kông Chro - Gia Lai",
		"name_filter" => "kông chro - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "kôngchro-gialai",
		"index" => 1
	],
	[
		"id" => "271",
		"area_id" => 21,
		"name" => "Krông Pa - Gia Lai",
		"name_filter" => "krông pa - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "krôngpa-gialai",
		"index" => 1
	],
	[
		"id" => "272",
		"area_id" => 21,
		"name" => "Mang Yang - Gia Lai",
		"name_filter" => "mang yang - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "mangyang-gialai",
		"index" => 1
	],
	[
		"id" => "273",
		"area_id" => 21,
		"name" => "Phú Thiện - Gia Lai",
		"name_filter" => "phú thiện - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúthiện-gialai",
		"index" => 1
	],
	[
		"id" => "274",
		"area_id" => 21,
		"name" => "Pleiku - Gia Lai",
		"name_filter" => "pleiku - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "pleiku-gialai",
		"index" => 1
	],
	[
		"id" => "275",
		"area_id" => 22,
		"name" => "Bắc Mê - Hà Giang",
		"name_filter" => "bắc mê - hà giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắcmê-hàgiang",
		"index" => 1
	],
	[
		"id" => "276",
		"area_id" => 22,
		"name" => "Bắc Quang - Hà Giang",
		"name_filter" => "bắc quang - hà giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắcquang-hàgiang",
		"index" => 1
	],
	[
		"id" => "277",
		"area_id" => 22,
		"name" => "Đồng Văn - Hà Giang",
		"name_filter" => "đồng văn - hà giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "đồngvăn-hàgiang",
		"index" => 1
	],
	[
		"id" => "278",
		"area_id" => 22,
		"name" => "Hà Giang - Hà Giang",
		"name_filter" => "hà giang - hà giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "hàgiang-hàgiang",
		"index" => 1
	],
	[
		"id" => "279",
		"area_id" => 22,
		"name" => "Hoàng Su Phì - Hà Giang",
		"name_filter" => "hoàng su phì - hà giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "hoàngsuphì-hàgiang",
		"index" => 1
	],
	[
		"id" => "280",
		"area_id" => 22,
		"name" => "Mèo Vạc - Hà Giang",
		"name_filter" => "mèo vạc - hà giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "mèovạc-hàgiang",
		"index" => 1
	],
	[
		"id" => "281",
		"area_id" => 22,
		"name" => "Quản Bạ - Hà Giang",
		"name_filter" => "quản bạ - hà giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảnbạ-hàgiang",
		"index" => 1
	],
	[
		"id" => "282",
		"area_id" => 22,
		"name" => "Quang Bình - Hà Giang",
		"name_filter" => "quang bình - hà giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "quangbình-hàgiang",
		"index" => 1
	],
	[
		"id" => "283",
		"area_id" => 22,
		"name" => "Vị Xuyên - Hà Giang",
		"name_filter" => "vị xuyên - hà giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "vịxuyên-hàgiang",
		"index" => 1
	],
	[
		"id" => "284",
		"area_id" => 22,
		"name" => "Xín Mần - Hà Giang",
		"name_filter" => "xín mần - hà giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "xínmần-hàgiang",
		"index" => 1
	],
	[
		"id" => "285",
		"area_id" => 22,
		"name" => "Yên Minh - Hà Giang",
		"name_filter" => "yên minh - hà giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênminh-hàgiang",
		"index" => 1
	],
	[
		"id" => "286",
		"area_id" => 23,
		"name" => "Bình Lục - Hà Nam",
		"name_filter" => "bình lục - hà nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhlục-hànam",
		"index" => 1
	],
	[
		"id" => "287",
		"area_id" => 23,
		"name" => "Duy Tiên - Hà Nam",
		"name_filter" => "duy tiên - hà nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "duytiên-hànam",
		"index" => 1
	],
	[
		"id" => "288",
		"area_id" => 23,
		"name" => "Kim Bảng - Hà Nam",
		"name_filter" => "kim bảng - hà nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "kimbảng-hànam",
		"index" => 1
	],
	[
		"id" => "289",
		"area_id" => 23,
		"name" => "Lý Nhân - Hà Nam",
		"name_filter" => "lý nhân - hà nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "lýnhân-hànam",
		"index" => 1
	],
	[
		"id" => "290",
		"area_id" => 23,
		"name" => "Phủ Lý - Hà Nam",
		"name_filter" => "phủ lý - hà nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "phủlý-hànam",
		"index" => 1
	],
	[
		"id" => "291",
		"area_id" => 23,
		"name" => "Thanh Liêm - Hà Nam",
		"name_filter" => "thanh liêm - hà nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhliêm-hànam",
		"index" => 1
	],
	[
		"id" => "321",
		"area_id" => 25,
		"name" => "Can Lộc - Hà Tĩnh",
		"name_filter" => "can lộc - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "canlộc-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "322",
		"area_id" => 25,
		"name" => "Cẩm Xuyên - Hà Tĩnh",
		"name_filter" => "cẩm xuyên - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "cẩmxuyên-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "323",
		"area_id" => 25,
		"name" => "Đức Thọ - Hà Tĩnh",
		"name_filter" => "đức thọ - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "đứcthọ-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "324",
		"area_id" => 25,
		"name" => "Hà Tĩnh - Hà Tĩnh",
		"name_filter" => "hà tĩnh - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "hàtĩnh-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "325",
		"area_id" => 25,
		"name" => "Hồng Lĩnh - Hà Tĩnh",
		"name_filter" => "hồng lĩnh - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "hồnglĩnh-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "326",
		"area_id" => 25,
		"name" => "Hương Khê - Hà Tĩnh",
		"name_filter" => "hương khê - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "hươngkhê-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "327",
		"area_id" => 25,
		"name" => "Hương Sơn - Hà Tĩnh",
		"name_filter" => "hương sơn - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "hươngsơn-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "328",
		"area_id" => 25,
		"name" => "Kỳ Anh - Hà Tĩnh",
		"name_filter" => "kỳ anh - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "kỳanh-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "329",
		"area_id" => 25,
		"name" => "Lộc Hà - Hà Tĩnh",
		"name_filter" => "lộc hà - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "lộchà-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "330",
		"area_id" => 25,
		"name" => "Nghi Xuân - Hà Tĩnh",
		"name_filter" => "nghi xuân - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "nghixuân-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "331",
		"area_id" => 25,
		"name" => "Thạch Hà - Hà Tĩnh",
		"name_filter" => "thạch hà - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "thạchhà-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "332",
		"area_id" => 25,
		"name" => "Vũ Quang - Hà Tĩnh",
		"name_filter" => "vũ quang - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "vũquang-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "333",
		"area_id" => 26,
		"name" => "Bình Giang - Hải Dương",
		"name_filter" => "bình giang - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhgiang-hảidương",
		"index" => 1
	],
	[
		"id" => "334",
		"area_id" => 26,
		"name" => "Cẩm Giàng - Hải Dương",
		"name_filter" => "cẩm giàng - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "cẩmgiàng-hảidương",
		"index" => 1
	],
	[
		"id" => "335",
		"area_id" => 26,
		"name" => "Chí Linh - Hải Dương",
		"name_filter" => "chí linh - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "chílinh-hảidương",
		"index" => 1
	],
	[
		"id" => "336",
		"area_id" => 26,
		"name" => "Gia Lộc - Hải Dương",
		"name_filter" => "gia lộc - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "gialộc-hảidương",
		"index" => 1
	],
	[
		"id" => "337",
		"area_id" => 26,
		"name" => "Hải Dương - Hải Dương",
		"name_filter" => "hải dương - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "hảidương-hảidương",
		"index" => 1
	],
	[
		"id" => "338",
		"area_id" => 26,
		"name" => "Kim Thành - Hải Dương",
		"name_filter" => "kim thành - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "kimthành-hảidương",
		"index" => 1
	],
	[
		"id" => "339",
		"area_id" => 26,
		"name" => "Kinh Môn - Hải Dương",
		"name_filter" => "kinh môn - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "kinhmôn-hảidương",
		"index" => 1
	],
	[
		"id" => "340",
		"area_id" => 26,
		"name" => "Nam Sách - Hải Dương",
		"name_filter" => "nam sách - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "namsách-hảidương",
		"index" => 1
	],
	[
		"id" => "341",
		"area_id" => 26,
		"name" => "Ninh Giang - Hải Dương",
		"name_filter" => "ninh giang - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "ninhgiang-hảidương",
		"index" => 1
	],
	[
		"id" => "342",
		"area_id" => 26,
		"name" => "Thanh Hà - Hải Dương",
		"name_filter" => "thanh hà - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhhà-hảidương",
		"index" => 1
	],
	[
		"id" => "343",
		"area_id" => 26,
		"name" => "Thanh Miện - Hải Dương",
		"name_filter" => "thanh miện - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhmiện-hảidương",
		"index" => 1
	],
	[
		"id" => "344",
		"area_id" => 26,
		"name" => "Tứ Kỳ - Hải Dương",
		"name_filter" => "tứ kỳ - hải dương",
		"category" => "Quận - Huyện",
		"name_nospace" => "tứkỳ-hảidương",
		"index" => 1
	],
	[
		"id" => "360",
		"area_id" => 28,
		"name" => "Châu Thành - Hậu Giang",
		"name_filter" => "châu thành - hậu giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuthành-hậugiang",
		"index" => 1
	],
	[
		"id" => "361",
		"area_id" => 28,
		"name" => "Châu Thành A - Hậu Giang",
		"name_filter" => "châu thành a - hậu giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuthànha-hậugiang",
		"index" => 1
	],
	[
		"id" => "362",
		"area_id" => 28,
		"name" => "Long Mỹ - Hậu Giang",
		"name_filter" => "long mỹ - hậu giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "longmỹ-hậugiang",
		"index" => 1
	],
	[
		"id" => "363",
		"area_id" => 28,
		"name" => "Ngã Bảy - Hậu Giang",
		"name_filter" => "ngã bảy - hậu giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "ngãbảy-hậugiang",
		"index" => 1
	],
	[
		"id" => "364",
		"area_id" => 28,
		"name" => "Phụng Hiệp - Hậu Giang",
		"name_filter" => "phụng hiệp - hậu giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "phụnghiệp-hậugiang",
		"index" => 1
	],
	[
		"id" => "365",
		"area_id" => 28,
		"name" => "Vị Thanh - Hậu Giang",
		"name_filter" => "vị thanh - hậu giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "vịthanh-hậugiang",
		"index" => 1
	],
	[
		"id" => "366",
		"area_id" => 28,
		"name" => "Vị Thủy - Hậu Giang",
		"name_filter" => "vị thủy - hậu giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "vịthủy-hậugiang",
		"index" => 1
	],
	[
		"id" => "391",
		"area_id" => 30,
		"name" => "Cao Phong - Hòa Bình",
		"name_filter" => "cao phong - hòa bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "caophong-hòabình",
		"index" => 1
	],
	[
		"id" => "392",
		"area_id" => 30,
		"name" => "Đà Bắc - Hòa Bình",
		"name_filter" => "đà bắc - hòa bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "đàbắc-hòabình",
		"index" => 1
	],
	[
		"id" => "393",
		"area_id" => 30,
		"name" => "Hoà Bình - Hòa Bình",
		"name_filter" => "hoà bình - hòa bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "hoàbình-hòabình",
		"index" => 1
	],
	[
		"id" => "394",
		"area_id" => 30,
		"name" => "Kim Bôi - Hòa Bình",
		"name_filter" => "kim bôi - hòa bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "kimbôi-hòabình",
		"index" => 1
	],
	[
		"id" => "395",
		"area_id" => 30,
		"name" => "Kỳ Sơn - Hòa Bình",
		"name_filter" => "kỳ sơn - hòa bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "kỳsơn-hòabình",
		"index" => 1
	],
	[
		"id" => "396",
		"area_id" => 30,
		"name" => "Lạc Sơn - Hòa Bình",
		"name_filter" => "lạc sơn - hòa bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "lạcsơn-hòabình",
		"index" => 1
	],
	[
		"id" => "397",
		"area_id" => 30,
		"name" => "Lạc Thủy - Hòa Bình",
		"name_filter" => "lạc thủy - hòa bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "lạcthủy-hòabình",
		"index" => 1
	],
	[
		"id" => "398",
		"area_id" => 30,
		"name" => "Lương Sơn - Hòa Bình",
		"name_filter" => "lương sơn - hòa bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "lươngsơn-hòabình",
		"index" => 1
	],
	[
		"id" => "399",
		"area_id" => 30,
		"name" => "Mai Châu - Hòa Bình",
		"name_filter" => "mai châu - hòa bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "maichâu-hòabình",
		"index" => 1
	],
	[
		"id" => "400",
		"area_id" => 30,
		"name" => "Tân Lạc - Hòa Bình",
		"name_filter" => "tân lạc - hòa bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânlạc-hòabình",
		"index" => 1
	],
	[
		"id" => "401",
		"area_id" => 30,
		"name" => "Yên Thủy - Hòa Bình",
		"name_filter" => "yên thủy - hòa bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênthủy-hòabình",
		"index" => 1
	],
	[
		"id" => "402",
		"area_id" => 31,
		"name" => "Ân Thi - Hưng Yên",
		"name_filter" => "ân thi - hưng yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "ânthi-hưngyên",
		"index" => 1
	],
	[
		"id" => "403",
		"area_id" => 31,
		"name" => "Hưng Yên - Hưng Yên",
		"name_filter" => "hưng yên - hưng yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "hưngyên-hưngyên",
		"index" => 1
	],
	[
		"id" => "404",
		"area_id" => 31,
		"name" => "Khoái Châu - Hưng Yên",
		"name_filter" => "khoái châu - hưng yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "khoáichâu-hưngyên",
		"index" => 1
	],
	[
		"id" => "405",
		"area_id" => 31,
		"name" => "Kim Động - Hưng Yên",
		"name_filter" => "kim động - hưng yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "kimđộng-hưngyên",
		"index" => 1
	],
	[
		"id" => "406",
		"area_id" => 31,
		"name" => "Mỹ Hào - Hưng Yên",
		"name_filter" => "mỹ hào - hưng yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "mỹhào-hưngyên",
		"index" => 1
	],
	[
		"id" => "407",
		"area_id" => 31,
		"name" => "Phù Cừ - Hưng Yên",
		"name_filter" => "phù cừ - hưng yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "phùcừ-hưngyên",
		"index" => 1
	],
	[
		"id" => "408",
		"area_id" => 31,
		"name" => "Tiên Lữ - Hưng Yên",
		"name_filter" => "tiên lữ - hưng yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "tiênlữ-hưngyên",
		"index" => 1
	],
	[
		"id" => "409",
		"area_id" => 31,
		"name" => "Văn Giang - Hưng Yên",
		"name_filter" => "văn giang - hưng yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "văngiang-hưngyên",
		"index" => 1
	],
	[
		"id" => "410",
		"area_id" => 31,
		"name" => "Văn Lâm - Hưng Yên",
		"name_filter" => "văn lâm - hưng yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "vănlâm-hưngyên",
		"index" => 1
	],
	[
		"id" => "411",
		"area_id" => 31,
		"name" => "Yên Mỹ - Hưng Yên",
		"name_filter" => "yên mỹ - hưng yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênmỹ-hưngyên",
		"index" => 1
	],
	[
		"id" => "412",
		"area_id" => 32,
		"name" => "Cam Lâm - Khánh Hòa",
		"name_filter" => "cam lâm - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "camlâm-khánhhòa",
		"index" => 1
	],
	[
		"id" => "413",
		"area_id" => 32,
		"name" => "Cam Ranh - Khánh Hòa",
		"name_filter" => "cam ranh - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "camranh-khánhhòa",
		"index" => 1
	],
	[
		"id" => "413",
		"area_id" => 32,
		"name" => "Bình Ba - Cam Ranh - Khánh Hòa",
		"name_filter" => "bình ba - cam ranh - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhba-camranh-khánhhòa",
		"index" => 1
	],
	[
		"id" => "414",
		"area_id" => 32,
		"name" => "Diên Khánh - Khánh Hòa",
		"name_filter" => "diên khánh - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "diênkhánh-khánhhòa",
		"index" => 1
	],
	[
		"id" => "415",
		"area_id" => 32,
		"name" => "Khánh Sơn - Khánh Hòa",
		"name_filter" => "khánh sơn - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "khánhsơn-khánhhòa",
		"index" => 1
	],
	[
		"id" => "416",
		"area_id" => 32,
		"name" => "Khánh Vĩnh - Khánh Hòa",
		"name_filter" => "khánh vĩnh - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "khánhvĩnh-khánhhòa",
		"index" => 1
	],
	[
		"id" => "417",
		"area_id" => 32,
		"name" => "Nha Trang - Khánh Hòa",
		"name_filter" => "nha trang - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "nhatrang-khánhhòa",
		"index" => 1
	],
	[
		"id" => "418",
		"area_id" => 32,
		"name" => "Ninh Hòa - Khánh Hòa",
		"name_filter" => "ninh hòa - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "ninhhòa-khánhhòa",
		"index" => 1
	],
	[
		"id" => "418",
		"area_id" => 32,
		"name" => "Dốc Lết - Ninh Hòa - Khánh Hòa",
		"name_filter" => "dốc lết - ninh hòa - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "dốclết-ninhhòa-khánhhòa",
		"index" => 1
	],
	[
		"id" => "419",
		"area_id" => 32,
		"name" => "Trường Sa - Khánh Hòa",
		"name_filter" => "trường sa - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "trườngsa-khánhhòa",
		"index" => 1
	],
	[
		"id" => "420",
		"area_id" => 32,
		"name" => "Vạn Ninh - Khánh Hòa",
		"name_filter" => "vạn ninh - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "vạnninh-khánhhòa",
		"index" => 1
	],
	[
		"id" => "420",
		"area_id" => 32,
		"name" => "Đại Lãnh - Vạn Ninh - Khánh Hòa",
		"name_filter" => "đại lãnh - vạn ninh - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "đạilãnh-vạnninh-khánhhòa",
		"index" => 1
	],
	[
		"id" => "420",
		"area_id" => 32,
		"name" => "Đầm Môn - Vạn Ninh - Khánh Hòa",
		"name_filter" => "đầm môn - vạn ninh - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "đầmmôn-vạnninh-khánhhòa",
		"index" => 1
	],
	[
		"id" => "420",
		"area_id" => 32,
		"name" => "Điệp Sơn - Vạn Ninh - Khánh Hòa",
		"name_filter" => "điệp sơn - vạn ninh - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "điệpsơn-vạnninh-khánhhòa",
		"index" => 1
	],
	[
		"id" => "420",
		"area_id" => 32,
		"name" => "Vịnh Vân Phong - Vạn Ninh - Khánh Hòa",
		"name_filter" => "vịnh vân phong - vạn ninh - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "vịnhvânphong-vạnninh-khánhhòa",
		"index" => 1
	],
	[
		"id" => "420",
		"area_id" => 32,
		"name" => "Vạn Giã - Khánh Hòa",
		"name_filter" => "vạn giã - khánh hòa",
		"category" => "Quận - Huyện",
		"name_nospace" => "vạngiã-khánhhòa",
		"index" => 1
	],
	[
		"id" => "421",
		"area_id" => 33,
		"name" => "An Biên - Kiên Giang",
		"name_filter" => "an biên - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "anbiên-kiêngiang",
		"index" => 1
	],
	[
		"id" => "422",
		"area_id" => 33,
		"name" => "An Minh - Kiên Giang",
		"name_filter" => "an minh - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "anminh-kiêngiang",
		"index" => 1
	],
	[
		"id" => "423",
		"area_id" => 33,
		"name" => "Châu Thành - Kiên Giang",
		"name_filter" => "châu thành - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuthành-kiêngiang",
		"index" => 1
	],
	[
		"id" => "424",
		"area_id" => 33,
		"name" => "Giang Thành - Kiên Giang",
		"name_filter" => "giang thành - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "giangthành-kiêngiang",
		"index" => 1
	],
	[
		"id" => "425",
		"area_id" => 33,
		"name" => "Giồng Riềng - Kiên Giang",
		"name_filter" => "giồng riềng - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "giồngriềng-kiêngiang",
		"index" => 1
	],
	[
		"id" => "426",
		"area_id" => 33,
		"name" => "Gò Quao - Kiên Giang",
		"name_filter" => "gò quao - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "gòquao-kiêngiang",
		"index" => 1
	],
	[
		"id" => "427",
		"area_id" => 33,
		"name" => "Hà Tiên - Kiên Giang",
		"name_filter" => "hà tiên - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "hàtiên-kiêngiang",
		"index" => 1
	],
	[
		"id" => "428",
		"area_id" => 33,
		"name" => "Hòn Đất - Kiên Giang",
		"name_filter" => "hòn đất - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "hònđất-kiêngiang",
		"index" => 1
	],
	[
		"id" => "429",
		"area_id" => 33,
		"name" => "Kiên Hải - Kiên Giang",
		"name_filter" => "kiên hải - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "kiênhải-kiêngiang",
		"index" => 1
	],
	[
		"id" => "430",
		"area_id" => 33,
		"name" => "Kiên Lương - Kiên Giang",
		"name_filter" => "kiên lương - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "kiênlương-kiêngiang",
		"index" => 1
	],
	[
		"id" => "431",
		"area_id" => 33,
		"name" => "Phú Quốc - Kiên Giang",
		"name_filter" => "phú quốc - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúquốc-kiêngiang",
		"index" => 1
	],
	[
		"id" => "432",
		"area_id" => 33,
		"name" => "Rạch Giá - Kiên Giang",
		"name_filter" => "rạch giá - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "rạchgiá-kiêngiang",
		"index" => 1
	],
	[
		"id" => "432",
		"area_id" => 33,
		"name" => "Nam Du - Rạch Giá - Kiên Giang",
		"name_filter" => "nam du - rạch giá - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "namdu-rạchgiá-kiêngiang",
		"index" => 1
	],
	[
		"id" => "433",
		"area_id" => 33,
		"name" => "Tân Hiệp - Kiên Giang",
		"name_filter" => "tân hiệp - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânhiệp-kiêngiang",
		"index" => 1
	],
	[
		"id" => "434",
		"area_id" => 33,
		"name" => "U Minh Thượng - Kiên Giang",
		"name_filter" => "u minh thượng - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "uminhthượng-kiêngiang",
		"index" => 1
	],
	[
		"id" => "435",
		"area_id" => 33,
		"name" => "Vĩnh Thuận - Kiên Giang",
		"name_filter" => "vĩnh thuận - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhthuận-kiêngiang",
		"index" => 1
	],
	[
		"id" => "436",
		"area_id" => 34,
		"name" => "Đắk Glei - Kon Tum",
		"name_filter" => "đắk glei - kon tum",
		"category" => "Quận - Huyện",
		"name_nospace" => "đắkglei-kontum",
		"index" => 1
	],
	[
		"id" => "437",
		"area_id" => 34,
		"name" => "Đắk Hà - Kon Tum",
		"name_filter" => "đắk hà - kon tum",
		"category" => "Quận - Huyện",
		"name_nospace" => "đắkhà-kontum",
		"index" => 1
	],
	[
		"id" => "438",
		"area_id" => 34,
		"name" => "Đăk Tô - Kon Tum",
		"name_filter" => "đăk tô - kon tum",
		"category" => "Quận - Huyện",
		"name_nospace" => "đăktô-kontum",
		"index" => 1
	],
	[
		"id" => "439",
		"area_id" => 34,
		"name" => "Kon Plông - Kon Tum",
		"name_filter" => "kon plông - kon tum",
		"category" => "Quận - Huyện",
		"name_nospace" => "konplông-kontum",
		"index" => 1
	],
	[
		"id" => "440",
		"area_id" => 34,
		"name" => "Kon Rẫy - Kon Tum",
		"name_filter" => "kon rẫy - kon tum",
		"category" => "Quận - Huyện",
		"name_nospace" => "konrẫy-kontum",
		"index" => 1
	],
	[
		"id" => "441",
		"area_id" => 34,
		"name" => "Kon Tum - Kon Tum",
		"name_filter" => "kon tum - kon tum",
		"category" => "Quận - Huyện",
		"name_nospace" => "kontum-kontum",
		"index" => 1
	],
	[
		"id" => "442",
		"area_id" => 34,
		"name" => "Ngọc Hồi - Kon Tum",
		"name_filter" => "ngọc hồi - kon tum",
		"category" => "Quận - Huyện",
		"name_nospace" => "ngọchồi-kontum",
		"index" => 1
	],
	[
		"id" => "443",
		"area_id" => 34,
		"name" => "Sa Thầy - Kon Tum",
		"name_filter" => "sa thầy - kon tum",
		"category" => "Quận - Huyện",
		"name_nospace" => "sathầy-kontum",
		"index" => 1
	],
	[
		"id" => "444",
		"area_id" => 34,
		"name" => "Tu Mơ Rông - Kon Tum",
		"name_filter" => "tu mơ rông - kon tum",
		"category" => "Quận - Huyện",
		"name_nospace" => "tumơrông-kontum",
		"index" => 1
	],
	[
		"id" => "445",
		"area_id" => 35,
		"name" => "Lai Châu - Lai Châu",
		"name_filter" => "lai châu - lai châu",
		"category" => "Quận - Huyện",
		"name_nospace" => "laichâu-laichâu",
		"index" => 1
	],
	[
		"id" => "446",
		"area_id" => 35,
		"name" => "Mường Tè - Lai Châu",
		"name_filter" => "mường tè - lai châu",
		"category" => "Quận - Huyện",
		"name_nospace" => "mườngtè-laichâu",
		"index" => 1
	],
	[
		"id" => "447",
		"area_id" => 35,
		"name" => "Nậm Nhùn - Lai Châu",
		"name_filter" => "nậm nhùn - lai châu",
		"category" => "Quận - Huyện",
		"name_nospace" => "nậmnhùn-laichâu",
		"index" => 1
	],
	[
		"id" => "448",
		"area_id" => 35,
		"name" => "Phong Thổ - Lai Châu",
		"name_filter" => "phong thổ - lai châu",
		"category" => "Quận - Huyện",
		"name_nospace" => "phongthổ-laichâu",
		"index" => 1
	],
	[
		"id" => "449",
		"area_id" => 35,
		"name" => "Sìn Hồ - Lai Châu",
		"name_filter" => "sìn hồ - lai châu",
		"category" => "Quận - Huyện",
		"name_nospace" => "sìnhồ-laichâu",
		"index" => 1
	],
	[
		"id" => "450",
		"area_id" => 35,
		"name" => "Tam Đường - Lai Châu",
		"name_filter" => "tam đường - lai châu",
		"category" => "Quận - Huyện",
		"name_nospace" => "tamđường-laichâu",
		"index" => 1
	],
	[
		"id" => "451",
		"area_id" => 35,
		"name" => "Tân Uyên - Lai Châu",
		"name_filter" => "tân uyên - lai châu",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânuyên-laichâu",
		"index" => 1
	],
	[
		"id" => "452",
		"area_id" => 35,
		"name" => "Than Uyên - Lai Châu",
		"name_filter" => "than uyên - lai châu",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanuyên-laichâu",
		"index" => 1
	],
	[
		"id" => "453",
		"area_id" => 36,
		"name" => "Bảo Lâm - Lâm Đồng",
		"name_filter" => "bảo lâm - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "bảolâm-lâmđồng",
		"index" => 1
	],
	[
		"id" => "454",
		"area_id" => 36,
		"name" => "Bảo Lộc - Lâm Đồng",
		"name_filter" => "bảo lộc - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "bảolộc-lâmđồng",
		"index" => 1
	],
	[
		"id" => "455",
		"area_id" => 36,
		"name" => "Cát Tiên - Lâm Đồng",
		"name_filter" => "cát tiên - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "cáttiên-lâmđồng",
		"index" => 1
	],
	[
		"id" => "456",
		"area_id" => 36,
		"name" => "Di Linh - Lâm Đồng",
		"name_filter" => "di linh - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "dilinh-lâmđồng",
		"index" => 1
	],
	[
		"id" => "457",
		"area_id" => 36,
		"name" => "Đà Lạt - Lâm Đồng",
		"name_filter" => "đà lạt - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "đàlạt-lâmđồng",
		"index" => 1
	],
	[
		"id" => "458",
		"area_id" => 36,
		"name" => "Đạ Huoai - Lâm Đồng",
		"name_filter" => "đạ huoai - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "đạhuoai-lâmđồng",
		"index" => 1
	],
	[
		"id" => "459",
		"area_id" => 36,
		"name" => "Đạ Tẻh - Lâm Đồng",
		"name_filter" => "đạ tẻh - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "đạtẻh-lâmđồng",
		"index" => 1
	],
	[
		"id" => "460",
		"area_id" => 36,
		"name" => "Đam Rông - Lâm Đồng",
		"name_filter" => "đam rông - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "đamrông-lâmđồng",
		"index" => 1
	],
	[
		"id" => "461",
		"area_id" => 36,
		"name" => "Đơn Dương - Lâm Đồng",
		"name_filter" => "đơn dương - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "đơndương-lâmđồng",
		"index" => 1
	],
	[
		"id" => "462",
		"area_id" => 36,
		"name" => "Đức Trọng - Lâm Đồng",
		"name_filter" => "đức trọng - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "đứctrọng-lâmđồng",
		"index" => 1
	],
	[
		"id" => "463",
		"area_id" => 36,
		"name" => "Lạc Dương - Lâm Đồng",
		"name_filter" => "lạc dương - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "lạcdương-lâmđồng",
		"index" => 1
	],
	[
		"id" => "464",
		"area_id" => 36,
		"name" => "Lâm Hà - Lâm Đồng",
		"name_filter" => "lâm hà - lâm đồng",
		"category" => "Quận - Huyện",
		"name_nospace" => "lâmhà-lâmđồng",
		"index" => 1
	],
	[
		"id" => "465",
		"area_id" => 37,
		"name" => "Bắc Sơn - Lạng Sơn",
		"name_filter" => "bắc sơn - lạng sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắcsơn-lạngsơn",
		"index" => 1
	],
	[
		"id" => "466",
		"area_id" => 37,
		"name" => "Bình Gia - Lạng Sơn",
		"name_filter" => "bình gia - lạng sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhgia-lạngsơn",
		"index" => 1
	],
	[
		"id" => "467",
		"area_id" => 37,
		"name" => "Cao Lộc - Lạng Sơn",
		"name_filter" => "cao lộc - lạng sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "caolộc-lạngsơn",
		"index" => 1
	],
	[
		"id" => "468",
		"area_id" => 37,
		"name" => "Chi Lăng - Lạng Sơn",
		"name_filter" => "chi lăng - lạng sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "chilăng-lạngsơn",
		"index" => 1
	],
	[
		"id" => "469",
		"area_id" => 37,
		"name" => "Đình Lập - Lạng Sơn",
		"name_filter" => "đình lập - lạng sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "đìnhlập-lạngsơn",
		"index" => 1
	],
	[
		"id" => "470",
		"area_id" => 37,
		"name" => "Hữu Lũng - Lạng Sơn",
		"name_filter" => "hữu lũng - lạng sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "hữulũng-lạngsơn",
		"index" => 1
	],
	[
		"id" => "471",
		"area_id" => 37,
		"name" => "Lạng Sơn - Lạng Sơn",
		"name_filter" => "lạng sơn - lạng sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "lạngsơn-lạngsơn",
		"index" => 1
	],
	[
		"id" => "472",
		"area_id" => 37,
		"name" => "Lộc Bình - Lạng Sơn",
		"name_filter" => "lộc bình - lạng sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "lộcbình-lạngsơn",
		"index" => 1
	],
	[
		"id" => "473",
		"area_id" => 37,
		"name" => "Tràng Định - Lạng Sơn",
		"name_filter" => "tràng định - lạng sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "tràngđịnh-lạngsơn",
		"index" => 1
	],
	[
		"id" => "474",
		"area_id" => 37,
		"name" => "Vãn Lãng - Lạng Sơn",
		"name_filter" => "vãn lãng - lạng sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "vãnlãng-lạngsơn",
		"index" => 1
	],
	[
		"id" => "475",
		"area_id" => 37,
		"name" => "Văn Quan - Lạng Sơn",
		"name_filter" => "văn quan - lạng sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "vănquan-lạngsơn",
		"index" => 1
	],
	[
		"id" => "476",
		"area_id" => 38,
		"name" => "Bảo Thắng - Lào Cai",
		"name_filter" => "bảo thắng - lào cai",
		"category" => "Quận - Huyện",
		"name_nospace" => "bảothắng-làocai",
		"index" => 1
	],
	[
		"id" => "477",
		"area_id" => 38,
		"name" => "Bảo Yên - Lào Cai",
		"name_filter" => "bảo yên - lào cai",
		"category" => "Quận - Huyện",
		"name_nospace" => "bảoyên-làocai",
		"index" => 1
	],
	[
		"id" => "478",
		"area_id" => 38,
		"name" => "Bát Xát - Lào Cai",
		"name_filter" => "bát xát - lào cai",
		"category" => "Quận - Huyện",
		"name_nospace" => "bátxát-làocai",
		"index" => 1
	],
	[
		"id" => "479",
		"area_id" => 38,
		"name" => "Bắc Hà - Lào Cai",
		"name_filter" => "bắc hà - lào cai",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắchà-làocai",
		"index" => 1
	],
	[
		"id" => "480",
		"area_id" => 38,
		"name" => "Lào Cai - Lào Cai",
		"name_filter" => "lào cai - lào cai",
		"category" => "Quận - Huyện",
		"name_nospace" => "làocai-làocai",
		"index" => 1
	],
	[
		"id" => "481",
		"area_id" => 38,
		"name" => "Mường Khương - Lào Cai",
		"name_filter" => "mường khương - lào cai",
		"category" => "Quận - Huyện",
		"name_nospace" => "mườngkhương-làocai",
		"index" => 1
	],
	[
		"id" => "482",
		"area_id" => 38,
		"name" => "Sa Pa - Lào Cai",
		"name_filter" => "sa pa - lào cai",
		"category" => "Quận - Huyện",
		"name_nospace" => "sapa-làocai",
		"index" => 1
	],
	[
		"id" => "483",
		"area_id" => 38,
		"name" => "Si Ma Cai - Lào Cai",
		"name_filter" => "si ma cai - lào cai",
		"category" => "Quận - Huyện",
		"name_nospace" => "simacai-làocai",
		"index" => 1
	],
	[
		"id" => "484",
		"area_id" => 38,
		"name" => "Văn Bàn - Lào Cai",
		"name_filter" => "văn bàn - lào cai",
		"category" => "Quận - Huyện",
		"name_nospace" => "vănbàn-làocai",
		"index" => 1
	],
	[
		"id" => "485",
		"area_id" => 39,
		"name" => "Bến Lức - Long An",
		"name_filter" => "bến lức - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "bếnlức-longan",
		"index" => 1
	],
	[
		"id" => "486",
		"area_id" => 39,
		"name" => "Cần Đước - Long An",
		"name_filter" => "cần đước - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "cầnđước-longan",
		"index" => 1
	],
	[
		"id" => "487",
		"area_id" => 39,
		"name" => "Cần Giuộc - Long An",
		"name_filter" => "cần giuộc - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "cầngiuộc-longan",
		"index" => 1
	],
	[
		"id" => "488",
		"area_id" => 39,
		"name" => "Châu Thành - Long An",
		"name_filter" => "châu thành - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuthành-longan",
		"index" => 1
	],
	[
		"id" => "489",
		"area_id" => 39,
		"name" => "Đức Hòa - Long An",
		"name_filter" => "đức hòa - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "đứchòa-longan",
		"index" => 1
	],
	[
		"id" => "490",
		"area_id" => 39,
		"name" => "Đức Huệ - Long An",
		"name_filter" => "đức huệ - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "đứchuệ-longan",
		"index" => 1
	],
	[
		"id" => "491",
		"area_id" => 39,
		"name" => "Kiến Tường - Long An",
		"name_filter" => "kiến tường - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "kiếntường-longan",
		"index" => 1
	],
	[
		"id" => "492",
		"area_id" => 39,
		"name" => "Mộc Hóa - Long An",
		"name_filter" => "mộc hóa - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "mộchóa-longan",
		"index" => 1
	],
	[
		"id" => "493",
		"area_id" => 39,
		"name" => "Tân An - Long An",
		"name_filter" => "tân an - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânan-longan",
		"index" => 1
	],
	[
		"id" => "494",
		"area_id" => 39,
		"name" => "Tân Hưng - Long An",
		"name_filter" => "tân hưng - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânhưng-longan",
		"index" => 1
	],
	[
		"id" => "495",
		"area_id" => 39,
		"name" => "Tân Thạnh - Long An",
		"name_filter" => "tân thạnh - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânthạnh-longan",
		"index" => 1
	],
	[
		"id" => "496",
		"area_id" => 39,
		"name" => "Tân Trụ - Long An",
		"name_filter" => "tân trụ - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "tântrụ-longan",
		"index" => 1
	],
	[
		"id" => "497",
		"area_id" => 39,
		"name" => "Thạnh Hóa - Long An",
		"name_filter" => "thạnh hóa - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "thạnhhóa-longan",
		"index" => 1
	],
	[
		"id" => "498",
		"area_id" => 39,
		"name" => "Thủ Thừa - Long An",
		"name_filter" => "thủ thừa - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "thủthừa-longan",
		"index" => 1
	],
	[
		"id" => "499",
		"area_id" => 39,
		"name" => "Vĩnh Hưng - Long An",
		"name_filter" => "vĩnh hưng - long an",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhhưng-longan",
		"index" => 1
	],
	[
		"id" => "500",
		"area_id" => 40,
		"name" => "Giao Thủy - Nam Định",
		"name_filter" => "giao thủy - nam định",
		"category" => "Quận - Huyện",
		"name_nospace" => "giaothủy-namđịnh",
		"index" => 1
	],
	[
		"id" => "501",
		"area_id" => 40,
		"name" => "Hải Hậu - Nam Định",
		"name_filter" => "hải hậu - nam định",
		"category" => "Quận - Huyện",
		"name_nospace" => "hảihậu-namđịnh",
		"index" => 1
	],
	[
		"id" => "502",
		"area_id" => 40,
		"name" => "Mỹ Lộc - Nam Định",
		"name_filter" => "mỹ lộc - nam định",
		"category" => "Quận - Huyện",
		"name_nospace" => "mỹlộc-namđịnh",
		"index" => 1
	],
	[
		"id" => "503",
		"area_id" => 40,
		"name" => "Nam Định - Nam Định",
		"name_filter" => "nam định - nam định",
		"category" => "Quận - Huyện",
		"name_nospace" => "namđịnh-namđịnh",
		"index" => 1
	],
	[
		"id" => "504",
		"area_id" => 40,
		"name" => "Nam Trực - Nam Định",
		"name_filter" => "nam trực - nam định",
		"category" => "Quận - Huyện",
		"name_nospace" => "namtrực-namđịnh",
		"index" => 1
	],
	[
		"id" => "505",
		"area_id" => 40,
		"name" => "Nghĩa Hưng - Nam Định",
		"name_filter" => "nghĩa hưng - nam định",
		"category" => "Quận - Huyện",
		"name_nospace" => "nghĩahưng-namđịnh",
		"index" => 1
	],
	[
		"id" => "506",
		"area_id" => 40,
		"name" => "Trực Ninh - Nam Định",
		"name_filter" => "trực ninh - nam định",
		"category" => "Quận - Huyện",
		"name_nospace" => "trựcninh-namđịnh",
		"index" => 1
	],
	[
		"id" => "507",
		"area_id" => 40,
		"name" => "Vụ Bản - Nam Định",
		"name_filter" => "vụ bản - nam định",
		"category" => "Quận - Huyện",
		"name_nospace" => "vụbản-namđịnh",
		"index" => 1
	],
	[
		"id" => "508",
		"area_id" => 40,
		"name" => "Xuân Trường - Nam Định",
		"name_filter" => "xuân trường - nam định",
		"category" => "Quận - Huyện",
		"name_nospace" => "xuântrường-namđịnh",
		"index" => 1
	],
	[
		"id" => "509",
		"area_id" => 40,
		"name" => "Ý Yên - Nam Định",
		"name_filter" => "ý yên - nam định",
		"category" => "Quận - Huyện",
		"name_nospace" => "ýyên-namđịnh",
		"index" => 1
	],
	[
		"id" => "510",
		"area_id" => 41,
		"name" => "Anh Sơn - Nghệ An",
		"name_filter" => "anh sơn - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "anhsơn-nghệan",
		"index" => 1
	],
	[
		"id" => "511",
		"area_id" => 41,
		"name" => "Con Cuông - Nghệ An",
		"name_filter" => "con cuông - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "concuông-nghệan",
		"index" => 1
	],
	[
		"id" => "512",
		"area_id" => 41,
		"name" => "Cửa Lò - Nghệ An",
		"name_filter" => "cửa lò - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "cửalò-nghệan",
		"index" => 1
	],
	[
		"id" => "513",
		"area_id" => 41,
		"name" => "Diễn Châu - Nghệ An",
		"name_filter" => "diễn châu - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "diễnchâu-nghệan",
		"index" => 1
	],
	[
		"id" => "514",
		"area_id" => 41,
		"name" => "Đô Lương - Nghệ An",
		"name_filter" => "đô lương - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "đôlương-nghệan",
		"index" => 1
	],
	[
		"id" => "515",
		"area_id" => 41,
		"name" => "Hưng Nguyên - Nghệ An",
		"name_filter" => "hưng nguyên - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "hưngnguyên-nghệan",
		"index" => 1
	],
	[
		"id" => "516",
		"area_id" => 41,
		"name" => "Kỳ Sơn - Nghệ An",
		"name_filter" => "kỳ sơn - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "kỳsơn-nghệan",
		"index" => 1
	],
	[
		"id" => "517",
		"area_id" => 41,
		"name" => "Nam Đàn - Nghệ An",
		"name_filter" => "nam đàn - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "namđàn-nghệan",
		"index" => 1
	],
	[
		"id" => "518",
		"area_id" => 41,
		"name" => "Nghi Lộc - Nghệ An",
		"name_filter" => "nghi lộc - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "nghilộc-nghệan",
		"index" => 1
	],
	[
		"id" => "519",
		"area_id" => 41,
		"name" => "Nghĩa Đàn - Nghệ An",
		"name_filter" => "nghĩa đàn - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "nghĩađàn-nghệan",
		"index" => 1
	],
	[
		"id" => "520",
		"area_id" => 41,
		"name" => "Quế Phong - Nghệ An",
		"name_filter" => "quế phong - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "quếphong-nghệan",
		"index" => 1
	],
	[
		"id" => "521",
		"area_id" => 41,
		"name" => "Quỳ Châu - Nghệ An",
		"name_filter" => "quỳ châu - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "quỳchâu-nghệan",
		"index" => 1
	],
	[
		"id" => "522",
		"area_id" => 41,
		"name" => "Quỳ Hợp - Nghệ An",
		"name_filter" => "quỳ hợp - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "quỳhợp-nghệan",
		"index" => 1
	],
	[
		"id" => "523",
		"area_id" => 41,
		"name" => "Quỳnh Lưu - Nghệ An",
		"name_filter" => "quỳnh lưu - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "quỳnhlưu-nghệan",
		"index" => 1
	],
	[
		"id" => "524",
		"area_id" => 41,
		"name" => "Tân Kỳ - Nghệ An",
		"name_filter" => "tân kỳ - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânkỳ-nghệan",
		"index" => 1
	],
	[
		"id" => "525",
		"area_id" => 41,
		"name" => "Thái Hòa - Nghệ An",
		"name_filter" => "thái hòa - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "tháihòa-nghệan",
		"index" => 1
	],
	[
		"id" => "526",
		"area_id" => 41,
		"name" => "Thanh Chương - Nghệ An",
		"name_filter" => "thanh chương - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhchương-nghệan",
		"index" => 1
	],
	[
		"id" => "527",
		"area_id" => 41,
		"name" => "Tương Dương - Nghệ An",
		"name_filter" => "tương dương - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "tươngdương-nghệan",
		"index" => 1
	],
	[
		"id" => "528",
		"area_id" => 41,
		"name" => "Vinh - Nghệ An",
		"name_filter" => "vinh - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "vinh-nghệan",
		"index" => 1
	],
	[
		"id" => "529",
		"area_id" => 41,
		"name" => "Yên Thành - Nghệ An",
		"name_filter" => "yên thành - nghệ an",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênthành-nghệan",
		"index" => 1
	],
	[
		"id" => "530",
		"area_id" => 42,
		"name" => "Gia Viễn - Ninh Bình",
		"name_filter" => "gia viễn - ninh bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "giaviễn-ninhbình",
		"index" => 1
	],
	[
		"id" => "531",
		"area_id" => 42,
		"name" => "Hoa Lư - Ninh Bình",
		"name_filter" => "hoa lư - ninh bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "hoalư-ninhbình",
		"index" => 1
	],
	[
		"id" => "532",
		"area_id" => 42,
		"name" => "Kim Sơn - Ninh Bình",
		"name_filter" => "kim sơn - ninh bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "kimsơn-ninhbình",
		"index" => 1
	],
	[
		"id" => "533",
		"area_id" => 42,
		"name" => "Nho Quan - Ninh Bình",
		"name_filter" => "nho quan - ninh bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "nhoquan-ninhbình",
		"index" => 1
	],
	[
		"id" => "534",
		"area_id" => 42,
		"name" => "Ninh Bình - Ninh Bình",
		"name_filter" => "ninh bình - ninh bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "ninhbình-ninhbình",
		"index" => 1
	],
	[
		"id" => "535",
		"area_id" => 42,
		"name" => "Tam Điệp - Ninh Bình",
		"name_filter" => "tam điệp - ninh bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "tamđiệp-ninhbình",
		"index" => 1
	],
	[
		"id" => "536",
		"area_id" => 42,
		"name" => "Yên Khánh - Ninh Bình",
		"name_filter" => "yên khánh - ninh bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênkhánh-ninhbình",
		"index" => 1
	],
	[
		"id" => "537",
		"area_id" => 42,
		"name" => "Yên Mô - Ninh Bình",
		"name_filter" => "yên mô - ninh bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênmô-ninhbình",
		"index" => 1
	],
	[
		"id" => "538",
		"area_id" => 43,
		"name" => "Bác Ái - Ninh Thuận",
		"name_filter" => "bác ái - ninh thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "bácái-ninhthuận",
		"index" => 1
	],
	[
		"id" => "539",
		"area_id" => 43,
		"name" => "Ninh Hải - Ninh Thuận",
		"name_filter" => "ninh hải - ninh thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "ninhhải-ninhthuận",
		"index" => 1
	],
	[
		"id" => "539",
		"area_id" => 43,
		"name" => "Bình Hưng, Ninh Hải - Ninh Thuận",
		"name_filter" => "bình hưng, ninh hải - ninh thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhhưng,ninhhải-ninhthuận",
		"index" => 1
	],
	[
		"id" => "540",
		"area_id" => 43,
		"name" => "Ninh Phước - Ninh Thuận",
		"name_filter" => "ninh phước - ninh thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "ninhphước-ninhthuận",
		"index" => 1
	],
	[
		"id" => "541",
		"area_id" => 43,
		"name" => "Ninh Sơn - Ninh Thuận",
		"name_filter" => "ninh sơn - ninh thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "ninhsơn-ninhthuận",
		"index" => 1
	],
	[
		"id" => "542",
		"area_id" => 43,
		"name" => "Phan Rang - Tháp Chàm - Ninh Thuận",
		"name_filter" => "phan rang - tháp chàm - ninh thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "phanrang-thápchàm-ninhthuận",
		"index" => 1
	],
	[
		"id" => "543",
		"area_id" => 43,
		"name" => "Thuận Bắc - Ninh Thuận",
		"name_filter" => "thuận bắc - ninh thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "thuậnbắc-ninhthuận",
		"index" => 1
	],
	[
		"id" => "544",
		"area_id" => 43,
		"name" => "Thuận Nam - Ninh Thuận",
		"name_filter" => "thuận nam - ninh thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "thuậnnam-ninhthuận",
		"index" => 1
	],
	[
		"id" => "545",
		"area_id" => 44,
		"name" => "Cẩm Khê - Phú Thọ",
		"name_filter" => "cẩm khê - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "cẩmkhê-phúthọ",
		"index" => 1
	],
	[
		"id" => "546",
		"area_id" => 44,
		"name" => "Đoan Hùng - Phú Thọ",
		"name_filter" => "đoan hùng - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "đoanhùng-phúthọ",
		"index" => 1
	],
	[
		"id" => "547",
		"area_id" => 44,
		"name" => "Hạ Hòa - Phú Thọ",
		"name_filter" => "hạ hòa - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "hạhòa-phúthọ",
		"index" => 1
	],
	[
		"id" => "548",
		"area_id" => 44,
		"name" => "Lâm Thao - Phú Thọ",
		"name_filter" => "lâm thao - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "lâmthao-phúthọ",
		"index" => 1
	],
	[
		"id" => "549",
		"area_id" => 44,
		"name" => "Phú Thọ - Phú Thọ",
		"name_filter" => "phú thọ - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúthọ-phúthọ",
		"index" => 1
	],
	[
		"id" => "550",
		"area_id" => 44,
		"name" => "Phù Ninh - Phú Thọ",
		"name_filter" => "phù ninh - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "phùninh-phúthọ",
		"index" => 1
	],
	[
		"id" => "551",
		"area_id" => 44,
		"name" => "Tam Nông - Phú Thọ",
		"name_filter" => "tam nông - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "tamnông-phúthọ",
		"index" => 1
	],
	[
		"id" => "552",
		"area_id" => 44,
		"name" => "Tân Sơn - Phú Thọ",
		"name_filter" => "tân sơn - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânsơn-phúthọ",
		"index" => 1
	],
	[
		"id" => "553",
		"area_id" => 44,
		"name" => "Thanh Ba - Phú Thọ",
		"name_filter" => "thanh ba - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhba-phúthọ",
		"index" => 1
	],
	[
		"id" => "554",
		"area_id" => 44,
		"name" => "Thanh Sơn - Phú Thọ",
		"name_filter" => "thanh sơn - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhsơn-phúthọ",
		"index" => 1
	],
	[
		"id" => "555",
		"area_id" => 44,
		"name" => "Thanh Thủy - Phú Thọ",
		"name_filter" => "thanh thủy - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhthủy-phúthọ",
		"index" => 1
	],
	[
		"id" => "556",
		"area_id" => 44,
		"name" => "Việt Trì - Phú Thọ",
		"name_filter" => "việt trì - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "việttrì-phúthọ",
		"index" => 1
	],
	[
		"id" => "557",
		"area_id" => 44,
		"name" => "Yên Lập - Phú Thọ",
		"name_filter" => "yên lập - phú thọ",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênlập-phúthọ",
		"index" => 1
	],
	[
		"id" => "558",
		"area_id" => 45,
		"name" => "Đông Hòa - Phú Yên",
		"name_filter" => "đông hòa - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "đônghòa-phúyên",
		"index" => 1
	],
	[
		"id" => "559",
		"area_id" => 45,
		"name" => "Đồng Xuân - Phú Yên",
		"name_filter" => "đồng xuân - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "đồngxuân-phúyên",
		"index" => 1
	],
	[
		"id" => "560",
		"area_id" => 45,
		"name" => "Phú Hòa - Phú Yên",
		"name_filter" => "phú hòa - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúhòa-phúyên",
		"index" => 1
	],
	[
		"id" => "561",
		"area_id" => 45,
		"name" => "Sông Cầu - Phú Yên",
		"name_filter" => "sông cầu - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "sôngcầu-phúyên",
		"index" => 1
	],
	[
		"id" => "562",
		"area_id" => 45,
		"name" => "Sông Hinh - Phú Yên",
		"name_filter" => "sông hinh - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "sônghinh-phúyên",
		"index" => 1
	],
	[
		"id" => "563",
		"area_id" => 45,
		"name" => "Sơn Hòa - Phú Yên",
		"name_filter" => "sơn hòa - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "sơnhòa-phúyên",
		"index" => 1
	],
	[
		"id" => "564",
		"area_id" => 45,
		"name" => "Tây Hòa - Phú Yên",
		"name_filter" => "tây hòa - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "tâyhòa-phúyên",
		"index" => 1
	],
	[
		"id" => "565",
		"area_id" => 45,
		"name" => "Tuy An - Phú Yên",
		"name_filter" => "tuy an - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "tuyan-phúyên",
		"index" => 1
	],
	[
		"id" => "566",
		"area_id" => 45,
		"name" => "Tuy Hòa - Phú Yên",
		"name_filter" => "tuy hòa - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "tuyhòa-phúyên",
		"index" => 1
	],
	[
		"id" => "567",
		"area_id" => 46,
		"name" => "Bố Trạch - Quảng Bình",
		"name_filter" => "bố trạch - quảng bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "bốtrạch-quảngbình",
		"index" => 1
	],
	[
		"id" => "568",
		"area_id" => 46,
		"name" => "Đồng Hới - Quảng Bình",
		"name_filter" => "đồng hới - quảng bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "đồnghới-quảngbình",
		"index" => 1
	],
	[
		"id" => "569",
		"area_id" => 46,
		"name" => "Lệ Thủy - Quảng Bình",
		"name_filter" => "lệ thủy - quảng bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "lệthủy-quảngbình",
		"index" => 1
	],
	[
		"id" => "570",
		"area_id" => 46,
		"name" => "Minh Hóa - Quảng Bình",
		"name_filter" => "minh hóa - quảng bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "minhhóa-quảngbình",
		"index" => 1
	],
	[
		"id" => "571",
		"area_id" => 46,
		"name" => "Quảng Ninh - Quảng Bình",
		"name_filter" => "quảng ninh - quảng bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảngninh-quảngbình",
		"index" => 1
	],
	[
		"id" => "572",
		"area_id" => 46,
		"name" => "Quảng Trạch - Quảng Bình",
		"name_filter" => "quảng trạch - quảng bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảngtrạch-quảngbình",
		"index" => 1
	],
	[
		"id" => "573",
		"area_id" => 46,
		"name" => "Tuyên Hóa - Quảng Bình",
		"name_filter" => "tuyên hóa - quảng bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "tuyênhóa-quảngbình",
		"index" => 1
	],
	[
		"id" => "574",
		"area_id" => 47,
		"name" => "Bắc Trà My - Quảng Nam",
		"name_filter" => "bắc trà my - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắctràmy-quảngnam",
		"index" => 1
	],
	[
		"id" => "575",
		"area_id" => 47,
		"name" => "Duy Xuyên - Quảng Nam",
		"name_filter" => "duy xuyên - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "duyxuyên-quảngnam",
		"index" => 1
	],
	[
		"id" => "576",
		"area_id" => 47,
		"name" => "Đại Lộc - Quảng Nam",
		"name_filter" => "đại lộc - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "đạilộc-quảngnam",
		"index" => 1
	],
	[
		"id" => "577",
		"area_id" => 47,
		"name" => "Điện Bàn - Quảng Nam",
		"name_filter" => "điện bàn - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "điệnbàn-quảngnam",
		"index" => 1
	],
	[
		"id" => "578",
		"area_id" => 47,
		"name" => "Đông Giang - Quảng Nam",
		"name_filter" => "đông giang - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "đônggiang-quảngnam",
		"index" => 1
	],
	[
		"id" => "579",
		"area_id" => 47,
		"name" => "Hiệp Đức - Quảng Nam",
		"name_filter" => "hiệp đức - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "hiệpđức-quảngnam",
		"index" => 1
	],
	[
		"id" => "580",
		"area_id" => 47,
		"name" => "Hội An - Quảng Nam",
		"name_filter" => "hội an - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "hộian-quảngnam",
		"index" => 1
	],
	[
		"id" => "581",
		"area_id" => 47,
		"name" => "Nam Giang - Quảng Nam",
		"name_filter" => "nam giang - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "namgiang-quảngnam",
		"index" => 1
	],
	[
		"id" => "582",
		"area_id" => 47,
		"name" => "Nam Trà My - Quảng Nam",
		"name_filter" => "nam trà my - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "namtràmy-quảngnam",
		"index" => 1
	],
	[
		"id" => "583",
		"area_id" => 47,
		"name" => "Nông Sơn - Quảng Nam",
		"name_filter" => "nông sơn - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "nôngsơn-quảngnam",
		"index" => 1
	],
	[
		"id" => "584",
		"area_id" => 47,
		"name" => "Núi Thành - Quảng Nam",
		"name_filter" => "núi thành - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "núithành-quảngnam",
		"index" => 1
	],
	[
		"id" => "585",
		"area_id" => 47,
		"name" => "Phú Ninh - Quảng Nam",
		"name_filter" => "phú ninh - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúninh-quảngnam",
		"index" => 1
	],
	[
		"id" => "586",
		"area_id" => 47,
		"name" => "Phước Sơn - Quảng Nam",
		"name_filter" => "phước sơn - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "phướcsơn-quảngnam",
		"index" => 1
	],
	[
		"id" => "587",
		"area_id" => 47,
		"name" => "Quế Sơn - Quảng Nam",
		"name_filter" => "quế sơn - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "quếsơn-quảngnam",
		"index" => 1
	],
	[
		"id" => "588",
		"area_id" => 47,
		"name" => "Tam Kỳ - Quảng Nam",
		"name_filter" => "tam kỳ - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "tamkỳ-quảngnam",
		"index" => 1
	],
	[
		"id" => "589",
		"area_id" => 47,
		"name" => "Tây Giang - Quảng Nam",
		"name_filter" => "tây giang - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "tâygiang-quảngnam",
		"index" => 1
	],
	[
		"id" => "590",
		"area_id" => 47,
		"name" => "Thăng Bình - Quảng Nam",
		"name_filter" => "thăng bình - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "thăngbình-quảngnam",
		"index" => 1
	],
	[
		"id" => "591",
		"area_id" => 47,
		"name" => "Tiên Phước - Quảng Nam",
		"name_filter" => "tiên phước - quảng nam",
		"category" => "Quận - Huyện",
		"name_nospace" => "tiênphước-quảngnam",
		"index" => 1
	],
	[
		"id" => "592",
		"area_id" => 48,
		"name" => "Ba Tơ - Quảng Ngãi",
		"name_filter" => "ba tơ - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "batơ-quảngngãi",
		"index" => 1
	],
	[
		"id" => "593",
		"area_id" => 48,
		"name" => "Bình Sơn - Quảng Ngãi",
		"name_filter" => "bình sơn - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhsơn-quảngngãi",
		"index" => 1
	],
	[
		"id" => "594",
		"area_id" => 48,
		"name" => "Đức Phổ - Quảng Ngãi",
		"name_filter" => "đức phổ - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "đứcphổ-quảngngãi",
		"index" => 1
	],
	[
		"id" => "595",
		"area_id" => 48,
		"name" => "Lý Sơn - Quảng Ngãi",
		"name_filter" => "lý sơn - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "lýsơn-quảngngãi",
		"index" => 1
	],
	[
		"id" => "596",
		"area_id" => 48,
		"name" => "Minh Long - Quảng Ngãi",
		"name_filter" => "minh long - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "minhlong-quảngngãi",
		"index" => 1
	],
	[
		"id" => "597",
		"area_id" => 48,
		"name" => "Mộ Đức - Quảng Ngãi",
		"name_filter" => "mộ đức - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "mộđức-quảngngãi",
		"index" => 1
	],
	[
		"id" => "598",
		"area_id" => 48,
		"name" => "Nghĩa Hành - Quảng Ngãi",
		"name_filter" => "nghĩa hành - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "nghĩahành-quảngngãi",
		"index" => 1
	],
	[
		"id" => "599",
		"area_id" => 48,
		"name" => "Quảng Ngãi - Quảng Ngãi",
		"name_filter" => "quảng ngãi - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảngngãi-quảngngãi",
		"index" => 1
	],
	[
		"id" => "600",
		"area_id" => 48,
		"name" => "Sơn Hà - Quảng Ngãi",
		"name_filter" => "sơn hà - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "sơnhà-quảngngãi",
		"index" => 1
	],
	[
		"id" => "601",
		"area_id" => 48,
		"name" => "Sơn Tây - Quảng Ngãi",
		"name_filter" => "sơn tây - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "sơntây-quảngngãi",
		"index" => 1
	],
	[
		"id" => "602",
		"area_id" => 48,
		"name" => "Sơn Tịnh - Quảng Ngãi",
		"name_filter" => "sơn tịnh - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "sơntịnh-quảngngãi",
		"index" => 1
	],
	[
		"id" => "603",
		"area_id" => 48,
		"name" => "Tây Trà - Quảng Ngãi",
		"name_filter" => "tây trà - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "tâytrà-quảngngãi",
		"index" => 1
	],
	[
		"id" => "604",
		"area_id" => 48,
		"name" => "Trà Bồng - Quảng Ngãi",
		"name_filter" => "trà bồng - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "tràbồng-quảngngãi",
		"index" => 1
	],
	[
		"id" => "605",
		"area_id" => 48,
		"name" => "Tư Nghĩa - Quảng Ngãi",
		"name_filter" => "tư nghĩa - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "tưnghĩa-quảngngãi",
		"index" => 1
	],
	[
		"id" => "606",
		"area_id" => 49,
		"name" => "Ba Chẽ - Quảng Ninh",
		"name_filter" => "ba chẽ - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "bachẽ-quảngninh",
		"index" => 1
	],
	[
		"id" => "607",
		"area_id" => 49,
		"name" => "Bình Liêu - Quảng Ninh",
		"name_filter" => "bình liêu - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhliêu-quảngninh",
		"index" => 1
	],
	[
		"id" => "608",
		"area_id" => 49,
		"name" => "Cẩm Phả - Quảng Ninh",
		"name_filter" => "cẩm phả - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "cẩmphả-quảngninh",
		"index" => 1
	],
	[
		"id" => "609",
		"area_id" => 49,
		"name" => "Cô Tô - Quảng Ninh",
		"name_filter" => "cô tô - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "côtô-quảngninh",
		"index" => 1
	],
	[
		"id" => "610",
		"area_id" => 49,
		"name" => "Đầm Hà - Quảng Ninh",
		"name_filter" => "đầm hà - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "đầmhà-quảngninh",
		"index" => 1
	],
	[
		"id" => "611",
		"area_id" => 49,
		"name" => "Đông Triều - Quảng Ninh",
		"name_filter" => "đông triều - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "đôngtriều-quảngninh",
		"index" => 1
	],
	[
		"id" => "612",
		"area_id" => 49,
		"name" => "Hạ Long - Quảng Ninh",
		"name_filter" => "hạ long - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "hạlong-quảngninh",
		"index" => 1
	],
	[
		"id" => "613",
		"area_id" => 49,
		"name" => "Hải Hà - Quảng Ninh",
		"name_filter" => "hải hà - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "hảihà-quảngninh",
		"index" => 1
	],
	[
		"id" => "614",
		"area_id" => 49,
		"name" => "Hoành Bồ - Quảng Ninh",
		"name_filter" => "hoành bồ - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "hoànhbồ-quảngninh",
		"index" => 1
	],
	[
		"id" => "615",
		"area_id" => 49,
		"name" => "Móng Cái - Quảng Ninh",
		"name_filter" => "móng cái - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "móngcái-quảngninh",
		"index" => 1
	],
	[
		"id" => "616",
		"area_id" => 49,
		"name" => "Quảng Yên - Quảng Ninh",
		"name_filter" => "quảng yên - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảngyên-quảngninh",
		"index" => 1
	],
	[
		"id" => "617",
		"area_id" => 49,
		"name" => "Tiên Yên - Quảng Ninh",
		"name_filter" => "tiên yên - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "tiênyên-quảngninh",
		"index" => 1
	],
	[
		"id" => "618",
		"area_id" => 49,
		"name" => "Uông Bí - Quảng Ninh",
		"name_filter" => "uông bí - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "uôngbí-quảngninh",
		"index" => 1
	],
	[
		"id" => "619",
		"area_id" => 49,
		"name" => "Vân Đồn - Quảng Ninh",
		"name_filter" => "vân đồn - quảng ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "vânđồn-quảngninh",
		"index" => 1
	],
	[
		"id" => "620",
		"area_id" => 50,
		"name" => "Cam Lộ - Quảng Trị",
		"name_filter" => "cam lộ - quảng trị",
		"category" => "Quận - Huyện",
		"name_nospace" => "camlộ-quảngtrị",
		"index" => 1
	],
	[
		"id" => "621",
		"area_id" => 50,
		"name" => "Cồn Cỏ - Quảng Trị",
		"name_filter" => "cồn cỏ - quảng trị",
		"category" => "Quận - Huyện",
		"name_nospace" => "cồncỏ-quảngtrị",
		"index" => 1
	],
	[
		"id" => "622",
		"area_id" => 50,
		"name" => "Đa Krông - Quảng Trị",
		"name_filter" => "đa krông - quảng trị",
		"category" => "Quận - Huyện",
		"name_nospace" => "đakrông-quảngtrị",
		"index" => 1
	],
	[
		"id" => "623",
		"area_id" => 50,
		"name" => "Đông Hà - Quảng Trị",
		"name_filter" => "đông hà - quảng trị",
		"category" => "Quận - Huyện",
		"name_nospace" => "đônghà-quảngtrị",
		"index" => 1
	],
	[
		"id" => "624",
		"area_id" => 50,
		"name" => "Gio Linh - Quảng Trị",
		"name_filter" => "gio linh - quảng trị",
		"category" => "Quận - Huyện",
		"name_nospace" => "giolinh-quảngtrị",
		"index" => 1
	],
	[
		"id" => "625",
		"area_id" => 50,
		"name" => "Hải Lăng - Quảng Trị",
		"name_filter" => "hải lăng - quảng trị",
		"category" => "Quận - Huyện",
		"name_nospace" => "hảilăng-quảngtrị",
		"index" => 1
	],
	[
		"id" => "626",
		"area_id" => 50,
		"name" => "Hướng Hóa - Quảng Trị",
		"name_filter" => "hướng hóa - quảng trị",
		"category" => "Quận - Huyện",
		"name_nospace" => "hướnghóa-quảngtrị",
		"index" => 1
	],
	[
		"id" => "627",
		"area_id" => 50,
		"name" => "Quảng Trị - Quảng Trị",
		"name_filter" => "quảng trị - quảng trị",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảngtrị-quảngtrị",
		"index" => 1
	],
	[
		"id" => "628",
		"area_id" => 50,
		"name" => "Triệu Phong - Quảng Trị",
		"name_filter" => "triệu phong - quảng trị",
		"category" => "Quận - Huyện",
		"name_nospace" => "triệuphong-quảngtrị",
		"index" => 1
	],
	[
		"id" => "629",
		"area_id" => 50,
		"name" => "Vĩnh Linh - Quảng Trị",
		"name_filter" => "vĩnh linh - quảng trị",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhlinh-quảngtrị",
		"index" => 1
	],
	[
		"id" => "630",
		"area_id" => 51,
		"name" => "Châu Thành - Sóc Trăng",
		"name_filter" => "châu thành - sóc trăng",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuthành-sóctrăng",
		"index" => 1
	],
	[
		"id" => "631",
		"area_id" => 51,
		"name" => "Cù Lao Dung - Sóc Trăng",
		"name_filter" => "cù lao dung - sóc trăng",
		"category" => "Quận - Huyện",
		"name_nospace" => "cùlaodung-sóctrăng",
		"index" => 1
	],
	[
		"id" => "632",
		"area_id" => 51,
		"name" => "Kế Sách - Sóc Trăng",
		"name_filter" => "kế sách - sóc trăng",
		"category" => "Quận - Huyện",
		"name_nospace" => "kếsách-sóctrăng",
		"index" => 1
	],
	[
		"id" => "633",
		"area_id" => 51,
		"name" => "Long Phú - Sóc Trăng",
		"name_filter" => "long phú - sóc trăng",
		"category" => "Quận - Huyện",
		"name_nospace" => "longphú-sóctrăng",
		"index" => 1
	],
	[
		"id" => "634",
		"area_id" => 51,
		"name" => "Mỹ Tú - Sóc Trăng",
		"name_filter" => "mỹ tú - sóc trăng",
		"category" => "Quận - Huyện",
		"name_nospace" => "mỹtú-sóctrăng",
		"index" => 1
	],
	[
		"id" => "635",
		"area_id" => 51,
		"name" => "Mỹ Xuyên - Sóc Trăng",
		"name_filter" => "mỹ xuyên - sóc trăng",
		"category" => "Quận - Huyện",
		"name_nospace" => "mỹxuyên-sóctrăng",
		"index" => 1
	],
	[
		"id" => "636",
		"area_id" => 51,
		"name" => "Ngã Năm - Sóc Trăng",
		"name_filter" => "ngã năm - sóc trăng",
		"category" => "Quận - Huyện",
		"name_nospace" => "ngãnăm-sóctrăng",
		"index" => 1
	],
	[
		"id" => "637",
		"area_id" => 51,
		"name" => "Sóc Trăng - Sóc Trăng",
		"name_filter" => "sóc trăng - sóc trăng",
		"category" => "Quận - Huyện",
		"name_nospace" => "sóctrăng-sóctrăng",
		"index" => 1
	],
	[
		"id" => "638",
		"area_id" => 51,
		"name" => "Thạnh Trị - Sóc Trăng",
		"name_filter" => "thạnh trị - sóc trăng",
		"category" => "Quận - Huyện",
		"name_nospace" => "thạnhtrị-sóctrăng",
		"index" => 1
	],
	[
		"id" => "639",
		"area_id" => 51,
		"name" => "Trần Đề - Sóc Trăng",
		"name_filter" => "trần đề - sóc trăng",
		"category" => "Quận - Huyện",
		"name_nospace" => "trầnđề-sóctrăng",
		"index" => 1
	],
	[
		"id" => "640",
		"area_id" => 51,
		"name" => "Vĩnh Châu - Sóc Trăng",
		"name_filter" => "vĩnh châu - sóc trăng",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhchâu-sóctrăng",
		"index" => 1
	],
	[
		"id" => "641",
		"area_id" => 52,
		"name" => "Bắc Yên - Sơn La",
		"name_filter" => "bắc yên - sơn la",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắcyên-sơnla",
		"index" => 1
	],
	[
		"id" => "642",
		"area_id" => 52,
		"name" => "Mai Sơn - Sơn La",
		"name_filter" => "mai sơn - sơn la",
		"category" => "Quận - Huyện",
		"name_nospace" => "maisơn-sơnla",
		"index" => 1
	],
	[
		"id" => "643",
		"area_id" => 52,
		"name" => "Mộc Châu - Sơn La",
		"name_filter" => "mộc châu - sơn la",
		"category" => "Quận - Huyện",
		"name_nospace" => "mộcchâu-sơnla",
		"index" => 1
	],
	[
		"id" => "644",
		"area_id" => 52,
		"name" => "Mường La - Sơn La",
		"name_filter" => "mường la - sơn la",
		"category" => "Quận - Huyện",
		"name_nospace" => "mườngla-sơnla",
		"index" => 1
	],
	[
		"id" => "645",
		"area_id" => 52,
		"name" => "Phù Yên - Sơn La",
		"name_filter" => "phù yên - sơn la",
		"category" => "Quận - Huyện",
		"name_nospace" => "phùyên-sơnla",
		"index" => 1
	],
	[
		"id" => "646",
		"area_id" => 52,
		"name" => "Quỳnh Nhai - Sơn La",
		"name_filter" => "quỳnh nhai - sơn la",
		"category" => "Quận - Huyện",
		"name_nospace" => "quỳnhnhai-sơnla",
		"index" => 1
	],
	[
		"id" => "647",
		"area_id" => 52,
		"name" => "Sông Mã - Sơn La",
		"name_filter" => "sông mã - sơn la",
		"category" => "Quận - Huyện",
		"name_nospace" => "sôngmã-sơnla",
		"index" => 1
	],
	[
		"id" => "648",
		"area_id" => 52,
		"name" => "Sốp Cộp - Sơn La",
		"name_filter" => "sốp cộp - sơn la",
		"category" => "Quận - Huyện",
		"name_nospace" => "sốpcộp-sơnla",
		"index" => 1
	],
	[
		"id" => "649",
		"area_id" => 52,
		"name" => "Sơn La - Sơn La",
		"name_filter" => "sơn la - sơn la",
		"category" => "Quận - Huyện",
		"name_nospace" => "sơnla-sơnla",
		"index" => 1
	],
	[
		"id" => "650",
		"area_id" => 52,
		"name" => "Thuận Châu - Sơn La",
		"name_filter" => "thuận châu - sơn la",
		"category" => "Quận - Huyện",
		"name_nospace" => "thuậnchâu-sơnla",
		"index" => 1
	],
	[
		"id" => "651",
		"area_id" => 52,
		"name" => "Yên Châu - Sơn La",
		"name_filter" => "yên châu - sơn la",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênchâu-sơnla",
		"index" => 1
	],
	[
		"id" => "652",
		"area_id" => 53,
		"name" => "Bến Cầu - Tây Ninh",
		"name_filter" => "bến cầu - tây ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "bếncầu-tâyninh",
		"index" => 1
	],
	[
		"id" => "653",
		"area_id" => 53,
		"name" => "Châu Thành - Tây Ninh",
		"name_filter" => "châu thành - tây ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuthành-tâyninh",
		"index" => 1
	],
	[
		"id" => "654",
		"area_id" => 53,
		"name" => "Dương Minh Châu - Tây Ninh",
		"name_filter" => "dương minh châu - tây ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "dươngminhchâu-tâyninh",
		"index" => 1
	],
	[
		"id" => "655",
		"area_id" => 53,
		"name" => "Gò Dầu - Tây Ninh",
		"name_filter" => "gò dầu - tây ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "gòdầu-tâyninh",
		"index" => 1
	],
	[
		"id" => "656",
		"area_id" => 53,
		"name" => "Hòa Thành - Tây Ninh",
		"name_filter" => "hòa thành - tây ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "hòathành-tâyninh",
		"index" => 1
	],
	[
		"id" => "657",
		"area_id" => 53,
		"name" => "Tân Biên - Tây Ninh",
		"name_filter" => "tân biên - tây ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânbiên-tâyninh",
		"index" => 1
	],
	[
		"id" => "658",
		"area_id" => 53,
		"name" => "Tân Châu - Tây Ninh",
		"name_filter" => "tân châu - tây ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânchâu-tâyninh",
		"index" => 1
	],
	[
		"id" => "659",
		"area_id" => 53,
		"name" => "Tây Ninh - Tây Ninh",
		"name_filter" => "tây ninh - tây ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "tâyninh-tâyninh",
		"index" => 1
	],
	[
		"id" => "660",
		"area_id" => 53,
		"name" => "Trảng Bàng - Tây Ninh",
		"name_filter" => "trảng bàng - tây ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "trảngbàng-tâyninh",
		"index" => 1
	],
	[
		"id" => "661",
		"area_id" => 54,
		"name" => "Đông Hưng - Thái Bình",
		"name_filter" => "đông hưng - thái bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "đônghưng-tháibình",
		"index" => 1
	],
	[
		"id" => "662",
		"area_id" => 54,
		"name" => "Hưng Hà - Thái Bình",
		"name_filter" => "hưng hà - thái bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "hưnghà-tháibình",
		"index" => 1
	],
	[
		"id" => "663",
		"area_id" => 54,
		"name" => "Kiến Xương - Thái Bình",
		"name_filter" => "kiến xương - thái bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "kiếnxương-tháibình",
		"index" => 1
	],
	[
		"id" => "664",
		"area_id" => 54,
		"name" => "Quỳnh Phụ - Thái Bình",
		"name_filter" => "quỳnh phụ - thái bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "quỳnhphụ-tháibình",
		"index" => 1
	],
	[
		"id" => "665",
		"area_id" => 54,
		"name" => "Thái Bình - Thái Bình",
		"name_filter" => "thái bình - thái bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "tháibình-tháibình",
		"index" => 1
	],
	[
		"id" => "666",
		"area_id" => 54,
		"name" => "Thái Thụy - Thái Bình",
		"name_filter" => "thái thụy - thái bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "tháithụy-tháibình",
		"index" => 1
	],
	[
		"id" => "667",
		"area_id" => 54,
		"name" => "Tiền Hải - Thái Bình",
		"name_filter" => "tiền hải - thái bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "tiềnhải-tháibình",
		"index" => 1
	],
	[
		"id" => "668",
		"area_id" => 54,
		"name" => "Vũ Thư - Thái Bình",
		"name_filter" => "vũ thư - thái bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "vũthư-tháibình",
		"index" => 1
	],
	[
		"id" => "669",
		"area_id" => 55,
		"name" => "Đại Từ - Thái Nguyên",
		"name_filter" => "đại từ - thái nguyên",
		"category" => "Quận - Huyện",
		"name_nospace" => "đạitừ-tháinguyên",
		"index" => 1
	],
	[
		"id" => "670",
		"area_id" => 55,
		"name" => "Định Hóa - Thái Nguyên",
		"name_filter" => "định hóa - thái nguyên",
		"category" => "Quận - Huyện",
		"name_nospace" => "địnhhóa-tháinguyên",
		"index" => 1
	],
	[
		"id" => "671",
		"area_id" => 55,
		"name" => "Đồng Hỷ - Thái Nguyên",
		"name_filter" => "đồng hỷ - thái nguyên",
		"category" => "Quận - Huyện",
		"name_nospace" => "đồnghỷ-tháinguyên",
		"index" => 1
	],
	[
		"id" => "672",
		"area_id" => 55,
		"name" => "Phổ Yên - Thái Nguyên",
		"name_filter" => "phổ yên - thái nguyên",
		"category" => "Quận - Huyện",
		"name_nospace" => "phổyên-tháinguyên",
		"index" => 1
	],
	[
		"id" => "673",
		"area_id" => 55,
		"name" => "Phú Bình - Thái Nguyên",
		"name_filter" => "phú bình - thái nguyên",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúbình-tháinguyên",
		"index" => 1
	],
	[
		"id" => "674",
		"area_id" => 55,
		"name" => "Phú Lương - Thái Nguyên",
		"name_filter" => "phú lương - thái nguyên",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúlương-tháinguyên",
		"index" => 1
	],
	[
		"id" => "675",
		"area_id" => 55,
		"name" => "Sông Công - Thái Nguyên",
		"name_filter" => "sông công - thái nguyên",
		"category" => "Quận - Huyện",
		"name_nospace" => "sôngcông-tháinguyên",
		"index" => 1
	],
	[
		"id" => "676",
		"area_id" => 55,
		"name" => "Thái Nguyên - Thái Nguyên",
		"name_filter" => "thái nguyên - thái nguyên",
		"category" => "Quận - Huyện",
		"name_nospace" => "tháinguyên-tháinguyên",
		"index" => 1
	],
	[
		"id" => "677",
		"area_id" => 55,
		"name" => "Võ Nhai - Thái Nguyên",
		"name_filter" => "võ nhai - thái nguyên",
		"category" => "Quận - Huyện",
		"name_nospace" => "võnhai-tháinguyên",
		"index" => 1
	],
	[
		"id" => "678",
		"area_id" => 56,
		"name" => "Bá Thước - Thanh Hóa",
		"name_filter" => "bá thước - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "báthước-thanhhóa",
		"index" => 1
	],
	[
		"id" => "679",
		"area_id" => 56,
		"name" => "Bỉm Sơn - Thanh Hóa",
		"name_filter" => "bỉm sơn - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "bỉmsơn-thanhhóa",
		"index" => 1
	],
	[
		"id" => "680",
		"area_id" => 56,
		"name" => "Cẩm Thủy - Thanh Hóa",
		"name_filter" => "cẩm thủy - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "cẩmthủy-thanhhóa",
		"index" => 1
	],
	[
		"id" => "681",
		"area_id" => 56,
		"name" => "Đông Sơn - Thanh Hóa",
		"name_filter" => "đông sơn - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "đôngsơn-thanhhóa",
		"index" => 1
	],
	[
		"id" => "682",
		"area_id" => 56,
		"name" => "Hà Trung - Thanh Hóa",
		"name_filter" => "hà trung - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "hàtrung-thanhhóa",
		"index" => 1
	],
	[
		"id" => "683",
		"area_id" => 56,
		"name" => "Hậu Lộc - Thanh Hóa",
		"name_filter" => "hậu lộc - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "hậulộc-thanhhóa",
		"index" => 1
	],
	[
		"id" => "684",
		"area_id" => 56,
		"name" => "Hoằng Hóa - Thanh Hóa",
		"name_filter" => "hoằng hóa - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "hoằnghóa-thanhhóa",
		"index" => 1
	],
	[
		"id" => "685",
		"area_id" => 56,
		"name" => "Lang Chánh - Thanh Hóa",
		"name_filter" => "lang chánh - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "langchánh-thanhhóa",
		"index" => 1
	],
	[
		"id" => "686",
		"area_id" => 56,
		"name" => "Mường Lát - Thanh Hóa",
		"name_filter" => "mường lát - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "mườnglát-thanhhóa",
		"index" => 1
	],
	[
		"id" => "687",
		"area_id" => 56,
		"name" => "Nga Sơn - Thanh Hóa",
		"name_filter" => "nga sơn - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "ngasơn-thanhhóa",
		"index" => 1
	],
	[
		"id" => "688",
		"area_id" => 56,
		"name" => "Ngọc Lặc - Thanh Hóa",
		"name_filter" => "ngọc lặc - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "ngọclặc-thanhhóa",
		"index" => 1
	],
	[
		"id" => "689",
		"area_id" => 56,
		"name" => "Như Thanh - Thanh Hóa",
		"name_filter" => "như thanh - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "nhưthanh-thanhhóa",
		"index" => 1
	],
	[
		"id" => "690",
		"area_id" => 56,
		"name" => "Như Xuân - Thanh Hóa",
		"name_filter" => "như xuân - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "nhưxuân-thanhhóa",
		"index" => 1
	],
	[
		"id" => "691",
		"area_id" => 56,
		"name" => "Nông Cống - Thanh Hóa",
		"name_filter" => "nông cống - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "nôngcống-thanhhóa",
		"index" => 1
	],
	[
		"id" => "692",
		"area_id" => 56,
		"name" => "Quan Hóa - Thanh Hóa",
		"name_filter" => "quan hóa - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "quanhóa-thanhhóa",
		"index" => 1
	],
	[
		"id" => "693",
		"area_id" => 56,
		"name" => "Quan Sơn - Thanh Hóa",
		"name_filter" => "quan sơn - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "quansơn-thanhhóa",
		"index" => 1
	],
	[
		"id" => "694",
		"area_id" => 56,
		"name" => "Quảng Xương - Thanh Hóa",
		"name_filter" => "quảng xương - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảngxương-thanhhóa",
		"index" => 1
	],
	[
		"id" => "695",
		"area_id" => 56,
		"name" => "Sầm Sơn - Thanh Hóa",
		"name_filter" => "sầm sơn - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "sầmsơn-thanhhóa",
		"index" => 1
	],
	[
		"id" => "696",
		"area_id" => 56,
		"name" => "Thạch Thành - Thanh Hóa",
		"name_filter" => "thạch thành - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "thạchthành-thanhhóa",
		"index" => 1
	],
	[
		"id" => "697",
		"area_id" => 56,
		"name" => "Thanh Hóa - Thanh Hóa",
		"name_filter" => "thanh hóa - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhhóa-thanhhóa",
		"index" => 1
	],
	[
		"id" => "698",
		"area_id" => 56,
		"name" => "Thiệu Hóa - Thanh Hóa",
		"name_filter" => "thiệu hóa - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "thiệuhóa-thanhhóa",
		"index" => 1
	],
	[
		"id" => "699",
		"area_id" => 56,
		"name" => "Thọ Xuân - Thanh Hóa",
		"name_filter" => "thọ xuân - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "thọxuân-thanhhóa",
		"index" => 1
	],
	[
		"id" => "700",
		"area_id" => 56,
		"name" => "Thường Xuân - Thanh Hóa",
		"name_filter" => "thường xuân - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "thườngxuân-thanhhóa",
		"index" => 1
	],
	[
		"id" => "701",
		"area_id" => 56,
		"name" => "Tĩnh Gia - Thanh Hóa",
		"name_filter" => "tĩnh gia - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "tĩnhgia-thanhhóa",
		"index" => 1
	],
	[
		"id" => "702",
		"area_id" => 56,
		"name" => "Triệu Sơn - Thanh Hóa",
		"name_filter" => "triệu sơn - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "triệusơn-thanhhóa",
		"index" => 1
	],
	[
		"id" => "703",
		"area_id" => 56,
		"name" => "Vĩnh Lộc - Thanh Hóa",
		"name_filter" => "vĩnh lộc - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhlộc-thanhhóa",
		"index" => 1
	],
	[
		"id" => "704",
		"area_id" => 56,
		"name" => "Yên Định - Thanh Hóa",
		"name_filter" => "yên định - thanh hóa",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênđịnh-thanhhóa",
		"index" => 1
	],
	[
		"id" => "705",
		"area_id" => 57,
		"name" => "Huế - Thừa Thiên-Huế",
		"name_filter" => "huế - thừa thiên-huế",
		"category" => "Quận - Huyện",
		"name_nospace" => "huế-thừathiên-huế",
		"index" => 1
	],
	[
		"id" => "706",
		"area_id" => 57,
		"name" => "Hương Thủy - Thừa Thiên-Huế",
		"name_filter" => "hương thủy - thừa thiên-huế",
		"category" => "Quận - Huyện",
		"name_nospace" => "hươngthủy-thừathiên-huế",
		"index" => 1
	],
	[
		"id" => "707",
		"area_id" => 57,
		"name" => "Hương Trà - Thừa Thiên-Huế",
		"name_filter" => "hương trà - thừa thiên-huế",
		"category" => "Quận - Huyện",
		"name_nospace" => "hươngtrà-thừathiên-huế",
		"index" => 1
	],
	[
		"id" => "708",
		"area_id" => 57,
		"name" => "Nam Đông - Thừa Thiên-Huế",
		"name_filter" => "nam đông - thừa thiên-huế",
		"category" => "Quận - Huyện",
		"name_nospace" => "namđông-thừathiên-huế",
		"index" => 1
	],
	[
		"id" => "709",
		"area_id" => 57,
		"name" => "A Lưới - Thừa Thiên-Huế",
		"name_filter" => "a lưới - thừa thiên-huế",
		"category" => "Quận - Huyện",
		"name_nospace" => "alưới-thừathiên-huế",
		"index" => 1
	],
	[
		"id" => "710",
		"area_id" => 57,
		"name" => "Phong Điền - Thừa Thiên-Huế",
		"name_filter" => "phong điền - thừa thiên-huế",
		"category" => "Quận - Huyện",
		"name_nospace" => "phongđiền-thừathiên-huế",
		"index" => 1
	],
	[
		"id" => "711",
		"area_id" => 57,
		"name" => "Phú Lộc - Thừa Thiên-Huế",
		"name_filter" => "phú lộc - thừa thiên-huế",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúlộc-thừathiên-huế",
		"index" => 1
	],
	[
		"id" => "712",
		"area_id" => 57,
		"name" => "Phú Vang - Thừa Thiên-Huế",
		"name_filter" => "phú vang - thừa thiên-huế",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúvang-thừathiên-huế",
		"index" => 1
	],
	[
		"id" => "713",
		"area_id" => 57,
		"name" => "Quảng Điền - Thừa Thiên-Huế",
		"name_filter" => "quảng điền - thừa thiên-huế",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảngđiền-thừathiên-huế",
		"index" => 1
	],
	[
		"id" => "714",
		"area_id" => 58,
		"name" => "Cai Lậy - Tiền Giang",
		"name_filter" => "cai lậy - tiền giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "cailậy-tiềngiang",
		"index" => 1
	],
	[
		"id" => "715",
		"area_id" => 58,
		"name" => "Cái Bè - Tiền Giang",
		"name_filter" => "cái bè - tiền giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "cáibè-tiềngiang",
		"index" => 1
	],
	[
		"id" => "716",
		"area_id" => 58,
		"name" => "Châu Thành - Tiền Giang",
		"name_filter" => "châu thành - tiền giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuthành-tiềngiang",
		"index" => 1
	],
	[
		"id" => "717",
		"area_id" => 58,
		"name" => "Chợ Gạo - Tiền Giang",
		"name_filter" => "chợ gạo - tiền giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "chợgạo-tiềngiang",
		"index" => 1
	],
	[
		"id" => "718",
		"area_id" => 58,
		"name" => "Gò Công - Tiền Giang",
		"name_filter" => "gò công - tiền giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "gòcông-tiềngiang",
		"index" => 1
	],
	[
		"id" => "719",
		"area_id" => 58,
		"name" => "Gò Công Đông - Tiền Giang",
		"name_filter" => "gò công đông - tiền giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "gòcôngđông-tiềngiang",
		"index" => 1
	],
	[
		"id" => "720",
		"area_id" => 58,
		"name" => "Gò Công Tây - Tiền Giang",
		"name_filter" => "gò công tây - tiền giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "gòcôngtây-tiềngiang",
		"index" => 1
	],
	[
		"id" => "721",
		"area_id" => 58,
		"name" => "Mỹ Tho - Tiền Giang",
		"name_filter" => "mỹ tho - tiền giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "mỹtho-tiềngiang",
		"index" => 1
	],
	[
		"id" => "722",
		"area_id" => 58,
		"name" => "Tân Phú Đông - Tiền Giang",
		"name_filter" => "tân phú đông - tiền giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânphúđông-tiềngiang",
		"index" => 1
	],
	[
		"id" => "723",
		"area_id" => 58,
		"name" => "Tân Phước - Tiền Giang",
		"name_filter" => "tân phước - tiền giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânphước-tiềngiang",
		"index" => 1
	],
	[
		"id" => "724",
		"area_id" => 59,
		"name" => "Càng Long - Trà Vinh",
		"name_filter" => "càng long - trà vinh",
		"category" => "Quận - Huyện",
		"name_nospace" => "cànglong-tràvinh",
		"index" => 1
	],
	[
		"id" => "725",
		"area_id" => 59,
		"name" => "Cầu Kè - Trà Vinh",
		"name_filter" => "cầu kè - trà vinh",
		"category" => "Quận - Huyện",
		"name_nospace" => "cầukè-tràvinh",
		"index" => 1
	],
	[
		"id" => "726",
		"area_id" => 59,
		"name" => "Cầu Ngang - Trà Vinh",
		"name_filter" => "cầu ngang - trà vinh",
		"category" => "Quận - Huyện",
		"name_nospace" => "cầungang-tràvinh",
		"index" => 1
	],
	[
		"id" => "727",
		"area_id" => 59,
		"name" => "Châu Thành - Trà Vinh",
		"name_filter" => "châu thành - trà vinh",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuthành-tràvinh",
		"index" => 1
	],
	[
		"id" => "728",
		"area_id" => 59,
		"name" => "Duyên Hải - Trà Vinh",
		"name_filter" => "duyên hải - trà vinh",
		"category" => "Quận - Huyện",
		"name_nospace" => "duyênhải-tràvinh",
		"index" => 1
	],
	[
		"id" => "729",
		"area_id" => 59,
		"name" => "Tiểu Cần - Trà Vinh",
		"name_filter" => "tiểu cần - trà vinh",
		"category" => "Quận - Huyện",
		"name_nospace" => "tiểucần-tràvinh",
		"index" => 1
	],
	[
		"id" => "730",
		"area_id" => 59,
		"name" => "Trà Cú - Trà Vinh",
		"name_filter" => "trà cú - trà vinh",
		"category" => "Quận - Huyện",
		"name_nospace" => "tràcú-tràvinh",
		"index" => 1
	],
	[
		"id" => "731",
		"area_id" => 59,
		"name" => "Trà Vinh - Trà Vinh",
		"name_filter" => "trà vinh - trà vinh",
		"category" => "Quận - Huyện",
		"name_nospace" => "tràvinh-tràvinh",
		"index" => 1
	],
	[
		"id" => "732",
		"area_id" => 60,
		"name" => "Chiêm Hóa - Tuyên Quang",
		"name_filter" => "chiêm hóa - tuyên quang",
		"category" => "Quận - Huyện",
		"name_nospace" => "chiêmhóa-tuyênquang",
		"index" => 1
	],
	[
		"id" => "733",
		"area_id" => 60,
		"name" => "Hàm Yên - Tuyên Quang",
		"name_filter" => "hàm yên - tuyên quang",
		"category" => "Quận - Huyện",
		"name_nospace" => "hàmyên-tuyênquang",
		"index" => 1
	],
	[
		"id" => "734",
		"area_id" => 60,
		"name" => "Lâm Bình - Tuyên Quang",
		"name_filter" => "lâm bình - tuyên quang",
		"category" => "Quận - Huyện",
		"name_nospace" => "lâmbình-tuyênquang",
		"index" => 1
	],
	[
		"id" => "735",
		"area_id" => 60,
		"name" => "Na Hang - Tuyên Quang",
		"name_filter" => "na hang - tuyên quang",
		"category" => "Quận - Huyện",
		"name_nospace" => "nahang-tuyênquang",
		"index" => 1
	],
	[
		"id" => "736",
		"area_id" => 60,
		"name" => "Sơn Dương - Tuyên Quang",
		"name_filter" => "sơn dương - tuyên quang",
		"category" => "Quận - Huyện",
		"name_nospace" => "sơndương-tuyênquang",
		"index" => 1
	],
	[
		"id" => "737",
		"area_id" => 60,
		"name" => "Tuyên Quang - Tuyên Quang",
		"name_filter" => "tuyên quang - tuyên quang",
		"category" => "Quận - Huyện",
		"name_nospace" => "tuyênquang-tuyênquang",
		"index" => 1
	],
	[
		"id" => "738",
		"area_id" => 60,
		"name" => "Yên Sơn - Tuyên Quang",
		"name_filter" => "yên sơn - tuyên quang",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênsơn-tuyênquang",
		"index" => 1
	],
	[
		"id" => "739",
		"area_id" => 61,
		"name" => "Bình Minh - Vĩnh Long",
		"name_filter" => "bình minh - vĩnh long",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhminh-vĩnhlong",
		"index" => 1
	],
	[
		"id" => "740",
		"area_id" => 61,
		"name" => "Bình Tân - Vĩnh Long",
		"name_filter" => "bình tân - vĩnh long",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhtân-vĩnhlong",
		"index" => 1
	],
	[
		"id" => "741",
		"area_id" => 61,
		"name" => "Long Hồ - Vĩnh Long",
		"name_filter" => "long hồ - vĩnh long",
		"category" => "Quận - Huyện",
		"name_nospace" => "longhồ-vĩnhlong",
		"index" => 1
	],
	[
		"id" => "742",
		"area_id" => 61,
		"name" => "Mang Thít - Vĩnh Long",
		"name_filter" => "mang thít - vĩnh long",
		"category" => "Quận - Huyện",
		"name_nospace" => "mangthít-vĩnhlong",
		"index" => 1
	],
	[
		"id" => "743",
		"area_id" => 61,
		"name" => "Tam Bình - Vĩnh Long",
		"name_filter" => "tam bình - vĩnh long",
		"category" => "Quận - Huyện",
		"name_nospace" => "tambình-vĩnhlong",
		"index" => 1
	],
	[
		"id" => "744",
		"area_id" => 61,
		"name" => "Trà Ôn - Vĩnh Long",
		"name_filter" => "trà ôn - vĩnh long",
		"category" => "Quận - Huyện",
		"name_nospace" => "tràôn-vĩnhlong",
		"index" => 1
	],
	[
		"id" => "745",
		"area_id" => 61,
		"name" => "Vĩnh Long - Vĩnh Long",
		"name_filter" => "vĩnh long - vĩnh long",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhlong-vĩnhlong",
		"index" => 1
	],
	[
		"id" => "746",
		"area_id" => 61,
		"name" => "Vũng Liêm - Vĩnh Long",
		"name_filter" => "vũng liêm - vĩnh long",
		"category" => "Quận - Huyện",
		"name_nospace" => "vũngliêm-vĩnhlong",
		"index" => 1
	],
	[
		"id" => "747",
		"area_id" => 62,
		"name" => "Bình Xuyên - Vĩnh Phúc",
		"name_filter" => "bình xuyên - vĩnh phúc",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhxuyên-vĩnhphúc",
		"index" => 1
	],
	[
		"id" => "748",
		"area_id" => 62,
		"name" => "Lập Thạch - Vĩnh Phúc",
		"name_filter" => "lập thạch - vĩnh phúc",
		"category" => "Quận - Huyện",
		"name_nospace" => "lậpthạch-vĩnhphúc",
		"index" => 1
	],
	[
		"id" => "749",
		"area_id" => 62,
		"name" => "Phúc Yên - Vĩnh Phúc",
		"name_filter" => "phúc yên - vĩnh phúc",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúcyên-vĩnhphúc",
		"index" => 1
	],
	[
		"id" => "750",
		"area_id" => 62,
		"name" => "Sông Lô - Vĩnh Phúc",
		"name_filter" => "sông lô - vĩnh phúc",
		"category" => "Quận - Huyện",
		"name_nospace" => "sônglô-vĩnhphúc",
		"index" => 1
	],
	[
		"id" => "751",
		"area_id" => 62,
		"name" => "Tam Dương - Vĩnh Phúc",
		"name_filter" => "tam dương - vĩnh phúc",
		"category" => "Quận - Huyện",
		"name_nospace" => "tamdương-vĩnhphúc",
		"index" => 1
	],
	[
		"id" => "752",
		"area_id" => 62,
		"name" => "Tam Đảo - Vĩnh Phúc",
		"name_filter" => "tam đảo - vĩnh phúc",
		"category" => "Quận - Huyện",
		"name_nospace" => "tamđảo-vĩnhphúc",
		"index" => 1
	],
	[
		"id" => "753",
		"area_id" => 62,
		"name" => "Vĩnh Tường - Vĩnh Phúc",
		"name_filter" => "vĩnh tường - vĩnh phúc",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhtường-vĩnhphúc",
		"index" => 1
	],
	[
		"id" => "754",
		"area_id" => 62,
		"name" => "Vĩnh Yên - Vĩnh Phúc",
		"name_filter" => "vĩnh yên - vĩnh phúc",
		"category" => "Quận - Huyện",
		"name_nospace" => "vĩnhyên-vĩnhphúc",
		"index" => 1
	],
	[
		"id" => "755",
		"area_id" => 62,
		"name" => "Yên Lạc - Vĩnh Phúc",
		"name_filter" => "yên lạc - vĩnh phúc",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênlạc-vĩnhphúc",
		"index" => 1
	],
	[
		"id" => "756",
		"area_id" => 63,
		"name" => "Lục Yên - Yên Bái",
		"name_filter" => "lục yên - yên bái",
		"category" => "Quận - Huyện",
		"name_nospace" => "lụcyên-yênbái",
		"index" => 1
	],
	[
		"id" => "757",
		"area_id" => 63,
		"name" => "Mù Cang Chải - Yên Bái",
		"name_filter" => "mù cang chải - yên bái",
		"category" => "Quận - Huyện",
		"name_nospace" => "mùcangchải-yênbái",
		"index" => 1
	],
	[
		"id" => "758",
		"area_id" => 63,
		"name" => "Nghĩa Lộ - Yên Bái",
		"name_filter" => "nghĩa lộ - yên bái",
		"category" => "Quận - Huyện",
		"name_nospace" => "nghĩalộ-yênbái",
		"index" => 1
	],
	[
		"id" => "759",
		"area_id" => 63,
		"name" => "Trạm Tấu - Yên Bái",
		"name_filter" => "trạm tấu - yên bái",
		"category" => "Quận - Huyện",
		"name_nospace" => "trạmtấu-yênbái",
		"index" => 1
	],
	[
		"id" => "760",
		"area_id" => 63,
		"name" => "Trấn Yên - Yên Bái",
		"name_filter" => "trấn yên - yên bái",
		"category" => "Quận - Huyện",
		"name_nospace" => "trấnyên-yênbái",
		"index" => 1
	],
	[
		"id" => "761",
		"area_id" => 63,
		"name" => "Văn Chấn - Yên Bái",
		"name_filter" => "văn chấn - yên bái",
		"category" => "Quận - Huyện",
		"name_nospace" => "vănchấn-yênbái",
		"index" => 1
	],
	[
		"id" => "762",
		"area_id" => 63,
		"name" => "Văn Yên - Yên Bái",
		"name_filter" => "văn yên - yên bái",
		"category" => "Quận - Huyện",
		"name_nospace" => "vănyên-yênbái",
		"index" => 1
	],
	[
		"id" => "763",
		"area_id" => 63,
		"name" => "Yên Bái - Yên Bái",
		"name_filter" => "yên bái - yên bái",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênbái-yênbái",
		"index" => 1
	],
	[
		"id" => "764",
		"area_id" => 63,
		"name" => "Yên Bình - Yên Bái",
		"name_filter" => "yên bình - yên bái",
		"category" => "Quận - Huyện",
		"name_nospace" => "yênbình-yênbái",
		"index" => 1
	],
	[
		"id" => "131",
		"area_id" => 63,
		"name" => "Quy Nhơn - Bình Định",
		"name_filter" => "quy nhơn - bình định",
		"category" => "Quận - Huyện",
		"name_nospace" => "quynhơn-bìnhđịnh",
		"index" => 1
	],
	[
		"id" => "114233",
		"area_id" => 1,
		"name" => "Trà sư - An Giang",
		"name_filter" => "trà sư - an giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "tràsư-angiang",
		"index" => 1
	],
	[
		"id" => "114234",
		"area_id" => 1,
		"name" => "Chùa Bà Châu Đốc",
		"name_filter" => "chùa bà châu đốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "chùabàchâuđốc",
		"index" => 1
	],
	[
		"id" => "114235",
		"area_id" => 5,
		"name" => "Cánh Đồng Quạt Gió",
		"name_filter" => "cánh đồng quạt gió",
		"category" => "Quận - Huyện",
		"name_nospace" => "cánhđồngquạtgió",
		"index" => 1
	],
	[
		"id" => "114236",
		"area_id" => 7,
		"name" => "Cồn Phụng - Bến Tre",
		"name_filter" => "cồn phụng - bến tre",
		"category" => "Quận - Huyện",
		"name_nospace" => "cồnphụng-bếntre",
		"index" => 1
	],
	[
		"id" => "114237",
		"area_id" => 11,
		"name" => "Phan Rí - Bình Thuận",
		"name_filter" => "phan rí - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "phanrí-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "114238",
		"area_id" => 11,
		"name" => "Liên Hương - Bình Thuận",
		"name_filter" => "liên hương - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "liênhương-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "114239",
		"area_id" => 11,
		"name" => "Coco Beach - Bình Thuận",
		"name_filter" => "coco beach - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "cocobeach-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "114240",
		"area_id" => 11,
		"name" => "Cổ Thạch - Bình Thuận",
		"name_filter" => "cổ thạch - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "cổthạch-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "114241",
		"area_id" => 11,
		"name" => "Hòn Rơm - Bình Thuận",
		"name_filter" => "hòn rơm - bình thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "hònrơm-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "114242",
		"area_id" => 12,
		"name" => "Biển Khai Long - Cà Mau",
		"name_filter" => "biển khai long - cà mau",
		"category" => "Quận - Huyện",
		"name_nospace" => "biểnkhailong-càmau",
		"index" => 1
	],
	[
		"id" => "114243",
		"area_id" => 13,
		"name" => "Chợ Nổi Cái Răng",
		"name_filter" => "chợ nổi cái răng",
		"category" => "Quận - Huyện",
		"name_nospace" => "chợnổicáirăng",
		"index" => 1
	],
	[
		"id" => "114244",
		"area_id" => 16,
		"name" => "Hồ Lắk - Đắk Lắk",
		"name_filter" => "hồ lắk - đắk lắk",
		"category" => "Quận - Huyện",
		"name_nospace" => "hồlắk-đắklắk",
		"index" => 1
	],
	[
		"id" => "114245",
		"area_id" => 21,
		"name" => "Chư Đăng Ya - Gia Lai",
		"name_filter" => "chư đăng ya - gia lai",
		"category" => "Quận - Huyện",
		"name_nospace" => "chưđăngya-gialai",
		"index" => 1
	],
	[
		"id" => "114246",
		"area_id" => 33,
		"name" => "Biển Mũi Nai - Kiên Giang",
		"name_filter" => "biển mũi nai - kiên giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "biểnmũinai-kiêngiang",
		"index" => 1
	],
	[
		"id" => "114247",
		"area_id" => 25,
		"name" => "Vũng Áng - Hà Tĩnh",
		"name_filter" => "vũng áng - hà tĩnh",
		"category" => "Quận - Huyện",
		"name_nospace" => "vũngáng-hàtĩnh",
		"index" => 1
	],
	[
		"id" => "114248",
		"area_id" => 27,
		"name" => "Cát Bà - Hải Phòng",
		"name_filter" => "cát bà - hải phòng",
		"category" => "Quận - Huyện",
		"name_nospace" => "cátbà-hảiphòng",
		"index" => 1
	],
	[
		"id" => "114249",
		"area_id" => 28,
		"name" => "Chợ Nổi Ngã Bảy",
		"name_filter" => "chợ nổi ngã bảy",
		"category" => "Quận - Huyện",
		"name_nospace" => "chợnổingãbảy",
		"index" => 1
	],
	[
		"id" => "114250",
		"area_id" => 57,
		"name" => "Vịnh Lăng Cô",
		"name_filter" => "vịnh lăng cô",
		"category" => "Quận - Huyện",
		"name_nospace" => "vịnhlăngcô",
		"index" => 1
	],
	[
		"id" => "114251",
		"area_id" => 32,
		"name" => "Vịnh Ninh Vân",
		"name_filter" => "vịnh ninh vân",
		"category" => "Quận - Huyện",
		"name_nospace" => "vịnhninhvân",
		"index" => 1
	],
	[
		"id" => "114252",
		"area_id" => 38,
		"name" => "Bảo Hà - Lào Cai",
		"name_filter" => "bảo hà - lào cai",
		"category" => "Quận - Huyện",
		"name_nospace" => "bảohà-làocai",
		"index" => 1
	],
	[
		"id" => "114253",
		"area_id" => 39,
		"name" => "Làng Nổi Tân Lập",
		"name_filter" => "làng nổi tân lập",
		"category" => "Quận - Huyện",
		"name_nospace" => "làngnổitânlập",
		"index" => 1
	],
	[
		"id" => "114254",
		"area_id" => 42,
		"name" => "Tràng An - Bái Đính",
		"name_filter" => "tràng an - bái đính",
		"category" => "Quận - Huyện",
		"name_nospace" => "tràngan-báiđính",
		"index" => 1
	],
	[
		"id" => "114255",
		"area_id" => 42,
		"name" => "Tam Cốc Bích Động",
		"name_filter" => "tam cốc bích động",
		"category" => "Quận - Huyện",
		"name_nospace" => "tamcốcbíchđộng",
		"index" => 1
	],
	[
		"id" => "114256",
		"area_id" => 43,
		"name" => "Ninh Chữ - Ninh Thuận",
		"name_filter" => "ninh chữ - ninh thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "ninhchữ-ninhthuận",
		"index" => 1
	],
	[
		"id" => "114257",
		"area_id" => 43,
		"name" => "Vịnh Vĩnh Hy - Ninh Thuận",
		"name_filter" => "vịnh vĩnh hy - ninh thuận",
		"category" => "Quận - Huyện",
		"name_nospace" => "vịnhvĩnhhy-ninhthuận",
		"index" => 1
	],
	[
		"id" => "114258",
		"area_id" => 45,
		"name" => "Ghềnh Đá Dĩa - Phú Yên",
		"name_filter" => "ghềnh đá dĩa - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "ghềnhđádĩa-phúyên",
		"index" => 1
	],
	[
		"id" => "114259",
		"area_id" => 45,
		"name" => "Vịnh Xuân Đài - Phú Yên",
		"name_filter" => "vịnh xuân đài - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "vịnhxuânđài-phúyên",
		"index" => 1
	],
	[
		"id" => "114260",
		"area_id" => 45,
		"name" => "Đầm Ô Loan - Phú Yên",
		"name_filter" => "đầm ô loan - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "đầmôloan-phúyên",
		"index" => 1
	],
	[
		"id" => "114261",
		"area_id" => 46,
		"name" => "Phong Nha - Quảng Bình",
		"name_filter" => "phong nha - quảng bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "phongnha-quảngbình",
		"index" => 1
	],
	[
		"id" => "114262",
		"area_id" => 53,
		"name" => "Núi Bà Đen - Tây Ninh",
		"name_filter" => "núi bà đen - tây ninh",
		"category" => "Quận - Huyện",
		"name_nospace" => "núibàđen-tâyninh",
		"index" => 1
	],
	[
		"id" => "114263",
		"area_id" => 58,
		"name" => "Biển Tân Thành - Tiền Giang",
		"name_filter" => "biển tân thành - tiền giang",
		"category" => "Quận - Huyện",
		"name_nospace" => "biểntânthành-tiềngiang",
		"index" => 1
	],
	[
		"id" => "114264",
		"area_id" => 58,
		"name" => "Cù Lao Thái Sơn",
		"name_filter" => "cù lao thái sơn",
		"category" => "Quận - Huyện",
		"name_nospace" => "cùlaotháisơn",
		"index" => 1
	],
	[
		"id" => "114265",
		"area_id" => 59,
		"name" => "Biển Ba Động - Trà Vinh",
		"name_filter" => "biển ba động - trà vinh",
		"category" => "Quận - Huyện",
		"name_nospace" => "biểnbađộng-tràvinh",
		"index" => 1
	],
	[
		"id" => "114266",
		"area_id" => 2,
		"name" => "Long Hải",
		"name_filter" => "long hải",
		"category" => "Quận - Huyện",
		"name_nospace" => "longhải",
		"index" => 1
	],
	[
		"id" => "117412",
		"area_id" => 45,
		"name" => "Vũng Rô - Phú Yên",
		"name_filter" => "vũng rô - phú yên",
		"category" => "Quận - Huyện",
		"name_nospace" => "vũngrô-phúyên",
		"index" => 1
	],
	[
		"id" => "102188",
		"area_id" => 24,
		"name" => "Sân bay Nội Bài",
		"name_filter" => "sân bay nội bài",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaynộibài",
		"index" => 1
	],
	[
		"id" => "28284",
		"area_id" => 29,
		"name" => "Sân bay Tân Sơn Nhất",
		"name_filter" => "sân bay tân sơn nhất",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaytânsơnnhất",
		"index" => 1
	],
	[
		"id" => "135234",
		"area_id" => 16,
		"name" => "Phước An - Krông Pắk - Đắk Lắk",
		"name_filter" => "phước an - krông pắk - đắk lắk",
		"category" => "Phường - Xã",
		"name_nospace" => "phướcan-krôngpắk-đắklắk",
		"index" => 1
	],
	[
		"id" => "135235",
		"area_id" => 46,
		"name" => "Hoàn Lão - Bố Trạch - Quảng Bình",
		"name_filter" => "hoàn lão - bố trạch - quảng bình",
		"category" => "Phường - Xã",
		"name_nospace" => "hoànlão-bốtrạch-quảngbình",
		"index" => 1
	],
	[
		"id" => "135236",
		"area_id" => 46,
		"name" => "Roòn - Quảng Trạch - Quảng Bình",
		"name_filter" => "roòn - quảng trạch - quảng bình",
		"category" => "Phường - Xã",
		"name_nospace" => "roòn-quảngtrạch-quảngbình",
		"index" => 1
	],
	[
		"id" => "135237",
		"area_id" => 46,
		"name" => "Thanh Khê - Bố Trạch - Quảng Bình",
		"name_filter" => "thanh khê - bố trạch - quảng bình",
		"category" => "Phường - Xã",
		"name_nospace" => "thanhkhê-bốtrạch-quảngbình",
		"index" => 1
	],
	[
		"id" => "135238",
		"area_id" => 46,
		"name" => "Nông Trường Việt Trung",
		"name_filter" => "nông trường việt trung",
		"category" => "Phường - Xã",
		"name_nospace" => "nôngtrườngviệttrung",
		"index" => 1
	],
	[
		"id" => "135239",
		"area_id" => 46,
		"name" => "Lý Hoà - Bố Trạch - Quảng Bình",
		"name_filter" => "lý hoà - bố trạch - quảng bình",
		"category" => "Phường - Xã",
		"name_nospace" => "lýhoà-bốtrạch-quảngbình",
		"index" => 1
	],
	[
		"id" => "135240",
		"area_id" => 50,
		"name" => "Hồ Xá - Vĩnh Linh - Quảng Trị",
		"name_filter" => "hồ xá - vĩnh linh - quảng trị",
		"category" => "Phường - Xã",
		"name_nospace" => "hồxá-vĩnhlinh-quảngtrị",
		"index" => 1
	],
	[
		"id" => "135241",
		"area_id" => 50,
		"name" => "Lao Bảo - Hương Hóa - Quảng Trị",
		"name_filter" => "lao bảo - hương hóa - quảng trị",
		"category" => "Phường - Xã",
		"name_nospace" => "laobảo-hươnghóa-quảngtrị",
		"index" => 1
	],
	[
		"id" => "135244",
		"area_id" => 48,
		"name" => "Cảng Sa Kỳ - Quảng Ngãi",
		"name_filter" => "cảng sa kỳ - quảng ngãi",
		"category" => "Sân Bay",
		"name_nospace" => "cảngsakỳ-quảngngãi",
		"index" => 1
	],
	[
		"id" => "135243",
		"area_id" => 599,
		"name" => "Sa Kỳ - Quảng Ngãi",
		"name_filter" => "sa kỳ - quảng ngãi",
		"category" => "Quận - Huyện",
		"name_nospace" => "sakỳ-quảngngãi",
		"index" => 1
	],
	[
		"id" => "135544",
		"area_id" => 2,
		"name" => "Sân bay Côn Đảo",
		"name_filter" => "sân bay côn đảo",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaycônđảo",
		"index" => 1
	],
	[
		"id" => "133906",
		"area_id" => 204,
		"name" => "Sân Bay Buôn Ma Thuột",
		"name_filter" => "sân bay buôn ma thuột",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaybuônmathuột",
		"index" => 1
	],
	[
		"id" => "135546",
		"area_id" => 163,
		"name" => "Sân bay Cà Mau",
		"name_filter" => "sân bay cà mau",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaycàmau",
		"index" => 1
	],
	[
		"id" => "135552",
		"area_id" => 413,
		"name" => "Sân bay Cam Ranh",
		"name_filter" => "sân bay cam ranh",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaycamranh",
		"index" => 1
	],
	[
		"id" => "135547",
		"area_id" => 172,
		"name" => "Sân bay Cần Thơ",
		"name_filter" => "sân bay cần thơ",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaycầnthơ",
		"index" => 1
	],
	[
		"id" => "135551",
		"area_id" => 351,
		"name" => "Sân bay Cát Bi",
		"name_filter" => "sân bay cát bi",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaycátbi",
		"index" => 1
	],
	[
		"id" => "135548",
		"area_id" => 195,
		"name" => "Sân bay Đà Nẵng",
		"name_filter" => "sân bay đà nẵng",
		"category" => "Sân Bay",
		"name_nospace" => "sânbayđànẵng",
		"index" => 1
	],
	[
		"id" => "135549",
		"area_id" => 227,
		"name" => "Sân bay Điện Biên Phủ",
		"name_filter" => "sân bay điện biên phủ",
		"category" => "Sân Bay",
		"name_nospace" => "sânbayđiệnbiênphủ",
		"index" => 1
	],
	[
		"id" => "135557",
		"area_id" => 568,
		"name" => "Sân bay Đồng Hới",
		"name_filter" => "sân bay đồng hới",
		"category" => "Sân Bay",
		"name_nospace" => "sânbayđồnghới",
		"index" => 1
	],
	[
		"id" => "112068",
		"area_id" => 462,
		"name" => "Sân Bay Liên Khương",
		"name_filter" => "sân bay liên khương",
		"category" => "Sân Bay",
		"name_nospace" => "sânbayliênkhương",
		"index" => 1
	],
	[
		"id" => "135559",
		"area_id" => 706,
		"name" => "Sân bay Phú Bài",
		"name_filter" => "sân bay phú bài",
		"category" => "Sân Bay",
		"name_nospace" => "sânbayphúbài",
		"index" => 1
	],
	[
		"id" => "135545",
		"area_id" => 129,
		"name" => "Sân bay Phù Cát",
		"name_filter" => "sân bay phù cát",
		"category" => "Sân Bay",
		"name_nospace" => "sânbayphùcát",
		"index" => 1
	],
	[
		"id" => "135554",
		"area_id" => 431,
		"name" => "Sân bay Phú Quốc",
		"name_filter" => "sân bay phú quốc",
		"category" => "Sân Bay",
		"name_nospace" => "sânbayphúquốc",
		"index" => 1
	],
	[
		"id" => "135550",
		"area_id" => 274,
		"name" => "Sân bay Pleiku",
		"name_filter" => "sân bay pleiku",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaypleiku",
		"index" => 1
	],
	[
		"id" => "135553",
		"area_id" => 432,
		"name" => "Sân bay Rạch Giá",
		"name_filter" => "sân bay rạch giá",
		"category" => "Sân Bay",
		"name_nospace" => "sânbayrạchgiá",
		"index" => 1
	],
	[
		"id" => "135558",
		"area_id" => 699,
		"name" => "Sân bay Thọ Xuân",
		"name_filter" => "sân bay thọ xuân",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaythọxuân",
		"index" => 1
	],
	[
		"id" => "135556",
		"area_id" => 566,
		"name" => "Sân bay Tuy Hòa",
		"name_filter" => "sân bay tuy hòa",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaytuyhòa",
		"index" => 1
	],
	[
		"id" => "135555",
		"area_id" => 528,
		"name" => "Sân bay Vinh",
		"name_filter" => "sân bay vinh",
		"category" => "Sân Bay",
		"name_nospace" => "sânbayvinh",
		"index" => 1
	],
	[
		"id" => "112626",
		"area_id" => 112625,
		"name" => "Quảng Tây - Trung Quốc",
		"name_filter" => "quảng tây - trung quốc",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "quảngtây-trungquốc",
		"index" => 1
	],
	[
		"id" => "112627",
		"area_id" => 112625,
		"name" => "Quảng Đông - Trung Quốc",
		"name_filter" => "quảng đông - trung quốc",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "quảngđông-trungquốc",
		"index" => 1
	],
	[
		"id" => "112628",
		"area_id" => 112625,
		"name" => "Ma Cao - Trung Quốc",
		"name_filter" => "ma cao - trung quốc",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "macao-trungquốc",
		"index" => 1
	],
	[
		"id" => "112629",
		"area_id" => 112625,
		"name" => "Hữu Nghị Quan - Trung Quốc",
		"name_filter" => "hữu nghị quan - trung quốc",
		"category" => "Tỉnh - Thành Phố",
		"name_nospace" => "hữunghịquan-trungquốc",
		"index" => 1
	],
	[
		"id" => "112669",
		"area_id" => 112625,
		"name" => "Hữu Nghị Quan - Trung Quốc",
		"name_filter" => "hữu nghị quan - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "hữunghịquan-trungquốc",
		"index" => 1
	],
	[
		"id" => "112668",
		"area_id" => 112625,
		"name" => "Ma Cao - Trung Quốc",
		"name_filter" => "ma cao - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "macao-trungquốc",
		"index" => 1
	],
	[
		"id" => "112632",
		"area_id" => 112625,
		"name" => "Bách Sắc - Trung Quốc",
		"name_filter" => "bách sắc - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "báchsắc-trungquốc",
		"index" => 1
	],
	[
		"id" => "112633",
		"area_id" => 112625,
		"name" => "Hà Trì - Trung Quốc",
		"name_filter" => "hà trì - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "hàtrì-trungquốc",
		"index" => 1
	],
	[
		"id" => "112634",
		"area_id" => 112625,
		"name" => "Liễu Châu - Trung Quốc",
		"name_filter" => "liễu châu - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "liễuchâu-trungquốc",
		"index" => 1
	],
	[
		"id" => "112635",
		"area_id" => 112625,
		"name" => "Quế Lâm - Trung Quốc",
		"name_filter" => "quế lâm - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "quếlâm-trungquốc",
		"index" => 1
	],
	[
		"id" => "112636",
		"area_id" => 112625,
		"name" => "Hạ Châu - Trung Quốc",
		"name_filter" => "hạ châu - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "hạchâu-trungquốc",
		"index" => 1
	],
	[
		"id" => "112637",
		"area_id" => 112625,
		"name" => "Sùng Tả - Trung Quốc",
		"name_filter" => "sùng tả - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "sùngtả-trungquốc",
		"index" => 1
	],
	[
		"id" => "112638",
		"area_id" => 112625,
		"name" => "Nam Ninh - Trung Quốc",
		"name_filter" => "nam ninh - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "namninh-trungquốc",
		"index" => 1
	],
	[
		"id" => "112639",
		"area_id" => 112625,
		"name" => "Lai Tân - Trung Quốc",
		"name_filter" => "lai tân - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "laitân-trungquốc",
		"index" => 1
	],
	[
		"id" => "112640",
		"area_id" => 112625,
		"name" => "Quý Cảng - Trung Quốc",
		"name_filter" => "quý cảng - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "quýcảng-trungquốc",
		"index" => 1
	],
	[
		"id" => "112641",
		"area_id" => 112625,
		"name" => "Ngô Châu - Trung Quốc",
		"name_filter" => "ngô châu - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "ngôchâu-trungquốc",
		"index" => 1
	],
	[
		"id" => "112642",
		"area_id" => 112625,
		"name" => "Phòng Thành Cảng - Trung Quốc",
		"name_filter" => "phòng thành cảng - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "phòngthànhcảng-trungquốc",
		"index" => 1
	],
	[
		"id" => "112643",
		"area_id" => 112625,
		"name" => "Khâm Châu - Trung Quốc",
		"name_filter" => "khâm châu - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "khâmchâu-trungquốc",
		"index" => 1
	],
	[
		"id" => "112644",
		"area_id" => 112625,
		"name" => "Bắc Hải - Trung Quốc",
		"name_filter" => "bắc hải - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắchải-trungquốc",
		"index" => 1
	],
	[
		"id" => "112645",
		"area_id" => 112625,
		"name" => "Bằng Tường - Trung Quốc",
		"name_filter" => "bằng tường - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "bằngtường-trungquốc",
		"index" => 1
	],
	[
		"id" => "112646",
		"area_id" => 112625,
		"name" => "Ngọc Lâm - Trung Quốc",
		"name_filter" => "ngọc lâm - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "ngọclâm-trungquốc",
		"index" => 1
	],
	[
		"id" => "112647",
		"area_id" => 112625,
		"name" => "Quảng Châu - Trung Quốc",
		"name_filter" => "quảng châu - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "quảngchâu-trungquốc",
		"index" => 1
	],
	[
		"id" => "112648",
		"area_id" => 112625,
		"name" => "Thâm Quyến - Trung Quốc",
		"name_filter" => "thâm quyến - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "thâmquyến-trungquốc",
		"index" => 1
	],
	[
		"id" => "112649",
		"area_id" => 112625,
		"name" => "Thanh Viễn - Trung Quốc",
		"name_filter" => "thanh viễn - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhviễn-trungquốc",
		"index" => 1
	],
	[
		"id" => "112650",
		"area_id" => 112625,
		"name" => "Thiều Quan - Trung Quốc",
		"name_filter" => "thiều quan - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "thiềuquan-trungquốc",
		"index" => 1
	],
	[
		"id" => "112651",
		"area_id" => 112625,
		"name" => "Hà Nguyên - Trung Quốc",
		"name_filter" => "hà nguyên - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "hànguyên-trungquốc",
		"index" => 1
	],
	[
		"id" => "112652",
		"area_id" => 112625,
		"name" => "Mai Châu - Trung Quốc",
		"name_filter" => "mai châu - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "maichâu-trungquốc",
		"index" => 1
	],
	[
		"id" => "112653",
		"area_id" => 112625,
		"name" => "Triều Châu - Trung Quốc",
		"name_filter" => "triều châu - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "triềuchâu-trungquốc",
		"index" => 1
	],
	[
		"id" => "112654",
		"area_id" => 112625,
		"name" => "Triệu Khánh - Trung Quốc",
		"name_filter" => "triệu khánh - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "triệukhánh-trungquốc",
		"index" => 1
	],
	[
		"id" => "112655",
		"area_id" => 112625,
		"name" => "Vân Phù - Trung Quốc",
		"name_filter" => "vân phù - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "vânphù-trungquốc",
		"index" => 1
	],
	[
		"id" => "112656",
		"area_id" => 112625,
		"name" => "Phật Sơn - Trung Quốc",
		"name_filter" => "phật sơn - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "phậtsơn-trungquốc",
		"index" => 1
	],
	[
		"id" => "112657",
		"area_id" => 112625,
		"name" => "Đông Hoản - Trung Quốc",
		"name_filter" => "đông hoản - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "đônghoản-trungquốc",
		"index" => 1
	],
	[
		"id" => "112658",
		"area_id" => 112625,
		"name" => "Huệ Châu - Trung Quốc",
		"name_filter" => "huệ châu - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "huệchâu-trungquốc",
		"index" => 1
	],
	[
		"id" => "112659",
		"area_id" => 112625,
		"name" => "Sán Vĩ - Trung Quốc",
		"name_filter" => "sán vĩ - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "sánvĩ-trungquốc",
		"index" => 1
	],
	[
		"id" => "112660",
		"area_id" => 112625,
		"name" => "Yết Dương - Trung Quốc",
		"name_filter" => "yết dương - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "yếtdương-trungquốc",
		"index" => 1
	],
	[
		"id" => "112661",
		"area_id" => 112625,
		"name" => "Sán Đầu - Trung Quốc",
		"name_filter" => "sán đầu - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "sánđầu-trungquốc",
		"index" => 1
	],
	[
		"id" => "112662",
		"area_id" => 112625,
		"name" => "Trạm Giang - Trung Quốc",
		"name_filter" => "trạm giang - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "trạmgiang-trungquốc",
		"index" => 1
	],
	[
		"id" => "112663",
		"area_id" => 112625,
		"name" => "Mậu Danh - Trung Quốc",
		"name_filter" => "mậu danh - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "mậudanh-trungquốc",
		"index" => 1
	],
	[
		"id" => "112664",
		"area_id" => 112625,
		"name" => "Dương Giang - Trung Quốc",
		"name_filter" => "dương giang - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "dươnggiang-trungquốc",
		"index" => 1
	],
	[
		"id" => "112665",
		"area_id" => 112625,
		"name" => "Giang Môn - Trung Quốc",
		"name_filter" => "giang môn - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "giangmôn-trungquốc",
		"index" => 1
	],
	[
		"id" => "112666",
		"area_id" => 112625,
		"name" => "Trung Sơn - Trung Quốc",
		"name_filter" => "trung sơn - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "trungsơn-trungquốc",
		"index" => 1
	],
	[
		"id" => "112667",
		"area_id" => 112625,
		"name" => "Châu Hải - Trung Quốc",
		"name_filter" => "châu hải - trung quốc",
		"category" => "Quận - Huyện",
		"name_nospace" => "châuhải-trungquốc",
		"index" => 1
	],
	[
		"id" => "178",
		"area_id" => 13,
		"name" => "Thốt Nốt - Cần Thơ",
		"name_filter" => "thốt nốt - cần thơ",
		"category" => "Quận - Huyện",
		"name_nospace" => "thốtnốt-cầnthơ",
		"index" => 1
	],
	[
		"id" => "28029",
		"area_id" => 28029,
		"name" => "Ba Đồn - Quảng Bình",
		"name_filter" => "ba đồn - quảng bình",
		"category" => "Quận - Huyện",
		"name_nospace" => "bađồn-quảngbình",
		"index" => 1
	],
	[
		"id" => "135965",
		"area_id" => 56,
		"name" => "Nghi Sơn - Thanh Hóa",
		"name_filter" => "nghi sơn - thanh hóa",
		"category" => "Phường - Xã",
		"name_nospace" => "nghisơn-thanhhóa",
		"index" => 1
	],
	[
		"id" => "145844",
		"area_id" => 244,
		"name" => "Trị An - Vĩnh Cửu - Đồng Nai",
		"name_filter" => "trị an - vĩnh cửu - đồng nai",
		"category" => "Phường - Xã",
		"name_nospace" => "trịan-vĩnhcửu-đồngnai",
		"index" => 1
	],
	[
		"id" => "135774",
		"area_id" => 112626,
		"name" => "Sân Bay Nam Ninh - Quảng Tây - Trung Quốc",
		"name_filter" => "sân bay nam ninh - quảng tây - trung quốc",
		"category" => "Sân Bay",
		"name_nospace" => "sânbaynamninh-quảngtây-trungquốc",
		"index" => 1
	],
	[
		"id" => "135775",
		"area_id" => 112626,
		"name" => "Bến xe Lãng Đông - Quảng Tây - Trung Quốc",
		"name_filter" => "bến xe lãng đông - quảng tây - trung quốc",
		"category" => "Bến xe",
		"name_nospace" => "bếnxelãngđông-quảngtây-trungquốc",
		"index" => 1
	],
	[
		"id" => "102376",
		"area_id" => 243,
		"name" => "Ngã Ba Trị An - Đồng Nai",
		"name_filter" => "ngã ba trị an - đồng nai",
		"category" => "Điểm dừng phổ biến",
		"name_nospace" => "ngãbatrịan-đồngnai",
		"index" => 1
	],
	[
		"id" => "157631",
		"area_id" => 138387,
		"name" => "Cầu kính Rồng Mây Sapa",
		"name_filter" => "cầu kính rồng mây sapa",
		"category" => "Điểm dừng phổ biến",
		"name_nospace" => "cầukínhrồngmâysapa",
		"index" => 1
	],
	[
		"id" => "157632",
		"area_id" => 138216,
		"name" => "Bản Tả Van - SaPa",
		"name_filter" => "bản tả van - sapa",
		"category" => "Điểm dừng phổ biến",
		"name_nospace" => "bảntảvan-sapa",
		"index" => 1
	],
	[
		"id" => "376",
		"area_id" => 376,
		"name" => "Quận 1 - Hồ Chí Minh",
		"name_filter" => "quận 1 - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quận1-hồchíminh",
		"index" => 1
	],
	[
		"id" => "367",
		"area_id" => 29,
		"name" => "Bình Chánh - Hồ Chí Minh",
		"name_filter" => "bình chánh - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhchánh-hồchíminh",
		"index" => 1
	],
	[
		"id" => "368",
		"area_id" => 29,
		"name" => "Bình Tân - Hồ Chí Minh",
		"name_filter" => "bình tân - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhtân-hồchíminh",
		"index" => 1
	],
	[
		"id" => "369",
		"area_id" => 29,
		"name" => "Bình Thạnh - Hồ Chí Minh",
		"name_filter" => "bình thạnh - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "bìnhthạnh-hồchíminh",
		"index" => 1
	],
	[
		"id" => "371",
		"area_id" => 29,
		"name" => "Củ Chi - Hồ Chí Minh",
		"name_filter" => "củ chi - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "củchi-hồchíminh",
		"index" => 1
	],
	[
		"id" => "372",
		"area_id" => 29,
		"name" => "Gò Vấp - Hồ Chí Minh",
		"name_filter" => "gò vấp - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "gòvấp-hồchíminh",
		"index" => 1
	],
	[
		"id" => "373",
		"area_id" => 29,
		"name" => "Hóc Môn - Hồ Chí Minh",
		"name_filter" => "hóc môn - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "hócmôn-hồchíminh",
		"index" => 1
	],
	[
		"id" => "374",
		"area_id" => 29,
		"name" => "Nhà Bè - Hồ Chí Minh",
		"name_filter" => "nhà bè - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "nhàbè-hồchíminh",
		"index" => 1
	],
	[
		"id" => "375",
		"area_id" => 29,
		"name" => "Phú Nhuận - Hồ Chí Minh",
		"name_filter" => "phú nhuận - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúnhuận-hồchíminh",
		"index" => 1
	],
	[
		"id" => "378",
		"area_id" => 29,
		"name" => "Quận 3 - Hồ Chí Minh",
		"name_filter" => "quận 3 - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quận3-hồchíminh",
		"index" => 1
	],
	[
		"id" => "379",
		"area_id" => 29,
		"name" => "Quận 4 - Hồ Chí Minh",
		"name_filter" => "quận 4 - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quận4-hồchíminh",
		"index" => 1
	],
	[
		"id" => "380",
		"area_id" => 29,
		"name" => "Quận 5 - Hồ Chí Minh",
		"name_filter" => "quận 5 - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quận5-hồchíminh",
		"index" => 1
	],
	[
		"id" => "381",
		"area_id" => 29,
		"name" => "Quận 6 - Hồ Chí Minh",
		"name_filter" => "quận 6 - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quận6-hồchíminh",
		"index" => 1
	],
	[
		"id" => "382",
		"area_id" => 29,
		"name" => "Quận 7 - Hồ Chí Minh",
		"name_filter" => "quận 7 - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quận7-hồchíminh",
		"index" => 1
	],
	[
		"id" => "383",
		"area_id" => 29,
		"name" => "Quận 8 - Hồ Chí Minh",
		"name_filter" => "quận 8 - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quận8-hồchíminh",
		"index" => 1
	],
	[
		"id" => "385",
		"area_id" => 29,
		"name" => "Quận 10 - Hồ Chí Minh",
		"name_filter" => "quận 10 - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quận10-hồchíminh",
		"index" => 1
	],
	[
		"id" => "386",
		"area_id" => 29,
		"name" => "Quận 11 - Hồ Chí Minh",
		"name_filter" => "quận 11 - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quận11-hồchíminh",
		"index" => 1
	],
	[
		"id" => "387",
		"area_id" => 29,
		"name" => "Quận 12 - Hồ Chí Minh",
		"name_filter" => "quận 12 - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "quận12-hồchíminh",
		"index" => 1
	],
	[
		"id" => "388",
		"area_id" => 29,
		"name" => "Tân Bình - Hồ Chí Minh",
		"name_filter" => "tân bình - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânbình-hồchíminh",
		"index" => 1
	],
	[
		"id" => "389",
		"area_id" => 29,
		"name" => "Tân Phú - Hồ Chí Minh",
		"name_filter" => "tân phú - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "tânphú-hồchíminh",
		"index" => 1
	],
	[
		"id" => "390",
		"area_id" => 29,
		"name" => "Thủ Đức - Hồ Chí Minh",
		"name_filter" => "thủ đức - hồ chí minh",
		"category" => "Quận - Huyện",
		"name_nospace" => "thủđức-hồchíminh",
		"index" => 1
	],
	[
		"id" => "303",
		"name" => "Hoàn Kiếm - Hà Nội",
		"name_filter" => "hoàn kiếm - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "hoànkiếm-hànội",
		"index" => 1
	],
	[
		"id" => "298",
		"name" => "Đống Đa - Hà Nội",
		"name_filter" => "đống đa - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "đốngđa-hànội",
		"index" => 1
	],
	[
		"id" => "292",
		"name" => "Ba Đình - Hà Nội",
		"name_filter" => "ba đình - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "bađình-hànội",
		"index" => 1
	],
	[
		"id" => "301",
		"name" => "Hai Bà Trưng - Hà Nội",
		"name_filter" => "hai bà trưng - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "haibàtrưng-hànội",
		"index" => 1
	],
	[
		"id" => "304",
		"name" => "Hoàng Mai - Hà Nội",
		"name_filter" => "hoàng mai - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "hoàngmai-hànội",
		"index" => 1
	],
	[
		"id" => "317",
		"name" => "Thanh Xuân - Hà Nội",
		"name_filter" => "thanh xuân - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhxuân-hànội",
		"index" => 1
	],
	[
		"id" => "305",
		"name" => "Long Biên - Hà Nội",
		"name_filter" => "long biên - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "longbiên-hànội",
		"index" => 1
	],
	[
		"id" => "28024",
		"name" => "Nam Từ Liêm - Hà Nội",
		"name_filter" => "nam từ liêm - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "namtừliêm-hànội",
		"index" => 1
	],
	[
		"id" => "28023",
		"name" => "Bắc Từ Liêm - Hà Nội",
		"name_filter" => "bắc từ liêm - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "bắctừliêm-hànội",
		"index" => 1
	],
	[
		"id" => "313",
		"name" => "Tây Hồ - Hà Nội",
		"name_filter" => "tây hồ - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "tâyhồ-hànội",
		"index" => 1
	],
	[
		"id" => "294",
		"name" => "Cầu Giấy - Hà Nội",
		"name_filter" => "cầu giấy - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "cầugiấy-hànội",
		"index" => 1
	],
	[
		"id" => "300",
		"name" => "Hà Đông - Hà Nội",
		"name_filter" => "hà đông - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "hàđông-hànội",
		"index" => 1
	],
	[
		"id" => "312",
		"name" => "Thị Xã Sơn Tây - Hà Nội",
		"name_filter" => "thị xã sơn tây - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "thịxãsơntây-hànội",
		"index" => 1
	],
	[
		"id" => "293",
		"name" => "Ba Vì - Hà Nội",
		"name_filter" => "ba vì - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "bavì-hànội",
		"index" => 1
	],
	[
		"id" => "295",
		"name" => "Chương Mỹ - Hà Nội",
		"name_filter" => "chương mỹ - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "chươngmỹ-hànội",
		"index" => 1
	],
	[
		"id" => "309",
		"name" => "Phúc Thọ - Hà Nội",
		"name_filter" => "phúc thọ - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúcthọ-hànội",
		"index" => 1
	],
	[
		"id" => "296",
		"name" => "Đan Phượng - Hà Nội",
		"name_filter" => "đan phượng - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "đanphượng-hànội",
		"index" => 1
	],
	[
		"id" => "297",
		"name" => "Đông Anh - Hà Nội",
		"name_filter" => "đông anh - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "đônganh-hànội",
		"index" => 1
	],
	[
		"id" => "299",
		"name" => "Gia Lâm - Hà Nội",
		"name_filter" => "gia lâm - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "gialâm-hànội",
		"index" => 1
	],
	[
		"id" => "302",
		"name" => "Hoài Đức - Hà Nội",
		"name_filter" => "hoài đức - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "hoàiđức-hànội",
		"index" => 1
	],
	[
		"id" => "306",
		"name" => "Mê Linh - Hà Nội",
		"name_filter" => "mê linh - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "mêlinh-hànội",
		"index" => 1
	],
	[
		"id" => "307",
		"name" => "Mỹ Đức - Hà Nội",
		"name_filter" => "mỹ đức - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "mỹđức-hànội",
		"index" => 1
	],
	[
		"id" => "308",
		"name" => "Phú Xuyên - Hà Nội",
		"name_filter" => "phú xuyên - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "phúxuyên-hànội",
		"index" => 1
	],
	[
		"id" => "310",
		"name" => "Quốc Oai - Hà Nội",
		"name_filter" => "quốc oai - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "quốcoai-hànội",
		"index" => 1
	],
	[
		"id" => "311",
		"name" => "Sóc Sơn - Hà Nội",
		"name_filter" => "sóc sơn - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "sócsơn-hànội",
		"index" => 1
	],
	[
		"id" => "314",
		"name" => "Thạch Thất - Hà Nội",
		"name_filter" => "thạch thất - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "thạchthất-hànội",
		"index" => 1
	],
	[
		"id" => "315",
		"name" => "Thanh Oai - Hà Nội",
		"name_filter" => "thanh oai - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhoai-hànội",
		"index" => 1
	],
	[
		"id" => "318",
		"name" => "Thường Tín - Hà Nội",
		"name_filter" => "thường tín - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "thườngtín-hànội",
		"index" => 1
	],
	[
		"id" => "320",
		"name" => "Ứng Hòa - Hà Nội",
		"name_filter" => "ứng hòa - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "ứnghòa-hànội",
		"index" => 1
	],
	[
		"id" => "316",
		"name" => "Thanh Trì - Hà Nội",
		"name_filter" => "thanh trì - hà nội",
		"category" => "Quận - Huyện",
		"name_nospace" => "thanhtrì-hànội",
		"index" => 1
	],
	[
		"id" => "135401",
		"area_id" => 135401,
		"name" => "Phú Thạnh - Tuy Hòa",
		"name_filter" => "phú thạnh - tuy hòa",
		"category" => "Phường - Xã",
		"name_nospace" => "phúthạnh-tuyhòa",
		"index" => 1
	],
	[
		"id" => "136496",
		"area_id" => 136496,
		"name" => "Hải Hoà - Thanh Hóa",
		"name_filter" => "hải hoà - thanh hóa",
		"category" => "Phường - Xã",
		"name_nospace" => "hảihoà-thanhhóa",
		"index" => 1
	],
	[
		"id" => "136609",
		"area_id" => 136609,
		"name" => "La Hai - Tuy Hòa",
		"name_filter" => "la hai - tuy hòa",
		"category" => "Phường - Xã",
		"name_nospace" => "lahai-tuyhòa",
		"index" => 1
	],
	[
		"id" => "136611",
		"area_id" => 136611,
		"name" => "Tháp Chàm - Ninh Thuận",
		"name_filter" => "tháp chàm - ninh thuận",
		"category" => "Phường - Xã",
		"name_nospace" => "thápchàm-ninhthuận",
		"index" => 1
	],
	[
		"id" => "136616",
		"area_id" => 136616,
		"name" => "Phú Lâm - Tuy Hòa",
		"name_filter" => "phú lâm - tuy hòa",
		"category" => "Phường - Xã",
		"name_nospace" => "phúlâm-tuyhòa",
		"index" => 1
	],
	[
		"id" => "136617",
		"area_id" => 136617,
		"name" => "Phú Đông - Tuy Hòa",
		"name_filter" => "phú đông - tuy hòa",
		"category" => "Phường - Xã",
		"name_nospace" => "phúđông-tuyhòa",
		"index" => 1
	],
	[
		"id" => "136618",
		"area_id" => 136618,
		"name" => "Phan Rí Cửa - Bình Thuận",
		"name_filter" => "phan rí cửa - bình thuận",
		"category" => "Phường - Xã",
		"name_nospace" => "phanrícửa-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "136619",
		"area_id" => 136619,
		"name" => "Chí Công - Bình Thuận",
		"name_filter" => "chí công - bình thuận",
		"category" => "Phường - Xã",
		"name_nospace" => "chícông-bìnhthuận",
		"index" => 1
	],
	[
		"id" => "136620",
		"area_id" => 136620,
		"name" => "Quan Lạn - Quảng Ninh",
		"name_filter" => "quan lạn - quảng ninh",
		"category" => "Phường - Xã",
		"name_nospace" => "quanlạn-quảngninh",
		"index" => 1
	],
	[
		"id" => "136622",
		"area_id" => 136622,
		"name" => "Đảo Mắt Rồng - Quảng Ninh",
		"name_filter" => "đảo mắt rồng - quảng ninh",
		"category" => "Phường - Xã",
		"name_nospace" => "đảomắtrồng-quảngninh",
		"index" => 1
	],
	[
		"id" => "136624",
		"area_id" => 136624,
		"name" => "Hòn Dấu - Hải Phòng",
		"name_filter" => "hòn dấu - hải phòng",
		"category" => "Phường - Xã",
		"name_nospace" => "hòndấu-hảiphòng",
		"index" => 1
	],
	[
		"id" => "136625",
		"area_id" => 136625,
		"name" => "Đảo Cái Chiên - Quảng Ninh",
		"name_filter" => "đảo cái chiên - quảng ninh",
		"category" => "Phường - Xã",
		"name_nospace" => "đảocáichiên-quảngninh",
		"index" => 1
	],
	[
		"id" => "135952",
		"area_id" => 135952,
		"name" => "Bến Xe Miền Trung - Nghệ An",
		"name_filter" => "bến xe miền trung - nghệ an",
		"category" => "Bến xe",
		"name_nospace" => "bếnxemiềntrung-nghệan",
		"index" => 1
	],
	[
		"id" => "1404",
		"area_id" => 1404,
		"name" => "Bến xe Bắc Vinh - Nghệ An",
		"name_filter" => "bến xe bắc vinh - nghệ an",
		"category" => "Bến xe",
		"name_nospace" => "bếnxebắcvinh-nghệan",
		"index" => 1
	],
	[
		"id" => "155849",
		"area_id" => 56,
		"name" => "Pù Luông - Bá Thước - Thanh Hóa",
		"name_filter" => "pù luông - bá thước - thanh hóa",
		"category" => "Bến xe",
		"name_nospace" => "pùluông-báthước-thanhhóa",
		"index" => 1
	],
	[
		"id" => "136789",
		"area_id" => 136789,
		"name" => "Nam Dong - Cư Jút",
		"name_filter" => "nam dong - cư jút",
		"category" => "Phường - Xã",
		"name_nospace" => "namdong-cưjút",
		"index" => 1
	],
	[
		"id" => "136799",
		"area_id" => 136799,
		"name" => "Bến xe khách Thượng Lý - Hải Phòng",
		"name_filter" => "bến xe khách thượng lý - hải phòng",
		"category" => "Bến xe",
		"name_nospace" => "bếnxekháchthượnglý-hảiphòng",
		"index" => 1
	],
	[
		"id" => "136225",
		"area_id" => 0,
		"name" => "Tuần Châu - Hạ Long",
		"name_filter" => "tuần châu - hạ long",
		"category" => "Phường - Xã",
		"name_nospace" => "tuầnchâu-hạlong",
		"index" => 1
	],
	[
		"id" => "136226",
		"area_id" => 0,
		"name" => "Bãi Cháy - Hạ Long",
		"name_filter" => "bãi cháy - hạ long",
		"category" => "Phường - Xã",
		"name_nospace" => "bãicháy-hạlong",
		"index" => 1
	],
	[
		"id" => "136227",
		"area_id" => 0,
		"name" => "Hòn Gai - Hạ Long",
		"name_filter" => "hòn gai - hạ long",
		"category" => "Phường - Xã",
		"name_nospace" => "hòngai-hạlong",
		"index" => 1
	],
	[
		"id" => "28438",
		"area_id" => 0,
		"name" => "Thị trấn Măng Đen",
		"name_filter" => "thị trấn măng đen",
		"category" => "Bến xe",
		"name_nospace" => "thịtrấnmăngđen",
		"index" => 1
	],
	[
        "id" => "2731",
        "area_id" => 0,
        "name" => "Bến xe Phú Lâm",
        "name_filter" => "ben xe phu lam",
        "category" => "Bến xe",
        "name_nospace" => "benxephulam"
	]
];