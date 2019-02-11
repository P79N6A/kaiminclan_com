<?php
class AttachmentImageService extends  Service {

    //原图
    private $imgPath;   //图片地址
    private $width;     //图片宽度
    private $height;    //图片高度
    private $type;      //图片类型
    private $img;       //图片(图像流)

    //缩略图
    private $newImg;    //缩略图(图像流)
    private $newWidth;
    private $newHeight;

    //水印图路径
    private $waterMarkPath;

    //输出图像质量,jpg有效
    private $quality;
    /**
     * Image constructor.
     * @param string $imagePath 图片路径
     * @param string $markPath 水印图片路径
     * @param int $new_width 缩略图宽度
     * @param int $new_height 缩略图高度
     * @param int $quality JPG图片格输出质量
     */
    public function init(string $imagePath,
                         string $markPath = null,
                         int $new_width = null,
                         int $new_height = null,
                         int $quality = 75)
    {
        $this->imgPath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;
        $this->waterMarkPath = $markPath;
        $this->newWidth = $new_width ? $new_width : $this->width;
        $this->newHeight = $new_height ? $new_height : $this->height;
        $this->quality = $quality ? $quality : 75;

        list($this->width, $this->height, $this->type) = getimagesize($this->imgPath);
        $this->img = $this->_loadImg($this->imgPath, $this->type);


        //生成缩略图
        $this->_thumb();
        //添加水印图片
        if (!empty($this->waterMarkPath)) $this->_addWaterMark();
        //输出图片
        $this->_outputImg();
    }

    /**
     *图片输出
     */
    private function _outputImg()
    {
        switch ($this->type) {
            case 1: // GIF
                imagegif($this->newImg, $this->imgPath);
                break;
            case 2: // JPG
                if (intval($this->quality) < 0 || intval($this->quality) > 100) $this->quality = 75;
                imagejpeg($this->newImg, $this->imgPath, $this->quality);
                break;
            case 3: // PNG
                imagepng($this->newImg, $this->imgPath);
                break;
        }
        imagedestroy($this->newImg);
        imagedestroy($this->img);
    }

    /**
     * 添加水印
     */
    private function _addWaterMark()
    {
        $ratio = 1 / 5; //水印缩放比率

        $Width = imagesx($this->newImg);
        $Height = imagesy($this->newImg);

        $n_width = $Width * $ratio;
        $n_height = $Width * $ratio;

        list($markWidth, $markHeight, $markType) = getimagesize($this->waterMarkPath);

        if ($n_width > $markWidth) $n_width = $markWidth;
        if ($n_height > $markHeight) $n_height = $markHeight;

        $Img = $this->_loadImg($this->waterMarkPath, $markType);
        $Img = $this->_thumb1($Img, $markWidth, $markHeight, $markType, $n_width, $n_height);
        $markWidth = imagesx($Img);
        $markHeight = imagesy($Img);
        imagecopyresampled($this->newImg, $Img, $Width - $markWidth - 10, $Height - $markHeight - 10, 0, 0, $markWidth, $markHeight, $markWidth, $markHeight);
        imagedestroy($Img);
    }

    /**
     * 缩略图(按等比例,根据设置的宽度和高度进行裁剪)
     */
    private function _thumb($isTailor = 1)
    {

        $isTailor = intval($isTailor);
        //如果原图本身小于缩略图，按原图长高
        if ($this->newWidth > $this->width) $this->newWidth = $this->width;
        if ($this->newHeight > $this->height) $this->newHeight = $this->height;

        //背景图长高
        $gd_width = $this->newWidth;
        $gd_height = $this->newHeight;

        if(!$isTailor){
            if($this->width > $this->height){
                //宽优先
                $this->newWidth = $this->newWidth;
                $this->newHeight = $this->newHeight*($this->newHeight/$this->width);
                $this->newHeight = round($this->newHeight,0);
            }else{
                //高优先
                $this->newWidth = $this->width*($this->newHeight/$this->width);
                $this->newWidth = round($this->newWidth,0);
                $this->newHeight = $this->newHeight;
            }
        }else{
            //如果缩略图宽高，其中有一边等于原图的宽高，就直接裁剪
            if ($gd_width == $this->width || $gd_height == $this->height) {
                $this->newWidth = $this->width;
                $this->newHeight = $this->height;
            } else {

                //计算缩放比率
                $per = 1;

                if (($this->newHeight / $this->height) > ($this->newWidth / $this->width)) {
                    $per = $this->newHeight / $this->height;
                } else {
                    $per = $this->newWidth / $this->width;
                }

                if ($per < 1) {
                    $this->newWidth = $this->width * $per;
                    $this->newHeight = $this->height * $per;
                }
            }

        }

        $this->newImg = $this->_CreateImg($gd_width, $gd_height, $this->type);
        imagecopyresampled($this->newImg, $this->img, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $this->width, $this->height);
    }


    /**
     * 缩略图(按等比例)
     * @param resource $img 图像流
     * @param int $width
     * @param int $height
     * @param int $type
     * @param int $new_width
     * @param int $new_height
     * @return resource
     */
    private function _thumb1($img, $width, $height, $type, $new_width, $new_height)
    {

        if ($width < $height) {
            $new_width = ($new_height / $height) * $width;
        } else {
            $new_height = ($new_width / $width) * $height;
        }

        $newImg = $this->_CreateImg($new_width, $new_height, $type);
        imagecopyresampled($newImg, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        return $newImg;
    }

    /**
     * 加载图片
     * @param string $imgPath
     * @param int $type
     * @return resource
     */
    private function _loadImg($imgPath, $type)
    {
        switch ($type) {
            case 1: // GIF
                $img = imagecreatefromgif($imgPath);
                break;
            case 2: // JPG
                $img = imagecreatefromjpeg($imgPath);
                break;
            case 3: // PNG
                $img = imagecreatefrompng($imgPath);
                break;
            default: //其他类型
                Tool::alertBack('不支持当前图片类型.' . $type);
                break;
        }
        return $img;
    }

    /**
     * 创建一个背景图像
     * @param int $width
     * @param int $height
     * @param int $type
     * @return resource
     */
    private function _CreateImg($width, $height, $type)
    {
        $img = imagecreatetruecolor($width, $height);
        switch ($type) {
            case 3: //png
                imagecolortransparent($img, 0); //设置背景为透明的
                imagealphablending($img, false);
                imagesavealpha($img, true);
                break;
            case 4://gif
                imagecolortransparent($img, 0);
                break;
        }

        return $img;
    }
}