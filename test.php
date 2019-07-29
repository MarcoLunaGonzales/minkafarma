<?php
function isPalindrome($word){
  // Please write your code here.
  $cadenaReversa="";
  $jj=strlen($word)-1;
  for($i=strlen($word)-1;$i>=0;$i--){ 
      $cadenaReversa=$cadenaReversa.$word[$i]; 
  }
  echo $word." ".$cadenaReversa; 
  if($word==$cadenaReversa){
    return true;
  }else{
    return false;
  }
}
$word = "deleveled";
echo isPalindrome($word) ? "true" : "false";
?>