<?php
/* vim: set fileencoding=cp932 ai et ts=4 sw=4 sts=0 fdm=marker: */
/* mi: charset=Shift_JIS */

// p2 -  �T�u�W�F�N�g -  �c�[���o�[�\���i�g�сj
// for subject.php

$matome_accesskey_at = '';
$matome_accesskey_navi = '';

// �V���܂Ƃߓǂ� =========================================
if ($upper_toolbar_done) {
    $matome_accesskey_at = " {$_conf['accesskey']}=\"{$_conf['k_accesskey']['matome']}\"";
    $matome_accesskey_navi = "{$_conf['k_accesskey']['matome']}.";
}

// �q�ɂłȂ����
if ($aThreadList->spmode != 'soko') {
    if ($shinchaku_attayo) {
        $shinchaku_matome_ht =<<<EOP
<a href="{$_conf['read_new_k_php']}?host={$aThreadList->host}&amp;bbs={$aThreadList->bbs}&amp;spmode={$aThreadList->spmode}{$norefresh_q}&amp;nt={$newtime}"{$matome_accesskey_at}>{$matome_accesskey_navi}�V�܂Ƃ�({$shinchaku_num})</a>
EOP;
        if ($shinokini_attayo) {
            $shinchaku_matome_ht .= <<<EOP
 <a href="{$_conf['read_new_k_php']}?host={$aThreadList->host}&amp;bbs={$aThreadList->bbs}&amp;spmode={$aThreadList->spmode}{$norefresh_q}&amp;nt={$newtime}&amp;onlyfav=1">��{$shinokini_num}</a>
EOP;
        }
    } else {
        $shinchaku_matome_ht =<<<EOP
<a href="{$_conf['read_new_k_php']}?host={$aThreadList->host}&amp;bbs={$aThreadList->bbs}&amp;spmode={$aThreadList->spmode}&amp;nt={$newtime}"{$matome_accesskey_at}>{$matome_accesskey_navi}�V�܂Ƃ�</a>
EOP;
    }
}

// �v�����g ==============================================
echo "<p>{$ptitle_ht} {$shinchaku_matome_ht}</p>\n";

// ��ϐ� ==============================================
$upper_toolbar_done = true;

?>