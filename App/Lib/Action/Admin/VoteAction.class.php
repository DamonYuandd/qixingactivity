<?php
/**
 * 
 * 介绍内容控制器
 * @author uclnn
 *
 */
class VoteAction extends AdminAction {
	
	function _initialize() {
		parent::_initialize ();
		//$this->setModel('News');
	}
	
	
	//作品列表
	public function index() {
		/*分页*/
		$order = 'id desc';
		import ( "ORG.Util.Page" );
		
		//筛选条件
		
		$where = '';
		$count = M('vote_option')->where ( $where )->count ();
		$page = new Page ( $count, 20 );
		$data = M('vote_option')->where( $where )->order($order)->limit ( $page->firstRow . ',' . $page->listRows )->select();
		
		
		$this->assign('pageBar',$page->show());
		$this->assign('data',$data);
		$this->display ();
	}
	
	
	//删除作品
	public function delete(){
		if(empty($_GET['id'])){
			$this->error ( '错误' );
		}
		$obj = M('vote_option')->where(array('id' => $_GET['id']))->delete();
		if($obj){
			$this->success ( '删除成功！' );
		}else{
			$this->error ( '异常' );
		}
	}
	
	//查看详情
	public function edit(){
		if(empty($_GET['id'])){
			$this->error ( '错误' );
		}
		$obj = M('vote_option')->where(array('id' => $_GET['id']))->find();
		if(!$obj){
			$this->error ( '异常' );
		} 
		
		$this->assign('obj',$obj);
		$this->display ();
	}
	
	//导出exl
	public function export(){
		$this->display();
	}
	
	//更新
	public function update(){
	    $path = date('Ymd',time());
              
        if ($_FILES['image']['tmp_name'] != "") {
			$name=time();
			import("ORG.Net.UploadFile");
	
			$upload = new UploadFile();// 实例化上传类
	
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
	
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	
			$upload->savePath =  './Public/Uploads/'.$path.'/';// 设置附件上传目录
	
			$upload->saveRule =  $name;
	
			if(!$upload->upload()) {// 上传错误提示错误信息
	
	
				$info=$upload->getErrorMsg();
				
                $this->error('上传失败'.$info);
	
			}else{// 上传成功 获取上传文件信息
	
				$info =  $upload->getUploadFileInfo();
	
			 
			}
		}
        
        if($info){
            $data['author_avatar'] = $path.'/'.$info[0]['savename'];
        }
        
        if(empty($_POST['name'])){
            $this->error('必须填写姓名');
        }
        $data['name'] = $_POST['name'];
        
        if(empty($_POST['content'])){
            $this->error('必须填写内容');
        }
        if( !empty($_POST['content']) ) {
			//HTML标签转实体
			if (get_magic_quotes_gpc ()) {
				$content = htmlspecialchars ( stripslashes ( $_POST ['content'] ) );
			} else {
				$content = htmlspecialchars ( $_POST ['content'] );
			}
			$data['content'] = $content;
		}
        
		$where = array('id' => $_POST['id']);
		M('vote_option')->where($where)->save($data);
		$this->success ( '修改成功！' );
	}
    
    //新增投票
    public function add(){
        $this->display();
    }
    
    public function addVote(){
        $path = date('Ymd',time());
        
        if (!empty($_FILES)) {
			$name=time();
			import("ORG.Net.UploadFile");
	
			$upload = new UploadFile();// 实例化上传类
	
			$upload->maxSize  = 3145728 ;// 设置附件上传大小
	
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	
			$upload->savePath =  './Public/Uploads/'.$path.'/';// 设置附件上传目录
	
			$upload->saveRule =  $name;
	
			if(!$upload->upload()) {// 上传错误提示错误信息
	
	
				$info=$upload->getErrorMsg();
				
                $this->error('上传失败'.$info);
	
			}else{// 上传成功 获取上传文件信息
	
				$info =  $upload->getUploadFileInfo();
	
			 
			}
		}
		else{
		      $this->error('必须上传图片');
		}
   
        $data['author_avatar'] = $path.'/'.$info[0]['savename'];
        if(empty($_POST['name'])){
            $this->error('必须填写姓名');
        }
        $data['name'] = $_POST['name'];
        
        if(empty($_POST['content'])){
            $this->error('必须填写内容');
        }
        if( !empty($_POST['content']) ) {
			//HTML标签转实体
			if (get_magic_quotes_gpc ()) {
				$content = htmlspecialchars ( stripslashes ( $_POST ['content'] ) );
			} else {
				$content = htmlspecialchars ( $_POST ['content'] );
			}
			$data['content'] = $content;
		}
        M('vote_option')->add($data);
        $this->success ( '添加成功！' );
       // dump($info);
    }
	
}
?>