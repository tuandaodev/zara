<?php if ( ! defined('BASEL_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Prepare CSS selectors for theme settions (colors, borders, typography etc.)
 * ------------------------------------------------------------------------------------------------
 */

return apply_filters( 'basel_get_selectors', array(

	'primary-color' => array(
		'color' => basel_text2line('
.color-primary,
.mobile-nav ul li.current-menu-item > a,
.main-nav .menu > li.current-menu-item > a,
.main-nav .menu > li.onepage-link.current-menu-item > a,
.main-nav .menu > li > a:hover,
.main-nav .menu > li > a:focus,
.basel-navigation .menu>li.menu-item-design-default ul li:hover>a,
.basel-navigation .menu > li.menu-item-design-full-width .sub-menu li a:hover, 
.basel-navigation .menu > li.menu-item-design-sized .sub-menu li a:hover,

.basel-product-categories.responsive-cateogires li.current-cat > a, 
.basel-product-categories.responsive-cateogires li.current-cat-parent > a,
.basel-product-categories.responsive-cateogires li.current-cat-ancestor > a,

.basel-my-account-links a:hover:before, 
.basel-my-account-links a:focus:before,

.mega-menu-list > li > a:hover,
.mega-menu-list .sub-sub-menu li a:hover,

a[href^="tel"],

.topbar-menu ul > li > .sub-menu-dropdown li > a:hover,

.btn.btn-color-primary.btn-style-bordered,
.button.btn-color-primary.btn-style-bordered,
button.btn-color-primary.btn-style-bordered,
.added_to_cart.btn-color-primary.btn-style-bordered,
input[type="submit"].btn-color-primary.btn-style-bordered,

a.login-to-prices-msg,
a.login-to-prices-msg:hover,

.basel-dark .single-product-content .entry-summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:before, 
.basel-dark .single-product-content .entry-summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:before,

.basel-dark .read-more-section .btn-read-more,

.basel-dark .products-footer .basel-blog-load-more, 
.basel-dark .products-footer .basel-products-load-more, 
.basel-dark .products-footer .basel-portfolio-load-more, 
.basel-dark .blog-footer .basel-blog-load-more, 
.basel-dark .blog-footer .basel-products-load-more, 
.basel-dark .blog-footer .basel-portfolio-load-more, 
.basel-dark .portfolio-footer .basel-blog-load-more, 
.basel-dark .portfolio-footer .basel-products-load-more, 
.basel-dark .portfolio-footer .basel-portfolio-load-more,
.basel-dark .color-primary,

.basel-hover-link .swap-elements .btn-add a,
.basel-hover-link .swap-elements .btn-add a:hover,
.basel-hover-link .swap-elements .btn-add a:focus,

.blog-post-loop .entry-title a:hover,
.blog-post-loop.sticky .entry-title:before,
.post-slide .entry-title a:hover,
.comments-area .reply a,
.single-post-navigation a:hover,
blockquote footer:before,
blockquote cite,
.format-quote .entry-content blockquote cite, 
.format-quote .entry-content blockquote cite a,

.basel-entry-meta .meta-author a,

.search-no-results.woocommerce .site-content:before,
.search-no-results .not-found .entry-header:before,

.login-form-footer .lost_password:hover, 
.login-form-footer .lost_password:focus,

.error404 .page-title,

.menu-label-new:after,

.widget_shopping_cart .product_list_widget li .quantity .amount,
.product_list_widget li ins .amount,
.price ins > .amount,
.price ins,
.single-product .price,
.single-product .price .amount,
.popup-quick-view .price,
.popup-quick-view .price .amount,
.basel-products-nav .product-short .price,
.basel-products-nav .product-short .price .amount,
.star-rating span:before,
.comment-respond .stars a:hover:after,
.comment-respond .stars a.active:after,
.single-product-content .comment-form .stars span a:hover,
.single-product-content .comment-form .stars span a.active,
.tabs-layout-accordion .basel-tab-wrapper .basel-accordion-title:hover,
.tabs-layout-accordion .basel-tab-wrapper .basel-accordion-title.active,
.single-product-content .woocommerce-product-details__short-description ul > li:before, 
.single-product-content #tab-description ul > li:before, 
.blog-post-loop .entry-content ul > li:before, 
.comments-area .comment-list li ul > li:before,
.brands-list .brand-item a:hover,
.footer-container .footer-widget-collapse.footer-widget-opened .widget-title:after,

.sidebar-widget li a:hover, 
.filter-widget li a:hover,
.sidebar-widget li > ul li a:hover, 
.filter-widget li > ul li a:hover,
.basel-price-filter ul li a:hover .amount,

.basel-hover-effect-4 .swap-elements > a,
.basel-hover-effect-4 .swap-elements > a:hover,

.product-grid-item .basel-product-cats a:hover, 
.product-grid-item .basel-product-brands-links a:hover,

.wishlist_table tr td.product-price ins .amount,

.basel-buttons .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse > a, 
.basel-buttons .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse > a,
.basel-buttons .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse > a:hover, 
.basel-buttons .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse > a:hover,
.basel-buttons .product-compare-button > a.added:before,

.single-product-content .entry-summary .yith-wcwl-add-to-wishlist a:hover,
.single-product-content .container .entry-summary .yith-wcwl-add-to-wishlist a:hover:before,
.single-product-content .entry-summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a:before, 
.single-product-content .entry-summary .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a:before,
.single-product-content .entry-summary .yith-wcwl-add-to-wishlist .yith-wcwl-add-button.feid-in > a:before,
.basel-sticky-btn .basel-sticky-btn-wishlist.exists, 
.basel-sticky-btn .basel-sticky-btn-wishlist:hover,

.vendors-list ul li a:hover,

.product-list-item .product-list-buttons .yith-wcwl-add-to-wishlist a:hover,
.product-list-item .product-list-buttons .yith-wcwl-add-to-wishlist a:focus, 
.product-list-item .product-list-buttons .product-compare-button a:hover,
.product-list-item .product-list-buttons .product-compare-button a:focus,

.product-list-item .product-list-buttons .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse > a:before,
.product-list-item .product-list-buttons .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse > a:before,
.product-list-item .product-list-buttons .product-compare-button > a.added:before,

.single-product-content .entry-summary .compare-btn-wrapper a:hover,
.single-product-content .entry-summary .compare-btn-wrapper a:hover:before,
.single-product-content .entry-summary .compare-btn-wrapper a.added:before,

.single-product-content .entry-summary .basel-sizeguide-btn:hover,
.single-product-content .entry-summary .basel-sizeguide-btn:hover:before,

.blog-post-loop .entry-content ul li:before,

.basel-menu-price .menu-price-price,
.basel-menu-price.cursor-pointer:hover .menu-price-title,

.comments-area #cancel-comment-reply-link:hover,
.comments-area .comment-body .comment-edit-link:hover,

.popup-quick-view .entry-summary .entry-title a:hover,

.wpb_text_column ul:not(.social-icons) > li:before,

.widget_product_categories .basel-cats-toggle:hover,
.widget_product_categories .toggle-active,
.widget_product_categories li.current-cat-parent > a, 
.widget_product_categories li.current-cat > a,

.woocommerce-checkout-review-order-table tfoot .order-total td .amount,

.widget_shopping_cart .product_list_widget li .remove:hover,

.basel-active-filters .widget_layered_nav_filters ul li a .amount,

.title-wrapper.basel-title-color-primary .title-subtitle,
.widget_shopping_cart .widget_shopping_cart_content > .total .amount,
.color-scheme-light .vc_tta-tabs.vc_tta-tabs-position-top.vc_tta-style-classic .vc_tta-tab.vc_active > a,
.wpb-js-composer .vc_tta.vc_general.vc_tta-style-classic .vc_tta-tab.vc_active > a
		'),
		'background-color' => basel_text2line('
.wishlist-info-widget .wishlist-count,
.basel-toolbar-compare .compare-count,
.basel-cart-design-2 > a .basel-cart-number,
.basel-cart-design-3 > a .basel-cart-number,

.basel-sticky-sidebar-opener:not(.sticky-toolbar):hover,
.basel-sticky-sidebar-opener:not(.sticky-toolbar):focus,

.btn.btn-color-primary,
.button.btn-color-primary,
button.btn-color-primary,
.added_to_cart.btn-color-primary,
input[type="submit"].btn-color-primary,
.btn.btn-color-primary:hover, 
.btn.btn-color-primary:focus, 
.button.btn-color-primary:hover, 
.button.btn-color-primary:focus, 
button.btn-color-primary:hover, 
button.btn-color-primary:focus, 
.added_to_cart.btn-color-primary:hover, 
.added_to_cart.btn-color-primary:focus, 
input[type="submit"].btn-color-primary:hover, 
input[type="submit"].btn-color-primary:focus,
.btn.btn-color-primary.btn-style-bordered:hover,
.btn.btn-color-primary.btn-style-bordered:focus,
.button.btn-color-primary.btn-style-bordered:hover,
.button.btn-color-primary.btn-style-bordered:focus,
button.btn-color-primary.btn-style-bordered:hover,
button.btn-color-primary.btn-style-bordered:focus,
.added_to_cart.btn-color-primary.btn-style-bordered:hover,
.added_to_cart.btn-color-primary.btn-style-bordered:focus,
input[type="submit"].btn-color-primary.btn-style-bordered:hover,
input[type="submit"].btn-color-primary.btn-style-bordered:focus,
.widget_shopping_cart .widget_shopping_cart_content .buttons .checkout,
.widget_shopping_cart .widget_shopping_cart_content .buttons .checkout:hover, 
.widget_shopping_cart .widget_shopping_cart_content .buttons .checkout:focus,
.basel-search-dropdown .basel-search-wrapper .basel-search-inner form button,
.basel-search-dropdown .basel-search-wrapper .basel-search-inner form button:hover,
.basel-search-dropdown .basel-search-wrapper .basel-search-inner form button:focus,
.no-results .searchform #searchsubmit,
.no-results .searchform #searchsubmit:hover,
.no-results .searchform #searchsubmit:focus,
.comments-area .comment-respond input[type="submit"],
.comments-area .comment-respond input[type="submit"]:hover,
.comments-area .comment-respond input[type="submit"]:focus,
.woocommerce .cart-collaterals .cart_totals .wc-proceed-to-checkout > a.button,
.woocommerce .cart-collaterals .cart_totals .wc-proceed-to-checkout > a.button:hover,
.woocommerce .cart-collaterals .cart_totals .wc-proceed-to-checkout > a.button:focus,
.woocommerce .checkout_coupon .button,
.woocommerce .checkout_coupon .button:hover,
.woocommerce .checkout_coupon .button:focus,
.woocommerce .place-order button,
.woocommerce .place-order button:hover,
.woocommerce .place-order button:focus,
.woocommerce-order-pay #order_review .button,
.woocommerce-order-pay #order_review .button:hover,
.woocommerce-order-pay #order_review .button:focus,
.woocommerce input[name="track"],
.woocommerce input[name="track"]:hover,
.woocommerce input[name="track"]:focus,
.woocommerce input[name="save_account_details"],
.woocommerce input[name="save_address"],
.woocommerce-page input[name="save_account_details"],
.woocommerce-page input[name="save_address"],
.woocommerce input[name="save_account_details"]:hover,
.woocommerce input[name="save_account_details"]:focus,
.woocommerce input[name="save_address"]:hover,
.woocommerce input[name="save_address"]:focus,
.woocommerce-page input[name="save_account_details"]:hover,
.woocommerce-page input[name="save_account_details"]:focus,
.woocommerce-page input[name="save_address"]:hover,
.woocommerce-page input[name="save_address"]:focus,
.search-no-results .not-found .entry-content .searchform #searchsubmit,
.search-no-results .not-found .entry-content .searchform #searchsubmit:hover, 
.search-no-results .not-found .entry-content .searchform #searchsubmit:focus,
.error404 .page-content > .searchform #searchsubmit,
.error404 .page-content > .searchform #searchsubmit:hover,
.error404 .page-content > .searchform #searchsubmit:focus,
.return-to-shop .button,
.return-to-shop .button:hover,
.return-to-shop .button:focus,
.basel-hover-excerpt .btn-add a,
.basel-hover-excerpt .btn-add a:hover,
.basel-hover-excerpt .btn-add a:focus,
.basel-hover-standard .btn-add > a,
.basel-hover-standard .btn-add > a:hover,
.basel-hover-standard .btn-add > a:focus,
.basel-price-table .basel-plan-footer > a,
.basel-price-table .basel-plan-footer > a:hover, 
.basel-price-table .basel-plan-footer > a:focus,
.basel-pf-btn button,
.basel-pf-btn button:hover,
.basel-pf-btn button:focus,
.basel-info-box.box-style-border .info-btn-wrapper a,
.basel-info-box.box-style-border .info-btn-wrapper a:hover,
.basel-info-box.box-style-border .info-btn-wrapper a:focus,
.basel-info-box2.box-style-border .info-btn-wrapper a,
.basel-info-box2.box-style-border .info-btn-wrapper a:hover,
.basel-info-box2.box-style-border .info-btn-wrapper a:focus,
.basel-hover-quick .woocommerce-variation-add-to-cart .button,
.basel-hover-quick .woocommerce-variation-add-to-cart .button:hover, 
.basel-hover-quick .woocommerce-variation-add-to-cart .button:focus,
.product-list-item .product-list-buttons > a,
.product-list-item .product-list-buttons > a:hover,
.product-list-item .product-list-buttons > a:focus,
.wpb_video_wrapper .button-play,

.basel-navigation .menu > li.callto-btn > a,
.basel-navigation .menu > li.callto-btn > a:hover,
.basel-navigation .menu > li.callto-btn > a:focus,

.basel-dark .products-footer .basel-blog-load-more:hover, 
.basel-dark .products-footer .basel-blog-load-more:focus, 
.basel-dark .products-footer .basel-products-load-more:hover, 
.basel-dark .products-footer .basel-products-load-more:focus, 
.basel-dark .products-footer .basel-portfolio-load-more:hover, 
.basel-dark .products-footer .basel-portfolio-load-more:focus, 
.basel-dark .blog-footer .basel-blog-load-more:hover, 
.basel-dark .blog-footer .basel-blog-load-more:focus, 
.basel-dark .blog-footer .basel-products-load-more:hover, 
.basel-dark .blog-footer .basel-products-load-more:focus, 
.basel-dark .blog-footer .basel-portfolio-load-more:hover, 
.basel-dark .blog-footer .basel-portfolio-load-more:focus, 
.basel-dark .portfolio-footer .basel-blog-load-more:hover, 
.basel-dark .portfolio-footer .basel-blog-load-more:focus, 
.basel-dark .portfolio-footer .basel-products-load-more:hover, 
.basel-dark .portfolio-footer .basel-products-load-more:focus, 
.basel-dark .portfolio-footer .basel-portfolio-load-more:hover, 
.basel-dark .portfolio-footer .basel-portfolio-load-more:focus,

.basel-dark .feedback-form .wpcf7-submit, 
.basel-dark .mc4wp-form input[type="submit"], 
.basel-dark .single_add_to_cart_button,
.basel-dark .basel-compare-col .add_to_cart_button,
.basel-dark .basel-compare-col .added_to_cart,
.basel-dark .basel-sticky-btn .basel-sticky-add-to-cart,
.basel-dark .single-product-content .comment-form .form-submit input[type="submit"],
.basel-dark .basel-registration-page .basel-switch-to-register, 
.basel-dark .register .button, .basel-dark .login .button, 
.basel-dark .lost_reset_password .button, 
.basel-dark .wishlist_table tr td.product-add-to-cart > .add_to_cart.button, 
.basel-dark .woocommerce .cart-actions .coupon .button,

.basel-dark .feedback-form .wpcf7-submit:hover, 
.basel-dark .mc4wp-form input[type="submit"]:hover, 
.basel-dark .single_add_to_cart_button:hover,
.basel-dark .basel-compare-col .add_to_cart_button:hover,
.basel-dark .basel-compare-col .added_to_cart:hover,
.basel-dark .basel-sticky-btn .basel-sticky-add-to-cart:hover,
.basel-dark .single-product-content .comment-form .form-submit input[type="submit"]:hover,
.basel-dark .basel-registration-page .basel-switch-to-register:hover, 
.basel-dark .register .button:hover, .basel-dark .login .button:hover, 
.basel-dark .lost_reset_password .button:hover, 
.basel-dark .wishlist_table tr td.product-add-to-cart > .add_to_cart.button:hover,
.basel-dark .woocommerce .cart-actions .coupon .button:hover,
.basel-ext-primarybtn-dark:focus, .basel-dark .feedback-form .wpcf7-submit:focus,
.basel-dark .mc4wp-form input[type="submit"]:focus, 
.basel-dark .single_add_to_cart_button:focus,
.basel-dark .basel-compare-col .add_to_cart_button:focus,
.basel-dark .basel-compare-col .added_to_cart:focus,
.basel-dark .basel-sticky-btn .basel-sticky-add-to-cart:focus,
.basel-dark .single-product-content .comment-form .form-submit input[type="submit"]:focus,
.basel-dark .basel-registration-page .basel-switch-to-register:focus, 
.basel-dark .register .button:focus, 
.basel-dark .login .button:focus, 
.basel-dark .lost_reset_password .button:focus, 
.basel-dark .wishlist_table tr td.product-add-to-cart > .add_to_cart.button:focus,
.basel-dark .woocommerce .cart-actions .coupon .button:focus,

.basel-stock-progress-bar .progress-bar,
.widget_price_filter .ui-slider .ui-slider-handle:after,
.widget_price_filter .ui-slider .ui-slider-range,
.widget_tag_cloud .tagcloud a:hover,
.widget_product_tag_cloud .tagcloud a:hover,
div.bbp-submit-wrapper button,
div.bbp-submit-wrapper button:hover,
div.bbp-submit-wrapper button:focus,
#bbpress-forums .bbp-search-form #bbp_search_submit,
#bbpress-forums .bbp-search-form #bbp_search_submit:hover,
#bbpress-forums .bbp-search-form #bbp_search_submit:focus,

body .select2-container--default .select2-results__option--highlighted[aria-selected], 

.product-video-button a:hover:before, 
.product-360-button a:hover:before,

.mobile-nav ul li .up-icon,

.scrollToTop:hover, 
.scrollToTop:focus,
.basel-sticky-filter-btn:hover, 
.basel-sticky-filter-btn:focus,

.categories-opened li a:active,

.basel-price-table .basel-plan-price,

.header-categories .secondary-header .mega-navigation,
.widget_nav_mega_menu,

.meta-post-categories,

.slider-title:before,
.title-wrapper.basel-title-style-simple .title:after,

.menu-label-new,
.product-label.onsale,

.color-scheme-light .vc_tta-tabs.vc_tta-tabs-position-top.vc_tta-style-classic .vc_tta-tab.vc_active > a span:after,
.wpb-js-composer .vc_tta.vc_general.vc_tta-style-classic .vc_tta-tab.vc_active > a span:after,

.portfolio-with-bg-alt .portfolio-entry:hover .entry-header > .portfolio-info

		'),
		'border-color' => basel_text2line('
.btn.btn-color-primary,
.button.btn-color-primary,
button.btn-color-primary,
.added_to_cart.btn-color-primary,
input[type="submit"].btn-color-primary,
.btn.btn-color-primary:hover, 
.btn.btn-color-primary:focus, 
.button.btn-color-primary:hover, 
.button.btn-color-primary:focus, 
button.btn-color-primary:hover, 
button.btn-color-primary:focus, 
.added_to_cart.btn-color-primary:hover, 
.added_to_cart.btn-color-primary:focus, 
input[type="submit"].btn-color-primary:hover, 
input[type="submit"].btn-color-primary:focus,
.btn.btn-color-primary.btn-style-bordered:hover,
.btn.btn-color-primary.btn-style-bordered:focus,
.button.btn-color-primary.btn-style-bordered:hover,
.button.btn-color-primary.btn-style-bordered:focus,
button.btn-color-primary.btn-style-bordered:hover,
button.btn-color-primary.btn-style-bordered:focus,
.widget_shopping_cart .widget_shopping_cart_content .buttons .checkout,
.widget_shopping_cart .widget_shopping_cart_content .buttons .checkout:hover,
.widget_shopping_cart .widget_shopping_cart_content .buttons .checkout:focus,
.basel-search-dropdown .basel-search-wrapper .basel-search-inner form button,
.basel-search-dropdown .basel-search-wrapper .basel-search-inner form button:hover,
.basel-search-dropdown .basel-search-wrapper .basel-search-inner form button:focus,
.comments-area .comment-respond input[type="submit"],
.comments-area .comment-respond input[type="submit"]:hover,
.comments-area .comment-respond input[type="submit"]:focus,
.sidebar-container .mc4wp-form input[type="submit"],
.sidebar-container .mc4wp-form input[type="submit"]:hover,
.sidebar-container .mc4wp-form input[type="submit"]:focus,
.footer-container .mc4wp-form input[type="submit"],
.footer-container .mc4wp-form input[type="submit"]:hover,
.footer-container .mc4wp-form input[type="submit"]:focus,
.filters-area .mc4wp-form input[type="submit"],
.filters-area .mc4wp-form input[type="submit"]:hover,
.filters-area .mc4wp-form input[type="submit"]:focus,
.woocommerce .cart-collaterals .cart_totals .wc-proceed-to-checkout > a.button,
.woocommerce .cart-collaterals .cart_totals .wc-proceed-to-checkout > a.button:hover,
.woocommerce .cart-collaterals .cart_totals .wc-proceed-to-checkout > a.button:focus,
.woocommerce .checkout_coupon .button,
.woocommerce .checkout_coupon .button:hover,
.woocommerce .checkout_coupon .button:focus,
.woocommerce .place-order button,
.woocommerce .place-order button:hover,
.woocommerce .place-order button:focus,
.woocommerce-order-pay #order_review .button,
.woocommerce-order-pay #order_review .button:hover,
.woocommerce-order-pay #order_review .button:focus,
.woocommerce input[name="track"],
.woocommerce input[name="track"]:hover,
.woocommerce input[name="track"]:focus,
.woocommerce input[name="save_account_details"],
.woocommerce input[name="save_address"],
.woocommerce-page input[name="save_account_details"],
.woocommerce-page input[name="save_address"],
.woocommerce input[name="save_account_details"]:hover,
.woocommerce input[name="save_account_details"]:focus, 
.woocommerce input[name="save_address"]:hover, 
.woocommerce input[name="save_address"]:focus, 
.woocommerce-page input[name="save_account_details"]:hover, 
.woocommerce-page input[name="save_account_details"]:focus, 
.woocommerce-page input[name="save_address"]:hover, 
.woocommerce-page input[name="save_address"]:focus,
.search-no-results .not-found .entry-content .searchform #searchsubmit,
.search-no-results .not-found .entry-content .searchform #searchsubmit:hover, 
.search-no-results .not-found .entry-content .searchform #searchsubmit:focus,
.error404 .page-content > .searchform #searchsubmit,
.error404 .page-content > .searchform #searchsubmit:hover, 
.error404 .page-content > .searchform #searchsubmit:focus,
.no-results .searchform #searchsubmit,
.no-results .searchform #searchsubmit:hover,
.no-results .searchform #searchsubmit:focus,
.return-to-shop .button,
.return-to-shop .button:hover,
.return-to-shop .button:focus,
.basel-hover-excerpt .btn-add a,
.basel-hover-excerpt .btn-add a:hover,
.basel-hover-excerpt .btn-add a:focus,
.basel-hover-standard .btn-add > a,
.basel-hover-standard .btn-add > a:hover,
.basel-hover-standard .btn-add > a:focus,
.basel-price-table .basel-plan-footer > a,
.basel-price-table .basel-plan-footer > a:hover, 
.basel-price-table .basel-plan-footer > a:focus,
.basel-pf-btn button,
.basel-pf-btn button:hover,
.basel-pf-btn button:focus,
.basel-info-box.box-style-border .info-btn-wrapper a,
.basel-info-box.box-style-border .info-btn-wrapper a:hover,
.basel-info-box.box-style-border .info-btn-wrapper a:focus,
.basel-info-box2.box-style-border .info-btn-wrapper a,
.basel-info-box2.box-style-border .info-btn-wrapper a:hover,
.basel-info-box2.box-style-border .info-btn-wrapper a:focus,
.basel-hover-quick .woocommerce-variation-add-to-cart .button,
.basel-hover-quick .woocommerce-variation-add-to-cart .button:hover, 
.basel-hover-quick .woocommerce-variation-add-to-cart .button:focus,
.product-list-item .product-list-buttons > a,
.product-list-item .product-list-buttons > a:hover,
.product-list-item .product-list-buttons > a:focus,
.wpb_video_wrapper .button-play,
.woocommerce-store-notice__dismiss-link:hover,
.woocommerce-store-notice__dismiss-link:focus,
.basel-compare-table .compare-loader:after,

.basel-sticky-sidebar-opener:not(.sticky-toolbar):hover,
.basel-sticky-sidebar-opener:not(.sticky-toolbar):focus,

.basel-dark .read-more-section .btn-read-more,

.basel-dark .products-footer .basel-blog-load-more, 
.basel-dark .products-footer .basel-products-load-more, 
.basel-dark .products-footer .basel-portfolio-load-more, 
.basel-dark .blog-footer .basel-blog-load-more, 
.basel-dark .blog-footer .basel-products-load-more, 
.basel-dark .blog-footer .basel-portfolio-load-more, 
.basel-dark .portfolio-footer .basel-blog-load-more, 
.basel-dark .portfolio-footer .basel-products-load-more, 
.basel-dark .portfolio-footer .basel-portfolio-load-more,

.basel-dark .products-footer .basel-blog-load-more:hover, 
.basel-dark .products-footer .basel-blog-load-more:focus, 
.basel-dark .products-footer .basel-products-load-more:hover, 
.basel-dark .products-footer .basel-products-load-more:focus, 
.basel-dark .products-footer .basel-portfolio-load-more:hover, 
.basel-dark .products-footer .basel-portfolio-load-more:focus, 
.basel-dark .blog-footer .basel-blog-load-more:hover, 
.basel-dark .blog-footer .basel-blog-load-more:focus, 
.basel-dark .blog-footer .basel-products-load-more:hover, 
.basel-dark .blog-footer .basel-products-load-more:focus, 
.basel-dark .blog-footer .basel-portfolio-load-more:hover, 
.basel-dark .blog-footer .basel-portfolio-load-more:focus, 
.basel-dark .portfolio-footer .basel-blog-load-more:hover, 
.basel-dark .portfolio-footer .basel-blog-load-more:focus, 
.basel-dark .portfolio-footer .basel-products-load-more:hover, 
.basel-dark .portfolio-footer .basel-products-load-more:focus, 
.basel-dark .portfolio-footer .basel-portfolio-load-more:hover, 
.basel-dark .portfolio-footer .basel-portfolio-load-more:focus,

.basel-dark .products-footer .basel-blog-load-more:after, 
.basel-dark .products-footer .basel-products-load-more:after, 
.basel-dark .products-footer .basel-portfolio-load-more:after, 
.basel-dark .blog-footer .basel-blog-load-more:after, 
.basel-dark .blog-footer .basel-products-load-more:after, 
.basel-dark .blog-footer .basel-portfolio-load-more:after, 
.basel-dark .portfolio-footer .basel-blog-load-more:after, 
.basel-dark .portfolio-footer .basel-products-load-more:after, 
.basel-dark .portfolio-footer .basel-portfolio-load-more:after,

.basel-dark .feedback-form .wpcf7-submit, 
.basel-dark .mc4wp-form input[type="submit"], 
.basel-dark .single_add_to_cart_button,
.basel-dark .basel-compare-col .add_to_cart_button,
.basel-dark .basel-compare-col .added_to_cart,
.basel-dark .basel-sticky-btn .basel-sticky-add-to-cart,
.basel-dark .single-product-content .comment-form .form-submit input[type="submit"],
.basel-dark .basel-registration-page .basel-switch-to-register, 
.basel-dark .register .button, .basel-dark .login .button, 
.basel-dark .lost_reset_password .button, 
.basel-dark .wishlist_table tr td.product-add-to-cart > .add_to_cart.button, 
.basel-dark .woocommerce .cart-actions .coupon .button,

.basel-dark .feedback-form .wpcf7-submit:hover, 
.basel-dark .mc4wp-form input[type="submit"]:hover, 
.basel-dark .single_add_to_cart_button:hover,
.basel-dark .basel-compare-col .add_to_cart_button:hover,
.basel-dark .basel-compare-col .added_to_cart:hover,
.basel-dark .basel-sticky-btn .basel-sticky-add-to-cart:hover, 
.basel-dark .single-product-content .comment-form .form-submit input[type="submit"]:hover,
.basel-dark .basel-registration-page .basel-switch-to-register:hover, 
.basel-dark .register .button:hover, .basel-dark .login .button:hover, 
.basel-dark .lost_reset_password .button:hover, 
.basel-dark .wishlist_table tr td.product-add-to-cart > .add_to_cart.button:hover,
.basel-dark .woocommerce .cart-actions .coupon .button:hover,
.basel-ext-primarybtn-dark:focus, .basel-dark .feedback-form .wpcf7-submit:focus,
.basel-dark .mc4wp-form input[type="submit"]:focus, 
.basel-dark .single_add_to_cart_button:focus,
.basel-dark .basel-compare-col .add_to_cart_button:focus,
.basel-dark .basel-compare-col .added_to_cart:focus,
.basel-dark .basel-sticky-btn .basel-sticky-add-to-cart:focus,
.basel-dark .single-product-content .comment-form .form-submit input[type="submit"]:focus,
.basel-dark .basel-registration-page .basel-switch-to-register:focus, 
.basel-dark .register .button:focus, 
.basel-dark .login .button:focus, 
.basel-dark .lost_reset_password .button:focus, 
.basel-dark .wishlist_table tr td.product-add-to-cart > .add_to_cart.button:focus,
.basel-dark .woocommerce .cart-actions .coupon .button:focus,

.cookies-buttons .cookies-accept-btn:hover,
.cookies-buttons .cookies-accept-btn:focus,

.blockOverlay:after,
.basel-price-table:hover,
.title-shop .nav-shop ul li a:after,
.widget_tag_cloud .tagcloud a:hover,
.widget_product_tag_cloud .tagcloud a:hover,
div.bbp-submit-wrapper button,
div.bbp-submit-wrapper button:hover,
div.bbp-submit-wrapper button:focus,
#bbpress-forums .bbp-search-form #bbp_search_submit,
#bbpress-forums .bbp-search-form #bbp_search_submit:hover,
#bbpress-forums .bbp-search-form #bbp_search_submit:focus,
.basel-hover-link .swap-elements .btn-add a,
.basel-hover-link .swap-elements .btn-add a:hover,
.basel-hover-link .swap-elements .btn-add a:focus,
.basel-hover-link .swap-elements .btn-add a.loading:after,
.scrollToTop:hover, 
.scrollToTop:focus,
.basel-sticky-filter-btn:hover, 
.basel-sticky-filter-btn:focus,

blockquote
	'),
	'stroke' => basel_text2line('
.with-animation .info-box-icon svg path,
.single-product-content .entry-summary .basel-sizeguide-btn:hover svg' ) ,
	),
	'secondary-color'     => array(
		'color' => basel_text2line('
.btn.btn-color-alt.btn-style-bordered, 
.button.btn-color-alt.btn-style-bordered, 
button.btn-color-alt.btn-style-bordered, 
.added_to_cart.btn-color-alt.btn-style-bordered, 
input[type="submit"].btn-color-alt.btn-style-bordered,

.title-wrapper.basel-title-color-alt .title-subtitle
		'),
		'background-color' => basel_text2line('
.btn.btn-color-alt, 
.button.btn-color-alt, 
button.btn-color-alt, 
.added_to_cart.btn-color-alt, 
input[type="submit"].btn-color-alt,

.btn.btn-color-alt:hover, 
.btn.btn-color-alt:focus, 
.button.btn-color-alt:hover, 
.button.btn-color-alt:focus, 
button.btn-color-alt:hover, 
button.btn-color-alt:focus, 
.added_to_cart.btn-color-alt:hover, 
.added_to_cart.btn-color-alt:focus, 
input[type="submit"].btn-color-alt:hover, 
input[type="submit"].btn-color-alt:focus,
.btn.btn-color-alt.btn-style-bordered:hover, 
.btn.btn-color-alt.btn-style-bordered:focus, 
.button.btn-color-alt.btn-style-bordered:hover, 
.button.btn-color-alt.btn-style-bordered:focus, 
button.btn-color-alt.btn-style-bordered:hover, 
button.btn-color-alt.btn-style-bordered:focus, 
.added_to_cart.btn-color-alt.btn-style-bordered:hover, 
.added_to_cart.btn-color-alt.btn-style-bordered:focus, 
input[type="submit"].btn-color-alt.btn-style-bordered:hover, 
input[type="submit"].btn-color-alt.btn-style-bordered:focus,

.widget_nav_mega_menu .menu > li:hover, 
.mega-navigation .menu > li:hover
		'),
		'border-color' => basel_text2line('
.btn.btn-color-alt, 
.button.btn-color-alt, 
button.btn-color-alt, 
.added_to_cart.btn-color-alt, 
input[type="submit"].btn-color-alt,
.btn.btn-color-alt:hover, 
.btn.btn-color-alt:focus, 
.button.btn-color-alt:hover, 
.button.btn-color-alt:focus, 
button.btn-color-alt:hover, 
button.btn-color-alt:focus, 
.added_to_cart.btn-color-alt:hover, 
.added_to_cart.btn-color-alt:focus, 
input[type="submit"].btn-color-alt:hover, 
input[type="submit"].btn-color-alt:focus,
.btn.btn-color-alt.btn-style-bordered:hover, 
.btn.btn-color-alt.btn-style-bordered:focus, 
.button.btn-color-alt.btn-style-bordered:hover, 
.button.btn-color-alt.btn-style-bordered:focus, 
button.btn-color-alt.btn-style-bordered:hover, 
button.btn-color-alt.btn-style-bordered:focus, 
.added_to_cart.btn-color-alt.btn-style-bordered:hover, 
.added_to_cart.btn-color-alt.btn-style-bordered:focus, 
input[type="submit"].btn-color-alt.btn-style-bordered:hover, 
input[type="submit"].btn-color-alt.btn-style-bordered:focus
			'),
		),
		
		'text-font' => array('body', 'p',
		'
.widget_nav_mega_menu .menu > li > a, 
.mega-navigation .menu > li > a,
.basel-navigation .menu > li.menu-item-design-full-width .sub-sub-menu li a, 
.basel-navigation .menu > li.menu-item-design-sized .sub-sub-menu li a,
.basel-navigation .menu > li.menu-item-design-default .sub-menu li a,
.font-default
		'),
		'primary-font' => array('h1 a, h2 a, h3 a, h4 a, h5 a, h6 a, h1, h2, h3, h4, h5, h6, .title', 'table th',
		'
.wc-tabs li a,
.masonry-filter li a,
.woocommerce .cart-empty,
.basel-navigation .menu > li.menu-item-design-full-width .sub-menu > li > a, 
.basel-navigation .menu > li.menu-item-design-sized .sub-menu > li > a,
.mega-menu-list > li > a,
fieldset legend,
table th,
.basel-empty-compare,
.compare-field,
.compare-value:before,
.color-scheme-dark .info-box-inner h1,
.color-scheme-dark .info-box-inner h2,
.color-scheme-dark .info-box-inner h3,
.color-scheme-dark .info-box-inner h4,
.color-scheme-dark .info-box-inner h5,
.color-scheme-dark .info-box-inner h6

		'),
	'secondary-font' => array('.title-alt, .subtitle, .font-alt, .basel-entry-meta'),
	'titles-font' => array('

.product-title a,
.post-slide .entry-title a,
.category-grid-item .hover-mask h3,
.basel-search-full-screen .basel-search-inner input[type="text"],
.blog-post-loop .entry-title,
.post-title-large-image .entry-title,
.single-product-content .entry-title
		',
		'.font-title'),
	'widget-titles-font' => array('.widgettitle, .widget-title'),
	'navigation-font' => array('.main-nav .menu > li > a'),
		'regular-buttons-bg-color' => array('.button, 
button, 
input[type=submit],
.yith-woocompare-widget a.button.compare,
.basel-dark .basel-registration-page .basel-switch-to-register,
.basel-dark .login .button,
.basel-dark .register .button,
.basel-dark .widget_shopping_cart .buttons a,
.basel-dark .yith-woocompare-widget a.button.compare,
.basel-dark .widget_price_filter .price_slider_amount .button,
.basel-dark .woocommerce-widget-layered-nav-dropdown__submit,
.basel-dark .basel-widget-layered-nav-dropdown__submit,
.basel-dark .woocommerce .cart-actions input[name="update_cart"]'),
	'shop-buttons-bg-color' => array('.single_add_to_cart_button,
.basel-sticky-btn .basel-sticky-add-to-cart,
.woocommerce .cart-actions .coupon .button,
.added_to_cart.btn-color-black, 
input[type=submit].btn-color-black,
.wishlist_table tr td.product-add-to-cart>.add_to_cart.button,
.basel-hover-quick .quick-shop-btn > a,
table.compare-list tr.add-to-cart td a,
.basel-compare-col .add_to_cart_button, 
.basel-compare-col .added_to_cart'),
	'accent-buttons-bg-color' => array('.added_to_cart.btn-color-primary, 
.btn.btn-color-primary,
 .button.btn-color-primary, 
 button.btn-color-primary, 
 input[type=submit].btn-color-primary,
.widget_shopping_cart .buttons .checkout,
.widget_shopping_cart .widget_shopping_cart_content .buttons .checkout,
.woocommerce .cart-collaterals .cart_totals .wc-proceed-to-checkout > a.button,
.woocommerce-checkout .place-order button,
.woocommerce-checkout .checkout_coupon .button,
.woocommerce input[name=save_account_details], 
.woocommerce input[name=save_address], 
.woocommerce input[name=track], 
.woocommerce-page input[name=save_account_details], 
.woocommerce-page input[name=save_address], 
.woocommerce-page input[name=track],
.return-to-shop .button,
.basel-navigation .menu > li.callto-btn > a,
.basel-hover-standard .btn-add > a,
.basel-hover-excerpt .btn-add a,
.basel-hover-quick .woocommerce-variation-add-to-cart .button,
.basel-search-dropdown .basel-search-wrapper .basel-search-inner form button,
.error404 .page-content>.searchform #searchsubmit,
.basel-info-box.box-style-border .info-btn-wrapper a, 
.basel-info-box2.box-style-border .info-btn-wrapper a,
.basel-price-table .basel-plan-footer > a,
.basel-pf-btn button,
.basel-dark .single_add_to_cart_button,
.basel-dark .basel-compare-col .add_to_cart_button, 
.basel-dark .basel-compare-col .added_to_cart,
.basel-dark .basel-sticky-btn .basel-sticky-add-to-cart,
.basel-dark .single-product-content .comment-form .form-submit input[type=submit],
.basel-dark .woocommerce .cart-actions .coupon .button'),
	'shop-button-color' => array('.basel-hover-alt .btn-add>a'),
	'gradient-color' => array('.page-title'),

) );

