<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\ChinesePhoneticAlphabet;
header("Content-type:text/html;charset=utf-8");
class Cpa extends Controller {
	public function cpa() {
		$cpa = new ChinesePhoneticAlphabet();
		$result = $cpa->pinyin("Chinese Phonetic Alphabet 日月同辉是一场盛宴，无人不知，无人不晓，虽然说并没有什么卵用");
		var_dump($result);
		// return view();
	}
}