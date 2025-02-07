<?php

class BlockStrap_Widget_Tabs extends WP_Super_Duper {


	public $arguments;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$aui_settings = is_admin() ? get_option( 'ayecode-ui-settings' ) : array();
		$bs5          = ! empty( $aui_settings['bs_ver'] ) && '5' === $aui_settings['bs_ver'] ? 'bs-' : '';

		$options = array(
			'textdomain'        => 'blockstrap',
			'output_types'      => array( 'block', 'shortcode' ),
			'nested-block'      => true,
			'block-icon'        => 'far fa-folder',
			'block-category'    => 'layout',
			'block-keywords'    => "['tabs','tab','content']",
			'block-supports'    => array(
				'customClassName' => false,
			),
			'block-outputx'     => array(
				array(
					'element'    => 'innerBlocksProps',
					'blockProps' => array(
						'if_className'     => 'props.attributes.styleid + " " [%WrapClass%]',
						'style'            => '{[%WrapStyle%]}',
						'if_id'            => 'props.attributes.id ? props.attributes.id : props.clientId',
						'innerBlocksProps' => array(
							'orientation' => 'vertical',

						),

					),
				),
			),

			'block-edit-return' => "el('div', wp.blockEditor.useBlockProps({
									//dangerouslySetInnerHTML: {__html: onChangeContent()},
									style: sd_build_aui_styles(props.attributes),
									className: sd_build_aui_class(props.attributes),

								}),
								el('nav',{className: props.attributes.tabs_greedy ? 'greedy' : ''},
								//el('nav',{className: 'nav nav-tabs',role:'tablist'},bs_build_tabs_head(props.attributes,props.clientId,props)),
								el('ul',{className: (props.attributes.tabs_style=='nav-pills' ? 'border-0 ' : '') + 'nav nav-tabs ' + props.attributes.tabs_style + ' ' + props.attributes.tab_size + ' mb-' + props.attributes.tabs_head_mb + ' ' + sd_build_aui_class({flex_justify_content: props.attributes.tabs_flex_justify_content,flex_justify_content_md: props.attributes.tabs_flex_justify_content_md,flex_justify_content_lg: props.attributes.tabs_flex_justify_content_lg}) ,role:'tablist'},

									(function() {
										let tabs = [];
										let tabs_array = [];

										if(childBlocks.length){
											let active_index = 0

											childBlocks.map((tab, index) => (
												tabs_array.push({name:tab.attributes.text,id:tab.attributes.anchor}),
												 active_index = tab.clientId === wp.data.select('core/editor').getBlockSelectionStart() || hasSelectedInnerBlock(tab) ? index : active_index

											));

											props.setAttributes({
											  tabs_head_array: JSON.stringify(tabs_array).replace('[','').replace(']','')
											});

											childBlocks.map((tab, index) => (
												//console.log('tab:'+index)
												tabs.push(
													el('li',{className:'nav-item'}, el('button',{
															className: active_index === index ? 'nav-link active ' + sd_build_aui_class({rounded_size: props.attributes.tabs_rounded_size}) : 'nav-link ' + sd_build_aui_class({rounded_size: props.attributes.tabs_rounded_size}),
															'data-{$bs5}toggle': 'tab',
															type: 'button',
															role: 'tab',
															'aria-selected': false,
															'aria-controls': 'nav-profile',
															'data-{$bs5}target':  '#block-' + tab.clientId
														},
													 tab.attributes.text ? tab.attributes.text : 'Tab ' + (index+1)
													 )
													 )
												 )

											));
										}

										return tabs;
									})(),

								),
								),
								 el( 'div', wp.blockEditor.useInnerBlocksProps( {className: 'tab-content'},  {orientation: 'horizontal',inner_element: 'div',
								 template:
													[
														[ 'blockstrap/blockstrap-widget-tab', {text:'Tab1',anchor:'tab-1'}, [[ 'core/paragraph', { placeholder: 'Add your blocks here' } ],] ],
														[ 'blockstrap/blockstrap-widget-tab', {text:'Tab2',anchor:'tab-2'}, [[ 'core/paragraph', { placeholder: 'Add your blocks here' } ],] ],

													]
								,
								 },) )
								)
								",
			'block-wrap'        => '',
			'class_name'        => __CLASS__,
			'base_id'           => 'bs_tabs',
			'name'              => __( 'BS > Tabs', 'blockstrap-page-builder-blocks' ),
			'widget_ops'        => array(
				'classname'   => 'bs-tabs',
				'description' => esc_html__( 'A container for tabs', 'blockstrap-page-builder-blocks' ),
			),
			'no_wrap'           => true,
			'block_group_tabs'  => array(
				'styles'   => array(
					'groups' => array( __( 'Tabs Head', 'blockstrap-page-builder-blocks' ), __( 'Tab Body Typography', 'blockstrap-page-builder-blocks' ) ),
					'tab'    => array(
						'title'     => __( 'Styles', 'blockstrap-page-builder-blocks' ),
						'key'       => 'bs_tab_styles',
						'tabs_open' => true,
						'open'      => true,
						'class'     => 'text-center flex-fill d-flex justify-content-center',
					),
				),
				'advanced' => array(
					'groups' => array(
						__( 'Background', 'blockstrap-page-builder-blocks' ),
						__( 'Wrapper Styles', 'blockstrap-page-builder-blocks' ),
						__( 'Advanced', 'blockstrap-page-builder-blocks' ),
					),
					'tab'    => array(
						'title'     => __( 'Advanced', 'blockstrap-page-builder-blocks' ),
						'key'       => 'bs_tab_advanced',
						'tabs_open' => true,
						'open'      => true,
						'class'     => 'text-center flex-fill d-flex justify-content-center',
					),
				),
			),
		);

		parent::__construct( $options );
	}

	/**
	 * Set the arguments later.
	 *
	 * @return array
	 */
	public function set_arguments() {

		$arguments = array();

		$arguments['tabs_style'] = array(
			'type'     => 'select',
			'title'    => __( 'Style', 'blockstrap-page-builder-blocks' ),
			'options'  => array(
				'nav-tabs'  => __( 'Tabs', 'blockstrap-page-builder-blocks' ),
				'nav-pills' => __( 'Pills', 'blockstrap-page-builder-blocks' ),
			),
			'default'  => 'nav-tabs',
			'desc_tip' => true,
			'group'    => __( 'Tabs Head', 'blockstrap-page-builder-blocks' ),
		);

		$arguments['tab_size'] = array(
			'type'     => 'select',
			'title'    => __( 'Tab button size', 'blockstrap-page-builder-blocks' ),
			'options'  => array(
				''              => __( 'Auto', 'blockstrap-page-builder-blocks' ),
				'nav-fill'      => __( 'Fill', 'blockstrap-page-builder-blocks' ),
				'nav-justified' => __( 'Justified', 'blockstrap-page-builder-blocks' ),
			),
			'default'  => '',
			'desc_tip' => true,
			'group'    => __( 'Tabs Head', 'blockstrap-page-builder-blocks' ),
		);

		$arguments['tabs_greedy'] = array(
			'type'     => 'checkbox',
			'title'    => __( 'Greedy tabs', 'blockstrap-page-builder-blocks' ),
			'default'  => '',
			'value'    => '1',
			'desc_tip' => false,
			'desc'     => __( 'This will add overflowing tabs to a dropdown instead of a new line.', 'blockstrap-page-builder-blocks' ),
			'group'    => __( 'Tabs Head', 'blockstrap-page-builder-blocks' ),
		);

		$arguments = $arguments + sd_get_flex_justify_content_input_group(
			'tabs_flex_justify_content',
			array(
				'group'           => __( 'Tabs Head', 'blockstrap-page-builder-blocks' ),
				'element_require' => '',
			)
		);

		$arguments['tabs_rounded_size'] = sd_get_border_input(
			'rounded_size',
			array(
				'group'           => __( 'Tabs Head', 'blockstrap-page-builder-blocks' ),
				'element_require' => '[%tabs_style%]=="nav-pills"',
			)
		);

		$arguments['tabs_head_mb'] = sd_get_margin_input(
			'mb',
			array(
				'group'   => __( 'Tabs Head', 'blockstrap-page-builder-blocks' ),
				'default' => 3,
			)
		);

		$arguments['tabs_head_array'] = array(
			'type'     => 'hidden',
			'title'    => __( 'Tabs head array', 'blockstrap-page-builder-blocks' ),
			'default'  => '',
			'desc_tip' => true,
			'group'    => __( 'Tabs Head', 'blockstrap-page-builder-blocks' ),
		);

		// text color
		$arguments['text_color'] = sd_get_text_color_input( 'text_color', array( 'group' => __( 'Tab Body Typography', 'blockstrap-page-builder-blocks' ) ) );

		// Text justify
		$arguments['text_justify'] = sd_get_text_justify_input( 'text_justify', array( 'group' => __( 'Tab Body Typography', 'blockstrap-page-builder-blocks' ) ) );

		// text align
		$arguments['text_align']    = sd_get_text_align_input(
			'text_align',
			array(
				'device_type'     => 'Mobile',
				'element_require' => '[%text_justify%]==""',
				'group'           => __( 'Tab Body Typography', 'blockstrap-page-builder-blocks' ),
			)
		);
		$arguments['text_align_md'] = sd_get_text_align_input(
			'text_align',
			array(
				'device_type'     => 'Tablet',
				'element_require' => '[%text_justify%]==""',
				'group'           => __( 'Tab Body Typography', 'blockstrap-page-builder-blocks' ),
			)
		);
		$arguments['text_align_lg'] = sd_get_text_align_input(
			'text_align',
			array(
				'device_type'     => 'Desktop',
				'element_require' => '[%text_justify%]==""',
				'group'           => __( 'Tab Body Typography', 'blockstrap-page-builder-blocks' ),
			)
		);

		$arguments = $arguments + sd_get_background_inputs( 'bg' );

		// margins mobile
		$arguments['mt'] = sd_get_margin_input( 'mt', array( 'device_type' => 'Mobile' ) );
		$arguments['mr'] = sd_get_margin_input( 'mr', array( 'device_type' => 'Mobile' ) );
		$arguments['mb'] = sd_get_margin_input( 'mb', array( 'device_type' => 'Mobile' ) );
		$arguments['ml'] = sd_get_margin_input( 'ml', array( 'device_type' => 'Mobile' ) );

		// margins tablet
		$arguments['mt_md'] = sd_get_margin_input( 'mt', array( 'device_type' => 'Tablet' ) );
		$arguments['mr_md'] = sd_get_margin_input( 'mr', array( 'device_type' => 'Tablet' ) );
		$arguments['mb_md'] = sd_get_margin_input( 'mb', array( 'device_type' => 'Tablet' ) );
		$arguments['ml_md'] = sd_get_margin_input( 'ml', array( 'device_type' => 'Tablet' ) );

		// margins desktop
		$arguments['mt_lg'] = sd_get_margin_input( 'mt', array( 'device_type' => 'Desktop' ) );
		$arguments['mr_lg'] = sd_get_margin_input( 'mr', array( 'device_type' => 'Desktop' ) );
		$arguments['mb_lg'] = sd_get_margin_input(
			'mb',
			array(
				'device_type' => 'Desktop',
				'default'     => 3,
			)
		);
		$arguments['ml_lg'] = sd_get_margin_input( 'ml', array( 'device_type' => 'Desktop' ) );

		// padding
		$arguments['pt'] = sd_get_padding_input( 'pt', array( 'device_type' => 'Mobile' ) );
		$arguments['pr'] = sd_get_padding_input( 'pr', array( 'device_type' => 'Mobile' ) );
		$arguments['pb'] = sd_get_padding_input( 'pb', array( 'device_type' => 'Mobile' ) );
		$arguments['pl'] = sd_get_padding_input( 'pl', array( 'device_type' => 'Mobile' ) );

		// padding tablet
		$arguments['pt_md'] = sd_get_padding_input( 'pt', array( 'device_type' => 'Tablet' ) );
		$arguments['pr_md'] = sd_get_padding_input( 'pr', array( 'device_type' => 'Tablet' ) );
		$arguments['pb_md'] = sd_get_padding_input( 'pb', array( 'device_type' => 'Tablet' ) );
		$arguments['pl_md'] = sd_get_padding_input( 'pl', array( 'device_type' => 'Tablet' ) );

		// padding desktop
		$arguments['pt_lg'] = sd_get_padding_input( 'pt', array( 'device_type' => 'Desktop' ) );
		$arguments['pr_lg'] = sd_get_padding_input( 'pr', array( 'device_type' => 'Desktop' ) );
		$arguments['pb_lg'] = sd_get_padding_input( 'pb', array( 'device_type' => 'Desktop' ) );
		$arguments['pl_lg'] = sd_get_padding_input( 'pl', array( 'device_type' => 'Desktop' ) );

		// border
		$arguments['border']       = sd_get_border_input( 'border' );
		$arguments['rounded']      = sd_get_border_input( 'rounded' );
		$arguments['rounded_size'] = sd_get_border_input( 'rounded_size' );

		// shadow
		$arguments['shadow'] = sd_get_shadow_input( 'shadow' );

		// position
		$arguments['position'] = sd_get_position_class_input( 'position' );

		$arguments['sticky_offset_top']    = sd_get_sticky_offset_input( 'top' );
		$arguments['sticky_offset_bottom'] = sd_get_sticky_offset_input( 'bottom' );

		$arguments['display']    = sd_get_display_input( 'd', array( 'device_type' => 'Mobile' ) );
		$arguments['display_md'] = sd_get_display_input( 'd', array( 'device_type' => 'Tablet' ) );
		$arguments['display_lg'] = sd_get_display_input( 'd', array( 'device_type' => 'Desktop' ) );

		$arguments['anchor'] = sd_get_anchor_input();

		$arguments['css_class'] = sd_get_class_input();

		return $arguments;
	}


	/**
	 * This is the output function for the widget, shortcode and block (front end).
	 *
	 * @param array $args The arguments values.
	 * @param array $widget_args The widget arguments when used.
	 * @param string $content The shortcode content argument.
	 *
	 * @return string
	 */
	public function output( $args = array(), $widget_args = array(), $content = '' ) {
		global $aui_bs5;
		if ( $content ) {
			$bs5          = $aui_bs5 ? 'bs-' : '';
			$wrap_class   = sd_build_aui_class( $args );
			$tabs_array   = $args['tabs_head_array'] ? json_decode( '[' . $args['tabs_head_array'] . ']', true ) : array();
			$tabs         = '';
			$first_tab_id = '';

			if ( ! empty( $tabs_array ) ) {
				foreach ( $tabs_array as $key => $tab ) {
					$active       = 0 === $key ? 'active' : '';
					$first_tab_id = $active ? esc_attr( $tab['id'] ) : $first_tab_id;
					$aria_active  = $active ? 'true' : 'false';
					$active      .= ' ' . sd_build_aui_class( array( 'rounded_size' => $args['tabs_rounded_size'] ) );
					$tabs        .= '<li class="nav-item"><button class="nav-link ' . $active . '" id="' . esc_attr( $tab['id'] ) . '-tab" data-' . $bs5 . 'toggle="tab" data-' . $bs5 . 'target="#' . esc_attr( $tab['id'] ) . '" type="button" role="tab" aria-controls="nav-home" aria-selected="' . $aria_active . '">' . esc_attr( $tab['name'] ) . '</button></li>';
				}
			}

			// set the first tab active.
			if ( $first_tab_id ) {
				$content = str_replace( 'id="' . $first_tab_id . '" class="tab-pane', 'id="' . $first_tab_id . '" class="active show tab-pane', $content );
			}

			$greedy      = ! empty( $args['tabs_greedy'] ) ? 'greedy' : '';
			$tabs_style  = ! empty( $args['tabs_style'] ) ? sanitize_html_class( $args['tabs_style'] ) : 'nav-tabs';
			$tabs_style .= ! empty( $args['tabs_style'] ) && 'nav-pills' === $args['tabs_style'] ? ' border-0' : '';
			$tabs_style .= ! empty( $args['tab_size'] ) ? ' ' . sanitize_html_class( $args['tab_size'] ) : '';
			$tabs_style .= ! empty( $args['tabs_head_mb'] ) ? ' mb-' . sanitize_html_class( $args['tabs_head_mb'] ) : '';
			$tabs_style .= ' ' . sd_build_aui_class(
				array(
					'flex_justify_content'    => $args['tabs_flex_justify_content'],
					'flex_justify_content_md' => $args['tabs_flex_justify_content_md'],
					'flex_justify_content_lg' => $args['tabs_flex_justify_content_lg'],
				)
			);
			$tabs_head   = sprintf(
				'<nav class="%1$s"><ul class="nav nav-tabs %2$s" role="tablist">%3$s</ul></nav>',
				$greedy,
				$tabs_style,
				$tabs
			);

			return sprintf(
				'<div class="%1$s bs-tabs">%2$s<div class="tab-content" >%3$s</div></div>',
				$wrap_class,
				$tabs_head,
				$content
			);
		}

		return '';

	}
}

// register it.
add_action(
	'widgets_init',
	function () {
		register_widget( 'BlockStrap_Widget_Tabs' );
	}
);

