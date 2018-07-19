<?php 
namespace app\admin\controller;
header("Content-type:text/html;charset=utf-8");
use think\Controller;
use think\Request;
class Index extends Controller {
	public function index() {
		if (input("post.")) {
			$data = $this->post_check(input("post."));

		}
        //重定向
        // $this->redirect("Gra/gra");
        $this->assign("a","adf");
		return view();
		
		
	}
    public function imgindex() {

        return view();
    }
    public function saveimg () {
        $base64_image_content = $_POST['imgpath'];
        $arr = explode(",", $base64_image_content);
        var_dump ($arr); die;
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];
            $new_file = ROOT_PATH . 'public' . DS . 'uploads'."temp"."/";
            if(!file_exists($new_file)){
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file.time().".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
                echo '新文件保存成功：', $new_file;
            }else{
                echo '新文件保存失败';
            }
        }
    }

	//视频播放界面
	public function video() {
		if (input("post.")) {
			
		}

		return view();
	}
	public function upload(){
    // 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('test');
    var_dump ($file);die;
    
    // 移动到框架应用根目录/public/uploads/ 目录下
    if($file){
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            echo $info->getExtension();
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getSaveName();
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getFilename(); 
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }
}
	public function post_check($post) {
		//获取数组中所有的键
		$keys = array_keys($post);
		//遍历数组进行过滤
		foreach ($post as $key => $value) {
			$post[$key] = htmlspecialchars($value);
		}
		echo "post:"; var_dump ($post); echo "<br>";die;
        if (!get_magic_quotes_gpc()) {    // 判断magic_quotes_gpc是否为打开
        	echo "I'm coming";echo "<br>";
            $post["test"] = addslashes($post["test"]); // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
            echo "addslashes:"; var_dump ($post['test']); echo "<br>";
        }
        // $post["test"] = stripslashes($post["test"]); // 删除反斜杠
        // echo "stripslashes:"; var_dump ($post['test']); echo "<br>";
        $post["test"] = htmlentities($post["test"]); // 把字符转换为 HTML 实体
        echo "htmlentities:"; var_dump ($post['test']); echo "<br>";
        $post["test"] = htmlspecialchars($post["test"]); // 把预定义的字符 "<" （小于）和 ">" （大于）转换为 HTML 实体：
        echo "htmlspecialchars:"; var_dump ($post['test']); echo "<br>";
        // $post["test"]= strip_tags($post["test"]); // 剥离字符串中的html标签
        return $post;
    }
}