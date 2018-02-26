<?php
class dl_fileparadox_in extends Download {

	public function Login($user, $pass){
		$data = $this->lib->curl('http://fileparadox.in/', '', 'login='.$user.'&password='.$pass.'&op=login');
		$cookie = $this->lib->GetCookies($data);
		return "lang=english;".$cookie;
	}	
	public function CheckAcc($cookie){
		$data = $this->lib->curl('http://fileparadox.in/?op=my_account', $cookie, '', 0);
		if(preg_match_all("/<TR><TD>Premium account expire:<\/TD><TD><b>(.*?)<\/b><\/TD>/", $data, $dulieu2)) {
			return array(true, $dulieu2[1][0]);
		}
		else { 
			return array(false, "accinvalid");
		}
	}
    public function Leech($url) {	
		$data = $this->lib->curl($url, $this->lib->cookie, '');
		if($this->isredirect($data)) {
			return trim($this->redirect);
		}
		elseif(stristr($data,"file_slot_download"))  {
			preg_match_all('/<input type="hidden" name="(rand|id)" value="(.*?)">/', $data, $dulieu2);
			$data = $this->lib->curl($url, $this->lib->cookie, 'op=download2&id='.$dulieu2[2][0].'&rand='.$dulieu2[2][1].'&method_premium=1', 0);
			preg_match_all('/ <!--<a href="(.*?)">/', $data, $data);
			return trim($data[1][0]);
		}
		elseif(stristr($data,"File Not Found"))  {
			$this->error("dead", true, false);
		}
		else $this->error("There was a error, pm admin to be supported", true, false);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* fileparadox_in Download Plugin by Tieuholuckyboy (28/08/2014)
/
?>
?>