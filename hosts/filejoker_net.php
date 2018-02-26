<?php

class dl_filejoker_net extends Download {
    
	private function curl_old($url,$cookies,$post,$header=1){
		$ch = @curl_init();
		$head[] = "X-Requested-With: XMLHttpRequest";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, $header);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
		if ($cookies) curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->UserAgent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER,$url); 
		if ($post){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
		$page = curl_exec( $ch);
		curl_close($ch); 
		return $page;
	}
	
    public function CheckAcc($cookie){
        $data = $this->lib->curl("https://filejoker.net/profile", $cookie, "");
		$dt = $this->lib->cut_str($data, '<div class="alert alert-success alert-promo">', '</div>');
        if(stristr($data, 'Premium account expires') && stristr($dt, ">Extend Premium<")) return array(true, "Premium Until: ".$this->lib->cut_str($dt, "Premium account expires: ", '				<a h').'<br>Traffic Available: '.$this->lib->cut_str($this->lib->cut_str($data, "td>Traffic Available:</td>", "</tr>"), 'td>', '</td'));
		elseif(stristr($data, 'Get all the features!') && stristr($dt, ">Buy Premium<")) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
		$data = $this->curl_old('https://filejoker.net', '', '');
		$data = $this->curl_old('https://filejoker.net/login', '', "recaptcha_response_field=&op=login&redirect=&rand=&email={$user}&password={$pass}");
        $cookie = $this->lib->GetCookies($data);
		return $cookie;
    }

 public function Leech($url) {
  $id = explode('/', $url)[3];
  $url = 'https://filejoker.net/'. $id;
  $data = $this->lib->curl($url, $this->lib->cookie, "");
  if(strpos($data, ">Get Download Link<") !== false) {
   $post = $this->parseForm($data);
   $data = $this->lib->curl($url, $this->lib->cookie, http_build_query($post));
   if(preg_match('@https?:\/\/fs\d+\.filejoker.net/[^"\'><\r\n\t]+@i', $data, $link)) return trim($link[0]);
  } elseif(strpos($data, ">File Not Found<")) $this->error("dead", true, false, 2);
  return false;
 }
}

/*
https://filejoker.net/9ygsozjs4vi1/SMPD-01.part2.rar
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* FileJoker.Net Download Plugin by rayyan2005[20.06.2015]
* Downloader Class By [FZ]
*/
?>