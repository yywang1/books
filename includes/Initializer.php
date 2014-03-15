<?php
class Initializer{
	public function initConf($container){
		$container['siteConf'] = function($c){
			include $c['ROOT_PATH'].'/includes/config.env.php';
			$siteConf = array(
				'db_host' => $db_host,
				'db_name' => $db_name,
				'db_user' => $db_user,
				'db_pass' => $db_pass,
				'theme' => $theme,
			);
            return $siteConf;
        };
        return $container;
    }
	
	public function initPath($container){
		$container['path'] = function($c){
			$paths = array();
			$paths['tpl'] = $c['ROOT_PATH'] . 'themes/' . $c['siteConf']['theme'] . '/app/';
			$paths['cache'] = $c['ROOT_PATH'] . 'templates/';
			$paths['files'] = $c['ROOT_PATH'] . 'files/';
			$paths['root'] = $c['WEB_ROOT'];
			$public_path = $c['WEB_ROOT'] . 'themes/' . $c['siteConf']['theme'];
			$paths['css'] = $public_path . '/css/';
			$paths['js'] = $public_path . '/js/';
			$paths['img'] = $public_path . '/images/';
            return $paths;
        };
        return $container;
    }
	
	public function initTwig($container){
		$container['twig'] = function($c){
			include $c['ROOT_PATH'] . '/vender/Twig/lib/Twig/Autoloader.php';
			Twig_Autoloader::register();
			$loader = new Twig_Loader_Filesystem($c['path']['tpl']);
			$twig = new Twig_Environment($loader, array(
				'cache' => false
			));
			return $twig;
        };
        return $container;
    }
	
	public function initVars($container){
		$container['vars'] = function($c){
			include $c['ROOT_PATH'].'/includes/config.vars.php';
			$vars = array(
				'attr_type' => $attr_type,
				'attr_style' => $attr_style,
				'attr_eval' => $attr_eval,
				'attr_tags' => $attr_tags,
			);
            return $vars;
        };
        return $container;
    }
	
	public function initBase($container){
		$container['db'] = function($c){
			include $c['ROOT_PATH'].'/includes/db.class.php';
			return new Db($c['siteConf'], $c['vars']['attr_tags']);
        };
		$container['filedao'] = function($c){
			include $c['ROOT_PATH'].'/includes/dao/FileDao.php';
            return new FileDao($c);
        };
		$container['userdao'] = function($c){
			include $c['ROOT_PATH'].'/includes/dao/UserDao.php';
            return new UserDao($c);
        };
		$container['user'] = $_SESSION['user'];
        return $container;
    }
	
	/* 
    public function initBase($container){
        $container['db'] = $container->share(function($c){
            $siteConf = $c['siteConf'];
            return MySQL::init($siteConf);
        });
        return $container;
    }

    public function initServices($container){
		$container['currency'] = $container->share(function($c){
            $dao = new CurrencyDao($c);
            $source = new DbCurrencyService($dao);
            return new CachedCurrencyService($source, $c['cache']);
        });
        return $container;
    }
	*/
}

?>