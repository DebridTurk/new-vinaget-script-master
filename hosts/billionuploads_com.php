<?php

class dl_billionuploads_com extends Download {
    
    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/[\d.]+(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) $this->error("reportpass", true, false);
		elseif(stristr($data,'<h2><b>File Not Found</b></h2><br><br>')) $this->error("dead", true, false, 2);
		elseif(!stristr($data, "Download or Watch")) 
		$this->error("Cannot get Download or Watch", true, false, 2);	
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('@https?:\/\/[\d.]+(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		return false;
	}
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* billionuploads Download Plugin by riping [22.7.2013]
* Downloader Class By [FZ]
* Support file password by giaythuytinh176 [29.7.2013]
*/
?>