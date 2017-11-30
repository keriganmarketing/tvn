<?php
/* WP Members Hooks for form overrides */

namespace Includes\Modules\Members;

use Includes\Modules\Agents\Agents;

class Members {

	private $loginPage;
	private $registerPage;
	private $passwordResetPage;
	private $forgotUsernamePage;
	private $changePasswordPage;
	private $editAccountPage;

	public function __construct() {

        if ( ! current_user_can( 'manage_options' ) ) {
            show_admin_bar( false );
        }

		//Set vars
		$this->loginPage          = 30;
		$this->registerPage       = 84;
		$this->passwordResetPage  = 32;
		$this->forgotUsernamePage = 90;
		$this->changePasswordPage = 92;
		$this->editAccountPage    = 34;

		$this->setLoginForm();
		$this->changeLoginFormRows();
		$this->changePageLinks();
		$this->changeFormStrings();
		$this->setRegisterForm();
		$this->changeRegisterFormRows();
		$this->changeRegistrationData();
		$this->hideAdminBar();
		$this->setEmailContentType();

        add_filter('wpmem_email_headers', function() {
            return "Content-Type: text/html";
        });


	}

	private function setLoginForm() {
		/* LOGIN */
		add_filter( 'wpmem_login_form_args', function ( $args, $action ) {
			/**
			 * This example adds a div wrapper around each
			 * row in the registration form.
			 */

			$args = array(

				// wrappers
				'heading_before'    => '<legend style="display:none !important;">',
				'heading_after'     => '</legend>',
				'fieldset_before'   => '<div class="row" style="width:100%">',
				'fieldset_after'    => '</div>',
				'main_div_before'   => '<div id="user-login">',
				'main_div_after'    => '</div>',
				'txt_before'        => '',
				'txt_after'         => '',
				'row_before'        => '',
				'row_after'         => '',
				'buttons_before'    => '<div class="buttons col-md-4">',
				'buttons_after'     => '</div>',
				'link_before'       => '<div class="link-text col-12">',
				'link_after'        => '</div>',

				// classes & ids
				'form_id'           => 'account-login',
				'form_class'        => 'form-inline',
				'button_id'         => '',
				'button_class'      => 'btn btn-primary btn-outlined',

				// other
				'strip_breaks'      => true,
				'wrap_inputs'       => false,
				'remember_check'    => false,
				'n'                 => "\n",
				't'                 => "\t",
				'redirect_to'       => "/my-account/",
				'login_form_action' => true,
				'heading'           => 'Login',
				'button_text'       => 'Log In',

			);

			return $args;
		}, 10, 2 );
	}

	private function changeLoginFormRows() {
		add_filter( 'wpmem_login_form_rows', function ( $rows, $action ) {
			/**
			 * Form rows are assembled as an array and the array
			 * is passed through this filter. The rows will be
			 * numerical array keys (defaults: 0 & 1).
			 **/

			if ( is_page( $this->loginPage ) ) {  //LOGIN PAGE
				$rows[0] = array(
					'row_before'   => '<div class="col-md-6 col-lg-4">',
					'label'        => '<label for="log" class="sr-only">Username</label>',
					'field_before' => '<div class="input-group mb-2">',
					'field'        => '<input name="log" type="text" id="log" value="" class="username form-control" required placeholder="Username" />',
					'field_after'  => '</div>',
					'row_after'    => '</div>'
				);

				$rows[1] = array(
					'row_before'   => '<div class="col-md-6 col-lg-4">',
					'label'        => '<label for="pwd" class="sr-only">Password</label>',
					'field_before' => '<div class="input-group mb-2">',
					'field'        => '<input name="pwd" type="password" id="pwd" value="" class="password form-control" required placeholder="Password" />',
					'field_after'  => '</div>',
					'row_after'    => '</div>'
				);
			}

			if ( is_page( $this->passwordResetPage ) ) { //RESET PASSWORD PAGE
				$rows[0] = array(
					'row_before'   => '<div class="col-md-6 col-lg-4">',
					'label'        => '<label for="user" class="sr-only">Username</label>',
					'field_before' => '<div class="input-group mb-2">',
					'field'        => '<input name="user" type="text" id="user" value="" class="user form-control" required placeholder="Username" />',
					'field_after'  => '</div>',
					'row_after'    => '</div>'
				);

				$rows[1] = array(
					'row_before'   => '<div class="col-md-6 col-lg-4">',
					'label'        => '<label for="email" class="sr-only">Password</label>',
					'field_before' => '<div class="input-group mb-2">',
					'field'        => '<input name="email" type="text" id="email" value="" class="email form-control" required placeholder="Email Address" />',
					'field_after'  => '</div>',
					'row_after'    => '</div>'
				);
			}

			if ( is_page( $this->forgotUsernamePage ) ) { //FORGOT USERNAME PAGE
				$rows[0] = array(
					'row_before'   => '<div class="col-md-6">',
					'label'        => '<label for="user_email" class="sr-only">Password</label>',
					'field_before' => '<div class="input-group mb-2">',
					'field'        => '<input name="user_email" type="text" id="user_email" value="" class="user_email form-control" required placeholder="Email Address" />',
					'field_after'  => '</div>',
					'row_after'    => '</div>'
				);
			}

			if ( is_page( $this->changePasswordPage ) ) {  //CHANGE PASSWORD
				$rows[0] = array(
					'row_before'   => '<div class="col-md-6 col-lg-4">',
					'label'        => '<label for="pass1" class="sr-only">New Password</label>',
					'field_before' => '<div class="input-group mb-2">',
					'field'        => '<input name="pass1" type="password" id="pass1" value="" class="pass1 form-control" required placeholder="New Password" />',
					'field_after'  => '</div>',
					'row_after'    => '</div>'
				);

				$rows[1] = array(
					'row_before'   => '<div class="col-md-6 col-lg-4">',
					'label'        => '<label for="pass2" class="sr-only">Confirm New Password</label>',
					'field_before' => '<div class="input-group mb-2">',
					'field'        => '<input name="pass2" type="password" id="pass2" value="" class="pass2 form-control" required placeholder="Confirm New Password" />',
					'field_after'  => '</div>',
					'row_after'    => '</div>'
				);
			}

			return $rows;
		}, 10, 2 );

	}

	private function changePageLinks() {

		add_filter( 'wpmem_forgot_link', function ( $str ) {
			return '/my-account/reset-password/';
		} );

		add_filter( 'wpmem_forgot_link_str', function ( $str, $link ) {
			return '<p>Forgot password? <a href="' . $link . '">Get a new one.</a></p>';
		}, 10, 2 );


		add_filter( 'wpmem_username_link', function ( $str ) {
			return '/my-account/forgot-username/';
		} );

		add_filter( 'wpmem_reg_link', function ( $str ) {
			return '/my-account/register/';
		} );

	}

	private function changeFormStrings() {

		add_filter( 'wpmem_username_link_str', function ( $str, $link ) {
			return '<p>Forgot Username? <a href="' . $link . '">Retrieve it.</a></p>';
		}, 10, 2 );

		add_filter( 'wpmem_reg_link_str', function ( $str, $link ) {
			return '<h3>Don\'t have an account yet?</h3> <p><a href="' . $link . '">Set up one and start saving properties.</a></p>';
		}, 10, 2 );

	}

	private function setRegisterForm() {

        add_action( 'wpmem_pre_register_data', function ( $fields ) {

            global $wpmem_themsg; //error message

            //echo '<pre>',print_r($fields),'</pre>';

            return;
        } );



		add_filter( 'wpmem_register_form_args', function ( $args, $toggle ) {

			$args = array(

				// wrappers
				'heading_before'   => '<legend style="display:none !important;">',
				'heading_after'    => '</legend>',
				'fieldset_before'  => '<h3>Contact Information</h3><div class="row" style="width:100%">',
				'fieldset_after'   => '</div>',
				'main_div_before'  => '<div id="register">',
				'main_div_after'   => '</div>',
				'txt_before'       => '',
				'txt_after'        => '',
				'row_before'       => '',
				'row_after'        => '',
				'buttons_before'   => '<div class="buttons col mb-4">',
				'buttons_after'    => '</div>',

				// classes & ids
				'form_id'          => 'beachy-registration',
				'form_class'       => 'form form-inline',
				'button_id'        => '',
				'button_class'     => 'btn btn-primary btn-outlined',

				// required field tags and text
				'req_mark'         => '<span class="req">*</span>',
				'req_label'        => '<span class="req">*</span>' . __( 'Required field', 'wp-members' ),
				'req_label_before' => '<div class="req-text text-right col">',
				'req_label_after'  => '</div>',

				// buttons
				'show_clear_form'  => true,
				'clear_form'       => __( 'Reset Form', 'wp-members' ),
				'submit_register'  => __( 'Register', 'wp-members' ),
				'submit_update'    => __( 'Update Profile', 'wp-members' ),

				// other
				'strip_breaks'     => true,
				'use_nonce'        => false,
				'wrap_inputs'      => true,
				'n'                => "\n",
				't'                => "\t",

			);

			return $args;
		}, 10, 2 );

	}

	private function changeRegisterFormRows() {

		add_filter( 'wpmem_register_form_rows', function ( $rows, $toggle ) {

			/*
			Form rows are assembled as an array and the entire
			array is passed through this filter. Each row will
			have an array key equal to its option name (meta_key).

			order,meta,type,value are all passed for doing value
			comparisons. The remaining keys are form output and
			changes here will be reflected in the displayed form.
			 */
			$currentUser     = get_user_meta( get_current_user_id() );
			$currentUserInfo = get_userdata( get_current_user_id() );
			//echo '<pre>',print_r($currentUser),'</pre>';

            $first_name = (isset($_POST['first_name']) ? $_POST['first_name'] : '');
            $last_name  = (isset($_POST['last_name']) ? $_POST['last_name'] : '');
            $phone1     = (isset($_POST['phone1']) ? $_POST['phone1'] : '');
            $user_email = (isset($_POST['user_email']) ? $_POST['user_email'] : '');

            echo (isset($_POST['quicksignup']) && $_POST['quicksignup'] == true ? '<h2>Got it!</h2><p>Now just choose a password and click register. Then, start saving properties.</p>' : '');

			$rows = array();

			$agents       = new Agents();
			$agentArray   = $agents->getAgentNames();
            $agentOptions = '<option value="" selected >Select an agent</option>';
			$agentOptions .= '<option value="" >First available</option>';

			$selectedAgent = ( isset( $currentUser['selected_agent'][0] ) ? $currentUser['selected_agent'][0] : null );
			foreach ( $agentArray as $agent ) {
				$agentOptions .= '<option value="' . $agent . '" ' . ( $selectedAgent == $agent ? 'selected' : '' ) . ' >' . $agent . '</option>';
			}

			//echo '<pre>',print_r($agentArray),'</pre>';

			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '<div class="col-md-6">',
				'label'        => '<label for="first_name" class="sr-only">First Name *</label>',
				'field_before' => '<div class="input-group mb-2">',
				'field'        => '<input name="first_name" type="text" id="first_name" value="' . ( $currentUser['first_name'][0] != '' ? $currentUser['first_name'][0] : $first_name ) . '" class="textbox form-control" required placeholder="First Name *" />',
				'field_after'  => '</div>',
				'row_after'    => '</div>'
			);

			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '<div class="col-md-6">',
				'label'        => '<label for="last_name" class="sr-only">Last Name *</label>',
				'field_before' => '<div class="input-group mb-2">',
				'field'        => '<input name="last_name" type="text" id="last_name" value="' . ( $currentUser['last_name'][0] != '' ? $currentUser['last_name'][0] : $last_name ) . '" class="textbox form-control" required placeholder="Last Name *" />',
				'field_after'  => '</div>',
				'row_after'    => '</div>'
			);

			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '<div class="col-md-6">',
				'label'        => '<label for="phone1" class="sr-only">Phone Number *</label>',
				'field_before' => '<div class="input-group mb-2">',
				'field'        => '<input name="phone1" type="text" id="phone1" value="' . ( isset( $currentUser['phone1'][0] ) ? $currentUser['phone1'][0] : $phone1 ) . '" class="textbox form-control" required placeholder="Phone Number *" />',
				'field_after'  => '</div>',
				'row_after'    => '</div>'
			);

			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '<div class="col-md-6">',
				'label'        => '<label for="user_email" class="sr-only">Email Address *</label>',
				'field_before' => '<div class="input-group mb-2">',
				'field'        => '<input name="user_email" type="text" id="user_email" value="' . ( isset( $currentUserInfo->user_email ) ? $currentUserInfo->user_email : $user_email ) . '" class="textbox form-control" required placeholder="Email Address *" />',
				'field_after'  => '</div>',
				'row_after'    => '</div>'
			);

			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '</div>',
				'label'        => '',
				'field_before' => '<div class="spacer">',
				'field'        => '',
				'field_after'  => '</div>',
				'row_after'    => '<h3>Account Information</h3><div class="row" style="width:100%">'
			);

			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '<div class="col-md-6">',
				'label'        => '<label for="user_login" class="sr-only">Choose a Username *</label>',
				'field_before' => '<div class="input-group mb-2">',
				'field'        => '<input name="user_login" type="text" id="user_login" value="' . ( isset( $currentUserInfo->user_login ) ? $currentUserInfo->user_login : '' ) . '" class="textbox form-control" required ' . ( ! is_page( $this->registerPage ) ? 'disabled' : '' ) . ' placeholder="Choose a Username *" />',
				'field_after'  => '</div>',
				'row_after'    => '</div>'
			);

			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '<div class="col-md-6">',
				'label'        => '<label for="selected_agent" class="sr-only" >Select an agent</label>',
				'field_before' => '<div class="input-group mb-2">',
				'field'        => '<select name="selected_agent" id="selected_agent" class="select form-control custom-select" placeholder="Select an agent" >' . $agentOptions . '</select>',
				'field_after'  => '</div>',
				'row_after'    => '</div>'
			);

			if ( is_page( $this->registerPage ) ) {

				$rows[] = array(
					'type'         => 'password',
					'row_before'   => '<div class="col-md-6">',
					'label'        => '<label for="password" class="sr-only">Password *</label>',
					'field_before' => '<div class="input-group mb-2">',
					'field'        => '<input name="password" type="password" id="password" value="" class="textbox form-control" required placeholder="Password *" />',
					'field_after'  => '</div>',
					'row_after'    => '</div>'
				);

				$rows[] = array(
					'type'         => 'password',
					'row_before'   => '<div class="col-md-6">',
					'label'        => '<label for="confirm_password" class="sr-only">Confirm Password</label>',
					'field_before' => '<div class="input-group mb-2">',
					'field'        => '<input name="confirm_password" type="password" id="confirm_password" value="" class="textbox form-control" required placeholder="Confirm Password *" />',
					'field_after'  => '</div>',
					'row_after'    => '</div>'
				);

			}

			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '</div>',
				'label'        => '',
				'field_before' => '<div class="spacer">',
				'field'        => '',
				'field_after'  => '</div>',
				'row_after'    => '<h3>Optional Information</h3><div class="row" style="width:100%">'
			);


			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '<div class="col-md-6">',
				'label'        => '<label for="addr1" class="sr-only">Address 1</label>',
				'field_before' => '<div class="input-group mb-2">',
				'field'        => '<input name="addr1" type="text" id="addr1" value="' . ( isset( $currentUser['addr1'][0] ) ? $currentUser['addr1'][0] : '' ) . '" class="textbox form-control" placeholder="Address 1" />',
				'field_after'  => '</div>',
				'row_after'    => '</div>'
			);
			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '<div class="col-md-6">',
				'label'        => '<label for="addr2" class="sr-only">Address 2</label>',
				'field_before' => '<div class="input-group mb-2">',
				'field'        => '<input name="addr2" type="text" id="addr2" value="' . ( isset( $currentUser['addr2'][0] ) ? $currentUser['addr2'][0] : '' ) . '" class="textbox form-control" placeholder="Address 2" />',
				'field_after'  => '</div>',
				'row_after'    => '</div>'
			);

			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '<div class="col-sm-9 col-md-4">',
				'label'        => '<label for="city" class="sr-only">City</label>',
				'field_before' => '<div class="input-group mb-2">',
				'field'        => '<input name="city" type="text" id="city" value="' . ( isset( $currentUser['city'][0] ) ? $currentUser['city'][0] : '' ) . '" class="textbox form-control" placeholder="City" />',
				'field_after'  => '</div>',
				'row_after'    => '</div>'
			);

			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '<div class="col-sm-3 col-lg-2">',
				'label'        => '<label for="thestate" class="sr-only">State</label>',
				'field_before' => '<div class="input-group mb-2">',
				'field'        => '<input name="thestate" type="text" id="thestate" value="' . ( isset( $currentUser['thestate'][0] ) ? $currentUser['thestate'][0] : '' ) . '" class="textbox form-control" placeholder="State" />',
				'field_after'  => '</div>',
				'row_after'    => '</div>'
			);

			$rows[] = array(
				'type'         => 'text',
				'row_before'   => '</div>',
				'label'        => '',
				'field_before' => '<div class="spacer">',
				'field'        => '',
				'field_after'  => '</div>',
				'row_after'    => '<div class="row" style="width:100%">'
			);

			return $rows;
		}, 10, 2 );

	}

	private function changeRegistrationData() {
		add_filter( 'wpmem_register_data', function ( $fields, $toggle ) {
			/*
			 * The data from the registration form is brought in
			 * with the $fields array.  You can filter any of the
			 * the values, and add/subtract from the array before
			 * returning the filtered result.
			 *
			 * Note that if the operation being done should only
			 * be done on "new" registration or user profile "edit"
			 * then you should include a logical test for $toggle
			 */

			if ( 'new' == $toggle ) {
				// This is a new registration
			}

			if ( 'edit' == $toggle ) {
				// This is a user profile edit
			}

			return $fields;
		}, 10, 2 );

	}

	private function hideAdminBar() {
		add_action( 'set_current_user', function () {
			if ( ! current_user_can( 'edit_posts' ) ) {
				show_admin_bar( false );
			}
		} );
	}

	private function setEmailContentType() {
		add_filter( 'wp_mail_content_type', function () {
			return "text/html";
		} );
	}
}
