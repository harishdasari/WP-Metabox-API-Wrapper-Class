
##WordPress Metabox API Wrapper Class

_**Note: This is not a WordPress Plugin. This is a PHP Library for creating custom metabox option for WordPress themes and plugins.**_

Add Custom Metaboxes to your post, page and custom post types.
You just need to define the options and input fields, and this wrapper class will do the rest such as registering metabox, generating input fields and saving post meta etc,. I used best security methods while saving post meta fields, such as nonces, user permission checks, validation and data sanitization.

You can add 10 different types of Input fields and Section.

###Types of Inputs Suported
1. Text
2. Textarea
3. Checkbox
4. Radio
5. Select
6. Multi-Select
7. Multi-Checkbox
8. Upload
9. Color
10. Editor

###Screenshot

![WP Postbox API Screenshot](https://raw.github.com/harishdasari/WP-Postbox-API-Wrapper-Class/master/screenshot.png)


###Installation
Copy the Directory `hd-wp-metabox-api` into your theme or plugin folder.

Include the following code in your theme `functions.php` file or plugin file.

	require_once( 'hd-wp-metabox-api/class-hd-wp-metabox-api.php' );

###Usage
First you need to create

1. Defining Options for Metabox
2. Defining input fields array
3. Initializing Metabox API Class using above options.

####Defining Options for Metabox
To create a top level menu page use following

	$example_options = array(
		'metabox_title' => 'Example Metabox', // Metabox Title
		'metabox_id'    => 'example_metabox', // Unique metabox id. it is alphanumeric and does not contains spaces.
		'post_type'     => 'post',            // Post Type : you can also define as array. array( 'post', 'page' )
		'context'       => 'normal',          // Metabox Context. Should be any of these 'normal', 'advanced' or 'side'
		'priority'      => 'high',            // Metabox Priority. Should be any of these 'high', 'core', 'default' or 'low',
	);

####Defining Input Field Options
Creating Input fields are easy and all should define in one single array.

First create an empty array

	$example_fields = array();

Define **input** field options and give unique `meta_key` as key.

	$example_fields = array(
		'hd_text_meta' => array(
			'title'   => 'Text Input',
			'type'    => 'text',
			'desc'    => 'Example Text Input',
			'sanit'   => 'nohtml',
		)
	);

Now add the other input fields you want. but make sure the array key should be unique.

####Initialize
Initialize the metabox class using the defined options and fields.

	$example_metabox = new HD_WP_Metabox_API( $example_options, $example_fields );



Full list of input field emamples and sections.

1. **To add Text Input**

		'hd_text_meta' => array(
			'title'   => 'Text Input',
			'type'    => 'text',
			'desc'    => 'Example Text Input',
			'sanit'   => 'nohtml',
		)

2. **To add Textarea Input**

		'hd_textarea_meta' => array(
			'title'   => 'Textarea Input',
			'type'    => 'textarea',
			'desc'    => 'Example Textarea Input',
			'sanit'   => 'nohtml',
		)

3. **To add Checkbox Input**

		'hd_checkbox_meta' => array(
			'title'   => 'Checkbox Input',
			'type'    => 'checkbox',
			'desc'    => 'Example Checkbox Input',
			'sanit'   => 'nohtml',
		)

4. **To add Radio Input**

		'hd_radio_meta' => array(
			'title'   => 'Radio Input',
			'type'    => 'radio',
			'choices' => array(
				'one'   => 'Option 1',
				'two'   => 'Option 2',
				'three' => 'Option 3'
			),
			'desc'    => 'Example Radio Input',
			'sanit'   => 'nohtml',
		)

5. **To add Select Input**

		'hd_select_meta' => array(
			'title'   => 'Select Input',
			'type'    => 'select',
			'choices' => array(
				'one'   => 'Option 1',
				'two'   => 'Option 2',
				'three' => 'Option 3'
			),
			'desc'    => 'Example Select Input',
			'sanit'   => 'nohtml',
		)

6. **To add Multi-Select Input**

		'hd_multiselect_meta' => array(
			'title'   => 'Multi Select Input',
			'type'    => 'select',
			'choices' => array(
				'one'   => 'Option 1',
				'two'   => 'Option 2',
				'three' => 'Option 3'
			),
			'multiple' => true,
			'desc'     => 'Example Multi Select Input',
			'sanit'    => 'nohtml',
		)

7. **To add Multi-Checkbox Input**

		'hd_multicheck_meta' => array(
			'title'   => 'Multi Checkbox Input',
			'type'    => 'multicheck',
			'choices' => array(
				'one'   => 'Option 1',
				'two'   => 'Option 2',
				'three' => 'Option 3'
			),
			'desc'    => 'Example Multi Checkbox Input',
			'sanit'   => 'nohtml',
		)

8. **To add Upload Input**

		'hd_upload_meta' => array(
			'title'   => 'Upload Input',
			'type'    => 'upload',
			'desc'    => 'Example Upload Input',
			'sanit'   => 'url',
		)

9. **To add Color Input**

		'hd_color_meta' => array(
			'title'   => 'Color Input',
			'type'    => 'color',
			'desc'    => 'Example Color Input',
			'sanit'   => 'color',
		)

10. **To add TinyMCE Editor Input**

		'hd_editor_meta' => array(
			'title'   => 'Editor Input',
			'type'    => 'editor',
			'desc'    => 'Example Editor Input',
			'sanit'   => 'nohtml',
		)

###Full Example

	<?php

	require_once( 'hd-wp-metabox-api/hd-wp-metabox-api.php' );

	$example_options = array(
		'metabox_title' => 'Example Metabox',
		'metabox_id'    => 'example_metabox',
		'post_type'     => array( 'post', 'page' ),
		'context'       => 'normal',
		'priority'      => 'high',
	);

	$example_fields = array(
		'hd_text_meta' => array(
			'title'   => 'Text Input',
			'type'    => 'text',
			'desc'    => 'Example Text Input',
			'sanit'   => 'nohtml',
		),
		'hd_textarea_meta' => array(
			'title'   => 'Textarea Input',
			'type'    => 'textarea',
			'desc'    => 'Example Textarea Input',
			'sanit'   => 'nohtml',
		),
		'hd_checkbox_meta' => array(
			'title'   => 'Checkbox Input',
			'type'    => 'checkbox',
			'desc'    => 'Example Checkbox Input',
			'sanit'   => 'nohtml',
		),
		'hd_radio_meta' => array(
			'title'   => 'Radio Input',
			'type'    => 'radio',
			'choices' => array(
				'one'   => 'Option 1',
				'two'   => 'Option 2',
				'three' => 'Option 3'
			),
			'desc'    => 'Example Radio Input',
			'sanit'   => 'nohtml',
		),
		'hd_select_meta' => array(
			'title'   => 'Select Input',
			'type'    => 'select',
			'choices' => array(
				'one'   => 'Option 1',
				'two'   => 'Option 2',
				'three' => 'Option 3'
			),
			'desc'    => 'Example Select Input',
			'sanit'   => 'nohtml',
		),
		'hd_multiselect_meta' => array(
			'title'   => 'Multi Select Input',
			'type'    => 'select',
			'choices' => array(
				'one'   => 'Option 1',
				'two'   => 'Option 2',
				'three' => 'Option 3'
			),
			'multiple' => true,
			'desc'     => 'Example Multi Select Input',
			'sanit'    => 'nohtml',
		),
		'hd_multicheck_meta' => array(
			'title'   => 'Multi Checkbox Input',
			'type'    => 'multicheck',
			'choices' => array(
				'one'   => 'Option 1',
				'two'   => 'Option 2',
				'three' => 'Option 3'
			),
			'desc'    => 'Example Multi Checkbox Input',
			'sanit'   => 'nohtml',
		),
		'hd_upload_meta' => array(
			'title'   => 'Upload Input',
			'type'    => 'upload',
			'desc'    => 'Example Upload Input',
			'sanit'   => 'url',
		),
		'hd_color_meta' => array(
			'title'   => 'Color Input',
			'type'    => 'color',
			'desc'    => 'Example Color Input',
			'sanit'   => 'color',
		),
		'hd_editor_meta' => array(
			'title'   => 'Editor Input',
			'type'    => 'editor',
			'desc'    => 'Example Editor Input',
			'sanit'   => 'nohtml',
		),
	);

	$example_metabox = new HD_WP_Metabox_API( $example_options, $example_fields );

### Actions and Filters

**Actions**


1. `add_action( 'hd_metabox_api_metabox_before', 'function_name' );`

	Callback arguments : `$options`, `$fields`

2. `add_action( 'hd_metabox_api_metabox_after', 'function_name' );`

	Callback arguments : `$options`, `$fields`

3. `add_action( 'hd_metabox_api_save_metabox', 'function_name' );`

	Callback arguments : `$post_id`, `$post`


**Filters**

1.	`add_filter( 'hd_metabox_api_supported_fields', 'function_name' );`

	Callback arguments : `$supported_fields`

2. `add_filter( 'hd_metabox_api_sanitize_option', 'function_name' );`

	Callback arguments : `$new_value`, `$field`, `$setting`

3. `add_filter( 'hd_html_helper_input_field', 'function_name' );`

	Callback arguments : `$input_html`, `$field`, `$show_help`


Note: where `function_name` is a callback function

###License
GNU General Public License v2.0 or later | [http://www.opensource.org/licenses/gpl-license.php](http://www.opensource.org/licenses/gpl-license.php)

<hr/>

Please post your suggetions and requests in issues, and also help me to imrpove this documenration.

Thank You <br/>
-- _Harish Dasari_ <br/>
[@harishdasari](http://twitter.com/harishdasari)

