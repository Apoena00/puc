<?php
class grid_tblpecas_lookup
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
          $conteudo = "N�o";
      } 
      if (!empty($conteudo)) 
      { 
          $bolforalinha = $conteudo; 
      } 
   }  
}
?>
