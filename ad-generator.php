<?php
/**
 * Plugin Name:		Ad Generator
 * Plugin URI:		https://github.com/AiratHalitov/ad-generator
 * Description:		Professional text randomizer and ad generator.
 * Author:		Airat Halitov
 * Author URI:		https://airat.biz
 * Version:		1.2.3
 * Text Domain:		ad-generator
 * Domain Path:		/languages/
 * GitHub Plugin URI:	airathalitov/ad-generator
 */
/**
 * @package		airathalitov/ad-generator
 * @category		Core
 * @author		Airat Halitov
 * @license		GPLv3
 * @link		https://github.com/AiratHalitov/ad-generator
 * @version		1.2.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ad_generator_shortcode {
	
	static $max_res = 10;
	static $mydomain = 'ad-generator';
	
	static function init () {
		add_shortcode( 'ad_generator', array( __CLASS__, 'ad_generator_func' ) );
		add_action( 'plugins_loaded', array( __CLASS__, 'ad_generator_textdomain' ) );
	}
	
	static function ad_generator_textdomain () {
		load_plugin_textdomain( self::$mydomain, false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
	
	static function ad_generator_func( $atts ) {
		$result_text = '';
		$result_text .= '<form method="post" action="">';
		$ad_text = isset( $_POST['ad_text'] ) ? (string) $_POST['ad_text'] : '';
		$ad_text = str_replace( '\\\\', '\\', $ad_text );
		$ad_text = str_replace( '\\"', '"', $ad_text );
		$ad_text = str_replace( "\\'", "'", $ad_text );
		
		$result_text .= '<textarea id="ad_text" name="ad_text" cols="80" rows="10" autofocus maxlength="4000" style="width: 100%;" placeholder="' . __( 'Введите шаблон', self::$mydomain ) . '">';
		
		if ( $ad_text ) {
			$result_text .= htmlspecialchars( $ad_text );
		} else {
			$result_text .= __( 'Это {|, пожалуй,} самый {лучший|прекрасный|отличный} {рандомизатор|рандомайзер} текста, который я только {видел|встречал}. Он такой [+,+удобный|быстрый] и функциональный {, что ничего другого уже не нужно|- мне всё в нем нравится} {!|.|. : )} {Спасибо!|Спасибо большое!|Спасибо, Айрат!}', self::$mydomain );
		}
		
		$result_text .= '</textarea><br /><button id="ad_text_btn" class="btn btn-large btn-primary" type="submit">' . __( 'Генерировать', self::$mydomain ) . '</button></form>';
		
		if ( $ad_text ) {
			$result_text .= '<br /><a href=' . $_SERVER['REQUEST_URI'] . ' id="ad_text_clear_btn">' . __( 'Очистить и начать заново', self::$mydomain ) . '</a>';
			
			require_once plugin_dir_path( __FILE__ ) . '/includes/Natty/TextRandomizer.php';
			
			$tRand = new Natty_TextRandomizer( $ad_text );
			$num_var = $tRand->numVariant();
			
			if ( $num_var > 1 ) {
				$max_tmp = min( $num_var, self::$max_res );
				$result_text .= sprintf( __( '<p><i>Число всех возможных вариантов: <strong>%s</strong>. Вот случайные <strong>%s</strong> из них ( возможны повторения ):</i></p>', self::$mydomain ), $num_var, $max_tmp );
				
				for ( $i = 0; $i < $max_tmp; ++$i ) {
					$result_text .= '<p id="ad_text_result">' . nl2br( htmlspecialchars( $tRand->getText() ) ) . '</p><hr />';
				}
			} else {
				$result_text .= __( '<p><i>Только <strong>1</strong> возможный вариант:</i></p>', self::$mydomain );
				$result_text .= '<p id="ad_text_result">' . nl2br( htmlspecialchars( $tRand->getText() ) ) . '</p><hr />';
			}
		}
		$myGH = 'https://github.com/AiratHalitov/ad-generator';
		$result_text .= sprintf( __( '<br /><p>Страница проекта на GitHub: <a href="%s" target=_blank>%s</a>', self::$mydomain ), $myGH, $myGH );
		
		return $result_text;
	}
}

ad_generator_shortcode::init();
