<?php
/* vim: set fileencoding=cp932 autoindent noexpandtab ts=4 sw=4 sts=0 fdm=marker: */
/* mi: charset=Shift_JIS */
/*
	p2 -  �a������֌W�̏���
*/

require_once (P2_LIBRARY_DIR . '/p2util.class.php');
require_once (P2_LIBRARY_DIR . '/filectl.class.php');

/**
 * �X����a������ɃZ�b�g����
 *
 * $set �́A0(����), 1(�ǉ�), top, up, down, bottom
 */
function setPal($host, $bbs, $key, $setpal)
{
	global $_conf;

	//==================================================================
	// key.idx ��ǂݍ���
	//==================================================================
	// idxfile�̃p�X�����߂�
	$datdir_host = P2Util::datdirOfHost($host);
	$idxfile = $datdir_host.'/'.$bbs.'/'.$key.'.idx';

	// ����idx�f�[�^������Ȃ�ǂݍ���
	if (is_readable($idxfile) && ($lines = @file($idxfile))) {
		$l = rtrim($lines[0]);
		$data = explode('<>', $l);
		$c = count($data);
		if ($c < 10) {
			while ($c < 10) {
				$data[] = '';
				$c++;
			};
		} elseif ($c > 10) {
			$data = array_slice($data, 0, 10);
		}
		unset($c);
	} else {
		$data = array_fill(0, 10, '');
	}

	//==================================================================
	// p2_palace.idx�ɏ�������
	//==================================================================
	$palace_idx = $_conf['pref_dir']. '/p2_palace.idx';

	//================================================
	// �ǂݍ���
	//================================================

	// p2_palace �t�@�C�����Ȃ���ΐ���
	FileCtl::make_datafile($palace_idx, $_conf['palace_perm']);

	//palace_idx�ǂݍ���;
	$pallines = @file($palace_idx);

	//================================================
	// ����
	//================================================
	// �ŏ��ɏd���v�f���폜���Ă���
	if (!empty($pallines)) {
		$i = -1;
		$neolines = array();
		foreach ($pallines as $l) {
			$i++;
			$l = rtrim($l);
			$lar = explode('<>', $l);
			// �d�����
			if ($lar[1] == $key) {
				$before_line_num = $i;	// �ړ��O�̍s�ԍ����Z�b�g
				continue;
			// key�̂Ȃ����͕̂s���f�[�^�Ȃ̂ŃX�L�b�v
			} elseif (!$lar[1]) {
				continue;
			} else {
				$neolines[] = $l;
			}
		}
	}

	// �V�K�f�[�^�ݒ�
	if ($setpal) {
		$newdata = $data;
		$newdata[1] = $key;
		$newdata[10] = $host;
		$newdata[11] = $bbs;
		$newline = implode('<>', $newdata) . "\n";
	}

	if ($setpal == 1 or $setpal == 'top') {
		$after_line_num = 0;	// �ړ���̍s�ԍ�

	} elseif ($setpal == 'up') {
		$after_line_num = $before_line_num-1;
		if ($after_line_num < 0) { $after_line_num = 0; }

	} elseif ($setpal == 'down') {
		$after_line_num = $before_line_num+1;
		if ($after_line_num >= sizeof($neolines)) { $after_line_num = 'bottom'; }

	} elseif ($setpal == 'bottom') {
		$after_line_num = 'bottom';

	} else {
		$after_line_num = null;
	}

	//================================================
	//��������
	//================================================
	$fp = @fopen($palace_idx, 'wb') or die("Error: {$palace_idx} ���X�V�ł��܂���ł���");
	@flock($fp, LOCK_EX);
	if ($neolines) {
		$i = 0;
		foreach ($neolines as $l) {
			if ($i === $after_line_num) {
				fputs($fp, $newline);
			}
			fputs($fp, $l."\n");
			$i++;
		}
		if ($after_line_num === 'bottom') {
			fputs($fp, $newline);
		}
		//�u$after_line_num == 'bottom'�v���ƌ듮�삷��B
	} else {
		fputs($fp, $newline);
	}
	@flock($fp, LOCK_UN);
	fclose($fp);

	return true;
}

?>