<?php
/* vim: set fileencoding=cp932 ai et ts=4 sw=4 sts=0 fdm=marker: */
/* mi: charset=Shift_JIS */

/**
 * �t�@�C���𑀍삷��N���X
 * �C���X�^���X����炸�ɃN���X���\�b�h�ŗ��p����
 */
class FileCtl{

    //===============================================
    // �������ݗp�̃t�@�C�����Ȃ���ΐ������ăp�[�~�b�V�����𒲐�����֐�
    //===============================================
    function make_datafile($file, $perm = 0606)
    {
        // �O�̂��߂Ƀf�t�H���g�␳���Ă���
        if (empty($perm)) {
            $perm = 0606;
        }

        if (!file_exists($file)) {
            // �e�f�B���N�g����������΍��
            FileCtl::mkdir_for($file) or die("Error: cannot make parent dirs. ( $file )");
            touch($file) or die("Error: cannot touch. ( $file )");
            chmod($file, $perm);
        } elseif (!is_writable($file)) {
            $file = realpath($file);
            $dir = dirname($file);
            if (!is_writable($dir)) {
                die("Error: cannot write. ( $dir )");
            }
            $cont = file_get_contents($file);
            if (FileCtl::file_write_contents($file, $cont) === FALSE) {
                die("Error: cannot write. ( $file )");
            }
            chmod($file, $perm);
        }
        return true;
    }

    /**
     * �e�f�B���N�g�����Ȃ���ΐ������ăp�[�~�b�V�����𒲐�����֐�
     */
    function mkdir_for($apath, $i = 1)
    {
        global $_conf;

        $dir_limit = 50;	// �e�K�w����鐧����

        $perm = (!empty($_conf['data_dir_perm'])) ? $_conf['data_dir_perm'] : 0707;

        if (!$parentdir = dirname($apath)) {
            die("Error: cannot mkdir. ( {$parentdir} )<br>�e�f�B���N�g�����󔒂ł��B");
        }
        if (!is_dir($parentdir)) {
            if ($i > $dir_limit) {
                die("Error: cannot mkdir. ( {$parentdir} )<br>�K�w���オ��߂����̂ŁA�X�g�b�v���܂����B");
            }
            FileCtl::mkdir_for($parentdir, ++$i);
            mkdir($parentdir, $perm) or die("Error: cannot mkdir. ( {$parentdir} )");
            chmod($parentdir, $perm);
        }
        return true;
    }

    /**
     * gz�t�@�C���̒��g���擾����
     */
    function get_gzfile_contents($filepath){
        if (is_readable($filepath)) {
            ob_start();
            readgzfile($filepath);
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        } else {
            return false;
        }
    }

    /**
     * ��������t�@�C���ɏ�������
     * �iPHP5��file_put_contents�̑�֓I�����j
     *
     * ����function�́APHP License �Ɋ�Â��AAidan Lister�� <aidan@php.net> �ɂ��A
     * PHP_Compat �� file_put_contents.php �̃R�[�h�����ɁA�Ǝ��̕ύX�iflock() �Ȃǁj�����������̂ł��B
     * "This product includes PHP, freely available from <http://www.php.net/>".
     */
    function file_write_contents($filename, &$cont, $flags = null, $resource_context = null)
    {
        // If $cont is an array, convert it to a string
        if (is_array($cont)) {
            $content = implode('', $cont);
        } else {
            $content =& $cont;
        }
        // If we don't have a string, throw an error
        if (!is_string($content)) {
            trigger_error('file_write_contents() The 2nd parameter should be either a string or an array', E_USER_WARNING);
            return false;
        }

        // Get the length of date to write
        $length = strlen($content);

        // Check what mode we are using
        $mode = ($flags & FILE_APPEND) ?
                    $mode = 'a' :
                    $mode = 'w';

        // Check if we're using the include path
        $use_inc_path = ($flags & FILE_USE_INCLUDE_PATH) ?
                    true :
                    false;

        // Open the file for writing
        if (($fh = @fopen($filename, $mode, $use_inc_path)) === false) {
            trigger_error('file_write_contents() failed to open stream: Permission denied', E_USER_WARNING);
            return false;
        }

        @flock($fh, LOCK_EX);

        // Write to the file
        $bytes = 0;
        if (($bytes = @fwrite($fh, $content)) === false) {
            $errormsg = sprintf('file_write_contents() Failed to write %d bytes to %s',
                            $length,
                            $filename);
            trigger_error($errormsg, E_USER_WARNING);
            return false;
        }

        @flock($fh, LOCK_UN);
        fclose($fh);

        if ($bytes != $length) {
            $errormsg = sprintf('file_put_contents() Only %d of %d bytes written, possibly out of free disk space.',
                            $bytes,
                            $length);
            trigger_error($errormsg, E_USER_WARNING);
            return false;
        }

        return $bytes;
   }

}

?>