<?php
    require_once('../Translation.php');
    require_once('../Services/Twilio.php');

    // Twilio REST API version
    $version = '2010-04-01';

    $transcribed_text;
 
    // AccountSid and Auth Token from Twilio.com/user/account
    $sid = 'XXXXXX';
    $token = 'YYYYYYY';

    
    if (!isset($_REQUEST['TranscriptionStatus'])) {
        echo "Must specify transcription status";
        die;
    }

    //If the Transcription is complete, get the Transcripted text
    $transcribed_text;
    if (strtolower($_REQUEST['TranscriptionStatus']) = "completed") {
        $transcribed_text = $_REQUEST['TranscriptionText'];
    } 

    //Your Bing AppID if you are using the Microsoft Translator, The google API Key if you are using the Google Translate API
    $key = "XXXXXXXXXXX";

    $language = $_REQUEST['language'];

    $translator = new GoogleTranslation($key,$language);
    /**
    *   Using the Microsoft Translator API:
    *   $translator = new BingTranslation($key, $language);
    */

    //Get the translated text from the translate function
    $translated_text = $translator->translate($transcribed_text);

    // Instantiate a new Twilio Rest Client
    $client = new Services_Twilio($sid, $token, $version);

    //Phone number you have previously validated with Twilio
    $phone_number = "YYYYYYYYYY";

    //The number to be called with the translated text
    $to_number;
 
    try {
        // Get The Most Recent From Number
        foreach ($client->account->calls as $call) {
            $to_number = $call->from;
            echo "to number is $to_number";
            break;
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
 
    try {
        // Initiate a new outbound call
        $call = $client->account->calls->create(
            $phone_number, // The number of the phone initiating the call
            $to_number, // The number of the phone receiving call
            'http://example.com/Voice/speak_translated.php?text='.urlencode($translated_text) // The URL Twilio will request when the call is answered
        );
        echo 'Started call: ' . $call->sid;
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }

    //Email the translated text:
    mail('example@example.com','Subject', $translated_text);
    
?>   
 
