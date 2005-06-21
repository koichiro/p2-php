<?php
/* vim: set fileencoding=cp932 autoindent noexpandtab ts=4 sw=4 sts=0 fdm=marker: */
/* mi: charset=Shift_JIS */

// p2 �X���b�h�T�u�W�F�N�g�\���֐�
// for subject.php

/**
 * sb_print - �X���b�h�ꗗ��\������ (<tr>�`</tr>)
 */
function sb_print(&$aThreadList)
{
	global $_conf, $_exconf, $browser, $sb_view, $p2_setting, $STYLE;

	if (!$aThreadList->threads) {
		print "<tr><td>�@�Y���T�u�W�F�N�g�͂Ȃ�������</td></tr>";
		return;
	}

	// �ϐ�================================================
	$only_one_bool = false;
	$checkbox_bool = false;
	$ita_name_bool = false;

	// >>1 �\��
	if (($_conf['sb_show_one'] == 1) or ($_conf['sb_show_one'] == 2 and ereg("news", $aThreadList->bbs) || $aThreadList->bbs == 'bizplus')) {
		// spmode�͏���
		if (empty($aThreadList->spmode)) {
			$only_one_bool = true;
		}
	}

	// �`�F�b�N�{�b�N�X
	if ($aThreadList->spmode == 'taborn' || $aThreadList->spmode == 'soko') {
		$checkbox_bool = true;
	}

	// ��
	if ($aThreadList->spmode && $aThreadList->spmode != 'taborn' && $aThreadList->spmode != 'soko') {
		$ita_name_bool = true;
	}

	$norefresh_q = '&amp;norefresh=true';

	// �\�[�g==================================================

	// ���݂̃\�[�g�`����class�w���CSS�J���[�����O ======================
	$class_sort_midoku = '';
	$class_sort_res = '';
	$class_sort_no = '';
	$class_sort_title = '';
	$class_sort_ita = '';
	$class_sort_spd = '';
	$class_sort_bd = '';
	$class_sort_fav = '';
	$class_sort_ikioi = '';
	if ($GLOBALS['now_sort']) {
		${'class_sort_'.$GLOBALS['now_sort']} = ' class="now_sort"';
	}

	$sortq_spmode = '';
	$sortq_host = '';
	$sortq_ita = '';
	// spmode��
	if ($aThreadList->spmode) {
		$sortq_spmode = "&amp;spmode={$aThreadList->spmode}";
	}
	// spmode�łȂ��A�܂��́Aspmode�����ځ[�� or dat�q�ɂȂ�
	if (!$aThreadList->spmode || $aThreadList->spmode == "taborn" || $aThreadList->spmode == "soko") { 
		$sortq_host = "&amp;host={$aThreadList->host}";
		$sortq_ita = "&amp;bbs={$aThreadList->bbs}";
	}

	$midoku_sort_ht = "<td class=\"tu\" nowrap><a{$class_sort_midoku} href=\"{$_conf['subject_php']}?sort=midoku{$sortq_spmode}{$sortq_host}{$sortq_ita}{$norefresh_q}\" target=\"_self\">�V��</a></td>";

	//=====================================================
	// �e�[�u���w�b�_
	//=====================================================
	echo "<tr class=\"tableheader\">\n";

	//����
	if ($sb_view == 'edit') { echo "<td class=\"te\">&nbsp;</td>"; }
	//�V��
	if ($sb_view != 'edit') { echo $midoku_sort_ht; }
	// ���X��
	if ($sb_view != 'edit') {
		echo "<td class=\"tn\" nowrap><a{$class_sort_res} href=\"{$_conf['subject_php']}?sort=res{$sortq_spmode}{$sortq_host}{$sortq_ita}{$norefresh_q}\" target=\"_self\">���X</a></td>";
	}
	// >>1
	if ($only_one_bool) { echo "<td class=\"t\">&nbsp;</td>"; }
	//�`�F�b�N�{�b�N�X
	if ($checkbox_bool) {
		echo "<td class=\"tc\"><input id=\"allbox\" name=\"allbox\" type=\"checkbox\" onclick=\"checkAll();\" title=\"���ׂĂ̍��ڂ�I���A�܂��͑I������\"></td>";
	}
	// No.
	echo "<td class=\"to\"><a{$class_sort_no} href=\"{$_conf['subject_php']}?sort=no{$sortq_spmode}{$sortq_host}{$sortq_ita}{$norefresh_q}\" target=\"_self\">No.</a></td>";
	// �^�C�g��
	echo "<td class=\"tl\"><a{$class_sort_title} href=\"{$_conf['subject_php']}?sort=title{$sortq_spmode}{$sortq_host}{$sortq_ita}{$norefresh_q}\" target=\"_self\">�^�C�g��</a></td>";
	// ��
	if ($ita_name_bool) {
		echo "<td class=\"t\"><a{$class_sort_ita} href=\"{$_conf['subject_php']}?sort=ita{$sortq_spmode}{$sortq_host}{$sortq_ita}{$norefresh_q}\" target=\"_self\">��</a></td>";
	}
	// ���΂₳
	if ($_conf['sb_show_spd']) {
		echo "<td class=\"ts\"><a{$class_sort_spd} href=\"{$_conf['subject_php']}?sort=spd{$sortq_spmode}{$sortq_host}{$sortq_ita}{$norefresh_q}\" target=\"_self\">���΂₳</a></td>";
	}
	// ����
	if ($_conf['sb_show_ikioi']) {
		echo "<td class=\"ti\"><a{$class_sort_ikioi} href=\"{$_conf['subject_php']}?sort=ikioi{$sortq_spmode}{$sortq_host}{$sortq_ita}{$norefresh_q}\" target=\"_self\">����</a></td>";
	}
	// Birthday
	if ($_exconf['status']['sb_show_datsize']) {
		echo "<td class=\"ti\">KB</td>";
	}else{
		echo "<td class=\"t\"><a{$class_sort_bd} href=\"{$_conf['subject_php']}?sort=bd{$sortq_spmode}{$sortq_host}{$sortq_ita}{$norefresh_q}\" target=\"_self\">Birthday</a></td>";
	}
	//���C�ɓ���
	if ($_conf['sb_show_fav'] && $aThreadList->spmode != 'taborn') {
		echo "<td class=\"t\"><a{$class_sort_fav} href=\"{$_conf['subject_php']}?sort=fav{$sortq_spmode}{$sortq_host}{$sortq_ita}{$norefresh_q}\" target=\"_self\" title=\"���C�ɃX��\">��</a></td>";
	}

	echo "\n</tr>\n";

	//=====================================================
	//�e�[�u���{�f�B
	//=====================================================

	//spmode������΃N�G���[�ǉ�
	$spmode_q = $aThreadList->spmode ? "&amp;spmode={$aThreadList->spmode}" : "";

	$time = time();
	$i = 0;
	foreach ($aThreadList->threads as $aThread) {
		$i++;
		$midoku_ari = '';
		$anum_ht = ""; //#r1

		$bbs_q = "&amp;bbs=".$aThread->bbs;
		$key_q = "&amp;key=".$aThread->key;

		if ($aThreadList->spmode != 'taborn') {
			if (!$aThread->torder) { $aThread->torder = $i; }
		}

		//td�� css�N���X
		if (($i % 2) == 0) {
			$class_t = ' class="t"';	// ��{
			$class_te = ' class="te"';	// ���ёւ�
			$class_tu = ' class="tu"';	// �V�����X��
			$class_tn = ' class="tn"';	// ���X��
			$class_tc = ' class="tc"';	// �`�F�b�N�{�b�N�X
			$class_to = ' class="to"';	// �I�[�_�[�ԍ�
			$class_tl = ' class="tl"';	// �^�C�g��
			$class_ti = ' class="ti"';	// ����
			$class_ts = ' class="ts"';	// ���΂₳
		} else {
			$class_t = ' class="t2"';
			$class_te = ' class="te2"';
			$class_tu = ' class="tu2"';
			$class_tn = ' class="tn2"';
			$class_tc = ' class="tc2"';
			$class_to = ' class="to2"';
			$class_tl = ' class="tl2"';
			$class_ti = ' class="ti2"';
			$class_ts = ' class="ts2"';
		}

		//�V�����X�� =============================================
		$unum_ht_c = '&nbsp;';
		// �����ς�
		if ($aThread->isKitoku()) {
			$unum_ht_c = "<a class=\"un\" href=\"{$_conf['subject_php']}?host={$aThread->host}{$bbs_q}{$key_q}{$spmode_q}&amp;dele=true\" target=\"_self\" onclick=\"return OpenSubWin('info.php?host={$aThread->host}{$bbs_q}{$key_q}&amp;popup=2&amp;dele=true',{$STYLE['info_pop_size']},0,0)\">{$aThread->unum}</a>";

			$anum = $aThread->rescount - $aThread->unum + 1 - $_conf['respointer'];
			if ($anum > $aThread->rescount) { $anum = $aThread->rescount; }
			$anum_ht = "#r".$anum;

			// �V������
			if ($aThread->unum > 0) {
				$midoku_ari = true;
				$unum_ht_c = "<a id=\"un{$i}\" class=\"un_a\" href=\"{$_conf['subject_php']}?host={$aThread->host}{$bbs_q}{$key_q}{$spmode_q}&amp;dele=true\" target=\"_self\" onclick=\"return OpenSubWin('info.php?host={$aThread->host}{$bbs_q}{$key_q}&amp;popup=2&amp;dele=true',{$STYLE['info_pop_size']},0,0)\">$aThread->unum</a>";
			}

			// subject.txt�ɂȂ���
			if (!$aThread->isonline) {
				// JavaScript�ł̊m�F�_�C�A���O����
				$unum_ht_c = "<a class=\"un_n\" href=\"{$_conf['subject_php']}?host={$aThread->host}{$bbs_q}{$key_q}{$spmode_q}&amp;dele=true\" target=\"_self\" onClick=\"if (!window.confirm('���O���폜���܂����H')) {return false;} return OpenSubWin('info.php?host={$aThread->host}{$bbs_q}{$key_q}&amp;popup=2&amp;dele=true',{$STYLE['info_pop_size']},0,0)\">-</a>";
			}

		}

		$unum_ht = "<td{$class_tu}>".$unum_ht_c."</td>";

		// �����X�� =============================================
		$rescount_ht = "<td{$class_tn}>{$aThread->rescount}</td>";

		// �� ============================================
		$ita_ht = '';
		if ($ita_name_bool) {
			$ita_name = !empty($aThread->itaj) ? $aThread->itaj : $aThread->bbs;
			$htm['ita_td'] = "<td{$class_t} nowrap><a href=\"{$_conf['subject_php']}?host={$aThread->host}{$bbs_q}\" target=\"_self\">".htmlspecialchars($ita_name)."</a></td>";
		}


		// ���C�ɓ��� ========================================
		$fav_ht = '';
		if ($_conf['sb_show_fav']) {
			//$favmark =  ($aThread->fav) ? '��' : '+';
			//$favdo = ($aThread->fav) ? 0; : 1;
			if ($aThreadList->spmode != 'taborn') {
				if ($aThread->fav) {
					$fav_ht = "<td$class_t><a class=\"fav\" href=\"{$_conf['subject_php']}?host={$aThread->host}{$bbs_q}{$key_q}{$spmode_q}{$norefresh_q}&amp;setfav=0\" target=\"_self\">��</a></td>";
				} else {
					$fav_ht = "<td{$class_t}><a class=\"fav\" href=\"{$_conf['subject_php']}?host={$aThread->host}{$bbs_q}{$key_q}{$spmode_q}{$norefresh_q}&amp;setfav=1\" target=\"_self\">+</a></td>";
				}
			}
		}

		// torder(info) =================================================
		// ���C�ɃX��
		if ($aThread->fav) {
			$torder_st = "<b>{$aThread->torder}</b>";
		} else {
			$torder_st = $aThread->torder;
		}
		$torder_ht = "<a id=\"to{$i}\" class=\"info\" href=\"info.php?host={$aThread->host}{$bbs_q}{$key_q}\" target=\"_self\" onclick=\"return OpenSubWin('info.php?host={$aThread->host}{$bbs_q}{$key_q}&amp;popup=1',{$STYLE['info_pop_size']},0,0)\">{$torder_st}</a>";

		// title =================================================
		$chUnColor_ht = '';

		$rescount_q = "&amp;rc=".$aThread->rescount;
		$offline_q = '';

		// dat�q�� or �a���Ȃ�
		if ($aThreadList->spmode == 'soko' || $aThreadList->spmode == 'palace') {
			$rescount_q = '';
			$offline_q = '&amp;offline=true';
			$anum_ht = '';
		}

		// �^�C�g�����擾�Ȃ�
		if (!$aThread->ttitle_ht) {
			$aThread->ttitle_ht = "http://{$aThread->host}/test/read.cgi/{$aThread->bbs}/{$aThread->key}/";
		}

		// ���X��
		$moto_thre_ht = '';
		if ($_conf['sb_show_motothre']) {
			if (!$aThread->isKitoku()) {
				$moto_thre_ht = '<a class="thre_title" href="'.$aThread->getMotoThread().'">�E</a> ';
			}
		}

		// �V�K�X��
		if ($aThread->new) {
			$classtitle_q = ' class="thre_title_new"';
		} else {
			$classtitle_q = ' class="thre_title"';
		}

		$thre_url = "{$_conf['read_php']}?host={$aThread->host}{$bbs_q}{$key_q}{$rescount_q}{$offline_q}{$anum_ht}";

		if ($midoku_ari) {
			$chUnColor_ht = "chUnColor('{$i}');";
		}
		$change_color = " onclick=\"chTtColor('{$i}');{$chUnColor_ht}\"";

		// �I�����[>>1 =============================================
		$one_ht = '';
		if ($only_one_bool) {
			$one_ht = "<td{$class_t}><a href=\"{$_conf['read_php']}?host={$aThread->host}{$bbs_q}{$key_q}&amp;one=true\">&gt;&gt;1</a></td>";
		}

		// �`�F�b�N�{�b�N�X =============================================
		$checkbox_ht = '';
		if ($checkbox_bool) {
			if ($aThreadList->spmode == 'taborn') {
				if (!$aThread->isonline) { $checked_ht = ' checked'; } // or ($aThread->rescount >= 1000)
			}
			$checkbox_ht = "<td{$class_tc}><input name=\"checkedkeys[]\" type=\"checkbox\" value=\"{$aThread->key}\"$checked_ht></td>";
		}

		// ���� =============================================
		$edit_ht = '';
		if ($sb_view == 'edit') {
			$unum_ht = '';
			$rescount_ht = '';
			$sb_view_q = '&amp;sb_view=edit';
			if ($aThreadList->spmode == 'fav') {
				$setkey = 'setfav';
			} elseif ($aThreadList->spmode == 'palace') {
				$setkey = 'setpal';
			}
			$narabikae_a = "{$_conf['subject_php']}?host={$aThread->host}{$bbs_q}{$key_q}{$spmode_q}{$sb_view_q}";

			$edit_ht = <<<EOP
		<td{$class_te}>
			<a class="te" href="{$narabikae_a}&amp;{$setkey}=top" target="_self">��</a>
			<a class="te" href="{$narabikae_a}&amp;{$setkey}=up" target="_self">��</a>
			<a class="te" href="{$narabikae_a}&amp;{$setkey}=down" target="_self">��</a>
			<a class="te" href="{$narabikae_a}&amp;{$setkey}=bottom" target="_self">��</a>
		</td>
EOP;
		}

		// ���΂₳�i�� ����/���X �� ���X�Ԋu�j
		$spd_ht = '';
		if ($_conf['sb_show_spd']) {
			if ($spd_st = $aThread->getTimePerRes()) {
				$spd_ht = "<td{$class_ts}>{$spd_st}</td>";
			}
		}

		// ����
		$ikioi_ht = '';
		if ($_conf['sb_show_ikioi']) {
			if ($aThread->dayres > 0) {
				// 0.0 �ƂȂ�Ȃ��悤�ɏ����_��2�ʂŐ؂�グ
				$dayres = ceil($aThread->dayres * 10) / 10;
				$dayres_st = sprintf('%01.1f', $dayres);
			} else {
				$dayres_st = '-';
			}
			$ikioi_ht = "<td{$class_ti}>".$dayres_st."</td>";
		}

		if($_exconf['status']['sb_show_datsize']){
			//�X���T�C�Y
			require_once(P2EX_LIBRARY_DIR . '/status/datsize.inc.php');
			$thread_size = getthread_dir( $aThread->host, $aThread->bbs, $aThread->key);
			$thread_size_ht = "<td{$class_ti}>{$thread_size}</td>";
		}else{
			// Birthday
			$birthday = date('y/m/d', $aThread->key); // (y/m/d H:i)
			$birth_ht = "<td{$class_t}>{$birthday}</td>";
		}

		//====================================================================================
		// �X���b�h�ꗗ table �{�f�B HTML�v�����g <tr></tr>
		//====================================================================================

		// �{�f�B
		echo "				<tr>
					{$edit_ht}
					{$unum_ht}
					{$rescount_ht}
					{$one_ht}
					{$checkbox_ht}
					<td{$class_to}>{$torder_ht}</td>
					<td{$class_tl} nowrap>{$moto_thre_ht}<a id=\"tt{$i}\" href=\"{$thre_url}\"{$classtitle_q}{$change_color}>{$aThread->ttitle_ht}</a></td>
					{$htm['ita_td']}
					{$spd_ht}
					{$ikioi_ht}
					{$birth_ht}
					{$thread_size_ht}
					{$fav_ht}
				</tr>\n";

	}

}

?>