<?php
//Currency format conversion - Indian Rupee
function money_conversion($n){
//$n = "1000000";
$len = strlen($n); //lenght of the no
$num = substr($n,$len-3,3); //get the last 3 digits
$n = intval($n/1000); //omit the last 3 digits already stored in $num
while($n > 0) //loop the process - further get digits 2 by 2
{
$len = strlen($n);
$num = substr($n,$len-2,2).",".$num;
$n = intval($n/100);
}
return $num;
}

$function = money_conversion(1000000000);
var_dump($function);
?>