
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
  scEventControl_data["intnaoconformidadeid" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["dtaidentificacao" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["strnaoconformidade" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["strdescricao" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["strnorma" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["intstatusncid" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["veiculos" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
  scEventControl_data["pecas" + iSeqRow] = {"blur": false, "change": false, "autocomp": false, "original": "", "calculated": ""};
}

function scEventControl_active(iSeqRow) {
  if (scEventControl_data["intnaoconformidadeid" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["intnaoconformidadeid" + iSeqRow]["change"]) {
    return true;
  }
  if (scEventControl_data["dtaidentificacao" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["dtaidentificacao" + iSeqRow]["change"]) {
    return true;
  }
  if (scEventControl_data["strnaoconformidade" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["strnaoconformidade" + iSeqRow]["change"]) {
    return true;
  }
  if (scEventControl_data["strdescricao" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["strdescricao" + iSeqRow]["change"]) {
    return true;
  }
  if (scEventControl_data["strnorma" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["strnorma" + iSeqRow]["change"]) {
    return true;
  }
  if (scEventControl_data["intstatusncid" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["intstatusncid" + iSeqRow]["change"]) {
    return true;
  }
  if (scEventControl_data["veiculos" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["veiculos" + iSeqRow]["change"]) {
    return true;
  }
  if (scEventControl_data["pecas" + iSeqRow]["blur"]) {
    return true;
  }
  if (scEventControl_data["pecas" + iSeqRow]["change"]) {
    return true;
  }
  return false;
} // scEventControl_active

function scEventControl_onFocus(oField, iSeq) {
  var fieldId, fieldName;
  fieldId = $(oField).attr("id");
  fieldName = fieldId.substr(12);
  scEventControl_data[fieldName]["blur"] = true;
  if ("intstatusncid" + iSeq == fieldName) {
    scEventControl_data[fieldName]["blur"] = false;
  }
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
  $('#id_sc_field_intnaoconformidadeid' + iSeqRow).bind('blur', function() { sc_form_tblnaoconformidades_intnaoconformidadeid_onblur(this, iSeqRow) })
                                                  .bind('focus', function() { sc_form_tblnaoconformidades_intnaoconformidadeid_onfocus(this, iSeqRow) });
  $('#id_sc_field_dtaidentificacao' + iSeqRow).bind('blur', function() { sc_form_tblnaoconformidades_dtaidentificacao_onblur(this, iSeqRow) })
                                              .bind('focus', function() { sc_form_tblnaoconformidades_dtaidentificacao_onfocus(this, iSeqRow) });
  $('#id_sc_field_dtaidentificacao_hora' + iSeqRow).bind('blur', function() { sc_form_tblnaoconformidades_dtaidentificacao_onblur(this, iSeqRow) })
                                                   .bind('focus', function() { sc_form_tblnaoconformidades_dtaidentificacao_onfocus(this, iSeqRow) });
  $('#id_sc_field_strnaoconformidade' + iSeqRow).bind('blur', function() { sc_form_tblnaoconformidades_strnaoconformidade_onblur(this, iSeqRow) })
                                                .bind('focus', function() { sc_form_tblnaoconformidades_strnaoconformidade_onfocus(this, iSeqRow) });
  $('#id_sc_field_strdescricao' + iSeqRow).bind('blur', function() { sc_form_tblnaoconformidades_strdescricao_onblur(this, iSeqRow) })
                                          .bind('focus', function() { sc_form_tblnaoconformidades_strdescricao_onfocus(this, iSeqRow) });
  $('#id_sc_field_intstatusncid' + iSeqRow).bind('blur', function() { sc_form_tblnaoconformidades_intstatusncid_onblur(this, iSeqRow) })
                                           .bind('focus', function() { sc_form_tblnaoconformidades_intstatusncid_onfocus(this, iSeqRow) });
  $('#id_sc_field_strnorma' + iSeqRow).bind('blur', function() { sc_form_tblnaoconformidades_strnorma_onblur(this, iSeqRow) })
                                      .bind('focus', function() { sc_form_tblnaoconformidades_strnorma_onfocus(this, iSeqRow) });
  $('#id_sc_field_veiculos' + iSeqRow).bind('blur', function() { sc_form_tblnaoconformidades_veiculos_onblur(this, iSeqRow) })
                                      .bind('focus', function() { sc_form_tblnaoconformidades_veiculos_onfocus(this, iSeqRow) });
  $('#id_sc_field_pecas' + iSeqRow).bind('blur', function() { sc_form_tblnaoconformidades_pecas_onblur(this, iSeqRow) })
                                   .bind('focus', function() { sc_form_tblnaoconformidades_pecas_onfocus(this, iSeqRow) });
} // scJQEventsAdd

function sc_form_tblnaoconformidades_intnaoconformidadeid_onblur(oThis, iSeqRow) {
  do_ajax_form_tblnaoconformidades_mob_validate_intnaoconformidadeid();
  scCssBlur(oThis);
}

function sc_form_tblnaoconformidades_intnaoconformidadeid_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblnaoconformidades_dtaidentificacao_onblur(oThis, iSeqRow) {
  do_ajax_form_tblnaoconformidades_mob_validate_dtaidentificacao();
  scCssBlur(oThis);
}

function sc_form_tblnaoconformidades_dtaidentificacao_onblur(oThis, iSeqRow) {
  do_ajax_form_tblnaoconformidades_mob_validate_dtaidentificacao();
  scCssBlur(oThis);
}

function sc_form_tblnaoconformidades_dtaidentificacao_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblnaoconformidades_dtaidentificacao_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblnaoconformidades_strnaoconformidade_onblur(oThis, iSeqRow) {
  do_ajax_form_tblnaoconformidades_mob_validate_strnaoconformidade();
  scCssBlur(oThis);
}

function sc_form_tblnaoconformidades_strnaoconformidade_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblnaoconformidades_strdescricao_onblur(oThis, iSeqRow) {
  do_ajax_form_tblnaoconformidades_mob_validate_strdescricao();
  scCssBlur(oThis);
}

function sc_form_tblnaoconformidades_strdescricao_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblnaoconformidades_intstatusncid_onblur(oThis, iSeqRow) {
  do_ajax_form_tblnaoconformidades_mob_validate_intstatusncid();
  scCssBlur(oThis);
}

function sc_form_tblnaoconformidades_intstatusncid_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblnaoconformidades_strnorma_onblur(oThis, iSeqRow) {
  do_ajax_form_tblnaoconformidades_mob_validate_strnorma();
  scCssBlur(oThis);
}

function sc_form_tblnaoconformidades_strnorma_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblnaoconformidades_veiculos_onblur(oThis, iSeqRow) {
  do_ajax_form_tblnaoconformidades_mob_validate_veiculos();
  scCssBlur(oThis);
}

function sc_form_tblnaoconformidades_veiculos_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function sc_form_tblnaoconformidades_pecas_onblur(oThis, iSeqRow) {
  do_ajax_form_tblnaoconformidades_mob_validate_pecas();
  scCssBlur(oThis);
}

function sc_form_tblnaoconformidades_pecas_onfocus(oThis, iSeqRow) {
  scEventControl_onFocus(oThis, iSeqRow);
  scCssFocus(oThis);
}

function displayChange_block(block, status) {
	if ("0" == block) {
		displayChange_block_0(status);
	}
	if ("1" == block) {
		displayChange_block_1(status);
	}
	if ("2" == block) {
		displayChange_block_2(status);
	}
}

function displayChange_block_0(status) {
	displayChange_field("intnaoconformidadeid", "", status);
	displayChange_field("dtaidentificacao", "", status);
	displayChange_field("strnaoconformidade", "", status);
	displayChange_field("strdescricao", "", status);
	displayChange_field("strnorma", "", status);
	displayChange_field("intstatusncid", "", status);
}

function displayChange_block_1(status) {
	displayChange_field("veiculos", "", status);
}

function displayChange_block_2(status) {
	displayChange_field("pecas", "", status);
}

function displayChange_row(row, status) {
	displayChange_field_intnaoconformidadeid(row, status);
	displayChange_field_dtaidentificacao(row, status);
	displayChange_field_strnaoconformidade(row, status);
	displayChange_field_strdescricao(row, status);
	displayChange_field_strnorma(row, status);
	displayChange_field_intstatusncid(row, status);
	displayChange_field_veiculos(row, status);
	displayChange_field_pecas(row, status);
}

function displayChange_field(field, row, status) {
	if ("intnaoconformidadeid" == field) {
		displayChange_field_intnaoconformidadeid(row, status);
	}
	if ("dtaidentificacao" == field) {
		displayChange_field_dtaidentificacao(row, status);
	}
	if ("strnaoconformidade" == field) {
		displayChange_field_strnaoconformidade(row, status);
	}
	if ("strdescricao" == field) {
		displayChange_field_strdescricao(row, status);
	}
	if ("strnorma" == field) {
		displayChange_field_strnorma(row, status);
	}
	if ("intstatusncid" == field) {
		displayChange_field_intstatusncid(row, status);
	}
	if ("veiculos" == field) {
		displayChange_field_veiculos(row, status);
	}
	if ("pecas" == field) {
		displayChange_field_pecas(row, status);
	}
}

function displayChange_field_intnaoconformidadeid(row, status) {
}

function displayChange_field_dtaidentificacao(row, status) {
}

function displayChange_field_strnaoconformidade(row, status) {
}

function displayChange_field_strdescricao(row, status) {
}

function displayChange_field_strnorma(row, status) {
}

function displayChange_field_intstatusncid(row, status) {
	if ("on" == status) {
		if ("all" == row) {
			var fieldList = $(".css_intstatusncid__obj");
			for (var i = 0; i < fieldList.length; i++) {
				$($(fieldList[i]).attr("id")).select2("destroy");
			}
		}
		else {
			$("#id_sc_field_intstatusncid" + row).select2("destroy");
		}
		scJQSelect2Add(row, "intstatusncid");
	}
}

function displayChange_field_veiculos(row, status) {
	if ("on" == status && typeof $("#nmsc_iframe_liga_form_tblncveiculos_mob")[0].contentWindow.scRecreateSelect2 === "function") {
		$("#nmsc_iframe_liga_form_tblncveiculos_mob")[0].contentWindow.scRecreateSelect2();
	}
}

function displayChange_field_pecas(row, status) {
	if ("on" == status && typeof $("#nmsc_iframe_liga_form_tblncpecas_mob")[0].contentWindow.scRecreateSelect2 === "function") {
		$("#nmsc_iframe_liga_form_tblncpecas_mob")[0].contentWindow.scRecreateSelect2();
	}
}

function scRecreateSelect2() {
	displayChange_field_intstatusncid("all", "on");
}
function scResetPagesDisplay() {
	$(".sc-form-page").show();
}

function scHidePage(pageNo) {
	$("#id_form_tblnaoconformidades_mob_form" + pageNo).hide();
}

function scCheckNoPageSelected() {
	if (!$(".sc-form-page").filter(".scTabActive").filter(":visible").length) {
		var inactiveTabs = $(".sc-form-page").filter(".scTabInactive").filter(":visible");
		if (inactiveTabs.length) {
			var tabNo = $(inactiveTabs[0]).attr("id").substr(36);
		}
	}
}
var sc_jq_calendar_value = {};

function scJQCalendarAdd(iSeqRow) {
  $("#id_sc_field_dtaidentificacao" + iSeqRow).datepicker({
    beforeShow: function(input, inst) {
      var $oField = $(this),
          aParts  = $oField.val().split(" "),
          sTime   = "";
      sc_jq_calendar_value["#id_sc_field_dtaidentificacao" + iSeqRow] = $oField.val();
      if (2 == aParts.length) {
        sTime = " " + aParts[1];
      }
      if ('' == sTime || ' ' == sTime) {
        sTime = ' <?php echo $this->jqueryCalendarTimeStart($this->field_config['dtaidentificacao']['date_format']); ?>';
      }
      $oField.datepicker("option", "dateFormat", "<?php echo $this->jqueryCalendarDtFormat("" . str_replace(array('/', 'aaaa', 'hh', 'ii', 'ss', ':', ';', $_SESSION['scriptcase']['reg_conf']['date_sep'], $_SESSION['scriptcase']['reg_conf']['time_sep']), array('', 'yyyy', '','','', '', '', '', ''), $this->field_config['dtaidentificacao']['date_format']) . "", "" . $_SESSION['scriptcase']['reg_conf']['date_sep'] . ""); ?>" + sTime);
    },
    onClose: function(dateText, inst) {
      do_ajax_form_tblnaoconformidades_mob_validate_dtaidentificacao(iSeqRow);
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
    dateFormat: "<?php echo $this->jqueryCalendarDtFormat("" . str_replace(array('/', 'aaaa', 'hh', 'ii', 'ss', ':', ';', $_SESSION['scriptcase']['reg_conf']['date_sep'], $_SESSION['scriptcase']['reg_conf']['time_sep']), array('', 'yyyy', '','','', '', '', '', ''), $this->field_config['dtaidentificacao']['date_format']) . "", "" . $_SESSION['scriptcase']['reg_conf']['date_sep'] . ""); ?>",
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
if ('novo' != $this->nmgp_opcao && isset($this->nmgp_cmp_readonly['strdescricao']) && $this->nmgp_cmp_readonly['strdescricao'] == 'on')
{
    unset($this->nmgp_cmp_readonly['strdescricao']);
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
  editor_selector: "mceEditor_strdescricao" + iSeqRow,
  setup: function(ed) {
    ed.on("init", function (e) {
      if ($('textarea[name="strdescricao' + iSeqRow + '"]').prop('disabled') == true) {
        ed.setMode("readonly");
      }
    });
  }
 }));
 tinyMCE.init(data);
} // scJQHtmlEditorAdd

function scJQUploadAdd(iSeqRow) {
} // scJQUploadAdd

function scJQSelect2Add(seqRow, specificField) {
  if (null == specificField || "intstatusncid" == specificField) {
    scJQSelect2Add_intstatusncid(seqRow);
  }
} // scJQSelect2Add

function scJQSelect2Add_intstatusncid(seqRow) {
  var elemSelector = "all" == seqRow ? ".css_intstatusncid_obj" : "#id_sc_field_intstatusncid" + seqRow;
  $(elemSelector).select2(
    {
      containerCssClass: 'css_intstatusncid_obj',
      dropdownCssClass: 'css_intstatusncid_obj',
      language: {
        noResults: function() {
          return "<?php echo $this->Ini->Nm_lang['lang_autocomp_notfound'] ?>";
        },
        searching: function() {
          return "<?php echo $this->Ini->Nm_lang['lang_autocomp_searching'] ?>";
        }
      }
    }
  );
} // scJQSelect2Add


function scJQElementsAdd(iLine) {
  scJQEventsAdd(iLine);
  scEventControl_init(iLine);
  scJQCalendarAdd(iLine);
  scJQHtmlEditorAdd(iLine);
  scJQUploadAdd(iLine);
  scJQSelect2Add(iLine);
  setTimeout(function () { if ('function' == typeof displayChange_field_intstatusncid) { displayChange_field_intstatusncid(iLine, "on"); } }, 150);
} // scJQElementsAdd

var scBtnGrpStatus = {};
function scBtnGrpShow(sGroup) {
  if (typeof(scBtnGrpShowMobile) === typeof(function(){})) { return scBtnGrpShowMobile(sGroup); };
  $('#sc_btgp_btn_' + sGroup).addClass('selected');
  var btnPos = $('#sc_btgp_btn_' + sGroup).offset();
  scBtnGrpStatus[sGroup] = 'open';
  $('#sc_btgp_btn_' + sGroup).mouseout(function() {
    scBtnGrpStatus[sGroup] = '';
    setTimeout(function() {
      scBtnGrpHide(sGroup, false);
    }, 1000);
  }).mouseover(function() {
    scBtnGrpStatus[sGroup] = 'over';
  });
  $('#sc_btgp_div_' + sGroup + ' span a').click(function() {
    scBtnGrpStatus[sGroup] = 'out';
    scBtnGrpHide(sGroup, false);
  });
  $('#sc_btgp_div_' + sGroup).css({
    'left': btnPos.left
  })
  .mouseover(function() {
    scBtnGrpStatus[sGroup] = 'over';
  })
  .mouseleave(function() {
    scBtnGrpStatus[sGroup] = 'out';
    setTimeout(function() {
      scBtnGrpHide(sGroup, false);
    }, 1000);
  })
  .show('fast');
}
function scBtnGrpHide(sGroup, bForce) {
  if (bForce || 'over' != scBtnGrpStatus[sGroup]) {
    $('#sc_btgp_div_' + sGroup).hide('fast');
    $('#sc_btgp_btn_' + sGroup).addClass('selected');
  }
}
