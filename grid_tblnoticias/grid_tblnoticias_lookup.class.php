<?php
class grid_tblnoticias_lookup
{
//  
   function lookup_bolfeed(&$bolfeed) 
   {
      $conteudo = "" ; 
      if ($bolfeed == "1")
      { 
          $conteudo = "Sim";
      } 
      if ($bolfeed == "0")
      { 
          $conteudo = "Não";
      } 
      if (!empty($conteudo)) 
      { 
          $bolfeed = $conteudo; 
      } 
   }  
}
?>
