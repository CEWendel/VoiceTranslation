<?php
    /**  
    *   The callback file that an HTTP request is sent to when the Twilio phone number is called
    *   This full path to this file should be the "Voice URL" in the App Details at Twilio.com/user/account
    */

	header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
 <Response>
    <Say>Hello, welcome to the Twilio Translation Service</Say>
    <Gather numDigits="1" action="handle_key.php" method="POST">
        <Say>
            To translate to English, press 1.  
            To translate to Spanish, press 2.
            To translate to French, press 3.
            To translate to German, press 4.
        </Say>
    </Gather>
</Response>
