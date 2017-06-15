<?php

/*
 * This is the letter dialog, altered to not include the addressees
 *     field, and storing title and contents in session variables, 
 *     rather than storing in the system automatically upon completion.
 *
 * This behavior is expected to be leveraged by the code which includes it.
 *
 */


// Form's HTML data'
$form_html = "
<form name='letter' id='letter-form' method='post' action='.?letter_recorded' onsubmit='return validateForm()'>

    <textarea name='title' id='letter-title' placeholder='Title' rows='1' cols='70'></textarea>
    <br>
    
    <textarea name='contents' id='letter-body' placeholder='Write something important to you...' cols='70'></textarea>
    <br>

    <input type='submit' id='sign-letter-button' value='Sign'>
</form>
";

// If form has been submitted, create the post and display sharing options
// if ( ( isset ( $_POST['title'] ) ) &&
//      ( isset ( $_POST['contents'] ) ) ) {
    
//     // Sanitize input and store as session variables
//     $_SESSION['letter_title'] = sanitize_text_field( $_POST['title'] );
//     $_SESSION['letter_body'] = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['contents'] ) ) );
// }
// // If form has not yet been submitted, present it to the user
// else {
    echo $form_html;
// }
?>

<script>
// Validates the form, ensuring that no field is empty
function validateForm() {
    if ( ( document.forms['letter']['title'].value === '' ) ||
         ( document.forms['letter']['contents'].value === '' ) ) {
        return false;
    }
}
</script>