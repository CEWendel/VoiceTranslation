<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Translation
{
    protected $to; //The language you are translating to
    protected $to_language_code; // The URL friendly language code you are converting to

    /**
     * Constructor, by defualt makes translation from English to Spanish
     *
     * @param string               $_to       The language you are translating to(defualt is Spanish)
     *
     */
    public function __construct($_to)
    {
        $this->to = $_to;
        $this->to_language_code = $this->convert_to_language_code($_to);
    }

    /**
     * Converts language to it's URL friendly language code
     *
     * @param string               $_language      The language you want the language code equivilent of
     *
     */
    public function convert_to_language_code($_language)
    {
        $language_code;
        //make lanugage lowercase
        $_language = strtolower($_language);
        //Construct languages array
        $languages_array = array(
            "english" => "en", "spanish" => "es",
             "french" => "fr", "german" => "de"
        );

        if(array_key_exists($_language, $languages_array))
        {
            $language_code = $languages_array[$_language];
        }
        else
        {
            die('The language specified is not available');
        }

        return $language_code;
    }

    /**
     * Getter for $to property 
     * NOTE: __get and __set magic methods were not used for speed purposes
     *
     * @return string               $this->to    The $to property of the Translation Class
     *
     */
    public function get_to()
    {
        return $this->to;
    }

    /**
     * Setter for the $to property
     *
     * @param string               $_value      The new value being set to the $to property of the Translation Class
     *
     */
    public function set_to($_value)
    {
        $this->to = $_value;
        $this->to_language_code = $this->convert_to_language_code($_value);
    }


}

class GoogleTranslation extends Translation
{
    const TRANSLATE_URL = "https://www.googleapis.com/language/translate/v2";
    const DETECT_URL = "https://www.googleapis.com/language/translate/v2/detect";

    private $appKey; //The App key gotten from the Google API Consol

    /**
     * Constructor, by defualt makes translation from English to Spanish and sets auto-detection to false
     *
     * @param string               $_appID    Bing Dev Application ID
     * @param string               $_to       The language you are translating to
     *
     */
     public function __construct($_appKey, $_to = "Spanish")
     {
         parent::__construct($_to);
         $this->appKey = $_appKey;
     }

     /**
     * Getter for $appKey property
     *
     * @return string               $this->appKey   The $appKey property of the GoogleTranslation Class
     *
     */
     public function get_appID()
    {
        return $this->appID;
    }

    /**
     * Setter for the $appKey property
     *
     * @param string              $_value      The new value being set to the $appKey property of the GoogleTranslation Class
     *
     */
    public function set_appID($_value)
    {
        $this->appID = $value;
    }

    /**
     * Makes a curl request to googleapis.com and returns the auto-detected language
     *
     * @param $_text    The text to be detected using the Google Translator API 
     *
     * @return $detected_language_code      The detected language code(does not need to be converted using convert_to_language_code function)
     *
     */
    public function detect_language($_text)
    {
        $detected_language_code;
        $ch = curl_init();
        //set query data here with the URL
        $qry_str = "?key=$this->appKey&q=$_text";
        $url = self::DETECT_URL . $qry_str;
        $url = str_replace(" ","%20",$url); // to properly format the url
        curl_setopt($ch, CURLOPT_URL, $url); 

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '3');

        $json_response = curl_exec($ch);
        $json_obj = json_decode($json_response);
        $detected_language_code = $json_obj->data->detections[0][0]->language;
        //echo "Detected language code is $detected_language_code";
        curl_close($ch);
        
        return $detected_language_code;
    }
    /**
     * Makes a curl request to api.microsofttranslator.com and returns the translated text
     *
     * @param $_text    The text to be translated using the Microsoft Translator API 
     *
     * @return $translated_text      The translated text
     *
     */
    public function translate($_text)
    {

        //Detect the language that the user is speaking
        $from = $this->detect_language($_text);
        
        $ch = curl_init();
        //echo "parent to language code is ".parent::$to_language_code; 
        // Set query data here with the URL
        $qry_str = "?key=$this->appKey&q=$_text&source=$from&target=$this->to_language_code";
        $url = self::TRANSLATE_URL . $qry_str;
        $url = str_replace(" ","%20",$url); // to properly format the url
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '3');

        $json_response = curl_exec($ch);
        $json_obj = json_decode($json_response);
        $translated_text = $json_obj->data->translations[0]->translatedText;
        curl_close($ch);

        return $translated_text;
        
        
    }

}


class BingTranslation extends Translation
{
    const TRANSLATE_URL = "http://api.microsofttranslator.com/V2/Http.svc/Translate";
    const DETECT_URL =  "http://api.microsofttranslator.com/V1/Http.svc/Detect";

	private $appID; //App ID from Bing Dev Account

	/**
     * Constructor, by defualt makes translation from English to Spanish and sets auto-detection to false
     *
     * @param string               $_appID    Bing Dev Application ID
     * @param string               $_to 	  The language you are translating to
     *
     */
     public function __construct($_appID, $_to = "Spanish")
    {
        parent::__construct($_to);
        $this->appID = $_appID;
    }

     /**
     * Getter for $appID property
     *
     * @return Object               $this->appID   The $appID property of the BingTranslation Class
     *
     */
     public function get_appID()
    {
        return $this->appID;
    }

    /**
     * Setter for the $appID property
     *
     * @param Object               $_value      The new value being set to the $appID property of the BingTranslation Class
     *
     */
    public function set_appID($_value)
    {
        $this->appID = $value;
    }


     /**
     * Makes a curl request to api.microsofttranslator.com and returns the auto-detected language
     *
     * @param $_text    The text to be detected using the Microsoft Translator API 
     *
     * @return $detected_language_code      The detected language code(does not need to be converted using convert_to_language_code function)
     *
     */
    public function detect_language($_text)
    {
        $detected_language_code;
        $ch = curl_init();
        //set query data here with the URL
        $qry_str = "?appID=$this->appID&text=$_text";
        $url = self::DETECT_URL . $qry_str;
        $url = str_replace(" ","%20",$url); // to properly format the url
        curl_setopt($ch, CURLOPT_URL, $url); 

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '3');

        $detected_language_code = curl_exec($ch);
        curl_close($ch);
        
        return $detected_language_code;
    }
	/**
     * Makes a curl request to api.microsofttranslator.com and returns the translated text
     *
     * @param $_text 	The text to be translated using the Microsoft Translator API 
     *
     * @return $translated_text 	 The translated text
     *
     */
	public function translate($_text)
	{
        //Detect the language that the user is speaking
        $from = $this->detect_language($_text);
        
		$ch = curl_init();
        //echo "parent to language code is ".parent::$to_language_code; 
		// Set query data here with the URL
		$qry_str = "?appId=$this->appID&from=$from&to=$this->to_language_code&text=$_text";
        $url = self::TRANSLATE_URL . $qry_str;
        $url = str_replace(" ","%20",$url); // to properly format the url
		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');

        $translated_text = curl_exec($ch);
		curl_close($ch);

		return $translated_text;
        
	}
}

?>