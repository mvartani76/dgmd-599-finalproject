<?php

namespace App\Validation\Providers;


use Cake\Utility\Hash;
use FFMpeg\FFProbe;

class VideoImage {

    protected $imageTypes = [
        'bmp'	=>    'image/bmp',
        'gif'	=>    'image/gif',
        'jpe'	=>    'image/jpeg',
        'jpeg'	=>    'image/jpeg',
        'jpg'	=>    'image/jpeg',
        'jfif'	=>    'image/pipeg',
        'png'   =>    'image/png',
        'svg'	=>    'image/svg+xml',
        'tif'	=>    'image/tiff',
        'tiff'	=>    'image/tiff',
        'ico'	=>    'image/x-icon',
        'pnm'	=>    'image/x-portable-anymap',
        'pbm'	=>    'image/x-portable-bitmap',
        'pgm'	=>    'image/x-portable-graymap',
        'rgb'	=>    'image/x-rgb'
    ];

    protected $videoTypes = [
        'mp2'	 => 'video/mpeg',
        'mpa'	 => 'video/mpeg',
        'mpe'	 => 'video/mpeg',
        'mp4'	 => 'video/mp4',
        'mpeg'	 => 'video/mpeg',
        'mpg'	 => 'video/mpeg',
        'mpv2'	 => 'video/mpeg',
        'mov'	 => 'video/quicktime',
        'qt'	 => 'video/quicktime',
        'lsf'	 => 'video/x-la-asf',
        'lsx'	 => 'video/x-la-asf',
        'asf'	 => 'video/x-ms-asf',
        'asr'	 => 'video/x-ms-asf',
        'asx'	 => 'video/x-ms-asf',
        'avi'	 => 'video/x-msvideo',
        'movie'	 => 'video/x-sgi-movie'
    ];

    protected $vectorTypes = [
        'ai'    => 'application/illustrator',
        'svg'   => 'image/svg+xml'
    ];

    public $data;


    /*public function __construct($data = null) {
        if ($data !== null)
            $this->data =& $data;

    }*/

    public function rightAspectRatio($chk, $ratio) {

        if ($this->isImage($chk)) {
            list($w, $h) = getimagesize($chk['tmp_name']);
            return ($ratio < ($w / $h));
        } elseif ($this->isVideo($chk)) {
            return true;
        }

        return false;
    }


    public function isVideoOrImage($chk) {

       /* if ($chk != null && $this->data === null) {
            $this->data = $chk;
        }*/

        return ($this->validateExtensionMime($chk) && ($this->isVideo($chk) || $this->isImage($chk)));
    }


    public function getMime2($in) {
        $db = finfo_open( FILEINFO_MIME_TYPE );
        $mt = finfo_file( $db, $in );
        finfo_close( $db );
        return $mt;
    }

    public function getMime($in) {
        $mt = trim( shell_exec( 'file -bi ' . escapeshellarg( $in ) ) );
        if( strpos( $mt, ';' ) !== false ) {
            $bits = explode( ';', $mt );
            $mt = $bits[ 0 ];
        }
        return trim( $mt );
    }

    public function validateExtensionMime($chk) {

        $filename = $chk['name'];
        $path     = $chk['tmp_name'];

        $exp = explode('.', $filename);
        $end = end($exp);
        $ext = strtolower($end);
        $mt  = $this->getMime($path);

        if ($mt === 'application/octet-stream') {
            $mt = $chk['type'];
        }

        if (!empty($this->imageTypes[$ext])) {
            if ($this->imageTypes[$ext] === $mt) {
                return true;
            } else {
                return false;
            }
        } elseif (!empty($this->videoTypes[$ext])) {
            if ($this->videoTypes[$ext] === $mt) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isVideo($chk) {

        $d = $this->getMime($chk['tmp_name']);

        if (array_search($d, $this->videoTypes)) {
            return true;
        } else {
            return false;
        }
    }

    public function isImage($chk) {

        $d = $this->getMime($chk['tmp_name']);

        if (array_search($d, $this->imageTypes)) {
            return true;
        } else {
            return false;
        }
    }

    public function isUnderPhpSizeLimit($chk)
    {
        return Hash::get($chk, 'error') !== UPLOAD_ERR_INI_SIZE;
    }

    public function isUnderFormSizeLimit($chk)
    {
        return Hash::get($chk, 'error') !== UPLOAD_ERR_FORM_SIZE;
    }

    public function isCompletedUpload($chk)
    {
        return Hash::get($chk, 'error') !== UPLOAD_ERR_PARTIAL;
    }

    public function isFileUpload($chk)
    {
        return Hash::get($chk, 'error') !== UPLOAD_ERR_NO_FILE;
    }

    public function isSuccessfulWrite($chk)
    {
        return Hash::get($chk, 'error') !== UPLOAD_ERR_CANT_WRITE;
    }

    public function isBelowMaxSize($chk, $size)
    {
        // Non-file uploads also mean the size is too small
        if (!isset($chk['size']) || !strlen($chk['size'])) {
            return false;
        }
        return $chk['size'] <= $size;
    }

    public function isAboveMinHeight($chk, $height)
    {
        if ($this->isVideo($chk)) {
            return $this->checkVideoHeight($chk, $height);
        }

        // Non-file uploads also mean the height is too big
        if (!isset($chk['tmp_name']) || !strlen($chk['tmp_name'])) {
            return false;
        }
        list(, $imgHeight) = getimagesize($chk['tmp_name']);
        return $height > 0 && $imgHeight >= $height;
    }

    public function isBelowMaxHeight($chk, $height)
    {
        // Non-file uploads also mean the height is too big
        if (!isset($chk['tmp_name']) || !strlen($chk['tmp_name'])) {
            return false;
        }
        list(, $imgHeight) = getimagesize($chk['tmp_name']);
        return $height > 0 && $imgHeight <= $height;
    }

    public function isBelowMaxWidth($chk, $width)
    {
        // Non-file uploads also mean the height is too big
        if (!isset($chk['tmp_name']) || !strlen($chk['tmp_name'])) {
            return false;
        }
        list($imgWidth) = getimagesize($chk['tmp_name']);
        return $width > 0 && $imgWidth <= $width;
    }

    public function isAboveMinWidth($chk, $width)
    {
        if ($this->isVideo($chk)) {
            return $this->checkVideoWidth($chk, $width);
        }
        // Non-file uploads also mean the height is too big
        if (!isset($chk['tmp_name']) || !strlen($chk['tmp_name'])) {
            return false;
        }
        list($imgWidth) = getimagesize($chk['tmp_name']);
        return $width > 0 && $imgWidth >= $width;
    }

    public function isSquare($chk) {
        $imgSize = getimagesize($chk['tmp_name']);

        return $imgSize[0] === $imgSize[1];
    }

    private function getDimensions($chk) {

        $output = array_filter(explode(PHP_EOL, shell_exec('ffprobe -v error -show_entries stream=width,height -of default=noprint_wrappers=1 ' .$chk['tmp_name'])));
        return ['width' => str_replace('width=','', $output[0]), 'height' => str_replace('height=','', $output[1])];
    }
    public function checkVideoWidth($chk, $width) {
        $dim = $this->getDimensions($chk);
        return ($dim['width'] > $width);
    }

    public function checkVideoHeight($chk, $height) {
        $dim = $this->getDimensions($chk);
        return ($dim['height'] > $height);
    }



}