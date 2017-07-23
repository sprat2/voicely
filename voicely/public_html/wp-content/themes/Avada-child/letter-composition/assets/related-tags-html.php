<?php

// This file returns properly formatted HTML for the "related tags" element of the 
//   letter-writing page.  This file will eventually generate its results via NLP 
//   on the current letter's parameters.

echo '<p><b>Trump</b> (12.5K)</p><p><b>Trump Administration</b> (692)</p><p><b>Trumpcare</b> (460)</p><p><b>Trump Transition</b> (443)</p><p><b>Trump 2016</b> (141)</p>';
echo '<br>';
if ( isset( $_POST['relatedDataElement'] ) )
    echo 'You sent the following relatedDataElement: ' . $_POST['relatedDataElement'] . '.';
else
    echo 'You didn\'t send a relatedDataElement.';