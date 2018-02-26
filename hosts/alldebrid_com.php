<?php

class dl_alldebrid_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("https://www.alldebrid.com/account/", $cookie, "");
		preg_match_all('/<div class="remaining_time_text">You currently have <strong> (.*?)<\/strong> of use before your AllDebrid account expires.<\/div>/', $data, $match);
		if(strpos($data,'</strong>Premium</li>')) {
			return array(true, $match[1][0]." left");
		}
		elseif(strpos($data,'</strong>normal</li>')) {
			return array(false, "accfree");
		}
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
		$data = $this->lib->curl("https://alldebrid.com/api.php?action=info_user&login=".urlencode($user)."&pw=".urlencode($pass)."", "", "", 0);
		if(preg_match("/<cookie>(.*?)<\/cookie>/", $data, $cookie)) {
			return "uid=".$cookie[1];
		} 
		else {
			return false;
		}
    }

    public function Leech($url) {
		$data = $this->lib->curl("https://www.alldebrid.com/service.php?nb=0&json=true&link=".urlencode($url), $this->lib->cookie, '', 0);
		$data = json_decode($data, true);
		if ($data['error'] == "") {
			return trim($data['link']);
		}
		else {
			$this->error($data['error'], true, false);
		}
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Share-online.biz Download Plugin by giaythuytinh176 [29.7.2013][16.11.2013][Fixed can't connect to SO]
* Downloader Class By [FZ]
*/
?>