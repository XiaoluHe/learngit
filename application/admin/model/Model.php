<!-- 此源码有个阈值可以自己调节,精确度等自测 -->
<?php
/*$Colorimg = new Colorimg();
$image=$Colorimg->IMGaction("G:/www/20161220/demo/5.jpg",1,1,50);
//告诉浏览器以图片形式解析
header('content-type:image/jpeg');
imagejpeg($image, "G:/www/20161220/demo/3.jpg");
*/
 
class Colorimg
{
    public $image;//图片
    private $cs;//比对阈值
    public function IMGaction($imgurl,$if_url=1,$if_deflate=0,$cs='50') {
        if($if_url==1) {
            $image = $this->ImgcolorCRRATE($imgurl);
        }else{
            $image = $imgurl;
        }
        if($if_deflate==1) {
            $image = $this->ImgDEFLATE($image);
        }
        //平均值
        $sample = $this->ColorGETMEANrgb($image);
 
       $image=$this->ImgsetPIXEL($image,$sample,$cs);
 
        return $image;
 
    }
    /**
     * 打开一张图片
     */
    public function ImgcolorCRRATE($image)
    {
        list($width, $height) = getimagesize($image);//获取图片信息
        $img_info = getimagesize($image);
        switch ($img_info[2]) {
            case 1:
                $img = imagecreatefromgif($image);
                break;
            case 2:
                $img = imagecreatefromjpeg($image);
                break;
            case 3:
                $img = imagecreatefrompng($image);
                break;
        }
        return $img;
    }
 
    /**
     * $rate为图片长宽最大值
     */
    public function ImgDEFLATE($image, $rate = '800')
    {
        $w = imagesx($image);
        $h = imagesy($image);
//指定缩放出来的最大的宽度（也有可能是高度）
        $max = $rate;
//根据最大值为300，算出另一个边的长度，得到缩放后的图片宽度和高度
        if ($w > $h) {
            $w = $max;
            $h = $h * ($max / imagesx($image));
        } else {
            $h = $max;
            $w = $w * ($max / imagesy($image));
        }
//声明一个$w宽，$h高的真彩图片资源
        $i = imagecreatetruecolor($w, $h);
 
//关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
        imagecopyresampled($i, $image, 0, 0, 0, 0, $w, $h, imagesx($image), imagesy($image));
        return $i;
    }
 
    /**
     * 传入多维数组n个点计算平均值
     *$rgbarrays=array(
     * $rgb1=array(
     * 'r'=>255,
     * 'g'=>255,
     * 'b'=>255
     * )
     * )
     */
    public function ColorRECKmean($rgbarrays)
    {
//获取总共几个点
        $sum = count($rgbarrays);
        $mean1['r'] = '';
        $mean1['g'] = '';
        $mean1['b'] = '';
        foreach ($rgbarrays as $rbg) {
            $mean1['r'] += $rbg['r'];
            $mean1['g'] += $rbg['g'];
            $mean1['b'] += $rbg['b'];
        }
        $mean['r'] = intval($mean1['r'] / $sum);
        $mean['g'] = intval($mean1['g'] / $sum);
        $mean['b'] = intval($mean1['b'] / $sum);
        return $mean;
    }
 
    /**
     * 取四个点,返回平均点的rgb数组
     */
    public function ColorGETMEANrgb($image)
    {
        $rgb1 = imagecolorat($image, 0, 0);
        $rgb2 = imagecolorat($image, 0, imagesy($image) - 1);
        $rgb3 = imagecolorat($image, imagesx($image) - 1, 0);
        $rgb4 = imagecolorat($image, imagesx($image) - 1, imagesy($image) - 1);
//平均值
        $sample = $this->ColorRECKmean(array($this->ColorRGBresolved($rgb1)), $this->ColorRGBresolved($rgb2), $this->ColorRGBresolved($rgb3), $this->ColorRGBresolved($rgb4));
        return $sample;
    }
 
    public function ImgsetPIXEL($image,$sample,$cs){
        //如果相似就加一个白色的点
        for ($x = 0; $x < imagesx($image); $x++) {
            for ($y = 0; $y < imagesy($image); $y++) {
                $rgb = imagecolorat($image, $x, $y);
                $than = $this->ColorTHANrgb($this->ColorRGBComp($this->ColorRGBresolved($rgb), $sample),$cs);
                if ($than) {
                    $color = imagecolorallocate($image, 255, 255, 255);
                    imagesetpixel($image, $x, $y, $color);
                }
            }
        }
        return $image;
    }
 
    /**
     * 比对颜色相似度
     * $rgb1和$rgb2必须数组$rgb['r']....
     */
    public function ColorRGBComp($rgb1, $rgb2)
    {
        $tbsr = abs($rgb1['r'] - $rgb2['r']);
        $tbsg = abs($rgb1['g'] - $rgb2['g']);
        $tbsb = abs($rgb1['b'] - $rgb2['b']);
        $cv = sqrt(pow($tbsr, 2) + pow($tbsg, 2) + pow($tbsb, 2));
        return $cv;
    }
    /**
     *把rgb颜色分解成数组
     *
     */
    function ColorRGBresolved($rgb)
    {
        $img['r'] = intval(($rgb >> 16) & 0xFF);
        $img['g'] = intval(($rgb >> 8) & 0xFF);
        $img['b'] = intval(($rgb) & 0xFF);
        return $img;
    }
 
    /**
     * 对比像素是否相似,相似返回true
     */
    public function ColorTHANrgb($cv, $cs)
    {
        if ($cv <=$cs) {
            return true;
        } else {
            return false;
        }
    }
}