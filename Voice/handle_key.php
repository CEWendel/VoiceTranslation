<?php
 
    // If the caller pressed anything but 1, 2, 3 or 4 send them back
    if($_REQUEST['Digits'] != '1' and $_REQUEST['Digits'] != '2' and $_REQUEST['Digits'] != '3' and $_REQUEST['Digits'] != '4') {
        header("Location: sms_response.php");
        die;
    }
     
    // otherwise, if 1 was pressed we Dial 3105551212. If 2 
    // we make an audio recording up to 30 seconds long.
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
<?php if ($_REQUEST['Digits'] == '1') { ?>
    <Say> Please say the text you want translated to english. </Say>
   <Record transcribe="true" transcribeCallback="<?php
        echo "http://example.com/Voice/text_translate.php?language=English" ?>"
        action="goodbye.php" maxLength="30" />
<?php } elseif ($_REQUEST['Digits'] == '2') { ?>
    <Say> Please say the text you want translated to spanish. </Say>
    <Record transcribe="true" transcribeCallback="<?php
        echo "http://example.com/Voice/text_translate.php?language=Spanish" ?>"
        action="goodbye.php" maxLength="60" />
<?php } elseif ($_REQUEST['Digits'] == '3') { ?>
     <Say> Please say the text you want translated to french. </Say>
    <Record transcribe="true" transcribeCallback="<?php
        echo "http://example.com/Voice/text_translate.php?language=French" ?>"
        action="goodbye.php" maxLength="30" />
<?php } elseif ($_REQUEST['Digits'] == '4') { ?>
     <Say> Please say the text you want translated to german. </Say>
    <Record transcribe="true" transcribeCallback="<?php
        echo "http://example.com/Voice/text_translate.php?language=German" ?>"
        action="goodbye.php" maxLength="30" />
<?php } ?>
</Response>