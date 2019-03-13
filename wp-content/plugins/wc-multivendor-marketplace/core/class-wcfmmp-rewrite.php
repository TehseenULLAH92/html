<?php
/**
 * WCFMmp plugin core
 *
 * WCfMmp Rewrite
 *
 * @author 		WC Lovers
 * @package 	wcfmmp/core
 * @version   1.0.0
 */
 
class WCFMmp_Rewrites {

	public $query_vars = array();
	public $wcfm_store_url = '';

	/**
	 * Hook into the functions
	 */
	public function __construct() {
		$this->wcfm_store_url = get_option( 'wcfm_store_url', 'store' );

		add_action( 'init', array( $this, 'register_rule' ), 9 );
		
		add_action( 'init', array( $this, 'custom_taxonomy_register_rule' ), 11 );

		add_filter( 'template_include', array( $this, 'store_template' ) );

		add_filter( 'query_vars', array( $this, 'register_query_var' ) );
		add_filter( 'pre_get_posts', array( $this, 'store_query_filter' ) );
		add_filter( 'woocommerce_get_breadcrumb', array( $this, 'store_page_breadcrumb'), 10 ,1  );
		
		// Store Page WPML Switcher Compatibility
		add_filter( 'icl_ls_languages', array( &$this, 'wcfmmp_store_page_wpml_language_switcher' ) );
	}


	/**
	 * Initializes the WCFMmp_Rewrites() class
	 *
	 * @since 1.0.0
	 *
	 * Checks for an existing WCFMmp_Rewrites() instance
	 * and if it doesn't find one, creates it.
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new WCFMmp_Rewrites();
		}

		return $instance;
	}

	/**
	 * Generate breadcrumb for store page
	 *
	 * @since 1.0.0
	 *
	 * @param array $crumbs
	 *
	 * @return array $crumbs
	 */
	public function store_page_breadcrumb( $crumbs ){
		if (  wcfm_is_store_page() ) {
			$author      = get_query_var( $this->wcfm_store_url );
			$seller_info = get_user_by( 'slug', $author );
			if( $seller_info ) {
				$crumbs[1]   = array( ucwords($this->wcfm_store_url) , site_url().'/'.$this->wcfm_store_url );
				$crumbs[2]   = array( $author, wcfmmp_get_store_url( $seller_info->data->ID ) );
			}
		}

		return $crumbs;
	}
	
	/**
	 * Store Page WPML Language Switcher Compatibility
	 */
	function wcfmmp_store_page_wpml_language_switcher( $languages ) {
		
		if (  wcfm_is_store_page() ) {
			if ( defined( 'ICL_SITEPRESS_VERSION' ) && ! ICL_PLUGIN_INACTIVE && class_exists( 'SitePress' ) ) {
				global $sitepress;
				$author      = get_query_var( $this->wcfm_store_url );
				$formated_languages = array();
				
				$default_lang = $sitepress->get_default_language();
				
				if( !empty( $languages ) ) {
					foreach( $languages as $lang => $language ) {
						if( $default_lang  && ( $default_lang  == $language['language_code'] ) ) {
							$language['url'] = site_url() .'/'. $this->wcfm_store_url .'/'. $author;
						} else {
							$language['url'] = site_url() .'/'. $language['language_code'] .'/'. $this->wcfm_store_url .'/'. $author;
						}
						$formated_languages[$lang] = $language;
					}
					$languages = $formated_languages;
				}
			}
		}
		
		return $languages;
	}

	/**
	 * Register the rewrite rule
	 *
	 * @return void
	 */
	function register_rule() {
		
		$wcfm_store_modified_endpoints = get_option( 'wcfm_store_endpoints', array() );
		
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]', 'top' );
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&paged=$matches[2]', 'top' );
		
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/'.$this->store_endpoint('about').'?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&'.$this->store_endpoint('about').'=true', 'top' );
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/'.$this->store_endpoint('policies').'?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&'.$this->store_endpoint('policies').'=true', 'top' );
		
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/'.$this->store_endpoint('reviews').'?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&'.$this->store_endpoint('reviews').'=true', 'top' );
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/'.$this->store_endpoint('reviews').'/page/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&paged=$matches[2]&'.$this->store_endpoint('reviews').'=true', 'top' );
		
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/'.$this->store_endpoint('followers').'?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&'.$this->store_endpoint('followers').'=true', 'top' );
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/'.$this->store_endpoint('followers').'/page/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&paged=$matches[2]&'.$this->store_endpoint('followers').'=true', 'top' );
		
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/'.$this->store_endpoint('followings').'?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&'.$this->store_endpoint('followings').'=true', 'top' );
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/'.$this->store_endpoint('followings').'/page/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&paged=$matches[2]&'.$this->store_endpoint('followings').'=true', 'top' );
		
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/'.$this->store_endpoint('articles').'?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&'.$this->store_endpoint('articles').'=true', 'top' );
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/'.$this->store_endpoint('articles').'/page/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&paged=$matches[2]&'.$this->store_endpoint('articles').'=true', 'top' );
		
		add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/category/?([^/]*)/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&term=$matches[2]&term_section=true', 'top' );
    add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/category/?([^/]*)/page/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&term=$matches[2]&paged=$matches[3]&term_section=true', 'top' );
    
		do_action( 'wcfmmp_rewrite_rules_loaded', $this->wcfm_store_url );
	}
	
	function custom_taxonomy_register_rule() {
		
		// Custom Taxonomies Filter Rules
		$product_taxonomies = get_object_taxonomies( 'product', 'objects' );
		if( !empty( $product_taxonomies ) ) {
			foreach( $product_taxonomies as $product_taxonomy ) {
				if( !in_array( $product_taxonomy->name, array( 'product_cat', 'product_tag', 'wcpv_product_vendors' ) ) ) {
					if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && $product_taxonomy->hierarchical ) {
						add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/tax-'.$product_taxonomy->name.'/?([^/]*)/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&term=$matches[2]&tax-'.$product_taxonomy->name.'=true&term_section=true', 'top' );
						add_rewrite_rule( $this->wcfm_store_url.'/([^/]+)/tax-'.$product_taxonomy->name.'/?([^/]*)/page/?([0-9]{1,})/?$', 'index.php?'.$this->wcfm_store_url.'=$matches[1]&term=$matches[2]&paged=$matches[3]&tax-'.$product_taxonomy->name.'=true&term_section=true', 'top' );
					}
				}
			}
		}
	}
	
	/**
	 * Register the query var
	 *
	 * @param array  $vars
	 *
	 * @return array
	 */
	function register_query_var( $vars ) {
		$vars[] = $this->wcfm_store_url;
		$vars[] = 'term_section';
		$vars[] = $this->store_endpoint( 'about' );
		$vars[] = $this->store_endpoint( 'policies' );
		$vars[] = $this->store_endpoint( 'reviews' );
		$vars[] = $this->store_endpoint( 'followers' );
		$vars[] = $this->store_endpoint( 'followings' );
		$vars[] = $this->store_endpoint( 'articles' );
		
		// Custom Taxonomies Filters Query Vars
		$product_taxonomies = get_object_taxonomies( 'product', 'objects' );
		if( !empty( $product_taxonomies ) ) {
			foreach( $product_taxonomies as $product_taxonomy ) {
				if( !in_array( $product_taxonomy->name, array( 'product_cat', 'product_tag', 'wcpv_product_vendors' ) ) ) {
					if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && $product_taxonomy->hierarchical ) {
						$vars[] = 'tax-'.$product_taxonomy->name;
					}
				}
			}
		}
		
		foreach ( $this->query_vars as $var ) {
			$vars[] = $var;
		}

		return $vars;
	}

	/**
	 * Include store template
	 *
	 * @param type  $template
	 *
	 * @return string
	 */
	function store_template( $template ) {
		global $WCFM, $WCFMmp;
		
		if ( !WCFMmp_Dependencies::woocommerce_plugin_active_check() ) {
			return $template;
		}
		
		$store_name = get_query_var( $this->wcfm_store_url );

		if ( !empty( $store_name ) ) {
			$store_user = get_user_by( 'slug', $store_name );
			
			// no user found
			if ( ! $store_user ) {
				return get_404_template();
			}

			// check if the user is seller
			if ( ! wcfm_is_vendor( $store_user->ID ) ) {
				return get_404_template();
			}
			
			// Check is store Online
			$is_store_offline = get_user_meta( $store_user->ID, '_wcfm_store_offline', true );
			$is_store_offline = apply_filters( 'wcfmmp_is_store_offline', $is_store_offline, $store_user->ID );
			if ( $is_store_offline ) {
				return get_404_template();
			}

			if ( get_query_var( $this->store_endpoint('about') ) ) {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => 'about' ) );
			} elseif ( get_query_var( $this->store_endpoint('policies') ) ) {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => 'policies' ) );
			} elseif ( get_query_var( $this->store_endpoint('reviews') ) ) {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => 'reviews' ) );
			} elseif ( get_query_var( $this->store_endpoint('followers') ) ) {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => 'followers' ) );
			} elseif ( get_query_var( $this->store_endpoint('followings') ) ) {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => 'followings' ) );
			} elseif ( get_query_var( $this->store_endpoint('articles') ) ) {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => 'articles' ) );
			} else {
				return $WCFMmp->template->get_template( 'store/wcfmmp-view-store.php', array( 'store_tab' => apply_filters( 'wcfmp_store_default_query_vars', 'products' ) ) );
			}
		}

		return $template;
	}
	
	function store_endpoint( $endpoint ) {
		global $WCFMmp;
		$endpoint = !empty( $WCFMmp->wcfmmp_store_endpoints[$endpoint] ) ? $WCFMmp->wcfmmp_store_endpoints[$endpoint] : $endpoint;
		return sanitize_title($endpoint);
	}

	/**
	 * Store query filter
	 *
	 * Handles the product filtering by category in store page
	 *
	 * @param object  $query
	 *
	 * @return void
	 */
	function store_query_filter( $query ) {
		global $wp_query, $WCFMmp;
		
		$author = get_query_var( $this->wcfm_store_url );

		if ( !is_admin() && $query->is_main_query() && !empty( $author ) ) {
			$seller_info  = get_user_by( 'slug', $author );
			if( $seller_info ) {
				
				// WC Product Query
				if ( !get_query_var( 'articles' ) ) {
					WC()->query->product_query( $query );
				}
				
				$store_info   = wcfmmp_get_store_info( $seller_info->data->ID );
				
				if( apply_filters( 'wcfmmp_is_allow_store_ppp', true ) ) {
					$global_store_ppp = isset( $WCFMmp->wcfmmp_marketplace_options['store_ppp'] ) ? $WCFMmp->wcfmmp_marketplace_options['store_ppp'] : get_option('posts_per_page');
					$post_per_page = isset( $store_info['store_ppp'] ) && !empty( $store_info['store_ppp'] ) ? $store_info['store_ppp'] : $global_store_ppp;
					$query->set( 'posts_per_page', apply_filters( 'wcfmmp_store_ppp', $post_per_page ) );
				}
				
				if ( get_query_var( 'articles' ) ) {
					$query->set( 'post_type', 'post' );
				} else {
					$query->set( 'post_type', 'product' );
				}
				$query->set( 'author_name', $author );
				$query->query['term_section'] = isset( $query->query['term_section'] ) ? $query->query['term_section'] : array();

				if ( $query->query['term_section'] ) {
					$is_custom_taxonomy_filter = false;
					// Custom Taxonomies Filter Rules
					$product_taxonomies = get_object_taxonomies( 'product', 'objects' );
					if( !empty( $product_taxonomies ) ) {
						foreach( $product_taxonomies as $product_taxonomy ) {
							if( !in_array( $product_taxonomy->name, array( 'product_cat', 'product_tag', 'wcpv_product_vendors' ) ) ) {
								if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && $product_taxonomy->hierarchical ) {
									$query->query['tax-'.$product_taxonomy->name] = isset( $query->query['tax-'.$product_taxonomy->name] ) ? $query->query['tax-'.$product_taxonomy->name] : array();
									
									if ( $query->query['tax-'.$product_taxonomy->name] ) {
									  $is_custom_taxonomy_filter = true;
										$query->set( 'tax_query',
											array(
												'relation' => 'AND',
												array(
													'taxonomy' => $product_taxonomy->name,
													'field'    => 'slug',
													'terms'    => $query->query['term'],
													'operator' => 'IN'
												)
											)
										);
									}
								}
							}
						}
					}
					
					if( !$is_custom_taxonomy_filter ) {
						$query->set( 'tax_query',
							array(
								array(
									'taxonomy' => 'product_cat',
									'field'    => 'slug',
									'terms'    => $query->query['term']
								)
							)
						);
					}
					
					//print_r($query);
				}
			}
		}
	}
}
