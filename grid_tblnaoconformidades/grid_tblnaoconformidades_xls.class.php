<?php

class grid_tblnaoconformidades_xls
{
   var $Db;
   var $Erro;
   var $Ini;
   var $Lookup;
   var $nm_data;
   var $Xls_dados;
   var $Xls_workbook;
   var $Xls_col;
   var $Xls_row;
   var $sc_proc_grid; 
   var $NM_cmp_hidden = array();
   var $Arquivo;
   var $Tit_doc;
   //---- 
   function __construct()
   {
   }

   //---- 
   function monta_xls()
   {
      $this->inicializa_vars();
      $this->grava_arquivo();
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida']) {
          if ($this->Ini->sc_export_ajax)
          {
              $this->Arr_result['file_export']  = NM_charset_to_utf8($this->Xls_f);
              $this->Arr_result['title_export'] = NM_charset_to_utf8($this->Tit_doc);
              $Temp = ob_get_clean();
              if ($Temp !== false && trim($Temp) != "")
              {
                  $this->Arr_result['htmOutput'] = NM_charset_to_utf8($Temp);
              }
              $oJson = new Services_JSON();
              echo $oJson->encode($this->Arr_result);
              exit;
          }
          else
          {
              $this->progress_bar_end();
          }
      }
      else { 
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['opcao'] = "";
      }
   }

   //----- 
   function inicializa_vars()
   {
      global $nm_lang;
      $this->Xls_tot_col = 0;
      $this->Xls_row     = 0;
      $this->New_Xls_row = 1;
      $dir_raiz          = strrpos($_SERVER['PHP_SELF'],"/") ;  
      $dir_raiz          = substr($_SERVER['PHP_SELF'], 0, $dir_raiz + 1) ;  
      $this->nm_location = $this->Ini->sc_protocolo . $this->Ini->server . $dir_raiz; 
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
      { 
          set_include_path(get_include_path() . PATH_SEPARATOR . $this->Ini->path_third . '/phpexcel/');
          require_once $this->Ini->path_third . '/phpexcel/PHPExcel.php';
          require_once $this->Ini->path_third . '/phpexcel/PHPExcel/IOFactory.php';
          require_once $this->Ini->path_third . '/phpexcel/PHPExcel/Cell/AdvancedValueBinder.php';
      } 
      $orig_form_dt = strtoupper($_SESSION['scriptcase']['reg_conf']['date_format']);
      $this->SC_date_conf_region = "";
      for ($i = 0; $i < 8; $i++)
      {
          if ($i > 0 && substr($orig_form_dt, $i, 1) != substr($this->SC_date_conf_region, -1, 1)) {
              $this->SC_date_conf_region .= $_SESSION['scriptcase']['reg_conf']['date_sep'];
          }
          $this->SC_date_conf_region .= substr($orig_form_dt, $i, 1);
      }
      $this->Xls_tp = ".xlsx";
      if (isset($_REQUEST['nmgp_tp_xls']) && !empty($_REQUEST['nmgp_tp_xls']))
      {
          $this->Xls_tp = "." . $_REQUEST['nmgp_tp_xls'];
      }
      $this->groupby_show = "S";
      if (isset($_REQUEST['nmgp_tot_xls']) && !empty($_REQUEST['nmgp_tot_xls']))
      {
          $this->groupby_show = $_REQUEST['nmgp_tot_xls'];
      }
      $this->Xls_col      = 0;
      $this->Tem_xls_res  = false;
      $this->Xls_password = "";
      $this->nm_data      = new nm_data("pt_br");
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
      { 
          $this->Tem_xls_res  = true;
          if (isset($_REQUEST['SC_module_export']) && $_REQUEST['SC_module_export'] != "")
          { 
              $this->Tem_xls_res = (strpos(" " . $_REQUEST['SC_module_export'], "resume") !== false || strpos(" " . $_REQUEST['SC_module_export'], "chart") !== false) ? true : false;
          } 
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['SC_Ind_Groupby'] == "sc_free_total")
          {
              $this->Tem_xls_res  = false;
          }
          if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['SC_Ind_Groupby'] == "sc_free_group_by" && empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['SC_Gb_Free_cmp']))
          {
              $this->Tem_xls_res  = false;
          }
          if ($this->Tem_xls_res)
          { 
              require_once($this->Ini->path_aplicacao . "grid_tblnaoconformidades_res_xls.class.php");
              $this->Res_xls = new grid_tblnaoconformidades_res_xls();
              $this->prep_modulos("Res_xls");
          } 
          $this->Arquivo    = "sc_xls";
          $this->Arquivo   .= "_" . date("YmdHis") . "_" . rand(0, 1000);
          $this->Arq_zip    = $this->Arquivo . "_grid_tblnaoconformidades.zip";
          $this->Arquivo   .= "_grid_tblnaoconformidades" . $this->Xls_tp;
          $this->Tit_doc    = "grid_tblnaoconformidades" . $this->Xls_tp;
          $this->Tit_zip    = "grid_tblnaoconformidades.zip";
          $this->Xls_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo;
          $this->Zip_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arq_zip;
          PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );;
          $this->Xls_dados = new PHPExcel();
          $this->Xls_dados->setActiveSheetIndex(0);
          $this->Nm_ActiveSheet = $this->Xls_dados->getActiveSheet();
          $this->Nm_ActiveSheet->setTitle($this->Ini->Nm_lang['lang_othr_grid_titl']);
          if ($_SESSION['scriptcase']['reg_conf']['css_dir'] == "RTL")
          {
              $this->Nm_ActiveSheet->setRightToLeft(true);
          }
      }
      require_once($this->Ini->path_aplicacao . "grid_tblnaoconformidades_total.class.php"); 
      $this->Tot = new grid_tblnaoconformidades_total($this->Ini->sc_page);
      $this->prep_modulos("Tot");
      $Gb_geral = "quebra_geral_" . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['SC_Ind_Groupby'];
      $this->Tot->$Gb_geral();
      $this->count_ger = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['tot_geral'][1];
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'] && !$this->Ini->sc_export_ajax) {
          require_once($this->Ini->path_lib_php . "/sc_progress_bar.php");
          $this->pb = new scProgressBar();
          $this->pb->setRoot($this->Ini->root);
          $this->pb->setDir($_SESSION['scriptcase']['grid_tblnaoconformidades']['glo_nm_path_imag_temp'] . "/");
          $this->pb->setProgressbarMd5($_GET['pbmd5']);
          $this->pb->initialize();
          $this->pb->setReturnUrl("./");
          $this->pb->setReturnOption($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_return']);
          if ($this->Tem_xls_res) {
              $PB_plus = intval ($this->count_ger * 0.04);
              $PB_plus = ($PB_plus < 2) ? 2 : $PB_plus;
          }
          else {
              $PB_plus = intval ($this->count_ger * 0.02);
              $PB_plus = ($PB_plus < 1) ? 1 : $PB_plus;
          }
          $PB_tot = $this->count_ger + $PB_plus;
          $this->PB_dif = $PB_tot - $this->count_ger;
          $this->pb->setTotalSteps($PB_tot );
      }
   }
   //---- 
   function prep_modulos($modulo)
   {
      $this->$modulo->Ini    = $this->Ini;
      $this->$modulo->Db     = $this->Db;
      $this->$modulo->Erro   = $this->Erro;
      $this->$modulo->Lookup = $this->Lookup;
   }


   //----- 
   function grava_arquivo()
   {
      global $nm_nada, $nm_lang;

      $GLOBALS["script_case_init"] = $this->Ini->sc_page;
      $pos      = strrpos($this->Ini->link_grid_tblncveiculos_cons_emb, '/');
      $link_xls = substr($this->Ini->link_grid_tblncveiculos_cons_emb, 0, $pos) . "/grid_tblncveiculos_xls.class.php";
      if (!is_file($this->Ini->link_grid_tblncveiculos_cons_emb) || !is_file($link_xls))
      {
          $this->NM_cmp_hidden['veiculos'] = "off";
      }
      else
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida'] = true;
          $_SESSION['scriptcase']['grid_tblncveiculos']['protect_modal'] = $this->Ini->sc_page;
          include_once ($this->Ini->link_grid_tblncveiculos_cons_emb);
          $this->grid_tblncveiculos = new grid_tblncveiculos_apl ;
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida'] = false;
          unset($_SESSION['scriptcase']['grid_tblncveiculos']['protect_modal']);
      }
      $pos      = strrpos($this->Ini->link_grid_tblncveiculos_cons_emb, '/');
      $link_xml = substr($this->Ini->link_grid_tblncveiculos_cons_emb, 0, $pos) . "/grid_tblncveiculos_xml.class.php";
      if (!is_file($this->Ini->link_grid_tblncveiculos_cons_emb) || !is_file($link_xml))
      {
          $this->NM_cmp_hidden['veiculos'] = "off";
      }
      else
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida'] = true;
          $_SESSION['scriptcase']['grid_tblncveiculos']['protect_modal'] = $this->Ini->sc_page;
          include_once ($this->Ini->link_grid_tblncveiculos_cons_emb);
          $this->grid_tblncveiculos = new grid_tblncveiculos_apl ;
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida'] = false;
          unset($_SESSION['scriptcase']['grid_tblncveiculos']['protect_modal']);
      }
      $pos      = strrpos($this->Ini->link_grid_tblncpecas_cons_emb, '/');
      $link_xls = substr($this->Ini->link_grid_tblncpecas_cons_emb, 0, $pos) . "/grid_tblncpecas_xls.class.php";
      if (!is_file($this->Ini->link_grid_tblncpecas_cons_emb) || !is_file($link_xls))
      {
          $this->NM_cmp_hidden['pecas'] = "off";
      }
      else
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida'] = true;
          $_SESSION['scriptcase']['grid_tblncpecas']['protect_modal'] = $this->Ini->sc_page;
          include_once ($this->Ini->link_grid_tblncpecas_cons_emb);
          $this->grid_tblncpecas = new grid_tblncpecas_apl ;
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida'] = false;
          unset($_SESSION['scriptcase']['grid_tblncpecas']['protect_modal']);
      }
      $pos      = strrpos($this->Ini->link_grid_tblncpecas_cons_emb, '/');
      $link_xml = substr($this->Ini->link_grid_tblncpecas_cons_emb, 0, $pos) . "/grid_tblncpecas_xml.class.php";
      if (!is_file($this->Ini->link_grid_tblncpecas_cons_emb) || !is_file($link_xml))
      {
          $this->NM_cmp_hidden['pecas'] = "off";
      }
      else
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida'] = true;
          $_SESSION['scriptcase']['grid_tblncpecas']['protect_modal'] = $this->Ini->sc_page;
          include_once ($this->Ini->link_grid_tblncpecas_cons_emb);
          $this->grid_tblncpecas = new grid_tblncpecas_apl ;
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida'] = false;
          unset($_SESSION['scriptcase']['grid_tblncpecas']['protect_modal']);
      }
      $_SESSION['scriptcase']['sc_sql_ult_conexao'] = ''; 
      $this->sc_proc_grid = false; 
      $nm_raiz_img  = ""; 
      $this->New_label['intnaoconformidadeid'] = "" . $this->Ini->Nm_lang['lang_tblnaoconformidades_fld_intnaoconformidadeid'] . "";
      $this->New_label['dtaidentificacao'] = "" . $this->Ini->Nm_lang['lang_tblnaoconformidades_fld_dtaidentificacao'] . "";
      $this->New_label['strnaoconformidade'] = "" . $this->Ini->Nm_lang['lang_tblnaoconformidades_fld_strnaoconformidade'] . "";
      $this->New_label['strdescricao'] = "" . $this->Ini->Nm_lang['lang_tblnaoconformidades_fld_strdescricao'] . "";
      $this->New_label['intstatusncid'] = "" . $this->Ini->Nm_lang['lang_tblnaoconformidades_fld_intstatusncid'] . "";
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_name']))
      {
          $this->Arquivo = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_name'];
          $this->Arq_zip = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_name'];
          $this->Tit_doc = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_name'];
          $Pos = strrpos($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_name'], ".");
          if ($Pos !== false) {
              $this->Arq_zip = substr($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_name'], 0, $Pos);
          }
          $this->Arq_zip .= ".zip";
          $this->Tit_zip  = $this->Arq_zip;
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_name']);
          $this->Xls_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arquivo;
          $this->Zip_f = $this->Ini->root . $this->Ini->path_imag_temp . "/" . $this->Arq_zip;
      }
      if (isset($_SESSION['scriptcase']['sc_apl_conf']['grid_tblnaoconformidades']['field_display']) && !empty($_SESSION['scriptcase']['sc_apl_conf']['grid_tblnaoconformidades']['field_display']))
      {
          foreach ($_SESSION['scriptcase']['sc_apl_conf']['grid_tblnaoconformidades']['field_display'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['usr_cmp_sel']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['usr_cmp_sel']))
      {
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['usr_cmp_sel'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['php_cmp_sel']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['php_cmp_sel']))
      {
          foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['php_cmp_sel'] as $NM_cada_field => $NM_cada_opc)
          {
              $this->NM_cmp_hidden[$NM_cada_field] = $NM_cada_opc;
          }
      }
      foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['field_order'] as $Cada_cmp)
      {
          if (!isset($this->NM_cmp_hidden[$Cada_cmp]) || $this->NM_cmp_hidden[$Cada_cmp] != "off")
          {
              $this->Xls_tot_col++;
          }
      }
      $this->sc_where_orig   = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['where_orig'];
      $this->sc_where_atual  = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['where_pesq'];
      $this->sc_where_filtro = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['where_pesq_filtro'];
      $this->arr_export = array('label' => array(), 'lines' => array());
      $this->arr_span   = array();

      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida_label']) && $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida_label'])
      { 
          $this->count_span = 0;
          $this->Xls_row++;
          $this->proc_label();
          $_SESSION['scriptcase']['export_return'] = $this->arr_export;
          return;
      } 
      if (isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['campos_busca']) && !empty($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['campos_busca']))
      { 
          $Busca_temp = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['campos_busca'];
          if ($_SESSION['scriptcase']['charset'] != "UTF-8")
          {
              $Busca_temp = NM_conv_charset($Busca_temp, $_SESSION['scriptcase']['charset'], "UTF-8");
          }
          $this->strdescricao = $Busca_temp['strdescricao']; 
          $tmp_pos = strpos($this->strdescricao, "##@@");
          if ($tmp_pos !== false && !is_array($this->strdescricao))
          {
              $this->strdescricao = substr($this->strdescricao, 0, $tmp_pos);
          }
          $this->intnaoconformidadeid = $Busca_temp['intnaoconformidadeid']; 
          $tmp_pos = strpos($this->intnaoconformidadeid, "##@@");
          if ($tmp_pos !== false && !is_array($this->intnaoconformidadeid))
          {
              $this->intnaoconformidadeid = substr($this->intnaoconformidadeid, 0, $tmp_pos);
          }
          $this->dtaidentificacao = $Busca_temp['dtaidentificacao']; 
          $tmp_pos = strpos($this->dtaidentificacao, "##@@");
          if ($tmp_pos !== false && !is_array($this->dtaidentificacao))
          {
              $this->dtaidentificacao = substr($this->dtaidentificacao, 0, $tmp_pos);
          }
          $this->dtaidentificacao_2 = $Busca_temp['dtaidentificacao_input_2']; 
          $this->strnaoconformidade = $Busca_temp['strnaoconformidade']; 
          $tmp_pos = strpos($this->strnaoconformidade, "##@@");
          if ($tmp_pos !== false && !is_array($this->strnaoconformidade))
          {
              $this->strnaoconformidade = substr($this->strnaoconformidade, 0, $tmp_pos);
          }
      } 
      $this->nm_field_dinamico = array();
      $this->nm_order_dinamico = array();
      $nmgp_select_count = "SELECT count(*) AS countTest from " . $this->Ini->nm_tabela; 
      if (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_sybase))
      { 
          $nmgp_select = "SELECT intnaoconformidadeid, str_replace (convert(char(10),dtaidentificacao,102), '.', '-') + ' ' + convert(char(8),dtaidentificacao,20), strnaoconformidade, strdescricao, intstatusncid from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_mysql))
      { 
          $nmgp_select = "SELECT intnaoconformidadeid, dtaidentificacao, strnaoconformidade, strdescricao, intstatusncid from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_mssql))
      { 
       $nmgp_select = "SELECT intnaoconformidadeid, convert(char(23),dtaidentificacao,121), strnaoconformidade, strdescricao, intstatusncid from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_oracle))
      { 
          $nmgp_select = "SELECT intnaoconformidadeid, dtaidentificacao, strnaoconformidade, strdescricao, intstatusncid from " . $this->Ini->nm_tabela; 
      } 
      elseif (in_array(strtolower($this->Ini->nm_tpbanco), $this->Ini->nm_bases_informix))
      { 
          $nmgp_select = "SELECT intnaoconformidadeid, EXTEND(dtaidentificacao, YEAR TO FRACTION), strnaoconformidade, strdescricao, intstatusncid from " . $this->Ini->nm_tabela; 
      } 
      else 
      { 
          $nmgp_select = "SELECT intnaoconformidadeid, dtaidentificacao, strnaoconformidade, strdescricao, intstatusncid from " . $this->Ini->nm_tabela; 
      } 
      $nmgp_select .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['where_pesq'];
      $nmgp_select_count .= " " . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['where_pesq'];
      $nmgp_order_by = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['order_grid'];
      $nmgp_select .= $nmgp_order_by; 
      $_SESSION['scriptcase']['sc_sql_ult_comando'] = $nmgp_select;
      $rs = $this->Db->Execute($nmgp_select);
      if ($rs === false && !$rs->EOF && $GLOBALS["NM_ERRO_IBASE"] != 1)
      {
         $this->Erro->mensagem(__FILE__, __LINE__, "banco", $this->Ini->Nm_lang['lang_errm_dber'], $this->Db->ErrorMsg());
         exit;
      }
      $this->SC_seq_register = 0;
      $prim_reg = true;
      $prim_gb  = true;
      $nm_houve_quebra = "N";
      $this->New_Xls_row = $this->Xls_row;
      $PB_tot = (isset($this->count_ger) && $this->count_ger > 0) ? "/" . $this->count_ger : "";
      while (!$rs->EOF)
      {
         $this->SC_seq_register++;
         $prim_reg = false;
         if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'] && !$this->Ini->sc_export_ajax) {
             $Mens_bar = $this->Ini->Nm_lang['lang_othr_prcs'];
             if ($_SESSION['scriptcase']['charset'] != "UTF-8") {
                 $Mens_bar = sc_convert_encoding($Mens_bar, "UTF-8", $_SESSION['scriptcase']['charset']);
             }
             $this->pb->setProgressbarMessage($Mens_bar . ": " . $this->SC_seq_register . $PB_tot);
             $this->pb->addSteps(1);
         }
         $this->Xls_col = 0;
         if ($this->New_Xls_row > $this->Xls_row) {
             $this->Xls_row = $this->New_Xls_row;
         }
         $this->Xls_row++;
         $this->intnaoconformidadeid = $rs->fields[0] ;  
         $this->intnaoconformidadeid = (string)$this->intnaoconformidadeid;
         $this->dtaidentificacao = $rs->fields[1] ;  
         $this->strnaoconformidade = $rs->fields[2] ;  
         $this->strdescricao = $rs->fields[3] ;  
         $this->intstatusncid = $rs->fields[4] ;  
         $this->intstatusncid = (string)$this->intstatusncid;
         $this->Orig_intnaoconformidadeid = $this->intnaoconformidadeid;
         $this->Orig_dtaidentificacao = $this->dtaidentificacao;
         $this->Orig_strnaoconformidade = $this->strnaoconformidade;
         $this->Orig_strdescricao = $this->strdescricao;
         $this->Orig_intstatusncid = $this->intstatusncid;
     if ($this->groupby_show == "S") {
         if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
         { 
             if ($prim_gb) {
                 $this->count_span = 0;
                 $this->proc_label();
             }
             if ($prim_gb || $nm_houve_quebra == "S") {
                 $this->xls_sub_cons_copy_label($this->Xls_row);
                 $this->Xls_row++;
             }
         }
         elseif ($prim_gb || $nm_houve_quebra == "S")
         {
             $this->count_span = 0;
             $this->proc_label();
         }
     }
     else {
         if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
         { 
             if ($prim_gb)
             {
                 $this->count_span = 0;
                 $this->proc_label();
                 $this->xls_sub_cons_copy_label($this->Xls_row);
                 $this->Xls_row++;
             }
         }
         elseif ($prim_gb)
         {
             $this->count_span = 0;
             $this->proc_label();
         }
     }
     $prim_gb = false;
     $nm_houve_quebra = "N";
         //----- lookup - intstatusncid
         $this->look_intstatusncid = $this->intstatusncid; 
         $this->Lookup->lookup_intstatusncid($this->look_intstatusncid, $this->intstatusncid) ; 
         $this->look_intstatusncid = ($this->look_intstatusncid == "&nbsp;") ? "" : $this->look_intstatusncid; 
         $this->sc_proc_grid = true; 
         foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['field_order'] as $Cada_col)
         { 
            if (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off")
            { 
                if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
                { 
                    $NM_func_exp = "NM_sub_cons_" . $Cada_col;
                    $this->$NM_func_exp();
                } 
                else 
                { 
                    $NM_func_exp = "NM_export_" . $Cada_col;
                    $this->$NM_func_exp();
                } 
            } 
         } 
         foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['field_order'] as $Cada_col)
         { 
            if ($Cada_col == "veiculos" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
            { 
                $xls_row_base = $this->Xls_row;
                if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
                { 
                    foreach ($this->Rows_sub_veiculos as $line => $cols)
                    {
                        $this->Xls_row++;
                        $this->arr_export['lines'][$this->Xls_row] = $cols;
                    }
                    if (!empty($this->Rows_sub_veiculos['lines']))
                    {
                        foreach ($cols as $col => $dados)
                        {
                            $cols[$col]['row_span_f'] = $xls_row_base - $this->Xls_row;
                             break;
                        }
                        $this->arr_export['lines'][$this->Xls_row] = $cols;
                    }
                }
                else 
                { 
                    foreach ($this->Rows_sub_veiculos as $lines)
                    {
                        $this->Xls_col = 0;
                        $this->xls_sub_cons_lines($lines);
                        $this->Xls_row++;
                    }
                    $this->Xls_row = $xls_row_base;
                }
            } 
            if ($Cada_col == "pecas" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
            { 
                $xls_row_base = $this->Xls_row;
                if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
                { 
                    foreach ($this->Rows_sub_pecas as $line => $cols)
                    {
                        $this->Xls_row++;
                        $this->arr_export['lines'][$this->Xls_row] = $cols;
                    }
                    if (!empty($this->Rows_sub_pecas['lines']))
                    {
                        foreach ($cols as $col => $dados)
                        {
                            $cols[$col]['row_span_f'] = $xls_row_base - $this->Xls_row;
                             break;
                        }
                        $this->arr_export['lines'][$this->Xls_row] = $cols;
                    }
                }
                else 
                { 
                    foreach ($this->Rows_sub_pecas as $lines)
                    {
                        $this->Xls_col = 0;
                        $this->xls_sub_cons_lines($lines);
                        $this->Xls_row++;
                    }
                    $this->Xls_row = $xls_row_base;
                }
            } 
         } 
         if (isset($this->NM_Row_din) && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
         { 
             foreach ($this->NM_Row_din as $row => $height) 
             { 
                 $this->Nm_ActiveSheet->getRowDimension($row)->setRowHeight($height);
             } 
         } 
         $rs->MoveNext();
      }
      if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'] && $prim_reg)
      { 
          $this->proc_label();
          $this->xls_sub_cons_copy_label($this->Xls_row);
          $nm_grid_sem_reg = $this->Ini->Nm_lang['lang_errm_empt']; 
          if (!NM_is_utf8($nm_grid_sem_reg ))
          {
              $nm_grid_sem_reg  = sc_convert_encoding($nm_grid_sem_reg , "UTF-8", $_SESSION['scriptcase']['charset']);
          }
          $this->Xls_row++;
          $this->arr_export['lines'][$this->Xls_row][1]['data']   = $nm_grid_sem_reg;
          $this->arr_export['lines'][$this->Xls_row][1]['align']  = "right";
          $this->arr_export['lines'][$this->Xls_row][1]['type']   = "char";
          $this->arr_export['lines'][$this->Xls_row][1]['format'] = "";
      }
      if (isset($this->NM_Col_din) && !$_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
      { 
          foreach ($this->NM_Col_din as $col => $width)
          { 
              $this->Nm_ActiveSheet->getColumnDimension($col)->setWidth($width / 5);
          } 
      } 
      if ($this->groupby_show == "S") {
          if ($this->New_Xls_row > $this->Xls_row) {
              $this->Xls_row = $this->New_Xls_row;
          }
      }
      if (!$_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
      { 
          if ($this->Tem_xls_res)
          { 
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_res_grid'] = true;
              if (!$this->Ini->sc_export_ajax) {
                  $this->PB_dif = intval ($this->PB_dif / 2);
                  $Mens_bar  = $this->Ini->Nm_lang['lang_othr_prcs'];
                  $Mens_smry = $this->Ini->Nm_lang['lang_othr_smry_titl'];
                  if ($_SESSION['scriptcase']['charset'] != "UTF-8") {
                      $Mens_bar  = sc_convert_encoding($Mens_bar, "UTF-8", $_SESSION['scriptcase']['charset']);
                      $Mens_smry = sc_convert_encoding($Mens_smry, "UTF-8", $_SESSION['scriptcase']['charset']);
                  }
                  $this->pb->setProgressbarMessage($Mens_bar . ": " . $Mens_smry);
                  $this->pb->addSteps($this->PB_dif);
              }
              $this->Res_xls->monta_xls();
              $Xls_res = PHPExcel_IOFactory::load($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_res_sheet']);
              foreach($Xls_res->getAllSheets() as $sheet)
              {
                  $this->Xls_dados->addExternalSheet($sheet);
              }
              unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_res_grid']);
              unlink($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_res_sheet']);
          } 
          if (!$this->Ini->sc_export_ajax) {
              $Mens_bar = $this->Ini->Nm_lang['lang_btns_export_finished'];
              if ($_SESSION['scriptcase']['charset'] != "UTF-8") {
                  $Mens_bar = sc_convert_encoding($Mens_bar, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              $this->pb->setProgressbarMessage($Mens_bar);
              $this->pb->addSteps($this->PB_dif);
          }
          if ($this->Xls_tp == ".xlsx")
          { 
              $objWriter = new PHPExcel_Writer_Excel2007($this->Xls_dados);
          } 
          else 
          { 
              $objWriter = new PHPExcel_Writer_Excel5($this->Xls_dados);
          } 
          $objWriter->save($this->Xls_f);
          if ($this->Xls_password != "")
          { 
              $str_zip   = "";
              $Zip_f     = (FALSE !== strpos($this->Zip_f, ' ')) ? " \"" . $this->Zip_f . "\"" :  $this->Zip_f;
              $Arq_input = (FALSE !== strpos($this->Xls_f, ' ')) ? " \"" . $this->Xls_f . "\"" :  $this->Xls_f;
              if (is_file($Zip_f)) {
                  unlink($Zip_f);
              }
              if (FALSE !== strpos(strtolower(php_uname()), 'windows')) 
              {
                  chdir($this->Ini->path_third . "/zip/windows");
                  $str_zip = "zip.exe -P -j " . $this->Xls_password . " " . $Zip_f . " " . $Arq_input;
              }
              elseif (FALSE !== strpos(strtolower(php_uname()), 'linux')) 
              {
                  if (FALSE !== strpos(strtolower(php_uname()), 'i686')) 
                  {
                      chdir($this->Ini->path_third . "/zip/linux-i386/bin");
                  }
                  else
                  {
                     chdir($this->Ini->path_third . "/zip/linux-amd64/bin");
                  }
                  $str_zip = "./7za -p" . $this->Xls_password . " a " . $Zip_f . " " . $Arq_input;
              }
              elseif (FALSE !== strpos(strtolower(php_uname()), 'darwin'))
              {
                  chdir($this->Ini->path_third . "/zip/mac/bin");
                  $str_zip = "./7za -p" . $this->Xls_password . " a " . $Zip_f . " " . $Arq_input;
              }
              if (!empty($str_zip)) {
                  exec($str_zip);
              }
              // ----- ZIP log
              $fp = @fopen(str_replace(".zip", "", $Zip_f) . '.log', 'w');
              if ($fp)
              {
                  @fwrite($fp, $str_zip . "\r\n\r\n");
                  @fclose($fp);
              }
              unlink($Arq_input);
              $this->Arquivo = $this->Arq_zip;
              $this->Xls_f   = $this->Zip_f;
              $this->Tit_doc = $this->Tit_zip;
          } 
      } 
      else 
      { 
          $_SESSION['scriptcase']['export_return'] = $this->arr_export;
      } 
      if(isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['export_sel_columns']['field_order']))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['field_order'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['export_sel_columns']['field_order'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['export_sel_columns']['field_order']);
      }
      if(isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['export_sel_columns']['usr_cmp_sel']))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['usr_cmp_sel'] = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['export_sel_columns']['usr_cmp_sel'];
          unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['export_sel_columns']['usr_cmp_sel']);
      }
      $rs->Close();
      if (method_exists($this->grid_tblncveiculos, "close_emb")) 
      {
          $this->grid_tblncveiculos->close_emb();
      }
      if (method_exists($this->grid_tblncpecas, "close_emb")) 
      {
          $this->grid_tblncpecas->close_emb();
      }
   }
   function proc_label()
   { 
      foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['field_order'] as $Cada_col)
      { 
          $SC_Label = (isset($this->New_label['intnaoconformidadeid'])) ? $this->New_label['intnaoconformidadeid'] : ""; 
          if ($Cada_col == "intnaoconformidadeid" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $this->count_span++;
              $current_cell_ref = $this->calc_cell($this->Xls_col);
              if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = sc_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
              { 
                  $this->arr_export['label'][$this->Xls_col]['data']     = $SC_Label;
                  $this->arr_export['label'][$this->Xls_col]['align']    = "right";
                  $this->arr_export['label'][$this->Xls_col]['autosize'] = "s";
                  $this->arr_export['label'][$this->Xls_col]['bold']     = "s";
              }
              else
              { 
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                  $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $SC_Label);
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getFont()->setBold(true);
                  $this->Nm_ActiveSheet->getColumnDimension($current_cell_ref)->setAutoSize(true);
              }
              $this->Xls_col++;
          }
          $SC_Label = (isset($this->New_label['dtaidentificacao'])) ? $this->New_label['dtaidentificacao'] : ""; 
          if ($Cada_col == "dtaidentificacao" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $this->count_span++;
              $current_cell_ref = $this->calc_cell($this->Xls_col);
              if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = sc_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
              { 
                  $this->arr_export['label'][$this->Xls_col]['data']     = $SC_Label;
                  $this->arr_export['label'][$this->Xls_col]['align']    = "left";
                  $this->arr_export['label'][$this->Xls_col]['autosize'] = "s";
                  $this->arr_export['label'][$this->Xls_col]['bold']     = "s";
              }
              else
              { 
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                  $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $SC_Label);
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getFont()->setBold(true);
                  $this->Nm_ActiveSheet->getColumnDimension($current_cell_ref)->setAutoSize(true);
              }
              $this->Xls_col++;
          }
          $SC_Label = (isset($this->New_label['strnaoconformidade'])) ? $this->New_label['strnaoconformidade'] : ""; 
          if ($Cada_col == "strnaoconformidade" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $this->count_span++;
              $current_cell_ref = $this->calc_cell($this->Xls_col);
              if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = sc_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
              { 
                  $this->arr_export['label'][$this->Xls_col]['data']     = $SC_Label;
                  $this->arr_export['label'][$this->Xls_col]['align']    = "left";
                  $this->arr_export['label'][$this->Xls_col]['autosize'] = "s";
                  $this->arr_export['label'][$this->Xls_col]['bold']     = "s";
              }
              else
              { 
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                  $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $SC_Label);
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getFont()->setBold(true);
                  $this->Nm_ActiveSheet->getColumnDimension($current_cell_ref)->setAutoSize(true);
              }
              $this->Xls_col++;
          }
          $SC_Label = (isset($this->New_label['strdescricao'])) ? $this->New_label['strdescricao'] : ""; 
          if ($Cada_col == "strdescricao" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $this->count_span++;
              $current_cell_ref = $this->calc_cell($this->Xls_col);
              if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = sc_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
              { 
                  $this->arr_export['label'][$this->Xls_col]['data']     = $SC_Label;
                  $this->arr_export['label'][$this->Xls_col]['align']    = "left";
                  $this->arr_export['label'][$this->Xls_col]['autosize'] = "s";
                  $this->arr_export['label'][$this->Xls_col]['bold']     = "s";
              }
              else
              { 
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                  $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $SC_Label);
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getFont()->setBold(true);
                  $this->Nm_ActiveSheet->getColumnDimension($current_cell_ref)->setAutoSize(true);
              }
              $this->Xls_col++;
          }
          $SC_Label = (isset($this->New_label['intstatusncid'])) ? $this->New_label['intstatusncid'] : ""; 
          if ($Cada_col == "intstatusncid" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $this->count_span++;
              $current_cell_ref = $this->calc_cell($this->Xls_col);
              if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = sc_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
              { 
                  $this->arr_export['label'][$this->Xls_col]['data']     = $SC_Label;
                  $this->arr_export['label'][$this->Xls_col]['align']    = "right";
                  $this->arr_export['label'][$this->Xls_col]['autosize'] = "s";
                  $this->arr_export['label'][$this->Xls_col]['bold']     = "s";
              }
              else
              { 
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                  $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $SC_Label);
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getFont()->setBold(true);
                  $this->Nm_ActiveSheet->getColumnDimension($current_cell_ref)->setAutoSize(true);
              }
              $this->Xls_col++;
          }
          $SC_Label = (isset($this->New_label['veiculos'])) ? $this->New_label['veiculos'] : "Veículos"; 
          if ($Cada_col == "veiculos" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $this->arr_span['veiculos'] = $this->count_span;
              $this->Emb_label_cols_veiculos = 0;
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida'] = true;
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida_label'] = true;
              $GLOBALS["script_case_init"] = $this->Ini->sc_page;
              $GLOBALS["nmgp_parms"] = "nmgp_opcao?#?xls?@?";
              if (method_exists($this->grid_tblncveiculos, "controle"))
              {
                  $this->grid_tblncveiculos->controle();
                  if (isset($_SESSION['scriptcase']['export_return']))
                  {
                     foreach ($_SESSION['scriptcase']['export_return']['label'] as $col => $dados)
                     {
                         if (isset($dados['col_span_i'])) {
                             $this->Emb_label_cols_veiculos += $dados['col_span_i'];
                         }
                         elseif (isset($dados['col_span_f'])) {
                             $this->Emb_label_cols_veiculos += $dados['col_span_f'];
                         }
                         else {
                             $this->Emb_label_cols_veiculos++;
                         }
                     }
                  }
                  $this->count_span += $this->Emb_label_cols_veiculos;
              }
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida'] = false;
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida_label'] = false;
              $current_cell_ref = $this->calc_cell($this->Xls_col);
              if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = sc_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
              { 
                  $this->arr_export['label'][$this->Xls_col]['data']     = $SC_Label;
                  $this->arr_export['label'][$this->Xls_col]['align']    = "left";
                  $this->arr_export['label'][$this->Xls_col]['autosize'] = "s";
                  $this->arr_export['label'][$this->Xls_col]['bold']     = "s";
              }
              else
              { 
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                  $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $SC_Label);
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getFont()->setBold(true);
                  $this->Nm_ActiveSheet->getColumnDimension($current_cell_ref)->setAutoSize(true);
              }
              if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
              { 
                  $this->arr_export['label'][$this->Xls_col]['col_span_f'] = $this->Emb_label_cols_veiculos;
                  $this->Xls_col++;
              }
              else
              { 
                  $this->Xls_col += $this->Emb_label_cols_veiculos;
              } 
          }
          $SC_Label = (isset($this->New_label['pecas'])) ? $this->New_label['pecas'] : "Peças"; 
          if ($Cada_col == "pecas" && (!isset($this->NM_cmp_hidden[$Cada_col]) || $this->NM_cmp_hidden[$Cada_col] != "off"))
          {
              $this->arr_span['pecas'] = $this->count_span;
              $this->Emb_label_cols_pecas = 0;
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida'] = true;
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida_label'] = true;
              $GLOBALS["script_case_init"] = $this->Ini->sc_page;
              $GLOBALS["nmgp_parms"] = "nmgp_opcao?#?xls?@?";
              if (method_exists($this->grid_tblncpecas, "controle"))
              {
                  $this->grid_tblncpecas->controle();
                  if (isset($_SESSION['scriptcase']['export_return']))
                  {
                     foreach ($_SESSION['scriptcase']['export_return']['label'] as $col => $dados)
                     {
                         if (isset($dados['col_span_i'])) {
                             $this->Emb_label_cols_pecas += $dados['col_span_i'];
                         }
                         elseif (isset($dados['col_span_f'])) {
                             $this->Emb_label_cols_pecas += $dados['col_span_f'];
                         }
                         else {
                             $this->Emb_label_cols_pecas++;
                         }
                     }
                  }
                  $this->count_span += $this->Emb_label_cols_pecas;
              }
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida'] = false;
              $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida_label'] = false;
              $current_cell_ref = $this->calc_cell($this->Xls_col);
              if (!NM_is_utf8($SC_Label))
              {
                  $SC_Label = sc_convert_encoding($SC_Label, "UTF-8", $_SESSION['scriptcase']['charset']);
              }
              if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
              { 
                  $this->arr_export['label'][$this->Xls_col]['data']     = $SC_Label;
                  $this->arr_export['label'][$this->Xls_col]['align']    = "left";
                  $this->arr_export['label'][$this->Xls_col]['autosize'] = "s";
                  $this->arr_export['label'][$this->Xls_col]['bold']     = "s";
              }
              else
              { 
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                  $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $SC_Label);
                  $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getFont()->setBold(true);
                  $this->Nm_ActiveSheet->getColumnDimension($current_cell_ref)->setAutoSize(true);
              }
              if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida'])
              { 
                  $this->arr_export['label'][$this->Xls_col]['col_span_f'] = $this->Emb_label_cols_pecas;
                  $this->Xls_col++;
              }
              else
              { 
                  $this->Xls_col += $this->Emb_label_cols_pecas;
              } 
          }
      } 
      $this->Xls_col = 0;
      $this->Xls_row++;
   } 
   //----- intnaoconformidadeid
   function NM_export_intnaoconformidadeid()
   {
         $current_cell_ref = $this->calc_cell($this->Xls_col);
         if (!NM_is_utf8($this->intnaoconformidadeid))
         {
             $this->intnaoconformidadeid = sc_convert_encoding($this->intnaoconformidadeid, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->intnaoconformidadeid))
         {
             $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getNumberFormat()->setFormatCode('###0');
         }
         $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $this->intnaoconformidadeid);
         $this->Xls_col++;
   }
   //----- dtaidentificacao
   function NM_export_dtaidentificacao()
   {
         $current_cell_ref = $this->calc_cell($this->Xls_col);
      if (!empty($this->dtaidentificacao))
      {
         if (substr($this->dtaidentificacao, 10, 1) == "-") 
         { 
             $this->dtaidentificacao = substr($this->dtaidentificacao, 0, 10) . " " . substr($this->dtaidentificacao, 11);
         } 
         if (substr($this->dtaidentificacao, 13, 1) == ".") 
         { 
            $this->dtaidentificacao = substr($this->dtaidentificacao, 0, 13) . ":" . substr($this->dtaidentificacao, 14, 2) . ":" . substr($this->dtaidentificacao, 17);
         } 
         $conteudo_x =  $this->dtaidentificacao;
         nm_conv_limpa_dado($conteudo_x, "YYYY-MM-DD HH:II:SS");
         if (is_numeric($conteudo_x) && strlen($conteudo_x) > 0) 
         { 
             $this->nm_data->SetaData($this->dtaidentificacao, "YYYY-MM-DD HH:II:SS  ");
             $this->dtaidentificacao = $this->nm_data->FormataSaida($this->nm_data->FormatRegion("DH", "ddmmaaaa;hhiiss"));
         } 
      }
         if (!NM_is_utf8($this->dtaidentificacao))
         {
             $this->dtaidentificacao = sc_convert_encoding($this->dtaidentificacao, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->Nm_ActiveSheet->setCellValueExplicit($current_cell_ref . $this->Xls_row, $this->dtaidentificacao, PHPExcel_Cell_DataType::TYPE_STRING);
         $this->Xls_col++;
   }
   //----- strnaoconformidade
   function NM_export_strnaoconformidade()
   {
         $current_cell_ref = $this->calc_cell($this->Xls_col);
         $this->strnaoconformidade = html_entity_decode($this->strnaoconformidade, ENT_COMPAT, $_SESSION['scriptcase']['charset']);
         $this->strnaoconformidade = strip_tags($this->strnaoconformidade);
         if (!NM_is_utf8($this->strnaoconformidade))
         {
             $this->strnaoconformidade = sc_convert_encoding($this->strnaoconformidade, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->Nm_ActiveSheet->setCellValueExplicit($current_cell_ref . $this->Xls_row, $this->strnaoconformidade, PHPExcel_Cell_DataType::TYPE_STRING);
         $this->Xls_col++;
   }
   //----- strdescricao
   function NM_export_strdescricao()
   {
         $current_cell_ref = $this->calc_cell($this->Xls_col);
         $this->strdescricao = html_entity_decode($this->strdescricao, ENT_COMPAT, $_SESSION['scriptcase']['charset']);
         $this->strdescricao = strip_tags($this->strdescricao);
         if (!NM_is_utf8($this->strdescricao))
         {
             $this->strdescricao = sc_convert_encoding($this->strdescricao, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
         $this->Nm_ActiveSheet->setCellValueExplicit($current_cell_ref . $this->Xls_row, $this->strdescricao, PHPExcel_Cell_DataType::TYPE_STRING);
         $this->Xls_col++;
   }
   //----- intstatusncid
   function NM_export_intstatusncid()
   {
         $current_cell_ref = $this->calc_cell($this->Xls_col);
         if (!NM_is_utf8($this->look_intstatusncid))
         {
             $this->look_intstatusncid = sc_convert_encoding($this->look_intstatusncid, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
         if (is_numeric($this->look_intstatusncid))
         {
             $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getNumberFormat()->setFormatCode('###0');
         }
         $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $this->look_intstatusncid);
         $this->Xls_col++;
   }
   //----- veiculos
   function NM_export_veiculos()
   {
         $GLOBALS["script_case_init"] = $this->Ini->sc_page;
         $GLOBALS["nmgp_parms"] = "nmgp_opcao?#?xls?@?intnaoconformidadeid?#?" . str_replace("'", "@aspass@", $this->Orig_intnaoconformidadeid) . "?@?";
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida'] = true;
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['nolabel'] = true;
         if (method_exists($this->grid_tblncveiculos, "controle"))
         {
             $this->grid_tblncveiculos->controle();
             if (isset($_SESSION['scriptcase']['export_return']))
             {
                 $this->Rows_sub_veiculos = array();
                 if (isset($_SESSION['scriptcase']['export_return']['label']))
                 {
                     $_SESSION['scriptcase']['export_return']['label'][0]['col_span_i'] = $this->arr_span['veiculos'];
                     $this->Xls_col += $this->Emb_label_cols_veiculos;
                 }
                 if (isset($_SESSION['scriptcase']['export_return']['lines']))
                 {
                     foreach ($_SESSION['scriptcase']['export_return']['lines'] as $line => $cols)
                     {
                         $prim_col = true;
                         foreach ($cols as $icol => $dados)
                         {
                             $this->Rows_sub_veiculos[$line][$icol] = $dados;
                             if ($prim_col)
                             {
                                 if (isset($this->Rows_sub_veiculos[$line][$icol]['col_span_i']))
                                 {
                                    $this->Rows_sub_veiculos[$line][$icol]['col_span_i'] += $this->arr_span['veiculos'];
                                 }
                                 else
                                 {
                                    $this->Rows_sub_veiculos[$line][$icol]['col_span_i'] = $this->arr_span['veiculos'];
                                 }
                                 $prim_col = false;
                             }
                         }
                     }
                 }
             }
         }
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida'] = false;
   }
   //----- pecas
   function NM_export_pecas()
   {
         $GLOBALS["script_case_init"] = $this->Ini->sc_page;
         $GLOBALS["nmgp_parms"] = "nmgp_opcao?#?xls?@?intnaoconformidadeid?#?" . str_replace("'", "@aspass@", $this->Orig_intnaoconformidadeid) . "?@?";
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida'] = true;
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['nolabel'] = true;
         if (method_exists($this->grid_tblncpecas, "controle"))
         {
             $this->grid_tblncpecas->controle();
             if (isset($_SESSION['scriptcase']['export_return']))
             {
                 $this->Rows_sub_pecas = array();
                 if (isset($_SESSION['scriptcase']['export_return']['label']))
                 {
                     $_SESSION['scriptcase']['export_return']['label'][0]['col_span_i'] = $this->arr_span['pecas'];
                     $this->Xls_col += $this->Emb_label_cols_pecas;
                 }
                 if (isset($_SESSION['scriptcase']['export_return']['lines']))
                 {
                     foreach ($_SESSION['scriptcase']['export_return']['lines'] as $line => $cols)
                     {
                         $prim_col = true;
                         foreach ($cols as $icol => $dados)
                         {
                             $this->Rows_sub_pecas[$line][$icol] = $dados;
                             if ($prim_col)
                             {
                                 if (isset($this->Rows_sub_pecas[$line][$icol]['col_span_i']))
                                 {
                                    $this->Rows_sub_pecas[$line][$icol]['col_span_i'] += $this->arr_span['pecas'];
                                 }
                                 else
                                 {
                                    $this->Rows_sub_pecas[$line][$icol]['col_span_i'] = $this->arr_span['pecas'];
                                 }
                                 $prim_col = false;
                             }
                         }
                     }
                 }
             }
         }
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida'] = false;
   }
   //----- intnaoconformidadeid
   function NM_sub_cons_intnaoconformidadeid()
   {
         if (!NM_is_utf8($this->intnaoconformidadeid))
         {
             $this->intnaoconformidadeid = sc_convert_encoding($this->intnaoconformidadeid, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['data']   = $this->intnaoconformidadeid;
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['align']  = "right";
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['type']   = "num";
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['format'] = "###0";
         $this->Xls_col++;
   }
   //----- dtaidentificacao
   function NM_sub_cons_dtaidentificacao()
   {
      if (!empty($this->dtaidentificacao))
      {
         if (substr($this->dtaidentificacao, 10, 1) == "-") 
         { 
             $this->dtaidentificacao = substr($this->dtaidentificacao, 0, 10) . " " . substr($this->dtaidentificacao, 11);
         } 
         if (substr($this->dtaidentificacao, 13, 1) == ".") 
         { 
            $this->dtaidentificacao = substr($this->dtaidentificacao, 0, 13) . ":" . substr($this->dtaidentificacao, 14, 2) . ":" . substr($this->dtaidentificacao, 17);
         } 
         $conteudo_x =  $this->dtaidentificacao;
         nm_conv_limpa_dado($conteudo_x, "YYYY-MM-DD HH:II:SS");
         if (is_numeric($conteudo_x) && strlen($conteudo_x) > 0) 
         { 
             $this->nm_data->SetaData($this->dtaidentificacao, "YYYY-MM-DD HH:II:SS  ");
             $this->dtaidentificacao = $this->nm_data->FormataSaida($this->nm_data->FormatRegion("DH", "ddmmaaaa;hhiiss"));
         } 
      }
         if (!NM_is_utf8($this->dtaidentificacao))
         {
             $this->dtaidentificacao = sc_convert_encoding($this->dtaidentificacao, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['data']   = $this->dtaidentificacao;
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['align']  = "left";
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['type']   = "char";
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['format'] = "";
         $this->Xls_col++;
   }
   //----- strnaoconformidade
   function NM_sub_cons_strnaoconformidade()
   {
         $this->strnaoconformidade = html_entity_decode($this->strnaoconformidade, ENT_COMPAT, $_SESSION['scriptcase']['charset']);
         $this->strnaoconformidade = strip_tags($this->strnaoconformidade);
         if (!NM_is_utf8($this->strnaoconformidade))
         {
             $this->strnaoconformidade = sc_convert_encoding($this->strnaoconformidade, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['data']   = $this->strnaoconformidade;
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['align']  = "left";
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['type']   = "char";
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['format'] = "";
         $this->Xls_col++;
   }
   //----- strdescricao
   function NM_sub_cons_strdescricao()
   {
         $this->strdescricao = html_entity_decode($this->strdescricao, ENT_COMPAT, $_SESSION['scriptcase']['charset']);
         $this->strdescricao = strip_tags($this->strdescricao);
         if (!NM_is_utf8($this->strdescricao))
         {
             $this->strdescricao = sc_convert_encoding($this->strdescricao, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['data']   = $this->strdescricao;
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['align']  = "left";
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['type']   = "char";
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['format'] = "";
         $this->Xls_col++;
   }
   //----- intstatusncid
   function NM_sub_cons_intstatusncid()
   {
         if (!NM_is_utf8($this->look_intstatusncid))
         {
             $this->look_intstatusncid = sc_convert_encoding($this->look_intstatusncid, "UTF-8", $_SESSION['scriptcase']['charset']);
         }
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['data']   = $this->look_intstatusncid;
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['align']  = "right";
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['type']   = "num";
         $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['format'] = "###0";
         $this->Xls_col++;
   }
   //----- veiculos
   function NM_sub_cons_veiculos()
   {
         $this->Rows_sub_veiculos = array();
         $GLOBALS["script_case_init"] = $this->Ini->sc_page;
         $GLOBALS["nmgp_parms"] = "nmgp_opcao?#?xls?@?intnaoconformidadeid?#?" . str_replace("'", "@aspass@", $this->Orig_intnaoconformidadeid) . "?@?";
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida'] = true;
         if (method_exists($this->grid_tblncveiculos, "controle"))
         {
             $this->grid_tblncveiculos->controle();
             if (isset($_SESSION['scriptcase']['export_return']))
             {
                 $this->row_sub = 1;
                 if (isset($_SESSION['scriptcase']['export_return']['lines']))
                 {
                     foreach ($_SESSION['scriptcase']['export_return']['lines'] as $line => $cols)
                     {
                         foreach ($cols as $icol => $dados)
                         {
                             $this->arr_export['lines'][$this->Xls_row][$this->Xls_col] = $dados;
                             $this->Xls_col++;
                         }
                         unset ($_SESSION['scriptcase']['export_return']['lines'][$line]);
                         break;
                     }
                 }
                 $this->row_sub++;
                 if (isset($_SESSION['scriptcase']['export_return']['lines']))
                 {
                     $xls_col_base = $this->Xls_col;
                     foreach ($_SESSION['scriptcase']['export_return']['lines'] as $line => $cols)
                     {
                         $this->Xls_col = $xls_col_base;
                         $prim_col = true;
                         foreach ($cols as $icol => $dados)
                         {
                             $this->Rows_sub_veiculos[$this->row_sub][$icol] = $dados;
                             if ($prim_col && $this->row_sub > 1)
                             {
                                 if (isset($this->Rows_sub_veiculos[$this->row_sub][$icol]['col_span_i']))
                                 {
                                    $this->Rows_sub_veiculos[$this->row_sub][$icol]['col_span_i'] += $this->arr_span['veiculos'];
                                 }
                                 else
                                 {
                                    $this->Rows_sub_veiculos[$this->row_sub][$icol]['col_span_i'] = $this->arr_span['veiculos'];
                                 }
                                $prim_col = false;
                             }
                             $this->Xls_col++;
                         }
                         $this->row_sub++;
                     }
                 }
             }
             else
             {
                 $this->Xls_col++;
             }
         }
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncveiculos']['embutida'] = false;
         $this->Xls_col++;
   }
   //----- pecas
   function NM_sub_cons_pecas()
   {
         $this->Rows_sub_pecas = array();
         $GLOBALS["script_case_init"] = $this->Ini->sc_page;
         $GLOBALS["nmgp_parms"] = "nmgp_opcao?#?xls?@?intnaoconformidadeid?#?" . str_replace("'", "@aspass@", $this->Orig_intnaoconformidadeid) . "?@?";
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida'] = true;
         if (method_exists($this->grid_tblncpecas, "controle"))
         {
             $this->grid_tblncpecas->controle();
             if (isset($_SESSION['scriptcase']['export_return']))
             {
                 $this->row_sub = 1;
                 if (isset($_SESSION['scriptcase']['export_return']['lines']))
                 {
                     foreach ($_SESSION['scriptcase']['export_return']['lines'] as $line => $cols)
                     {
                         foreach ($cols as $icol => $dados)
                         {
                             $this->arr_export['lines'][$this->Xls_row][$this->Xls_col] = $dados;
                             $this->Xls_col++;
                         }
                         unset ($_SESSION['scriptcase']['export_return']['lines'][$line]);
                         break;
                     }
                 }
                 $this->row_sub++;
                 if (isset($_SESSION['scriptcase']['export_return']['lines']))
                 {
                     $xls_col_base = $this->Xls_col;
                     foreach ($_SESSION['scriptcase']['export_return']['lines'] as $line => $cols)
                     {
                         $this->Xls_col = $xls_col_base;
                         $prim_col = true;
                         foreach ($cols as $icol => $dados)
                         {
                             $this->Rows_sub_pecas[$this->row_sub][$icol] = $dados;
                             if ($prim_col && $this->row_sub > 1)
                             {
                                 if (isset($this->Rows_sub_pecas[$this->row_sub][$icol]['col_span_i']))
                                 {
                                    $this->Rows_sub_pecas[$this->row_sub][$icol]['col_span_i'] += $this->arr_span['pecas'];
                                 }
                                 else
                                 {
                                    $this->Rows_sub_pecas[$this->row_sub][$icol]['col_span_i'] = $this->arr_span['pecas'];
                                 }
                                $prim_col = false;
                             }
                             $this->Xls_col++;
                         }
                         $this->row_sub++;
                     }
                 }
             }
             else
             {
                 $this->Xls_col++;
             }
         }
         $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblncpecas']['embutida'] = false;
         $this->Xls_col++;
   }
   function xls_sub_cons_copy_label($row)
   {
       if (!isset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['nolabel']) || $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['nolabel'])
       {
           foreach ($this->arr_export['label'] as $col => $dados)
           {
               $this->arr_export['lines'][$row][$col] = $dados;
           }
       }
   }
   function xls_sub_cons_label($lines)
   {
         foreach ($lines as $col => $dados)
         {
             if (isset($dados['col_span_i'])) {
                 $this->Xls_col += $dados['col_span_i'];
             }
             $current_cell_ref = $this->calc_cell($this->Xls_col);
             if ($dados['align'] == 'left') {
                 $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
             }
             else {
                 $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
             }
             $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $dados['data']);
             $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getFont()->setBold(true);
             if ($dados['autosize'] == 's') {
                 $this->Nm_ActiveSheet->getColumnDimension($current_cell_ref)->setAutoSize(true);
             }
             if (isset($dados['col_span_f'])) {
                 $this->Xls_col += $dados['col_span_f'];
             }
             else {
                 $this->Xls_col++;
             }
         }
   }
   function xls_sub_cons_lines($lines)
   {
         foreach ($lines as $icol => $dados)
         {
             if (isset($dados['col_span_i'])) {
                 $this->Xls_col += $dados['col_span_i'];
             }
             $current_cell_ref = $this->calc_cell($this->Xls_col);
             if ($dados['align'] == 'left') {
                 $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
             }
             else {
                 $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
             }
             if ($dados['type'] == 'img') {
                 if (is_file($dados['data']))
                 { 
                     $sc_obj_img = new nm_trata_img($dados['data']);
                     $nm_image_altura  = $sc_obj_img->getHeight();
                     $nm_image_largura = $sc_obj_img->getWidth();
                     $objDrawing = new PHPExcel_Worksheet_Drawing();
                     if (!empty($dados['name'])) {
                         $objDrawing->setName($dados['name']);
                     } 
                     $objDrawing->setPath($dados['data']);
                     $objDrawing->setHeight($nm_image_altura);
                     $col = $current_cell_ref;
                     $objDrawing->setCoordinates($col . $this->Xls_row);
                     $objDrawing->setWorksheet($this->Nm_ActiveSheet);
                     if (!isset($this->NM_Col_din[$col]) || $this->NM_Col_din[$col] < $nm_image_largura)
                     { 
                         $this->NM_Col_din[$col] = $nm_image_largura;
                     } 
                     if (!isset($this->NM_Row_din[$this->Xls_row]) || $this->NM_Row_din[$this->Xls_row] < $nm_image_altura)
                     { 
                         $this->NM_Row_din[$this->Xls_row] = $nm_image_altura;
                     } 
                 } 
                 else 
                 { 
                     $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                     $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, ' ');
                 } 
             } 
             elseif ($dados['type'] == 'data') {
                 if (empty($dados['data']) || $dados['data'] == "0000-00-00") {
                     $this->Nm_ActiveSheet->setCellValueExplicit($current_cell_ref . $this->Xls_row, $dados['data'], PHPExcel_Cell_DataType::TYPE_STRING);
                 }
                 else {
                     $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $dados['data']);
                     $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getNumberFormat()->setFormatCode($dados['format']);
                 }
             } 
             elseif ($dados['type'] == 'num') {
                 if (is_numeric($dados['data'])) {
                     $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getNumberFormat()->setFormatCode($dados['format']);
                 }
                 $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $dados['data']);
             } 
             else { 
                $this->Nm_ActiveSheet->setCellValueExplicit($current_cell_ref . $this->Xls_row, $dados['data'], PHPExcel_Cell_DataType::TYPE_STRING);
             } 
             if (isset($dados['bold'])){ 
                 $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getFont()->setBold(true);
             } 
             if ($dados['autosize'] == 's') {
                 $this->Nm_ActiveSheet->getColumnDimension($current_cell_ref)->setAutoSize(true);
             }
             if (isset($dados['col_span_f'])) {
                 $this->Xls_col += $dados['col_span_f'];
             }
             else {
                 $this->Xls_col++;
             }
         }
         if ($this->Xls_row > $this->New_Xls_row) {
             $this->New_Xls_row = $this->Xls_row;
         }
         if (isset($dados['row_span_f'])) {
             $this->Xls_row += $dados['row_span_f'];
         }
   }
   function quebra_geral_sc_free_total_bot()
   {
       if ($this->groupby_show != "S") {
           return;
       }
       $this->Tot->quebra_geral_sc_free_total();
       $prim_cmp = true;
       $mens_tot = $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['tot_geral'][0] . "(" . $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['tot_geral'][1] . ")";
       foreach ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['field_order'] as $Cada_cmp)
       {
           if (!isset($this->NM_cmp_hidden[$Cada_cmp]) || $this->NM_cmp_hidden[$Cada_cmp] != "off")
           {
               if ($prim_cmp)
               {
                   $mens_tot = html_entity_decode($mens_tot, ENT_COMPAT, $_SESSION['scriptcase']['charset']);
                   $mens_tot = strip_tags($mens_tot);
                   if (!NM_is_utf8($mens_tot)) {
                       $mens_tot = sc_convert_encoding($mens_tot, "UTF-8", $_SESSION['scriptcase']['charset']);
                   }
                   if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida']) {
                       $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['data']   = $mens_tot;
                       $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['align']  = "left";
                       $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['type']   = "char";
                       $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['format'] = "";
                       $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['bold']   = "";
                   }
                   else {
                       $current_cell_ref = $this->calc_cell($this->Xls_col);
                       $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                       $this->Nm_ActiveSheet->setCellValue($current_cell_ref . $this->Xls_row, $mens_tot);
                       $this->Nm_ActiveSheet->getStyle($current_cell_ref . $this->Xls_row)->getFont()->setBold(true);
                   }
               }
               elseif ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida']) {
                       $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['data']   = "";
                       $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['align']  = "left";
                       $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['type']   = "char";
                       $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['format'] = "";
               }
               $this->Xls_col++;
               $prim_cmp = false;
           }
       }
       if ($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['embutida']) {
           $this->Xls_row++;
           $this->Xls_col = 1;
           $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['data']   = "";
           $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['align']  = "left";
           $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['type']   = "char";
           $this->arr_export['lines'][$this->Xls_row][$this->Xls_col]['format'] = "";
       }
   }

   function calc_cell($col)
   {
       $arr_alfa = array("","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
       $val_ret = "";
       $result = $col + 1;
       while ($result > 26)
       {
           $cel      = $result % 26;
           $result   = $result / 26;
           if ($cel == 0)
           {
               $cel    = 26;
               $result--;
           }
           $val_ret = $arr_alfa[$cel] . $val_ret;
       }
       $val_ret = $arr_alfa[$result] . $val_ret;
       return $val_ret;
   }

   function nm_conv_data_db($dt_in, $form_in, $form_out)
   {
       $dt_out = $dt_in;
       if (strtoupper($form_in) == "DB_FORMAT")
       {
           if ($dt_out == "null" || $dt_out == "")
           {
               $dt_out = "";
               return $dt_out;
           }
           $form_in = "AAAA-MM-DD";
       }
       if (strtoupper($form_out) == "DB_FORMAT")
       {
           if (empty($dt_out))
           {
               $dt_out = "null";
               return $dt_out;
           }
           $form_out = "AAAA-MM-DD";
       }
       nm_conv_form_data($dt_out, $form_in, $form_out);
       return $dt_out;
   }
   function progress_bar_end()
   {
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_file']);
      if (is_file($this->Xls_f))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_file'] = $this->Xls_f;
      }
      $path_doc_md5 = md5($this->Ini->path_imag_temp . "/" . $this->Arquivo);
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades'][$path_doc_md5][0] = $this->Ini->path_imag_temp . "/" . $this->Arquivo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades'][$path_doc_md5][1] = $this->Tit_doc;
      $Mens_bar = $this->Ini->Nm_lang['lang_othr_file_msge'];
      if ($_SESSION['scriptcase']['charset'] != "UTF-8") {
          $Mens_bar = sc_convert_encoding($Mens_bar, "UTF-8", $_SESSION['scriptcase']['charset']);
      }
      $this->pb->setProgressbarMessage($Mens_bar);
      $this->pb->setDownloadLink($this->Ini->path_imag_temp . "/" . $this->Arquivo);
      $this->pb->setDownloadMd5($path_doc_md5);
      $this->pb->completed();
   }
   //---- 
   function monta_html()
   {
      global $nm_url_saida, $nm_lang;
      include($this->Ini->path_btn . $this->Ini->Str_btn_grid);
      unset($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_file']);
      if (is_file($this->Xls_f))
      {
          $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_file'] = $this->Xls_f;
      }
      $path_doc_md5 = md5($this->Ini->path_imag_temp . "/" . $this->Arquivo);
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades'][$path_doc_md5][0] = $this->Ini->path_imag_temp . "/" . $this->Arquivo;
      $_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades'][$path_doc_md5][1] = $this->Tit_doc;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
            "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML<?php echo $_SESSION['scriptcase']['reg_conf']['html_dir'] ?>>
<HEAD>
 <TITLE><?php echo $this->Ini->Nm_lang['lang_othr_grid_title'] ?> <?php echo $this->Ini->Nm_lang['lang_tbl_tblnaoconformidades'] ?> :: Excel</TITLE>
 <META http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['scriptcase']['charset_html'] ?>" />
<?php
if ($_SESSION['scriptcase']['proc_mobile'])
{
?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<?php
}
?>
 <META http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT"/>
 <META http-equiv="Last-Modified" content="<?php echo gmdate("D, d M Y H:i:s"); ?> GMT"/>
 <META http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate"/>
 <META http-equiv="Cache-Control" content="post-check=0, pre-check=0"/>
 <META http-equiv="Pragma" content="no-cache"/>
 <link rel="shortcut icon" href="../_lib/img/scriptcase__NM__ico__NM__favicon.ico">
  <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $this->Ini->str_schema_all ?>_export.css" /> 
  <link rel="stylesheet" type="text/css" href="../_lib/css/<?php echo $this->Ini->str_schema_all ?>_export<?php echo $_SESSION['scriptcase']['reg_conf']['css_dir'] ?>.css" /> 
 <?php
 if(isset($this->Ini->str_google_fonts) && !empty($this->Ini->str_google_fonts))
 {
 ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->Ini->str_google_fonts ?>" />
 <?php
 }
 ?>
  <link rel="stylesheet" type="text/css" href="../_lib/buttons/<?php echo $this->Ini->Str_btn_css ?>" /> 
</HEAD>
<BODY class="scExportPage">
<?php echo $this->Ini->Ajax_result_set ?>
<table style="border-collapse: collapse; border-width: 0; height: 100%; width: 100%"><tr><td style="padding: 0; text-align: center; vertical-align: middle">
 <table class="scExportTable" align="center">
  <tr>
   <td class="scExportTitle" style="height: 25px">XLS</td>
  </tr>
  <tr>
   <td class="scExportLine" style="width: 100%">
    <table style="border-collapse: collapse; border-width: 0; width: 100%"><tr><td class="scExportLineFont" style="padding: 3px 0 0 0" id="idMessage">
    <?php echo $this->Ini->Nm_lang['lang_othr_file_msge'] ?>
    </td><td class="scExportLineFont" style="text-align:right; padding: 3px 0 0 0">
     <?php echo nmButtonOutput($this->arr_buttons, "bexportview", "document.Fview.submit()", "document.Fview.submit()", "idBtnView", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "", "", "", "", "", "");
 ?>
     <?php echo nmButtonOutput($this->arr_buttons, "bdownload", "document.Fdown.submit()", "document.Fdown.submit()", "idBtnDown", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "", "", "", "", "", "");
 ?>
     <?php echo nmButtonOutput($this->arr_buttons, "bvoltar", "document.F0.submit()", "document.F0.submit()", "idBtnBack", "", "", "", "", "", "", $this->Ini->path_botoes, "", "", "", "", "", "only_text", "text_right", "", "", "", "", "", "", "");
 ?>
    </td></tr></table>
   </td>
  </tr>
 </table>
</td></tr></table>
<form name="Fview" method="get" action="<?php echo $this->Ini->path_imag_temp . "/" . $this->Arquivo ?>" target="_blank" style="display: none"> 
</form>
<form name="Fdown" method="get" action="grid_tblnaoconformidades_download.php" target="_blank" style="display: none"> 
<input type="hidden" name="script_case_init" value="<?php echo NM_encode_input($this->Ini->sc_page); ?>"> 
<input type="hidden" name="nm_tit_doc" value="grid_tblnaoconformidades"> 
<input type="hidden" name="nm_name_doc" value="<?php echo $path_doc_md5 ?>"> 
</form>
<FORM name="F0" method=post action="./"> 
<INPUT type="hidden" name="script_case_init" value="<?php echo NM_encode_input($this->Ini->sc_page); ?>"> 
<INPUT type="hidden" name="script_case_session" value="<?php echo NM_encode_input(session_id()); ?>"> 
<INPUT type="hidden" name="nmgp_opcao" value="<?php echo NM_encode_input($_SESSION['sc_session'][$this->Ini->sc_page]['grid_tblnaoconformidades']['xls_return']); ?>"> 
</FORM> 
</BODY>
</HTML>
<?php
   }
   function nm_gera_mask(&$nm_campo, $nm_mask)
   { 
      $trab_campo = $nm_campo;
      $trab_mask  = $nm_mask;
      $tam_campo  = strlen($nm_campo);
      $trab_saida = "";
      $mask_num = false;
      for ($x=0; $x < strlen($trab_mask); $x++)
      {
          if (substr($trab_mask, $x, 1) == "#")
          {
              $mask_num = true;
              break;
          }
      }
      if ($mask_num )
      {
          $ver_duas = explode(";", $trab_mask);
          if (isset($ver_duas[1]) && !empty($ver_duas[1]))
          {
              $cont1 = count(explode("#", $ver_duas[0])) - 1;
              $cont2 = count(explode("#", $ver_duas[1])) - 1;
              if ($cont2 >= $tam_campo)
              {
                  $trab_mask = $ver_duas[1];
              }
              else
              {
                  $trab_mask = $ver_duas[0];
              }
          }
          $tam_mask = strlen($trab_mask);
          $xdados = 0;
          for ($x=0; $x < $tam_mask; $x++)
          {
              if (substr($trab_mask, $x, 1) == "#" && $xdados < $tam_campo)
              {
                  $trab_saida .= substr($trab_campo, $xdados, 1);
                  $xdados++;
              }
              elseif ($xdados < $tam_campo)
              {
                  $trab_saida .= substr($trab_mask, $x, 1);
              }
          }
          if ($xdados < $tam_campo)
          {
              $trab_saida .= substr($trab_campo, $xdados);
          }
          $nm_campo = $trab_saida;
          return;
      }
      for ($ix = strlen($trab_mask); $ix > 0; $ix--)
      {
           $char_mask = substr($trab_mask, $ix - 1, 1);
           if ($char_mask != "x" && $char_mask != "z")
           {
               $trab_saida = $char_mask . $trab_saida;
           }
           else
           {
               if ($tam_campo != 0)
               {
                   $trab_saida = substr($trab_campo, $tam_campo - 1, 1) . $trab_saida;
                   $tam_campo--;
               }
               else
               {
                   $trab_saida = "0" . $trab_saida;
               }
           }
      }
      if ($tam_campo != 0)
      {
          $trab_saida = substr($trab_campo, 0, $tam_campo) . $trab_saida;
          $trab_mask  = str_repeat("z", $tam_campo) . $trab_mask;
      }
   
      $iz = 0; 
      for ($ix = 0; $ix < strlen($trab_mask); $ix++)
      {
           $char_mask = substr($trab_mask, $ix, 1);
           if ($char_mask != "x" && $char_mask != "z")
           {
               if ($char_mask == "." || $char_mask == ",")
               {
                   $trab_saida = substr($trab_saida, 0, $iz) . substr($trab_saida, $iz + 1);
               }
               else
               {
                   $iz++;
               }
           }
           elseif ($char_mask == "x" || substr($trab_saida, $iz, 1) != "0")
           {
               $ix = strlen($trab_mask) + 1;
           }
           else
           {
               $trab_saida = substr($trab_saida, 0, $iz) . substr($trab_saida, $iz + 1);
           }
      }
      $nm_campo = $trab_saida;
   } 
}

?>
