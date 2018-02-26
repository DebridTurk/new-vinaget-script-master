<?php
class dl_conexaomega_com_br extends Download {

	public function Login($user, $pass){
		$data = $this->lib->curl("http://www.conexaomega.com.br/login","", http_build_query(array('email' => $user,'senha' => $pass,'lembrar' => '1')) );
		if(stristr($data, 'HTTP/1.1 302 Found')) {
			return $this->lib->GetCookies($data);
		} else {
			return false;
		}
	}	
	public function CheckAcc($cookie){
		
		return array(true, "");
	
	}
    public function Leech($url) {	
		$data = $this->lib->curl("http://www.conexaomega.com.br/_gerar?link=".urlencode($url), $this->lib->cookie, "", 0);
		if(stristr($data, 'Sua sessão expirou por inatividade. Efetue o login novamente.')) {
			//$this->error($this->lib->cookie, true, false);
			$this->error("Cookie expired.", true, false);
		} 
		elseif(stristr($data, 'Link indisponível/não suportado')) {
			$this->error("Link unavailable / unsupported.", true, false);
		}
		else {
			$link = explode("|", $data);
			return trim($link[0]);
		}
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Conexaomega.com.br Download Plugin by Tieuholuckyboy (22/7/2014)
/
?>
?>