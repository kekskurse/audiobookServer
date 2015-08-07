<?php
class url
{
	private static $url = null;
    private static $enabledPort = true;
	static public function createURL ($url, $get = array())
	{
        $urlParameter = $url;
        if(self::$url==null)
        {
            $app = new \Slim\Slim();
            $req = $app->request;
            $rootUri = $req->getRootUri();
            $port = "";
            if($_SERVER["SERVER_PORT"]!=80 && self::$enabledPort)
            {
                $port = ":".$_SERVER["SERVER_PORT"];
            }
            self::setURL($_SERVER["SERVER_NAME"].$port.$rootUri);
        }
		if(!is_array($url)) //OLD URL
		{
			throw new \Exception("Param for createURL must be a array", 1);
			$u = self::createURLOld($url);
			if(!isset($_SERVER["HTTPS"]))
			{
				return "http://".$u;
			}
			else
			{
				return "https://".$u;
			}
		}
		//NEW
		$url = "";
		if(isset($_SERVER["HTTPS"]))
		{
			$url="https://";
		}
		else
		{
			$url = "http://";
		}
		$url .= self::$url;

		foreach($urlParameter as $u)
		{
			$url.=urlencode($u)."/";
		}	
        $url = substr($url, 0, -1);
		if(count($get)>0)
		{
			$url .= "?";
			$first = true;
			foreach($get as $key => $value)
			{
				if(!$first)
				{
					$url .= "&";
				}
				$url = urlencode($key)."=".urlencode($value);
			}
		}
		return $url;
	}
	static private function createURLOld($url)
	{
		if(substr(self::$url, -1, 1)!="/")
		{
			self::$url .= "/";
		}
		return self::$url.$url;	
	}
	public static function setURL($url)
	{
		$url = preg_replace("@https?\:\/\/@", "", $url);
		self::$url = $url;
		if(substr(self::$url, -1, 1)!="/")
		{
			self::$url .= "/";
		}
	}
    public static function enabledPort($status = true)
    {
        self::$enabledPort=$status;
    }
	public function isAktiv($activeURL, $subpages = true)
	{
		$url = "";
		if(isset($_SERVER["HTTPS"]))
		{
			$url = "https://";
		}
		else
		{
			$url = "http://";
		}
		#var_dump($_SERVER);
		$url .= $_SERVER["HTTP_HOST"];
		if(isset($_SERVER["REDIRECT_URL"]))
		{
			$url .= $_SERVER["REDIRECT_URL"];
		}
		elseif(isset($_SERVER['REQUEST_URI']))
		{
			$url .= $_SERVER['REQUEST_URI'];
		}
		
		if(substr($url,0, strlen(self::$url))!=self::$url)
		{
			return False;
		}
		$path = substr($url, strlen(self::$url));
		if($subpages==false)
		{
			if($activeURL == $path || $activeURL."/" == $path)
			{
				return true;
			}
			return false;
		}
		else
		{
			if(substr($path, 0, strlen($activeURL))==$activeURL)
			{
				return true;
			}
			return false;
		}
	}
}