<?php
	//Request the translated text that was passed into the url in "text_translate.php"
	$translated_text = $_REQUEST['text'];

    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

?>
<Response>
	<Say> The translated text is: </Say>
	<Say language="es"> <?php echo $translated_text;?> </Say>
</Response>