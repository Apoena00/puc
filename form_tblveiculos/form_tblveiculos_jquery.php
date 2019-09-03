
function scJQGeneralAdd() {
  scLoadScInput('input:text.sc-js-input');
  scLoadScInput('input:password.sc-js-input');
  scLoadScInput('input:checkbox.sc-js-input');
  scLoadScInput('input:radio.sc-js-input');
  scLoadScInput('select.sc-js-input');
  scLoadScInput('textarea.sc-js-input');

} // scJQGeneralAdd

function scFocusField(sField) {
  var $oField = $('#id_sc_field_' + sField);

  if (0 == $oField.length) {
    $oField = $('input[name=' + sField + ']');
  }

  if (0 == $oField.length && document.F1.elements[sField]) {
    $oField = $(document.F1.elements[sField]);
  }

  if ($("#id_ac_" + sField).length > 0) {
    if ($oField.hasClass("select2-hidden-accessible")) {
      if (false == scSetFocusOnField($oField)) {
        setTimeout(function() { scSetFocusOnField($oField); }, 500);
      }
    }
    else {
      if (false == scSetFocusOnField($oField)) {
        if (false == scSetFocusOnField($("#id_ac_" + sField))) {
          setTimeout(function() { scSetFocusOnField($("#id_ac_" + sField)); }, 500);
        }
      }
      else {
        setTimeout(function() { scSetFocusOnField($oField); }, 500);
      }
    }
  }
  else {
    setTimeout(function() { scSetFocusOnField($oField); }, 500);
  }
} // scFocusField

function scSetFocusOnField($oField) {
  if ($oField.length > 0 && $oField[0].offsetHeight > 0 && $oField[0].offsetWidth > 0 && !$oField[0].disabled) {
    $oField[0].focus();
    return true;
  }
  return false;
} // scSetFocusOnField

function scEventControl_init(iSeqRow) {
  scEventControl_data["intveiculoid" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["strveiculo" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["dtalancamento" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["strcaracteristicas" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["imgveiculo" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["bolforalinha" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
}

function scEventControl_active(iSeqRow) {
  if (scEventControl_data["intveiculoid" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["intveiculoid" + iSeqRow]["change"]) {
    return true;
  }
  if (scEventControl_data["strveiculo" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["strveiculo" + iSeqRow]["change"]) {
    return true;
  }
  if (scEventControl_data["dtalancamento" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["dtalancamento" + iSeqRow]["change"]) {
    return true;
  }
  if (scEventControl_data["strcaracteristicas" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["strcaracteristicas" + iSeqRow]["change"]) {
    return true;
  }
  if (scEventControl_data["bolforalinha" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["bolforalinha" + iSeqRow]["change"]) {
    return true;
  }
  return false;
} // scEventControl_active

function scEventControl_onFocus(oField, iSeq) {
  var fieldId, fieldName;
  fieldId = $(oField).attr("id");
  fieldName = fieldId.substr(12);
  scEventControl_data[fieldName]["blur"] = true;
  scEventControl_data[fieldName]["change"] = false;
} // scEventControl_onFocus

function scEventControl_onBlur(sFieldName) {
  scEventControl_data[sFieldName]["blur"] = false;
  if (scEventControl_data[sFieldName]["change"]) {
        if (scEventControl_data[sFieldName]["original"] == $("#id_sc_field_" + sFieldName).val() || scEventControl_data[sFieldName]["calculated"] == $("#id_sc_field_" + sFieldName).val()) {
          scEventControl_data[sFieldName]["change"] = false;
        }
  }
} // scEventControl_onBlur

function scEventControl_onChange(sFieldName) {
  scEventControl_data[sFieldName]["change"] = false;
} // scEventControl_onChange

function scEventControl_onAutocomp(sFieldName) {
  scEventControl_data[sFieldName]["autocomp"] = false;
} // scEventControl_onChange

var scEventControl_data = {};

function scJQEventsAdd(iSeqRow) {
  $('#id_sc_field_intveiculoid' + iSeqRow).bind('blur', function() { sc_form_tblveiculos_intveiculoid_onblur(this, iSeqRow) })
                                          .bind('focus', function() { sc_form_tblveiculos_intveiculoid_onfocus(this, iSeqRow) });
  $('#id_sc_field_strveiculo' + iSeqRow).bind('blur', function() { sc_form_tblveiculos_strveiculo_onblur(this, iSeqRow) })
                                        .bind('focus', function() { sc_form_tblveiculos_strveiculo_onfocus(this, iSeqRow) });
  $('#id_sc_field_dtalancamento' + iSeqRow).bind('blur', function() { sc_form_tblveiculos_dtalancamento_onblur(this, iSeqRow) })
                                           .bind('focus', function() { sc_form_tblveiculos_dtalancamento_onfocus(this, iSeqRow) });
  $('#id_sc_field_dtalancamento_hora' + iSeqRow).bind('blur', function() { sc_form_tblveiculos_dtalancamento_hora_onblur(this, iSeqRow) })
                                                .bind('focus', function() { sc_form_tblveiculos_dtalancamento_hora_onfocus(this, iSeqRow) });
  $('#id_sc_field_strcaracteristicas' + iSeqRow).bind('blur', function() { sc_form_tblveiculos_strcaracteristicas_onblur(this, iSeqRow) })
                                                .bind('focus', function() { sc_form_tblveiculos_strcaracteristicas_onfocus(this, iSeqRow) });
  $('#id_sc_field_imgveiculo' + iSeqRow).bind('blur', function() { sc_form_tblveiculos_imgveiculo_onblur(this, iSeqRow) })
                                        .bind('focus', function() { sc_form_tblveiculos_imgveiculo_onfocus(this, iSeqRow) });
  $('#id_sc_field_bolforalinha' + iSeqRow).bind('blur', function() { sc_form_tblveiculos_bolforalinha_onblur(this, iSeqRow) })
                                          .bind('focus', function() { sc_form_tblveiculos_bolforalinha_onfocus(this, iSeqRow) });
} // scJQEventsAdd

function sc_form_tblveiculos_intveiculoid_onblur(oThis, iSeqRow) {
  do_ajax_form_tblveiculos_validate_intveiculoid();
  scCssBlur(oThis);
}

function sc_form_tblveiculos_intveiculoid_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblveiculos_strveiculo_onblur(oThis, iSeqRow) {
  do_ajax_form_tblveiculos_validate_strveiculo();
  scCssBlur(oThis);
}

function sc_form_tblveiculos_strveiculo_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblveiculos_dtalancamento_onblur(oThis, iSeqRow) {
  do_ajax_form_tblveiculos_validate_dtalancamento();
  scCssBlur(oThis);
}

function sc_form_tblveiculos_dtalancamento_hora_onblur(oThis, iSeqRow) {
  do_ajax_form_tblveiculos_validate_dtalancamento();
  scCssBlur(oThis);
}

function sc_form_tblveiculos_dtalancamento_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblveiculos_dtalancamento_hora_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblveiculos_strcaracteristicas_onblur(oThis, iSeqRow) {
  do_ajax_form_tblveiculos_validate_strcaracteristicas();
  scCssBlur(oThis);
}

function sc_form_tblveiculos_strcaracteristicas_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblveiculos_imgveiculo_onblur(oThis, iSeqRow) {
  scCssBlur(oThis);
}

function sc_form_tblveiculos_imgveiculo_onfocus(oThis, iSeqRow) {
  scCssFocus(oThis);
}

function sc_form_tblveiculos_bolforalinha_onblur(oThis, iSeqRow) {
  do_ajax_form_tblveiculos_validate_bolforalinha();
  scCssBlur(oThis);
}

function sc_form_tblveiculos_bolforalinha_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function displayChange_block(block, status) {
	if ("0" == block) {
		displayChange_block_0(status);
	}
}

function displayChange_block_0(status) {
	displayChange_field("intveiculoid", "", status);
	displayChange_field("strveiculo", "", status);
	displayChange_field("dtalancamento", "", status);
	displayChange_field("strcaracteristicas", "", status);
	displayChange_field("imgveiculo", "", status);
	displayChange_field("bolforalinha", "", status);
}

function displayChange_row(row, status) {
	displayChange_field_intveiculoid(row, status);
	displayChange_field_strveiculo(row, status);
	displayChange_field_dtalancamento(row, status);
	displayChange_field_strcaracteristicas(row, status);
	displayChange_field_imgveiculo(row, status);
	displayChange_field_bolforalinha(row, status);
}

function displayChange_field(field, row, status) {
	if ("intveiculoid" == field) {
		displayChange_field_intveiculoid(row, status);
	}
	if ("strveiculo" == field) {
		displayChange_field_strveiculo(row, status);
	}
	if ("dtalancamento" == field) {
		displayChange_field_dtalancamento(row, status);
	}
	if ("strcaracteristicas" == field) {
		displayChange_field_strcaracteristicas(row, status);
	}
	if ("imgveiculo" == field) {
		displayChange_field_imgveiculo(row, status);
	}
	if ("bolforalinha" == field) {
		displayChange_field_bolforalinha(row, status);
	}
}

function displayChange_field_intveiculoid(row, status) {
}

function displayChange_field_strveiculo(row, status) {
}

function displayChange_field_dtalancamento(row, status) {
}

function displayChange_field_strcaracteristicas(row, status) {
}

function displayChange_field_imgveiculo(row, status) {
}

function displayChange_field_bolforalinha(row, status) {
}

function scRecreateSelect2() {
}
function scResetPagesDisplay() {
	$(".sc-form-page").show();
}

function scHidePage(pageNo) {
	$("#id_form_tblveiculos_form" + pageNo).hide();
}

function scCheckNoPageSelected() {
	if (!$(".sc-form-page").filter(".scTabActive").filter(":visible").length) {
		var inactiveTabs = $(".sc-form-page").filter(".scTabInactive").filter(":visible");
		if (inactiveTabs.length) {
			var tabNo = $(inactiveTabs[0]).attr("id").substr(24);
		}
	}
}
var sc_jq_calendar_value = {};

function scJQCalendarAdd(iSeqRow) {
  $("#id_sc_field_dtalancamento" + iSeqRow).datepicker({
    beforeShow: function(input, inst) {
      var $oField = $(this),
          aParts  = $oField.val().split(" "),
          sTime   = "";
      sc_jq_calendar_value["#id_sc_field_dtalancamento" + iSeqRow] = $oField.val();
      if (2 == aParts.length) {
        sTime = " " + aParts[1];
      }
      if ('' == sTime || ' ' == sTime) {
        sTime = ' <?php echo $this->jqueryCalendarTimeStart($this->field_config['dtalancamento']['date_format']); ?>';
      }
      $oField.datepicker("option", "dateFormat", "<?php echo $this->jqueryCalendarDtFormat("" . str_replace(array('/', 'aaaa', 'hh', 'ii', 'ss', ':', ';', $_SESSION['scriptcase']['reg_conf']['date_sep'], $_SESSION['scriptcase']['reg_conf']['time_sep']), array('', 'yyyy', '','','', '', '', '', ''), $this->field_config['dtalancamento']['date_format']) . "", "" . $_SESSION['scriptcase']['reg_conf']['date_sep'] . ""); ?>" + sTime);
    },
    onClose: function(dateText, inst) {
      do_ajax_form_tblveiculos_validate_dtalancamento(iSeqRow);
    },
    showWeek: true,
    numberOfMonths: 1,
    changeMonth: true,
    changeYear: true,
    yearRange: 'c-5:c+5',
    dayNames: ["<?php        echo html_entity_decode($this->Ini->Nm_lang['lang_days_sund'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);        ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_mond'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);        ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_tued'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);        ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_wend'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);        ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_thud'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);        ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_frid'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);        ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_days_satd'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);        ?>"],
    dayNamesMin: ["<?php     echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_sund'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_mond'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_tued'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_wend'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_thud'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_frid'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_substr_days_satd'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>"],
    monthNames: ["<?php      echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_janu"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>","<?php echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_febr"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>","<?php echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_marc"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>","<?php echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_apri"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>","<?php echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_mayy"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>","<?php echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_june"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>","<?php echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_july"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>","<?php echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_augu"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>","<?php echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_sept"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>","<?php echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_octo"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>","<?php echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_nove"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>","<?php echo html_entity_decode($this->Ini->Nm_lang["lang_mnth_dece"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);      ?>"],
    monthNamesShort: ["<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_janu'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);   ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_febr'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);   ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_marc'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);   ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_apri'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);   ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_mayy'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);   ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_june'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);   ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_july'], ENT_COMPAT, $_SESSION['scriptcase']['charset']);   ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_augu'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_sept'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_octo'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_nove'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>","<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_mnth_dece'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>"],
    weekHeader: "<?php echo html_entity_decode($this->Ini->Nm_lang['lang_shrt_days_sem'], ENT_COMPAT, $_SESSION['scriptcase']['charset']); ?>",
    firstDay: <?php echo $this->jqueryCalendarWeekInit("" . $_SESSION['scriptcase']['reg_conf']['date_week_ini'] . ""); ?>,
    dateFormat: "<?php echo $this->jqueryCalendarDtFormat("" . str_replace(array('/', 'aaaa', 'hh', 'ii', 'ss', ':', ';', $_SESSION['scriptcase']['reg_conf']['date_sep'], $_SESSION['scriptcase']['reg_conf']['time_sep']), array('', 'yyyy', '','','', '', '', '', ''), $this->field_config['dtalancamento']['date_format']) . "", "" . $_SESSION['scriptcase']['reg_conf']['date_sep'] . ""); ?>",
    showOtherMonths: true,
    showOn: "button",
<?php
$miniCalendarIcon = $this->jqueryIconFile('calendar');
$miniCalendarFA   = $this->jqueryFAFile('calendar');
if ('' != $miniCalendarIcon) {
?>
    buttonImage: "<?php echo $miniCalendarIcon; ?>",
    buttonImageOnly: true,
<?php
}
elseif ('' != $miniCalendarFA) {
?>
    buttonText: "<?php echo $miniCalendarFA; ?>",
<?php
}
?>
    currentText: "<?php  echo html_entity_decode($this->Ini->Nm_lang["lang_per_today"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);       ?>",
    closeText: "<?php  echo html_entity_decode($this->Ini->Nm_lang["lang_btns_mess_clse"], ENT_COMPAT, $_SESSION["scriptcase"]["charset"]);       ?>",
  });

} // scJQCalendarAdd

                var scJQHtmlEditorData = (function() {
                    var data = {};
                    function scJQHtmlEditorData(a, b) {
                        if (a) {
                            if (typeof(a) === typeof({})) {
                                for (var d in a) {
                                    if (a.hasOwnProperty(d)) {
                                        data[d] = a[d];
                                    }
                                }
                            } else if ((typeof(a) === typeof('')) || (typeof(a) === typeof(1))) {
                                if (b) {
                                    data[a] = b;
                                } else {
                                    if (typeof(a) === typeof('')) {
                                        var v = data;
                                        a = a.split('.');
                                        a.forEach(function (r) {
                                            v = v[r];
                                        });
                                        return v;
                                    }
                                    return data[a];
                                }
                            }
                        }
                        return data;
                    }
                    return scJQHtmlEditorData;
                }());
 function scJQHtmlEditorAdd(iSeqRow) {
<?php
$sLangTest = '';
if(is_file('../_lib/lang/arr_langs_tinymce.php'))
{
    include('../_lib/lang/arr_langs_tinymce.php');
    if(isset($Nm_arr_lang_tinymce[ $this->Ini->str_lang ]))
    {
        $sLangTest = $Nm_arr_lang_tinymce[ $this->Ini->str_lang ];
    }
}
if(empty($sLangTest))
{
    $sLangTest = 'en_GB';
}
?>
 var data = Object.assign({}, scJQHtmlEditorData({
  mode: "textareas",
  theme: "modern",
  browser_spellcheck : true,
<?php
if ('novo' != $this->nmgp_opcao && isset($this->nmgp_cmp_readonly['strcaracteristicas']) && $this->nmgp_cmp_readonly['strcaracteristicas'] == 'on')
{
    unset($this->nmgp_cmp_readonly['strcaracteristicas']);
?>
   readonly: "true",
<?php
}
?>
<?php
if ('yyyymmdd' == $_SESSION['scriptcase']['reg_conf']['date_format']) {
    $tinymceDateFormat = "%Y{$_SESSION['scriptcase']['reg_conf']['date_sep']}%m{$_SESSION['scriptcase']['reg_conf']['date_sep']}%d";
}
elseif ('mmddyyyy' == $_SESSION['scriptcase']['reg_conf']['date_format']) {
    $tinymceDateFormat = "%m{$_SESSION['scriptcase']['reg_conf']['date_sep']}%d{$_SESSION['scriptcase']['reg_conf']['date_sep']}%Y";
}
elseif ('ddmmyyyy' == $_SESSION['scriptcase']['reg_conf']['date_format']) {
    $tinymceDateFormat = "%d{$_SESSION['scriptcase']['reg_conf']['date_sep']}%m{$_SESSION['scriptcase']['reg_conf']['date_sep']}%Y";
}
else {
    $tinymceDateFormat = "%D";
}
?>
  insertdatetime_formats: ["%H:%M:%S", "%Y-%m-%d", "%I:%M:%S %p", "<?php echo $tinymceDateFormat ?>"],
  relative_urls : false,
  remove_script_host : false,
  convert_urls  : true,
  language : '<?php echo $sLangTest; ?>',
  plugins : 'advlist,autolink,link,image,lists,charmap,print,preview,hr,anchor,pagebreak,searchreplace,wordcount,visualblocks,visualchars,code,fullscreen,insertdatetime,media,nonbreaking,table,directionality,emoticons,template,textcolor,paste,textcolor,colorpicker,textpattern,contextmenu',
  toolbar1: "undo,redo,separator,formatselect,separator,bold,italic,separator,alignleft,aligncenter,alignright,alignjustify,separator,bullist,numlist,outdent,indent,separator,link,image",
  statusbar : false,
  menubar : 'file edit insert view format table tools',
  toolbar_items_size: 'small',
  content_style: ".mce-container-body {text-align: left !important}",
  editor_selector: "mceEditor_strcaracteristicas" + iSeqRow,
  setup: function(ed) {
    ed.on("init", function (e) {
      if ($('textarea[name="strcaracteristicas' + iSeqRow + '"]').prop('disabled') == true) {
        ed.setMode("readonly");
      }
    });
  }
 }));
 tinyMCE.init(data);
} // scJQHtmlEditorAdd

function scJQUploadAdd(iSeqRow) {
  $("#id_sc_field_imgveiculo" + iSeqRow).fileupload({
    datatype: "json",
    url: "form_tblveiculos_ul_save.php",
    dropZone: $("#hidden_field_data_imgveiculo" + iSeqRow),
    formData: function() {
      return [
        {name: 'param_field', value: 'imgveiculo'},
        {name: 'param_seq', value: '<?php echo $this->Ini->sc_page; ?>'},
        {name: 'upload_file_row', value: iSeqRow}
      ];
    },
    progress: function(e, data) {
      var loader, progress;
      if (data.lengthComputable && window.FormData !== undefined) {
        loader = $("#id_img_loader_imgveiculo" + iSeqRow);
        progress = parseInt(data.loaded / data.total * 100, 10);
        loader.show().find("div").css("width", progress + "%");
      }
      else {
        loader = $("#id_ajax_loader_imgveiculo" + iSeqRow);
        loader.show();
      }
    },
    done: function(e, data) {
      var fileData, respData, respPos, respMsg, thumbDisplay, checkDisplay, var_ajax_img_thumb, oTemp;
      fileData = null;
      respMsg = "";
      if (data && data.result && data.result[0] && data.result[0].body) {
        respData = data.result[0].body.innerText;
        respPos = respData.indexOf("[{");
        if (-1 !== respPos) {
          respMsg = respData.substr(0, respPos);
          respData = respData.substr(respPos);
          fileData = $.parseJSON(respData);
        }
        else {
          respMsg = respData;
        }
      }
      else {
        respData = data.result;
        respPos = respData.indexOf("[{");
        if (-1 !== respPos) {
          respMsg = respData.substr(0, respPos);
          respData = respData.substr(respPos);
          fileData = eval(respData);
        }
        else {
          respMsg = respData;
        }
      }
      if (window.FormData !== undefined)
      {
        $("#id_img_loader_imgveiculo" + iSeqRow).hide();
      }
      else
      {
        $("#id_ajax_loader_imgveiculo" + iSeqRow).hide();
      }
      if (null == fileData) {
        if ("" != respMsg) {
          oTemp = {"htmOutput" : "<?php echo $this->Ini->Nm_lang['lang_errm_upld_admn']; ?>"};
          scAjaxShowDebug(oTemp);
        }
        return;
      }
      if (fileData[0].error && "" != fileData[0].error) {
        var uploadErrorMessage = "";
        oResp = {};
        if ("acceptFileTypes" == fileData[0].error) {
          uploadErrorMessage = "<?php echo $this->form_encode_input($this->Ini->Nm_lang['lang_errm_file_invl']) ?>";
        }
        else if ("maxFileSize" == fileData[0].error) {
          uploadErrorMessage = "<?php echo $this->form_encode_input($this->Ini->Nm_lang['lang_errm_file_size']) ?>";
        }
        else if ("minFileSize" == fileData[0].error) {
          uploadErrorMessage = "<?php echo $this->form_encode_input($this->Ini->Nm_lang['lang_errm_file_size']) ?>";
        }
        else if ("emptyFile" == fileData[0].error) {
          uploadErrorMessage = "<?php echo $this->form_encode_input($this->Ini->Nm_lang['lang_errm_file_empty']) ?>";
        }
        scAjaxShowErrorDisplay("table", uploadErrorMessage);
        return;
      }
      $("#id_sc_field_imgveiculo" + iSeqRow).val("");
      $("#id_sc_field_imgveiculo_ul_name" + iSeqRow).val(fileData[0].sc_ul_name);
      $("#id_sc_field_imgveiculo_ul_type" + iSeqRow).val(fileData[0].type);
      var_ajax_img_imgveiculo = '<?php echo $this->Ini->path_imag_temp; ?>/' + fileData[0].sc_image_source;
      var_ajax_img_thumb = '<?php echo $this->Ini->path_imag_temp; ?>/' + fileData[0].sc_thumb_prot;
      thumbDisplay = ("" == var_ajax_img_imgveiculo) ? "none" : "";
      $("#id_ajax_img_imgveiculo" + iSeqRow).attr("src", var_ajax_img_thumb);
      $("#id_ajax_img_imgveiculo" + iSeqRow).css("display", thumbDisplay);
      if (document.F1.temp_out1_imgveiculo) {
        document.F1.temp_out_imgveiculo.value = var_ajax_img_thumb;
        document.F1.temp_out1_imgveiculo.value = var_ajax_img_imgveiculo;
      }
      else if (document.F1.temp_out_imgveiculo) {
        document.F1.temp_out_imgveiculo.value = var_ajax_img_imgveiculo;
      }
      checkDisplay = ("" == fileData[0].sc_random_prot.substr(12)) ? "none" : "";
      $("#chk_ajax_img_imgveiculo" + iSeqRow).css("display", checkDisplay);
      $("#txt_ajax_img_imgveiculo" + iSeqRow).html(fileData[0].name);
      $("#txt_ajax_img_imgveiculo" + iSeqRow).css("display", checkDisplay);
      $("#id_ajax_link_imgveiculo" + iSeqRow).html(fileData[0].sc_random_prot.substr(12));
    }
  });

} // scJQUploadAdd

function scJQSelect2Add(seqRow, specificField) {
} // scJQSelect2Add


function scJQElementsAdd(iLine) {
  scJQEventsAdd(iLine);
  scEventControl_init(iLine);
  scJQCalendarAdd(iLine);
  scJQHtmlEditorAdd(iLine);
  scJQUploadAdd(iLine);
  scJQSelect2Add(iLine);
} // scJQElementsAdd

