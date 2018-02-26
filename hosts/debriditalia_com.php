<?php
class dl_debriditalia_com extends Download {

	public function Login($user, $pass){
		$data = $this->lib->curl('http://debriditalia.com/login.php?u='.$user.'&p='.$pass.'&sid=0.'.mt_rand().''.mt_rand(), '', 'fplang=en');
		$cookie = $this->lib->GetCookies($data);
		return "fplang=en;".$cookie;
	}	
	public function CheckAcc($cookie){
		$data = $this->lib->curl('http://www.debriditalia.com/profilo.php', $cookie, '', 0);
		if(preg_match_all("/(<strong>Premium valid till:<\/strong>(.*?))<br \/><br \/>/", $data, $data2)) {
			return array(true, $data2[1][0]);
		}
		else { 
			return array(false, "accinvalid");
		}
	
	}
    public function Leech($url) {	
		$post = 'http://www.debriditalia.com/api.php?generate=&link='.urlencode($url);
		$data = $this->lib->curl($post,$this->lib->cookie,'',0);
		if(!stristr($data, 'ERROR: ')) {
			if(stristr($data, "http://www.debriditalia.com/dl/")) {
				$data = $this->lib->curl($data,$this->lib->cookie,'',1);
				preg_match('/ocation: (.*)/',$data,$match);
				return trim($match[1]);
			}
			else {
				return trim($data);
			}
		}
		else {
			if(stristr($data, 'not_supported')) $this->error("Host is not supported!", true, false);
			elseif(stristr($data, 'not_available')) $this->error("dead", true, false);
			else $this->error("An Error Ocurred while getting your link", true, false);
		}
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Debriditalia.com Download Plugin by Tieuholuckyboy (28/8/2014)
*/
?>