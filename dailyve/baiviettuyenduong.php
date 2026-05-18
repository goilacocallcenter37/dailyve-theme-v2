<?php
add_action( 'init', 'cammedia_tuyen_duong_register_post_type' );
function cammedia_tuyen_duong_register_post_type() {
	$args = [
		'label'  => esc_html__( 'Tuyến đường', 'text-domain' ),
		'labels' => [
			'menu_name'          => esc_html__( 'Tuyến đường', 'cammedia-textdomain' ),
			'name_admin_bar'     => esc_html__( 'Tuyến đường', 'cammedia-textdomain' ),
			'add_new'            => esc_html__( 'Thêm Tuyến đường', 'cammedia-textdomain' ),
			'add_new_item'       => esc_html__( 'Thêm mới Tuyến đường', 'cammedia-textdomain' ),
			'new_item'           => esc_html__( 'Tuyến đường mới', 'cammedia-textdomain' ),
			'edit_item'          => esc_html__( 'Chỉnh sửa Tuyến đường', 'cammedia-textdomain' ),
			'view_item'          => esc_html__( 'Xem Tuyến đường', 'cammedia-textdomain' ),
			'update_item'        => esc_html__( 'Xem Tuyến đường', 'cammedia-textdomain' ),
			'all_items'          => esc_html__( 'Tất cả Tuyến đường', 'cammedia-textdomain' ),
			'search_items'       => esc_html__( 'Tìm Tuyến đường', 'cammedia-textdomain' ),
			'parent_item_colon'  => esc_html__( 'Parent Tuyến đường', 'cammedia-textdomain' ),
			'not_found'          => esc_html__( 'Không tìm thấy Tuyến đường', 'cammedia-textdomain' ),
			'not_found_in_trash' => esc_html__( 'Không tìm thấy Tuyến đường', 'cammedia-textdomain' ),
			'name'               => esc_html__( 'Tuyến đường', 'cammedia-textdomain' ),
			'singular_name'      => esc_html__( 'Tuyến đường', 'cammedia-textdomain' ),
		],
		'public'              => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => false,
		'show_in_rest'        => false,
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite_no_front'    => false,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-location-alt',
		'supports' => [
			'title',
			'editor',
			'thumbnail',
		],
		
		'rewrite' => true
	];

	register_post_type( 'tuyen-duong', $args );
}

add_shortcode('baivietTuyenduong', 'cambaivietTuyenduongshortcode');
function cambaivietTuyenduongshortcode() {
	global $wp;
	$current_url = $wp->request;
	$current_url = explode('-', $current_url);
	$current_url = $current_url[array_key_last($current_url)];
	$current_url = explode('.', $current_url);
	$current_url = $current_url[0];
	$current_url = explode('t', $current_url);
	if (count($current_url)==2) {
		$args = array(
			'numberposts' => 1,
			'post_type' => 'tuyen-duong',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'tuyenduongfrom',
					'value' => $current_url[0]
				),
				array(
					'key' => 'tuyenduongto',
					'value' => $current_url[1]
				)
			)
		);
		$the_query = new WP_Query($args);
		if($the_query->have_posts()) :
			ob_start();
			while($the_query->have_posts()) : $the_query->the_post(); ?>
				<section class="section no-padding tuyenduong-section">
				 	<div class="section-content relative">
				 		<div class="row">
				 			<div class="col small-12 large-12">
				 				<h1 class="title_tuyenduong"><?php the_title(); ?></h1>
				 				<div class="col-inner">
									<?php the_content(); ?>
				 				</div>
				 			</div>
				 		</div>
				 	</div>
				 </section>
			<?php endwhile;
			wp_reset_postdata();
			$content = ob_get_clean();
			return $content;	
		endif;
		// $baiviet = get_posts($args);
		// if (count($baiviet)==1) {
		// 	return '<section class="section no-padding">
		// 	<div class="section-content relative">
		// 		<div class="row">
		// 			<div class="col small-12 large-12">
		// 				<h1 class="title_tuyenduong">'.$baiviet[0]->post_title.'</h1>
		// 				<div class="col-inner">
		// 				' . $baiviet[0]->post_content . '
		// 				</div>
		// 			</div>
		// 		</div>
		// 	</div>
		// </section>';
		// }
	}
	return '';
}

class Tuyenduong_Post {
	private $config = '{"title":"Tuy\u1ebfn \u0111\u01b0\u1eddng","prefix":"tuyenduong","domain":"cammedia-textdomain","class_name":"Tuyenduong_Post","context":"normal","priority":"default","cpt":"tuyen-duong","fields":[{"type":"select","label":"From","options":"1: Ha noi\r\n2: HCM","id":"tuyenduongfrom"},{"type":"select","label":"To","options":"1: Ha noi\r\n2: HCM","id":"tuyenduongto"}]}';
	private $tuyenduong;

	public function __construct() {
		global $dulieuTuyenduong;
		$this->tuyenduong = $dulieuTuyenduong;
		$this->config = json_decode( $this->config, true );
		$this->process_cpts();
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action( 'save_post', [ $this, 'save_post' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_select2_jquery' ] );
		
	}
	
	public function enqueue_select2_jquery() {
		// wp_register_style( 'select2css', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css', false, '1.0', 'all' );
		// wp_register_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.js', array( 'jquery' ), '1.0', true );
		// wp_enqueue_style( 'select2css' );
		// wp_enqueue_script( 'select2' );
	}

	public function process_cpts() {
		if ( !empty( $this->config['cpt'] ) ) {
			if ( empty( $this->config['post-type'] ) ) {
				$this->config['post-type'] = [];
			}
			$parts = explode( ',', $this->config['cpt'] );
			$parts = array_map( 'trim', $parts );
			$this->config['post-type'] = array_merge( $this->config['post-type'], $parts );
		}
	}

	public function add_meta_boxes() {
		foreach ( $this->config['post-type'] as $screen ) {
			add_meta_box(
				sanitize_title( $this->config['title'] ),
				$this->config['title'],
				[ $this, 'add_meta_box_callback' ],
				$screen,
				$this->config['context'],
				$this->config['priority']
			);
		}
	}

	public function save_post( $post_id ) {
		foreach ( $this->config['fields'] as $field ) {
			switch ( $field['type'] ) {
				default:
					if ( isset( $_POST[ $field['id'] ] ) ) {
						$sanitized = sanitize_text_field( $_POST[ $field['id'] ] );
						update_post_meta( $post_id, $field['id'], $sanitized );
					}
			}
		}
	}

	public function add_meta_box_callback() {
		$this->fields_table();
	}

	private function fields_table() {
// 		$baiviet = get_post(34389);
// 		print_r($baiviet);
// 		$postmetas = get_post_meta(34389);
// 		print_r($postmetas);//tuyenduongfrom, tuyenduongto
// 		$metadata = get_post_meta(34389, 'tuyenduongfrom', true);
// 		print_r($metadata);
		$args = array(
			'numberposts' => 1,
			'post_type' => 'tuyen-duong',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'tuyenduongfrom',
					'value' => 24
				),
				array(
					'key' => 'tuyenduongto',
					'value' => 27
				)
			)
		);
// 		$baiviet = get_posts($args);
// 		print_r($posts);
		?><table class="form-table" role="presentation">
			<tbody><?php
				foreach ( $this->config['fields'] as $field ) {
					?><tr>
						<th scope="row"><?php $this->label( $field ); ?></th>
						<td><?php $this->field( $field ); ?></td>
					</tr><?php
				}
			?>
				<tr>
					<th scope="row"><label></label></th>
					<td style="position: relative;"><input type="text" id="urlInp" readonly style="width: 100%;" placeholder="SEO URL" /><span class="d-none" id="thongbao">Copied</span></td>
				</tr>
	</tbody>
		</table>
<style>
	.d-none {
		display: none;
	}
	.d-inline {
		display: inline;
	}
	#thongbao {
		position: absolute;
		top: 50%;
		right: 0;
		background: black;
		color: white;
		padding: 0 5px;
	}
	.select2 {
		width: 300px;
	}
</style>
<script type='text/javascript'>
	function toSlug(str) {
		// Chuyển hết sang chữ thường
		str = str.toLowerCase();     

		// xóa dấu
		str = str
			.normalize('NFD') // chuyển chuỗi sang unicode tổ hợp
			.replace(/[\u0300-\u036f]/g, ''); // xóa các ký tự dấu sau khi tách tổ hợp

		// Thay ký tự đĐ
		str = str.replace(/[đĐ]/g, 'd');

		// Xóa ký tự đặc biệt
		str = str.replace(/([^0-9a-z-\s])/g, '');

		// Xóa khoảng trắng thay bằng ký tự -
		str = str.replace(/(\s+)/g, '-');

		// Xóa ký tự - liên tiếp
		str = str.replace(/-+/g, '-');

		// xóa phần dư - ở đầu & cuối
		str = str.replace(/^-+|-+$/g, '');

		// return
		return str;
	}
	jQuery(document).ready(function ($) {
		if( $( 'select.select2' ).length > 0 ) {
			$( 'select.select2' ).select2();
		}
		var urlPath = new URL(window.location.href);

		function chuyendoi() {
			var idFrom = $('#tuyenduongfrom').val();
			var idTo = $('#tuyenduongto').val();
			var nameFrom = $('#tuyenduongfrom option:selected').text();
			var nameTo = $('#tuyenduongto option:selected').text();
			var slugFrom = toSlug(nameFrom);
			var slugTo = toSlug(nameTo);
			if (slugFrom=='ho-chi-minhg') {
				slugFrom = 'sai-gon';
			}
			if (slugTo=='ho-chi-minhg') {
				slugTo = 'sai-gon';
			}
			var url = urlPath.origin + '/ve-xe-khach-tu-'+slugFrom+'-di-'+slugTo+'-'+idFrom+'t'+idTo+'.html';
			$('#urlInp').val(url);
			$('#urlInp').select();
		}
		$(document.body).on("change","select.select2",function(e) {
			chuyendoi();
		});
		$(document.body).on("click","#urlInp",function(e) {
			$(this).select();
			document.execCommand("copy");
			$('#thongbao').removeClass('d-none').addClass('d-inline');
			setTimeout(function() {
				$('#thongbao').removeClass('d-inline').addClass('d-none');
			}, 3000);
		});
		chuyendoi();
	});
</script>
		<?php
	}

	private function label( $field ) {
		switch ( $field['type'] ) {
			default:
				printf(
					'<label class="" for="%s">%s</label>',
					$field['id'], $field['label']
				);
		}
	}

	private function field( $field ) {
		switch ( $field['type'] ) {
			case 'select':
				$this->select( $field );
				break;
			default:
				$this->input( $field );
		}
	}

	private function input( $field ) {
		printf(
			'<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
			isset( $field['class'] ) ? $field['class'] : '',
			$field['id'], $field['id'],
			isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
			$field['type'],
			$this->value( $field )
		);
	}

	private function select( $field ) {
		printf(
			'<select id="%s" name="%s" class="select2 %s">%s</select>',
			$field['id'], $field['id'], $field['id'],
			$this->select_options( $field )
		);
	}

	private function select_selected( $field, $current ) {
		$value = $this->value( $field );
		if ( $value == $current ) {
			return 'selected';
		}
		return '';
	}

	private function select_options( $field ) {
		$output = [];
// 		$options = explode( "\r\n", $field['options'] );
// 		$i = 0;
// 		foreach ( $options as $option ) {
// 			$pair = explode( ':', $option );
// 			$pair = array_map( 'trim', $pair );
// 			$output[] = sprintf(
// 				'<option %s value="%s"> %s</option>',
// 				$this->select_selected( $field, $pair[0] ),
// 				$pair[0], $pair[1]
// 			);
// 			$i++;
// 		}
		foreach ( $this->tuyenduong as $option ) {
			$output[] = sprintf(
				'<option %s value="%s"> %s</option>',
				$this->select_selected( $field, $option['id'] ),
				$option['id'], $option['name']
			);
		}
		return implode( '<br>', $output );
	}

	private function value( $field ) {
		global $post;
		if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
			$value = get_post_meta( $post->ID, $field['id'], true );
		} else if ( isset( $field['default'] ) ) {
			$value = $field['default'];
		} else {
			return '';
		}
		return str_replace( '\u0027', "'", $value );
	}

}
new Tuyenduong_Post;