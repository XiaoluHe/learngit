<?php
namespace app\admin\controller;
header('Content-type: image/png');
use think\Controller;
use think\Loader;
use grafika\src\Grafika\Grafika;

class Gra extends Controller{
	public function gra0() {
		Loader::import('grafika.src.autoloader');
		$editor = new \Grafika\Imagick\Editor(); // Imagick editor
		$A1='1.png';
		$A2='2.png';
		$A3='3.png';
		$A4='4.png';
		$A5='5.png';
		$img='待扣图.jpg';
		$editor->open($image,ROOT_PATH.'public/static/img/blade.png');
		$im = new Imagick(ROOT_PATH.'public/static/img/blade.png');
		//8000为边缘容差，修改此数值可适当去除多余相似杂色
		$im->transparentPaintImage($im->getImagePixelColor(0, 0), 0, 8000,0);
		//生成png格式
		$im->setImageFormat("png");
		//保存文件名
		$im->writeImage("$A1");
		//细化边缘
		//
		exec("convert $A1 -threshold 75% $A2");
		exec("convert $A2 -fill black -opaque white $A3");
		exec("convert $A3 -channel RGBA -blur 0x2 $A4");
		exec("convert $A1 $A4 -alpha on -compose copy_opacity -composite $A5");



		unlink($A1);

		unlink($A2);
		unlink($A3);
		unlink($A4);
	}
	public function gdData() {
		$img_path = ROOT_PATH.'public/static/img/blade.png';
		Loader::import('grafika.src.autoloader');
		$grafika = new \Grafika\Gd\Editor();
		if( $grafika->isAvailable() ) { // Safety check
			$grafika->open($image,$img_path);
			$grafika->rotate($image,0,)
			$grafika->save($image,ROOT_PATH.'public/gd/gd1.jpg');
		}
	}
	public function imagick() {
		Loader::import('grafika.src.autoloader');
		$imagick = new \Grafika\Imagick\Editor();
		if( $imagick->isAvailable() ) { // Safety check
			var_dump("imagick");

		} 
		var_dump ($imagick->isAvailable());
	}
	public function gra() {
		$img_path = ROOT_PATH.'public/static/img/blade.png';
		$this->assign("img",$img_path);
		// if (unlink(ROOT_PATH."public/uploadstemp/"."Sobel1.jpg")) {
		// 	echo "success";
		// } else {
		// 	echo "error";
		// }
		// die;
		Loader::import('grafika.src.autoloader');
		$grafika = new \Grafika\Grafika();
		$editor = $grafika->createEditor();
		$editor->open( $image, $img_path);
		//反色(I)
		// $filter = $grafika->createFilter('Invert');
		// $editor->apply( $image, $filter );
		// $editor->save($image,ROOT_PATH.'public/uploadstemp/invert1.jpg');
		// 智能截图
		// $editor->open( $image, $img_path);
		// $editor->crop( $image, 200, 200, 'smart' );
		// $editor->save( $image, ROOT_PATH.'public/uploadstemp/smart.jpg' ); 
		//亮度扩展(E)
		// $filter = $grafika->createFilter('Brightness', 50);
		// $editor->apply( $image, $filter );
		// $editor->save($image,ROOT_PATH.'public/uploadstemp/Brightness-2.jpg');
		//均值滤波(A)
		//
		//
		//中值滤波(M)
		//
		//
		//边缘增强(F)
		// $filter = $grafika->createFilter('Sobel');
		// $editor->apply( $image, $filter );
		// $editor->save($image,ROOT_PATH.'public/uploadstemp/Sobel111.png');
		//真彩色转换为256色(T)
		//
		//
		//彩色转为灰度(G)
		// $filter = $grafika->createFilter('Grayscale');
		// $editor->apply( $image, $filter );
		// $editor->save($image,ROOT_PATH.'public/uploadstemp/Grayscale.jpg');
		//256色转换成真彩色(P)
		//
		//
		//转换成伪彩色(A)
		//
		//
		//抖动(D)图像噪点
		// $filter = $grafika->createFilter('Dither', "diffusion");
		// $editor->apply( $image, $filter );
		// $editor->save($image,ROOT_PATH.'public/uploadstemp/Dither.jpg');
		//直方图均衡(H)
		//
		//
		//颜色调节(C)
		// $filter = $grafika->createFilter('Colorize', -50,50,-50);
		// $editor->apply( $image, $filter ); 
		// $editor->save($image,ROOT_PATH.'public/uploadstemp/Colorize.jpg');
		//电平二值化(L)
		//
		//图像色阶调整
		
		$filter = $grafika->createFilter('Gamma', 2.0);
		$editor->apply( $image, $filter );
		$image->blob("png");
		// return view();
		
	}
	//处理图片  (while(list($k,$v) = each($arr))=>php索引数组的遍历)
	public function handleImg() {
		$img_path = input("post.img_path");
		$index = input("post.index");
		if ($index == 0) {
			$f = "Invert";
		} else {
			$f = "Grayscale";
		}
		$arr = explode("/", $img_path);
		//获取数组长度
		$lengh = count($arr);
		$img = $arr[$lengh-2].'/'.$arr[$lengh-1];
		$img_path = ROOT_PATH.'public/'.$img;
		Loader::import('grafika.src.autoloader');
		$grafika = new \Grafika\Grafika();
		$editor = $grafika->createEditor();
		$editor->open( $image, $img_path);
		$filter = $grafika->createFilter($f);
		$editor->apply( $image, $filter );
		$result = $image->getImageFile();
		$t = time();
		$img_replace = 'uploadstemp/'.$t.'jpg';
		$editor->save($image,ROOT_PATH.'public/'.$img_replace);

		// while(list($k,$v) = each($filter)){
			// if ($v) {
			//  	$filter = $grafika->createFilter('Dither', "diffusion");
			// } else {
			// 	$filter = $grafika->createFilter($k);
			// }
		// 	$filter = $grafika->createFilter('Dither', "diffusion");
		// 	$editor->apply( $image, $filter );
		// 	$t = time();
		// 	$img_replace = $t."png";
		// 	$editor->save($image,ROOT_PATH.$img_replace); 
		// }
		//返回图片路径
		exit(json_encode($img_replace));
	}
	public function bootstrap() {
		
		return view();
	}
}

?>