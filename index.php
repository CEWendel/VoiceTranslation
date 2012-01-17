<?php
    /**
    *   Example translating text with both Bing and Google translator
    */
    //TEST BRANCH

    require_once('Translation.php');

    //Text to translate and the variables that will store the translated text
    $text_to_translate = "Hello my name is Chris";
    $translated_google_text = "";
    $translated_bing_text = "";


    //Google API Key(gotten from https://code.google.com/apis/console/)
    $key = "XXXXXXXXX";

    //Language to translate to
    $language = "German";

    //Transle using Google Translate API
    $google_translator = new GoogleTranslate($key,$language);
    $translated_google_text = $google_translator->translate($text_to_translate);

    echo "The translation of " . $text_to_translate . "according to the Google Translator is " . $translated_google_text . '\n';

    //Bing appID(gotten from http://bing.com/toolbox/bingdeveloper)
    $appID = "YYYYYYYYY";

    //Translate using the Bing Translate API
    $bing_translator = new BingTranslate($appID,$language);
    $translated_bing_text = $bing_translator->translate($text_to_translate);

    echo "The translation of " . $text_to_translate . "according to the Microsoft Translator is " . $translated_bing_text;




?>