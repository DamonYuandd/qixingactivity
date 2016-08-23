<?php
/**
 *
 * 首页控制器
 * @author uclnn
 *
 */
class MemberAction extends HomeAction
{
	function _initialize() {
	
		parent::_initialize();
	}
	
	
	//每天簽到
	public function sign(){
	    if(empty($_SESSION['member'])){
			$this->error('请重新登入系统！！');
		}
		if(empty($_SESSION['member'])){
			return false;
		}

		$obj = D('Sign')->dailySign($_SESSION['member']);
		dump($obj);
	}
	
	//註冊用戶
	public function signUp(){
		if(empty($_POST)){
			$this->ajaxReturn("","没有任何数据提交！！",0);
		}
        
        $data = $_POST;
        
        if (empty($data['codes'])){
			$this->ajaxReturn("","请填写验证码",0);
		}
		//验证码
		if($_SESSION['verify'] != md5($data['codes'])) {
			$this->ajaxReturn("","验证码错误，请注意填写",0);
		}
        
        if (empty($data['name'])){
			$this->ajaxReturn("","请填写姓名",0);
		}
        
        if(empty($data['phone'])){
			$this->ajaxReturn("","请填写监护人电话",0);
			
		}else{
			//检验手机号码是否正确
			if(!checkMobile($data['phone'])){
				$this->ajaxReturn("","手机号码格式错误，请重填！",0);
			}
			//检查号码存在
			$check = M('member')->where(array('phone' => $data['phone']))->find();
			if($check == true){
				$this->ajaxReturn("","该手机号码已有登记记录",0);
			}
		}
        
        if(empty($data['email'])){
			$this->ajaxReturn("","请填写email",0);
		 
		}else{
			//检验email是否正确
			if(!checkEmail($data['email'])){
				$this->ajaxReturn("","email格式错误，请重填！",0);
			}
			//检查email存在
			$check = M('vote_option')->where(array('email' => $data['email']))->find();
			if($check == true){
				$this->ajaxReturn("","该email已有登记记录",0);
			}
		}
        
         if(empty($data['password'])){
			$this->ajaxReturn("","请填写密码！！",0);
		}
        
        if(empty($data['re_password'])){
			$this->ajaxReturn("","请填写确认密码！！",0);
		}
        
        if($data['password'] != $data['re_password']){
            $this->ajaxReturn("","请输入一致的密码！",0);
        }
        if(empty($data['weixin'])){
            $this->ajaxReturn("","请输入微信号！！",0);
        }
        if(empty($data['city'])){
            $this->ajaxReturn("","请输入地区！！",0);
        }
        if(empty($data['industry'])){
            $this->ajaxReturn("","请输入行业！！",0);
        }
        
        $data['password'] = md5($data['password']);
        $data['experience'] = intval($data['experience']);
        $data['createTime'] = time();
        $data['status'] = 1;
        $obj = M('Member')->add($data);
        if($obj){
            $this->ajaxReturn("","注册成功！！",1);
        }else{
            $this->ajaxReturn("","提交失败！！",0);
        }
	}
    
    //登入
    public function login(){
        if(empty($_POST)){
			$this->ajaxReturn("","没有任何数据提交！！",0);
		}
        
        $data = $_POST;
        
        if (empty($data['codes'])){
			$this->ajaxReturn("","请填写验证码",0);
		}
		//验证码
		if($_SESSION['verify'] != md5($data['codes'])) {
			$this->ajaxReturn("","验证码错误，请注意填写",0);
		}
        
        if(empty($data['email']) && empty($data['password'])){
            $this->ajaxReturn("","请填写email 和密码 ",0);
        }
        
        $obj = M('Member')->where(array('email' => $data['email'] , 'password' => md5($data['password']) ,'status' => 1))->find();
        if($obj){
            $_SESSION['member'] = $obj;
            $this->ajaxReturn("","登入成功",1);
        }else{
            $this->ajaxReturn("","email 或 密码错误，请重试！！",0);
        }
    }
    
    
}
?>