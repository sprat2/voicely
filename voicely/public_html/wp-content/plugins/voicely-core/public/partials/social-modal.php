<?php
function getSocialModal( $body_content, $on_click ) {
	$output = '' . 
		'<!-- Modal - extra definition data is to disable clicking out of it -->
		<div id="myModal" class="modal fade" data-controls-modal="myModal" data-backdrop="static" 
		data-keyboard="false" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<h2 class="modal-title">Share your passion!</h2>
					</div>
					<div class="modal-body" id="editable-modal-body">
						' . $body_content . '
					</div>
					<div class="modal-opt-out-link">
						<small><a href="' . $on_click . '" id="next-link">No thanks...</a></small>
					</div>
				</div>
			</div>
		</div>';
	
	return $output;
}

function getFacebookBody() {

	// Header identifying the social network
	$output = '';

	// Set up HybridAuth
	include "$_SERVER[DOCUMENT_ROOT]/wp-content/plugins/voicely-core/includes/vendor/autoload.php";

	// If we're not authorized, just show the sharing/auth button
	if ( !isset( $_GET['fb_auth_requested'] ) ) {
		// Define the share/auth button
		$output .= '
		<form method="get">
			<div id="share-new-letter-fb">
				<input name="selection_topics" hidden>
				<input name="fb_auth_requested" hidden>
				<button class="letter-written-social-share-button" id="share-via-facebook" type="submit">
					Share to Facebook
				</button>
			</div>
		</form>';
	}
	// Else we're authorized.  Show the textbox and friend selection dialogs.
	else {
		// Set up the default sharing message
		$default_user_prefix = 'I wrote an open letter.';
		if ( ( isset( $_SESSION['letter_title'] ) ) &&
			( isset( $_SESSION['letter_body'] ) ) &&
			( isset( $_SESSION['people_selected'] ) ) &&
			( isset( $_SESSION['topics_selected'] ) ) ) {
			$default_user_prefix = "I wrote an open letter to " . 
				get_the_title( $_SESSION["people_selected"] ) .
				" about " . $_SESSION["topics_selected"] . 
				", entitled '" . $_SESSION["letter_title"] . "'.";
		}

		// Retrieve the user's contacts for our selectable list
		// try {
		// 	$config = [
		// 		'callback' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
		// 		'keys' => ["key" => "1365051533556262", "secret" => "8306ed4c6f2e903e49769ed14e5f3d1d" ],
		// 	];
		// 	$object = new Hybridauth\Provider\Facebook($config);
		// 	$object->authenticate();
		// 	$userProfile = $object->getUserContacts();
		// 	echo 'Contacts:' . var_export( $userProfile );
		// } catch ( Exception $e ) {
		// 	echo 'Failed to authenticate: ' . $e->getMessage();
		// }

		// Display sharing message text input box and sharing button which will advance us to the next dialog
		$output .= '
		<form method="get">
			<textarea class="user-prefix" name="fb-user-prefix" id="user-prefix" placeholder="' . $default_user_prefix . '" ></textarea>
			<br>
			<br>
			' /* // TODO Display check-able list of friends */ . '
			<div id="share-new-letter-fb">
				<input name="facebook_complete" hidden>
				<button class="letter-written-social-share-button" id="share-via-facebook" type="submit">
					Share to Facebook
				</button>
			</div>
		</form>';
	}

	return $output;
}

function getTwitterBody() {

	// Header identifying the social network
	$output = '';

	// Set up HybridAuth
	include "$_SERVER[DOCUMENT_ROOT]/wp-content/plugins/voicely-core/includes/vendor/autoload.php";

	// If we're not authorized, just show the sharing/auth button
	if ( !isset( $_GET['twit_auth_requested'] ) ) {
		// Define the share/auth button
		$output .= '
		<form method="get">
			<div id="share-new-letter-tw">
				<input name="facebook_complete" hidden>
				<input name="twit_auth_requested" hidden>
				<button class="letter-written-social-share-button" id="share-via-twitter" type="submit">
					Share to Twitter
				</button>
			</div>
		</form>';
	}
	// Else we're authorized.  Show the textbox and friend selection dialogs.
	else {
		// Set up the default sharing message
		$default_user_prefix = 'I wrote an open letter.';
		if ( isset( $_SESSION['letter_title'] ) &&
			( isset( $_SESSION['people_selected'] ) ) &&
			( isset( $_SESSION['topics_selected'] ) ) ) {
			$default_user_prefix = "I wrote an open letter to " . 
				get_the_title( $_SESSION["people_selected"] ) .
				" about " . $_SESSION["topics_selected"] . 
				", entitled '" . $_SESSION["letter_title"] . "'.";
		}

		// Retrieve the user's contacts for our selectable list
		try {
			$config = [
				'callback' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
				'keys' => ["key" => "c4L94qXTR3hwCI1WUtGGAaBeF", "secret" => "RH1D8O3qazcveJMpDZfcm8CLQ7TJv24ReTi4FOCS3Z7snFqwri" ],
			];
			$object = new Hybridauth\Provider\Twitter($config);
			$object->authenticate();
			$userProfile = $object->getUserContacts();
			// echo 'Contacts:<pre>';
			// var_export( $userProfile );
			// echo '</pre>';
			foreach ( $userProfile as $friend ) {
				echo $friend->displayName . '<br>';
			}
		} catch ( Exception $e ) {
			echo 'Failed to authenticate: ' . $e->getMessage();
		}

		// Display sharing message text input box and sharing button which will advance us to the next dialog
		$output .= '
		<form method="get">
			<textarea class="user-prefix" name="tw-user-prefix" id="user-prefix" placeholder="' . $default_user_prefix . '" ></textarea>
			<br>
			<br>
			' /* // TODO Display check-able list of friends */ . '
			<div id="share-new-letter-tw">
				<input name="twitter_complete" hidden>
				<button class="letter-written-social-share-button" id="share-via-twitter" type="submit">
					Share to Twitter
				</button>
			</div>
		</form>';
	}

	return $output;
}

function getEmailBody() {
	// Header identifying the social network
	$output = '';

	$default_user_prefix = 'I wrote an open letter.';
	if ( isset( $_SESSION['letter_title'] ) &&
		( isset( $_SESSION['people_selected'] ) ) &&
		( isset( $_SESSION['topics_selected'] ) ) ) {
		$default_user_prefix = "I wrote an open letter to " . 
			get_the_title( $_SESSION["people_selected"] ) .
			" about " . $_SESSION["topics_selected"] . 
			", entitled \"" . $_SESSION["letter_title"] . "\".";
	}

	$output .= "<br>

	<textarea class='user-prefix' name='user-prefix' id='user-prefix' placeholder='" . $default_user_prefix . "' ></textarea>
	<br>

	";

	// Share button
	$output .= '
	<br>
	<div id="share-new-letter-em">
		<button class="letter-written-social-share-button" id="share-via-email onclick="myFunction()">Share via Email</button>
	</div>';

	return $output;
}

function getMailBody() {
	return '<p>Mail placeholder frame.</p>';
}