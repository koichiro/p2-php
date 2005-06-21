<?php
/* vim: set fileencoding=cp932 ai et ts=4 sw=4 sts=0 fdm=marker: */
/* mi: charset=Shift_JIS */

// p2 - ���X�������݃t�H�[���̋@�\�ǂݍ���

$fake_time = -10; // time ��10���O�ɋU��
$time = time() - 9*60*60;
$time = $time + $fake_time * 60;

// {{{ key.idx���疼�O�ƃ��[����Ǎ���
if ($lines = @file($key_idx)) {
    $line = explode('<>', rtrim($lines[0]));
    $hd['FROM'] = htmlspecialchars($line[7], ENT_QUOTES);
    $hd['mail'] = htmlspecialchars($line[8], ENT_QUOTES);
}
// }}}

// �O���POST���s������ΌĂяo��
$failed_post_file = P2Util::getFailedPostFilePath($host, $bbs, $key);
if ($cont_srd = DataPhp::getDataPhpCont($failed_post_file)) {
    $last_posted = unserialize($cont_srd);

    // �܂Ƃ߂ăT�j�^�C�Y
    $last_posted = array_map(create_function('$n', 'return htmlspecialchars($n, ENT_QUOTES);'), $last_posted);
    //$addslashesS = create_function('$str', 'return str_replace("\'", "\\\'", $str);');
    //$last_posted = array_map($addslashesS, $last_posted);

    $hd['FROM'] = $last_posted['FROM'];
    $hd['mail'] = $last_posted['mail'];
    $hd['MESSAGE'] = $last_posted['MESSAGE'];
    $hd['subject'] = $last_posted['subject'];
}

// �󔒂̓��[�U�ݒ�l�ɕϊ�
$hd['FROM'] = ($hd['FROM'] == '') ? htmlspecialchars($_conf['my_FROM'], ENT_QUOTES) : $hd['FROM'];
$hd['mail'] = ($hd['mail'] == '') ? htmlspecialchars($_conf['my_mail'], ENT_QUOTES) : $hd['mail'];

// P2NULL�͋󔒂ɕϊ�
$hd['FROM'] = ($hd['FROM'] == 'P2NULL') ? '' : $hd['FROM'];
$hd['mail'] = ($hd['mail'] == 'P2NULL') ? '' : $hd['mail'];


// �\���w��
if (!$_conf['ktai']) {
    $name_size_at = ' size="19"';
    $mail_size_at = ' size="19"';
    $msg_cols_at = ' cols="'.$STYLE['post_msg_cols'].'"';
} else {
    $STYLE['post_msg_rows'] = 3;
}

// Be.2ch
if (P2Util::isHost2chs($host) and $_conf['be_2ch_code'] && $_conf['be_2ch_mail']) {
    $htm['be2ch'] = '<input type="submit" name="submit_beres" value="BE�ŏ�������">';
}

// PC�p sage checkbox
if (!$_conf['ktai']) {
    $on_check_sage = ' onchange="checkSage();"';
    $htm['sage_cb'] = <<<EOP
<input id="sage" type="checkbox" onclick="mailSage();"><label for="sage">sage</label><br>
EOP;
}

// {{{ 2ch����������
$htm['maru_post'] = '';
if (P2Util::isHost2chs($host) and file_exists($_conf['sid2ch_php'])) {
    $htm['maru_post'] = <<<EOP
<span title="2ch��ID�̎g�p"><input id="maru" name="maru" type="checkbox"><label for="maru">��</label></span>
EOP;
}
// }}}
// {{{ �V���܂Ƃߓǂ݂���̃|�X�g

$htm['from_read_new'] = !empty($_GET['from_read_new']) ? '<input type="hidden" name="from_read_new" value="1">' : '';

// }}}

$csrfid = P2Util::getCsrfId();

// {{{ �{������̂Ƃ���sage�ĂȂ��Ƃ��ɑ��M���悤�Ƃ���ƒ��ӂ���

$js = array();
$js['onsubmit'] = '';

if (!$_conf['ktai']) {
    if ($_exconf['editor']['check_message'] || $_exconf['editor']['check_sage']) {
        $_check_message = $_exconf['editor']['check_message'];
        $_check_sage = $_exconf['editor']['check_sage'];
        $js['onsubmit'] = " onsubmit=\"return validateAll({$_check_message},{$_check_sage})\"";
    }
}

// }}}
// {{{�\�[�X�R�[�h�␳�p�`�F�b�N�{�b�N�X

$htm['src_fix'] = '';

if (!$_conf['ktai']) {
    if ($_exconf['editor']['srcfix'] == 1 ||
        ($_exconf['editor']['srcfix'] == 2 && preg_match('/pc\d\.2ch\.net/', $host))
    ) {
        $htm['src_fix'] = '<label><input type="checkbox" name="fix_source" value="1">�\�[�X�R�[�h�␳</label>';
    }
}

// }}}
// {{{ ��^���E�A�N�e�B�u���i�[

$htm['options'] = '';
$htm['options_k'] = '';

if (!$_conf['ktai']) {
    if ($_exconf['editor']['constant'] || $_exconf['editor']['with_aMona']) {
        @include (P2EX_LIBRARY_DIR . '/post_options.inc.php');
    }
} else {
    if ($_exconf['editor']['constant']) {
        @include (P2EX_LIBRARY_DIR . '/post_options_k.inc.php');
    }
}

// }}}
// {{{ �������݃v���r���[

$htm['dpreview_onoff'] = '';
$htm['dpreview']  = '';
$htm['dpreview2'] = '';
$js['dp_startup'] = '';
$js['dp_setname'] = '';
$js['dp_setmail'] = '';
$js['dp_setmailsage'] = '';
$js['dp_setmsg'] = '';
$dp_name_at = '';
$dp_mail_at = '';
$dp_msg_at  = '';

if (!$_conf['ktai']) {
    if ($_exconf['editor']['dpreview']) {
        $dpreview_pos = ($_exconf['editor']['dpreview'] & 2) ? 'dpreview2' : 'dpreview';
        $htm[$dpreview_pos] = <<<EOP
<fieldset id="dpreview" style="display:none;">
<legend>Preview:</legend>
    <div>
        <span class="prvw_resnum">?</span>
        �F<span class="prvw_name"><b id="dp_name"></b><span id="dp_trip"></span></span>
        �F<span id="dp_mail" class="prvw_mail"></span>
        �F<span class="prvw_dateid"><span id="dp_date"></span> ID:<span id="dp_id">???</span></span>
    </div>
    <div id="dp_msg" class="prvw_msg"></div>
</fieldset>
EOP;
        if ($_exconf['editor']['dpreview'] & 4) {
            $js['dp_startup'] = " DPInit();document.getElementById('dpreview').style.display = 'block'";
        } else {
            $htm['dpreview_onoff'] = "<input type=\"button\" value=\"�v���r���[\" onclick=\"DPInit();showHide('dpreview');\">";
        }
        $js['dp_setname'] = 'DPSetName(this.value);';
        $js['dp_setmail'] = 'DPSetMail(this.value);';
        $js['dp_setmailsage'] = "DPSetMail(document.getElementById('mail').value);";
        $js['dp_setmsg']  = 'DPSetMsg(this.value);';

        $htm['sage_cb'] = <<<EOP
<input id="sage" type="checkbox" onclick="mailSage();{$js['dp_setmail']}"><label for="sage">sage</label>
EOP;

        $on_check_sage = '';
        $dp_name_at = " onkeyup=\"{$js['dp_setname']}\" onchange=\"{$js['dp_setname']}\"";
        $dp_mail_at = " onkeyup=\"{$js['dp_setmail']}\" onchange=\"checkSage();{$js['dp_setmail']}\"";
        $dp_msg_at  = " onkeyup=\"{$js['dp_setmsg']}\" onchange=\"{$js['dp_setmsg']}\"";
    }
}

// }}}
// {{{ ����Ƀ��X

$htm['orig_msg'] = '';
if ((basename($_SERVER['PHP_SELF']) == 'post_form.php' || !empty($_GET['inyou'])) && !empty($_GET['resnum'])) {
    $q_resnum = $_GET['resnum'];
    $hd['MESSAGE'] = "&gt;&gt;" . $q_resnum . "\r\n";
    if (!empty($_GET['inyou'])) {
        require_once (P2_LIBRARY_DIR . '/thread.class.php');
        require_once (P2_LIBRARY_DIR . '/threadread.class.php');
        $aThread = &new ThreadRead;
        $aThread->setThreadPathInfo($host, $bbs, $key);
        $aThread->readDat($aThread->keydat);
        $q_resar = $aThread->explodeDatLine($aThread->datlines[$q_resnum-1]);
        $q_resar = array_map('trim', $q_resar);
        $q_resar[3] = strip_tags($q_resar[3], '<br>');
        if ($_GET['inyou'] == 1 || $_GET['inyou'] == 3) {
            $hd['MESSAGE'] .= "&gt;";
            $hd['MESSAGE'] .= preg_replace("/ *<br> ?/","\r\n&gt;", $q_resar[3]);
            $hd['MESSAGE'] .= "\r\n";
        }
        if ($_GET['inyou'] == 2 || $_GET['inyou'] == 3) {
            $htm['orig_msg'] = <<<EOM
<fieldset id="original_msg">
<legend>Original Message:</legend>
    <div>
        <span class="prvw_resnum">{$q_resnum}</span>
        �F<b class="prvw_name">{$q_resar[0]}</b>
        �F<span class="prvw_mail">{$q_resar[1]}</span>
        �F<span class="prvw_dateid">{$q_resar[2]}</span>
    </div>
    <div class="prvw_msg">{$q_resar[3]}</div>
</fieldset>
EOM;
        }
    }
}

// }}}

?>