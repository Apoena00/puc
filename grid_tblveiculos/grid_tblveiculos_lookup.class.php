<?php
class grid_tblveiculos_lookup
{
//  
   function lookup_bolforalinha(&$bolforalinha) 
   {
      $conteudo = "" ; 
      if ($bolforalinha == "1")
      { 
          $conteudo = "Sim";
      } 
      if ($bolforalinha == "0")
      { 
          $conteudo = "Não";
      } 
      if (!empty($conteudo)) 
      { 
          $bolforalinha = $conteudo; 
      } 
   }  
}
?>
