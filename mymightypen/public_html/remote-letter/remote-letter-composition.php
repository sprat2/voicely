<?php
session_start();

// Display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load WordPress functionality
define('WP_USE_THEMES', false);
require('../wp-load.php');

// Adjust this for SSL when applicable
$ajax_host = "http://mymightypen.org/remote-letter/";

?>
<head>
    <title>Our site</title>
    <link rel="stylesheet" type="text/css" href="styles/composition.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Pontano+Sans" rel="stylesheet">
    <!-- Clipboard copying dependency -->
    <!-- Documentation:
        https://github.com/zenorocha/clipboard.js -->
    <script src="js/clipboard.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <!-- "Submitted" dialog. Hidden initially. Filled out dynamically. -->
    <div id="submit-view-container" style="display: none;">
        <div id="submit-view">
            <div id="alert-bar">
                <div id="no-share-warning" class="alert alert-warning" role="alert" style="display: none;">
                    <strong>Speak up!</strong> Letters that are shared get more attention!
                </div>
            </div>
            <div id="letter-progress">
                <span class="sharing-icon"></span>
                <span class="message"></span>
            </div>
            <div id="fb-share-progress">
                <span class="sharing-icon"></span>
                <span class="message"></span>
            </div>
            <div id="tw-share-progress">
                <span class="sharing-icon"></span>
                <span class="message"></span>
            </div>
            <div id="url-to-clipboard">
            </div>
        </div>
    </div>

    <!-- "Writing" dialog. Visible initially. -->
    <div id="step-sections" class="panel-group">

        <!-- Step 1 - Addressing -->
        <div id="step-one" class="panel panel-default">

            <!-- Heading -->
            <div id="step-one-heading" class="panel-heading" onclick="startStepOne()">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#step-sections" href="#collapse1">
                        To:
                    </a>
                </h4>
            </div>
            
            <!-- Content -->
            <div id="collapse1" class="panel-collapse collapse">
                <div class="people-selector">
                    <!--<p>People selection placeholder.</p>
                    <textarea id="person-textarea" placeholder="Enter people"></textarea>-->
                    <input type="text" id="person-textarea" placeholder="Who are you writing to?">
                    <table id="person-selected-table"></table>
                    <table id="person-table"></table>
                </div>
            </div>

        </div>

        
        <!-- Step 2 - Composition -->
        <div id="step-two" class="panel panel-default">
            
            <!-- Heading -->
            <div id="step-two-heading" class="panel-heading" onclick="startStepTwo()">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#step-sections" href="#collapse2">
                        Write
                    </a>
                </h4>
            </div>

            <!-- Content -->
            <div id="collapse2" class="panel-collapse collapse">
                <div class="letter-writing-form">
                    <textarea id="letter-title" placeholder="Title" rows=1></textarea><br>
                    <textarea id="letter-body" placeholder="Write something important to you..."></textarea>
                </div>
            </div>
        </div>

        
        <!-- Step 3 - Tagging -->
        <div id="step-three" class="panel panel-default">
            
            <!-- Heading -->
            <div id="step-three-heading" class="panel-heading" onclick="startStepThree()">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#step-sections" href="#collapse3">
                        Tag
                    </a>
                </h4>
            </div>

            <!-- Content -->
            <div id="collapse3" class="panel-collapse collapse">
                <div class="tags-selector">
                    <p>Tags selection placeholder.</p>
                    <textarea id="temp-textarea-tags" placeholder="Enter tags"></textarea>
                    <br>
                </div>
            </div>
        </div>
        

        <!-- Step 4 - Facebook Sharing -->
        <div id="step-four" class="panel panel-default">
            
            <!-- Heading -->
            <div id="step-four-heading" class="panel-heading" onclick="startStepFour()">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#step-sections" href="#collapse4">
                        Share (Facebook)
                    </a>
                </h4>
            </div>

            <!-- Content -->
            <div id="collapse4" class="panel-collapse collapse">
                <div class="remote-letter-sharing-dialog" id="remote-letter-facebook-sharing-dialog">
                    <div class="alert alert-info">
                        <strong>Hey!</strong> <span id=fb-share-percentage>An unknown </span>% of users share their letters to Facebook.
                    </div>
                    <textarea id="share-message-fb"></textarea>
                </div>
            </div>
        </div>
        

        <!-- Step 5 - Twitter Sharing -->
        <div id="step-five" class="panel panel-default">
            
            <!-- Heading -->
            <div id="step-five-heading" class="panel-heading" onclick="startStepFive()">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#step-sections" href="#collapse5">
                        Share (Twitter)
                    </a>
                </h4>
            </div>

            <!-- Content -->
            <div id="collapse5" class="panel-collapse collapse">
                <div class="remote-letter-sharing-dialog" id="remote-letter-twitter-sharing-dialog">
                    <div class="alert alert-info">
                        <strong>Hey!</strong> <span id=tw-share-percentage>An unknown </span>% of users share their letters to Twitter.
                    </div>
                    <textarea id="share-message-tw"></textarea>
                </div>
            </div>
        </div>

        <!-- The "Next" button - this will proceed to the next accordion element -->
        <button id="next-button" type="button" class="btn btn-primary btn-block">Next</button>

    </div>



    <script>
    // Open and prepare for the first element
    $( document ).ready(function() {
        
        $('#collapse1:not(".in")').collapse('show');
        startStepOne();

        // Register the person-selector populating event
        $('#person-textarea').on('input', newPerson);

        // Populate the share percentage fields appropriately
        getPercentageOfShared('facebook');
        getPercentageOfShared('twitter');

        // Remove all cookies with a period preceeding their domain (those set by PHP, primarily)
        //   This solves many issues, but will likely create issues if a more persistent experience is desired in the future
        deleteAllCookies();
    });

    // Step one
    function startStepOne() {
        // Close all accordion elements
        $('.panel-collapse.in').collapse('hide');

        // Open the accordion element
        $('#collapse1:not(".in")').collapse('show');

        // Set the "next" button up for the next element
        document.getElementById("next-button").onclick = endStepOne;
        $("#next-button").text("Next");
    }
    function endStepOne() {
        startStepTwo();
    }

    // Step two
    function startStepTwo() {
        // Close all accordion elements
        $('.panel-collapse.in').collapse('hide');

        // Open the accordion element
        $('#collapse2:not(".in")').collapse('show');

        // Set the "next" button up for the next element
        document.getElementById("next-button").onclick = endStepTwo;
        $("#next-button").text("Next");
    }
    function endStepTwo() {
        startStepThree();
    }

    // Step three
    function startStepThree() {
        // Close all accordion elements
        $('.panel-collapse.in').collapse('hide');

        // Open the accordion element
        $('#collapse3:not(".in")').collapse('show');

        // Set the "next" button up for the next element
        document.getElementById("next-button").onclick = endStepThree;
        $("#next-button").text("Next");
    }
    function endStepThree() {
        startStepFour();
    }

    // Step four - Facebook sharing
    function startStepFour() {
        // Close all accordion elements
        $('.panel-collapse.in').collapse('hide');

        // Set up the textarea's value using previously entered information
        document.getElementById("share-message-fb").value = getShareMessageWithCurrentParams();

        // Open the accordion element
        $('#collapse4:not(".in")').collapse('show');

        // Set the "next" button up for the next element
        document.getElementById("next-button").onclick = endStepFour;
        $("#next-button").text("Next");

        // Prompt user for permission to post to Facebook on their behalf
        getToken('Facebook');
    }
    function endStepFour() {
        startStepFive();
    }

    // Step five - Twitter sharing
    function startStepFive() {
        // Close all accordion elements
        $('.panel-collapse.in').collapse('hide');

        // Set up the textarea's value using previous sharing message
        document.getElementById("share-message-tw").value = document.getElementById("share-message-fb").value;

        // Open the accordion element
        $('#collapse5:not(".in")').collapse('show');

        // Set the "next" button up for the next element
        document.getElementById("next-button").onclick = endStepFive
        $("#next-button").text("Send");

        // Prompt user for permission to post to Twitter on their behalf
        getToken('Twitter');
    }
    function endStepFive() {
        // Change the view from the writing dialog to the submitted dialog
        $('#step-sections').css('display', 'none');
        $('#submit-view-container').css('display', 'flex');

        // Set up the submitted dialog's letter portion
        // sharing-icon
        $('#letter-progress .sharing-icon').html('<img src="img/loading.gif">');
        // Message
        $('#letter-progress .message').html('Publishing letter');
        $('#letter-progress .message').attr('class', 'message loading');

        // Submit the letter
        //   (Response handled via callback)
        submitLetter();
    }

    // Get the % of letters published which have been shared to the given social network
    function getPercentageOfShared( provider ) {
        // Perform the AJAX request
        $.get(<?="\"".$ajax_host."\"";?>+"ajax/get-percentage-shared-to-network.php", 
        {
            provider: provider,
        }
        ).then(
            // Transmission success callback
            function( data ){
                // Parse the server's response as JSON
                try {
                    var returnedData = JSON.parse( data );

                    // Handle server-specified errors if present
                    if ( returnedData.error === true ) {
                        console.log(returnedData);
                        return 'An unknown';
                    }
                    // Else no errors - proceed
                    else {
                        // Process response

                        // Replace percentage field with the proper value
                        if ( returnedData.provider == 'facebook' )
                            $('#fb-share-percentage').html(returnedData.result);
                        else if ( returnedData.provider == 'twitter' )
                            $('#tw-share-percentage').html(returnedData.result);
                        else
                            console.log(returnedData);
                    }
                }
                // Handle server response JSON parse errors
                catch ( e ) {
                    console.log(data);
                    return 'An unknown';
                }
            },
            // Transmission failure callback
            function( data ){
                console.log(data);
                return 'An unknown';
            }
        );
    }

    // Populates the person selector with relevant people
    function newPerson() {
        // Perform the AJAX request
        $.get(<?="\"".$ajax_host."\"";?>+"ajax/get-people.php", 
        {
            given_person: document.getElementById("person-textarea").value
        }
        ).then(
            // Transmission success callback
            function( data ){
                // Parse the server's response as JSON
                try {
                    var returnedData = JSON.parse( data );

                    // Handle server-specified errors if present
                    if ( returnedData.error === true ) {
                        console.log(returnedData);
                    }
                    // Else no errors - proceed
                    else {
                        // Process response
                        populatePeopleTable( returnedData );
                    }
                }
                // Handle server response JSON parse errors
                catch ( e ) {
                    console.log(returnedData);
                }
            },
            // Transmission failure callback
            function( data ){
                console.log(data);
            }
        );
    }
    function populatePeopleTable( returnedData ) {
        // returnedData is indexed from 0

        var newRows = "";
        var allowance = 30 - ( $('#person-selected-table td').length + 3 );
        for ( i=0; (i<returnedData.length) && (i<allowance); i++ ) {

            if ( (i%3) == 0 )
                newRows += '<tr>';
            
            newRows += '<td><button type="button" class="btn btn-default btn-block person-button">' + returnedData[i] + '</button></td>';

            if ( (i%3) == 2 )
                newRows += '</tr>';

        }
        $('#person-table').html(newRows);

        // Register the onClick events
        $('.person-button').on('click', personSelected);
    }
    function personSelected() {
        var selectedPerson = this.innerHTML;

        // Don't allow more than 30 people to be selected
        if ( $('#person-selected-table td').length >= 30 )
            return;

        var numOfCompleteRows = $('#person-selected-table tr').length;

        // If free for a new row, create one and add the element
        if ( ( $('#person-selected-table td').length % 3 ) == 0 )
            $('#person-selected-table').append('<tr><td><button type="button" class="btn btn-primary btn-block person-button">' + selectedPerson + '</button></td></tr>');
        
        // Else append the new person to the last row
        else {
            $('#person-selected-table tr:nth-of-type(' + numOfCompleteRows + ')').append('<td><button type="button" class="btn btn-primary btn-block person-selected-button">' + selectedPerson + '</button></td>');
        }

        // Fire newPerson() to recalculate the number of rows shown by its display
        newPerson();
    }

    // Returns the default sharing message using the user's entered parameters from the first few steps
    function getShareMessageWithCurrentParams() {

        // Title
        var title = document.getElementById( "letter-title" ).value.trim();
        if ( title.length > 0 ) {
            title = " entitled \"" + title + "\"";
        }
        else {
            title = "";
        }

        // Addressees
        // Fetch the input
        var addressees = document.getElementById( "person-textarea" ).value.split(", ");
        // Remove empty elements
        addressees = addressees.filter( function(entry) { return /\S/.test(entry); });
        if ( addressees.length > 0 ) {
            // Combine with oxford comma
            if ( addressees.length > 2 ) {
                last_addressee = addressees.pop();
                addressees.push( 'and ' + last_addressee );
                addressees = addressees.join( ', ' );
            }
            // Combine two without a comma
            if ( addressees.length == 2 ) {
                last_addressee = addressees.pop();
                addressees.push( 'and ' + last_addressee );
                addressees = addressees.join( ' ' );
            }
            // Add prefix text
            addressees = " to " + addressees;
        }
        else {
            // If empty
            addressees = "";
        }

        // Combine & return
        var result = "I just wrote an open letter" + addressees + title + ".";
        return result;
    }

    // Submits the letter using the user's entered parameters from the now-completed steps
    //   Calls processServerLetterResponse( jsonResponse ) on completion
    function submitLetter() {        
        // Create an array of parameters to be included in the AJAX request
        var postData = {
            addressees: document.getElementById("person-textarea").value,
            title:      document.getElementById("letter-title").value,
            contents:   document.getElementById("letter-body").value,
            tags:       document.getElementById("temp-textarea-tags").value,
        };

        // Perform the AJAX request
        $.post(<?="\"".$ajax_host."\"";?>+"ajax/post.php", postData).then(
            // Transmission success callback
            function( data ){
                // Parse the server's response as JSON
                try {
                    var returnedRemoteLetterData = JSON.parse( data );

                    // Handle server-specified errors if present
                    if ( returnedRemoteLetterData.error === true ) {
                        alert( "We're sorry, but the server has rejected your submission." +
                            "\n\nError message: " + returnedRemoteLetterData.error_msg );

                        // Update UI for letter sending failure
                        setTimeout(letterFailureHandler, 700);
                    }
                    // Else no errors - proceed
                    else {
                        // Process response
                        processServerLetterResponse( returnedRemoteLetterData );
                    }
                }
                // Handle server response JSON parse errors
                catch ( e ) {
                    alert( "JSON parse error: " + e.message + "\n\nServer response:" + data +
                        "\n\nThe application has not been cleaned up properly." );

                    // Update UI for letter sending failure
                    setTimeout(letterFailureHandler, 700);
                }
            },
            // Transmission failure callback
            function( data ){
                alert( "Submission transmission failure." );

                // Update UI for letter sending failure
                setTimeout(letterFailureHandler, 700);
            }
        );
    }

    function processServerLetterResponse( returnedRemoteLetterData ) {
        console.log( returnedRemoteLetterData );
        
        // Continue execution on a delay
        setTimeout(letterSuccessHandler, 700, returnedRemoteLetterData);
    }

    // Marks a post as shared
    function markAsShared( provider, postID ) {
        $.get(<?="\"".$ajax_host."\"";?>+"auth/mark-as-shared.php", { 
            post_id: postID, 
            provider: provider 
        } ).then(
            function( data ){
                console.log( data );
            },
            function( data ) {
                console.log( data );
            }
        );
    }

    // Note: Could be made to better handle arbitrary providers
    function postToSocialMedia( provider, returnedRemoteLetterData ) {

        if ( provider.toLowerCase() === 'facebook' ) {
            var shareMessage = document.getElementById('share-message-fb').value;
        } else if ( provider.toLowerCase() === 'twitter' ) {
            var shareMessage = document.getElementById('share-message-tw').value;
        } else {
            alert( 'Unknown provider.\nApplication not cleaned up properly.' );
        }

        // Perform the AJAX request
        $.get(<?="\"".$ajax_host."\"";?>+"auth/post-to-social-media.php", 
        {
            provider: provider,
            message:  shareMessage,
            url:      returnedRemoteLetterData.url_to_letter,
        }
        ).then(
            // Transmission success callback
            function( data ){
                // Parse the server's response as JSON
                try {
                    var returnedData = JSON.parse( data );

                    // Handle server-specified errors if present
                    if ( returnedData.error === true ) {

                        console.log(returnedData);

                        // Not alerting user on failure, since ajax updates to show the failure anyway
                        //   Only for debugging
                        //alert( "We're sorry, but the server has rejected your submission." +
                        //    "\n\nError message: " + returnedData.error_msg );

                        // Update UI for letter sending failure
                        if ( provider.toLowerCase() === 'facebook' ) {
                            setTimeout( facebookFailureHandler, 700, returnedRemoteLetterData );
                        } else if ( provider.toLowerCase() === 'twitter' ) {
                            setTimeout( twitterFailureHandler, 700, returnedRemoteLetterData );
                        }
                    }
                    // Else no errors - proceed
                    else {
                        // Process response
                        if ( provider.toLowerCase() === 'facebook' ) {
                            setTimeout( facebookSuccessHandler, 700, returnedRemoteLetterData );
                        } else if ( provider.toLowerCase() === 'twitter' ) {
                            setTimeout( twitterSuccessHandler, 700, returnedRemoteLetterData );
                        }
                    }
                }
                // Handle server response JSON parse errors
                catch ( e ) {
                    console.log(returnedData);

                    alert( "JSON parse error: " + e.message + "\n\nServer response:" + data );

                    // Update UI for letter sending failure
                    if ( provider.toLowerCase() === 'facebook' ) {
                        setTimeout( facebookFailureHandler, 700, returnedRemoteLetterData );
                    } else if ( provider.toLowerCase() === 'twitter' ) {
                        setTimeout( twitterFailureHandler, 700, returnedRemoteLetterData );
                    }
                }
            },
            // Transmission failure callback
            function( data ){
                console.log(returnedData);

                alert( "Submission transmission failure." + data );

                // Update UI for letter sending failure
                if ( provider.toLowerCase() === 'facebook' ) {
                    setTimeout( facebookFailureHandler, 700, returnedRemoteLetterData );
                } else if ( provider.toLowerCase() === 'twitter' ) {
                    setTimeout( twitterFailureHandler, 700, returnedRemoteLetterData );
                }
            }
        );
    }

    // Opens a new popup, centered
    // From: http://www.xtf.dk/2011/08/center-new-popup-window-even-on.html
    function popupCenter(url, title, w, h) {
        // Fixes dual-screen position                         Most browsers      Firefox
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        var top = ((height / 2) - (h / 2)) + dualScreenTop;
        var newWindow = window.open(url, title, 'scrollbars=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        // Puts focus on the newWindow
        if (window.focus) {
            newWindow.focus();
        }
    }

    function getToken( provider ) {
        var url = <?="\"".$ajax_host."\"";?>+"auth/get-token.php?provider=" + encodeURI(provider);      
        popupCenter( url, provider + " Authorization", 500, 500 );
    }

    // Handles letter submission success
    function letterSuccessHandler( returnedRemoteLetterData ) {
        // Update the UI to indicate that the letter has been published successfully
        // sharing-icon
        $('#letter-progress .sharing-icon').html('<img src="img/check.png">');
        // Message
        $('#letter-progress .message').html('Published!');
        $('#letter-progress .message').attr('class', 'message');

        // sharing-icon
        $('#fb-share-progress .sharing-icon').html('<img src="img/loading.gif">');
        // Message
        $('#fb-share-progress .message').html('Sharing to Facebook');

        // Make the call to Share to Facebook
        postToSocialMedia( 'Facebook', returnedRemoteLetterData );
    }
    // Alters the UI to signal letter publishing failure
    function letterFailureHandler() {
        // sharing-icon
        $('#letter-progress .sharing-icon').html('<img src="img/x.png">');
        // Message
        $('#letter-progress .message').html('Failed to publish letter');
        $('#letter-progress .message').attr('class', 'message');
    }

    function facebookSuccessHandler( returnedRemoteLetterData ) {
        // Update the UI to indicate that we've shared to Facebook successfully
        // sharing-icon
        $('#fb-share-progress .sharing-icon').html('<img src="img/check.png">');
        // Message
        $('#fb-share-progress .message').html('Shared to Facebook!');
        $('#fb-share-progress .message').attr('class', 'message');

        // Mark the post as shared to Facebook within WordPress
        markAsShared( 'Facebook', returnedRemoteLetterData.post_id );

        // sharing-icon
        $('#tw-share-progress .sharing-icon').html('<img src="img/loading.gif">');
        // Message
        $('#tw-share-progress .message').html('Sharing to Twitter');
        $('#tw-share-progress .message').attr('class', 'message loading');

        // Make the call to Share to Twitter
        postToSocialMedia( 'Twitter', returnedRemoteLetterData );
    }
    function facebookFailureHandler( returnedRemoteLetterData ) {
        // sharing-icon
        $('#fb-share-progress .sharing-icon').html('<img src="img/x.png">');
        // Message
        $('#fb-share-progress .message').html('Failed to share to Facebook');
        $('#fb-share-progress .message').attr('class', 'message');
        // Display failure message
        $('#no-share-warning').css('display', 'block');

        // sharing-icon
        $('#tw-share-progress .sharing-icon').html('<img src="img/loading.gif">');
        // Message
        $('#tw-share-progress .message').html('Sharing to Twitter');
        $('#tw-share-progress .message').attr('class', 'message loading');

        // Make the call to Share to Twitter
        postToSocialMedia( 'Twitter', returnedRemoteLetterData );
    }

    function twitterSuccessHandler( returnedRemoteLetterData ) {
        // Update the UI to indicate that we've shared to Twitter successfully
        // sharing-icon
        $('#tw-share-progress .sharing-icon').html('<img src="img/check.png">');
        // Message
        $('#tw-share-progress .message').html('Shared to Twitter!');
        $('#tw-share-progress .message').attr('class', 'message');

        // Mark the post as shared to Twitter within WordPress
        markAsShared( 'Twitter', returnedRemoteLetterData.post_id );
        
        // Present the user with the clipboard copying element
        displayClipboard( returnedRemoteLetterData );
    }
    function twitterFailureHandler( returnedRemoteLetterData ) {
        // sharing-icon
        $('#tw-share-progress .sharing-icon').html('<img src="img/x.png">');
        // Message
        $('#tw-share-progress .message').html('Failed to share to Twitter');
        $('#tw-share-progress .message').attr('class', 'message');
        // Display failure message
        $('#no-share-warning').css('display', 'block');

        // Present the user with the clipboard copying element
        displayClipboard( returnedRemoteLetterData );
    }

    // Present the user with the clipboard copying element
    function displayClipboard( returnedRemoteLetterData ) {
        $('#url-to-clipboard').html(
            '<div class="remote-letter-url-sharing-dialog">' +
                '<input id="remote-letter-link-textinput" type="text" value="' + returnedRemoteLetterData.url_to_letter + '" readonly>' +
                '<button type="button" data-clipboard-target="#remote-letter-link-textinput" id="remote-letter-clipboard-button">' +
                    '<img id="remote-letter-clipboard-image" alt="Copy to clipboard" src="img/clipboard.svg">' +
                '</button>' +
            '</div>'
        );

        // Initialize the clipboard dependency
        new Clipboard('#remote-letter-clipboard-button');
    }

    function deleteAllCookies() {
        $.get( <?="\"".$ajax_host."\"";?>+"auth/delete-auth-cookies.php" );
    }
    </script>

</body>

</html>