<?php
/* 
Vul hier de blocks in. De namen moeten corresponderen met de filenamen in de blocksdirectory.
Syntax in de TinyMCE editor:
[NEWSLETTER var variabele1=Een mooie variabele; var variable2=Nog een mooie veriabale;]

Let op iedere variable statement begint met var [variabelenaam]=[waarde]; en eindigt met een ;
*/
$blocks = ['NEWSLETTER', 'SALES'];

$response = [];
foreach ($blocks as $block) {
    array_push($response, '/\[' . $block . '(.*?)\]/');
}

return $response;

?>