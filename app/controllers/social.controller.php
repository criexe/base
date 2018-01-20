<?php

class controller_social extends controller{
	
	function __construct(){
		
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Credentials: true");
	    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	    header('Access-Control-Max-Age: 1000');
	    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	    
	    $this->facebook    = input::get('facebook');
		$this->google_plus = input::get('google-plus');
		$this->linkedin    = input::get('linkedin');
		$this->instagram   = input::get('instagram');
		$this->twitter     = input::get('twitter');
		
		@$this->userAgent = $_SERVER['HTTP_USER_AGENT'] ? $_SERVER['HTTP_USER_AGENT'] : null;
	}
	
	function pageStats(){
		
		// Debug
		$dev = input::get('dev');
		
		// Get Page Names
		$facebook    = input::get('facebook');
		$google_plus = input::get('google-plus');
		$linkedin    = input::get('linkedin');
		$instagram   = input::get('instagram');
		$twitter     = input::get('twitter');
		
		// Cache
		$cache_name    = md5("$facebook - $google_plus - $linkedin - $instagram - $twitter");
		$cache_content = cache::get($cache_name);
		if($cache_content){
			echo json::encode($cache_content);
			exit;
		}
		
		// Net
		$net = new net();
		
		// Returns
		$r     = [];
		$total = 0;
		
		// Facebook
		try{
			if($facebook){
			
				$fb_connect = $net->connect([
					
					'url' 	  => "https://www.facebook.com/$facebook",
					'referer' => "https://www.facebook.com/$facebook"
				])['content'];
				
				$r['facebook']['page'] = $facebook;
				
				if(preg_match_all('%<div>([\.,0-9]*?) personen vinden dit leuk</div>%si', $fb_connect, $matches)){
					
					$r['facebook']['likes']     = (int)trim(str_replace([',', '.'], null, $matches[1][0]));
				}
				
				if(preg_match_all('%<div>([\.,0-9]*?) mensen volgen dit</div>%si', $fb_connect, $matches)){
					
					$r['facebook']['followers'] = (int)trim(str_replace([',', '.'], null, $matches[1][0]));
				}
				
				if($r['facebook']['followers'] > $r['facebook']['likes']) $total += $r['facebook']['followers'];
				else                                                      $total += $r['facebook']['likes'];
				
				unset($facebook, $fb_connect, $matches);
			}
		}
		catch(Exception $e){}
		
		try{
			if($google_plus){
			
				$gp_connect = $net->connect([
				
					'url' => "https://plus.google.com/+$google_plus"
				])['content'];
				
				$r['google-plus']['page'] = $google_plus;
				
				if(preg_match_all('%data-oid="([0-9]*?)"%si', $gp_connect, $matches)){
					
					$r['google-plus']['id'] = $matches[1][0];
				}
				
				if(preg_match_all('%hash: \'[0-9].*?\', data:function\(\){return \[([0-9]*?),null,"' . $r['google-plus']['id'] . '"\]%si', $gp_connect, $matches)){
					
					$r['google-plus']['followers'] = (int)trim(str_replace([',', '.'], null, $matches[1][0]));
					$total += $r['google-plus']['followers'];
				}
				
				unset($gp_connect, $google_plus, $matches);
			}
		}
		catch(Exception $e){}
		
		try{
			if($linkedin){
			
				$lin_connect = $net->connect([
				
					'url' => "https://www.linkedin.com/cws/followcompany?companyIdentifier=$linkedin&counterPosition=top&original_referer=https%3A%2F%2Fdeveloper.linkedin.com%2Fetc%2Fdesigns%2Flinkedin%2Fkaty%2Fglobal%2Fclientlibs%2Fhtml%2Fsandbox.html%3Falign-class%3Dmiddle-center&token=&isFramed=true&lang=en_US&_ts=1511792478769.8918&xd_origin_host=https%3A%2F%2Fdeveloper.linkedin.com"
				])['content'];
				
				$r['linkedin']['company'] = $linkedin;
				
				if(preg_match_all('%<span class="count.*?>(.*?)</span>%si', $lin_connect, $matches)){
					
					$r['linkedin']['followers'] = (int)trim(str_replace([',', '.'], null, $matches[1][0]));
					$total += $r['linkedin']['followers'];
				}
				
				unset($lin_connect, $linkedin, $matches);
			}
		}
		catch(Exception $e){
			
		}
		
		try{
			if($instagram){
			
				$ins_connect = $net->connect([
				
					'url' => "https://www.instagram.com/$instagram/?__a=1"
				])['content'];
				
				$ins_connect = json::decode($ins_connect);
				
				$r['instagram']['user'] = $instagram;
				
				if(is_array($ins_connect)){
					
					$count = $ins_connect['user']['followed_by']['count'];
					$r['instagram']['followers'] = $count;
					$total += $count;
				}
				
				unset($ins_connect, $instagram, $count);
			}
		}
		catch(Exception $e){}
		
		try{
			if($twitter){
			
				$tw_connect = $net->connect([
				
					'url' => "https://twitter.com/$twitter?lang=en"
				])['content'];
				
				$r['twitter']['user'] = $twitter;
				
				if(preg_match_all('%title="([\.,0-9]*?) Followers"%si', $tw_connect, $matches)){
					
					$r['twitter']['followers'] = (int)trim(str_replace([',', '.'], null, $matches[1][0]));
					$total += $r['twitter']['followers'];
				}
				
				unset($tw_connect, $twitter, $count);
			}
		}
		catch(Exception $e){}
	
	
		$r['total'] = $total;
		unset($total);
		
		// DB : Insert
		item::insert([
			
			'type'         => 'api.social.pageStats',
			'content'      => $r,
			'request_data' => ['post' => $_POST, 'get' => $_GET],
			'user_agent'   => $this->userAgent,
			'server'       => $_SERVER,
		]);
		
		// JSON
		$r = json::encode($r);
		
		// Create Cache
		cache::create($cache_name, $r);
		
		echo $r;
	}
	
	
	function userStats(){
		
		try{
			
			// Cache
			$cache_name    = 'userStats.' . md5($this->facebook . ' - ' . $this->google_plus . ' - ' . $this->linkedin . ' - ' . $this->instagram . ' - ' . $this->twitter);
			$cache_content = cache::get($cache_name);
			if($cache_content){
				echo json::encode($cache_content);
				exit;
			}
			
			// Net
			$net = new net();
			
			// Returns
			$r = [];
		
			if($this->linkedin){
				
				$in_connect = $net->connect([
					'url' => 'https://www.linkedin.com/cws/member/public_profile?public_profile_url=https%3A%2F%2Fwww.linkedin.com%2Fin%2F' . $this->linkedin . '&format=inline&related=false&original_referer=https%3A%2F%2Fdeveloper.linkedin.com%2Fetc%2Fdesigns%2Flinkedin%2Fkaty%2Fglobal%2Fclientlibs%2Fhtml%2Fsandbox.html%3Falign-class%3Dmiddle-center&token=&isFramed=true&lang=en_US&_ts=' . time() . '.4512&xd_origin_host=https%3A%2F%2Fdeveloper.linkedin.com'
				])['content'];
				
				var_dump($in_connect);
				
			} // Linkedin
		}
		catch(Exception $e){
			$err = $e->getMessage();
		}
	}
}