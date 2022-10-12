<?php 

   require 'db.php';
   require 'fun.php';
   require 'product.php';
   require 't.php';
   require 'smsc_api.php';

   class core {
      public static $user_ph = false;
      public static $user_pm = false;
      public static $user_r = false;
      public static $user_data = array();

      public function __construct() {
         new db; new t; new fun; new product;
         $time = 3600 * 24 * 365;
         // ini_set('session.gc_maxlifetime', $time);
         // ini_set('session.cookie_lifetime', $time);
         session_set_cookie_params($time);
         session_start();
         date_default_timezone_set('Asia/Almaty');
         $this->authorize();
      }

      private function authorize() {
         $user_ph = false;
         $user_pm = false;
         $user_ps = false;

         if (isset($_SESSION['uph']) && isset($_SESSION['ups'])) {
            $user_ph = $_SESSION['uph'];
            $user_ps = $_SESSION['ups'];
         }
         if ($user_ph && $user_ps) {
            $user = db::query("SELECT * FROM user WHERE phone = '$user_ph'");
            if (mysqli_num_rows($user)) {
               $user_data = mysqli_fetch_assoc($user);
               if ($user_ps == $user_data['password2'] && $user_data['rights']) {
                  self::$user_ph = $user_ph;
                  self::$user_data = $user_data;
               } else $this->user_unset();
            } else $this->user_unset();
         }
      }
   
      public function user_unset() {
         self::$user_ph = false;
         self::$user_data = array();
         unset($_SESSION['uph']);
         unset($_SESSION['ups']);
      }

   }


   // data
   $core = new core;
   $user = core::$user_data;
   $user_id = $user['id'];
   $user_right = fun::user_management($user_id);

   // setting
   $site = mysqli_fetch_array(db::query("select * from `site` where id = 1"));
   $ver = 2.89;
   $site_set = [
      'menu' => true,
      'search' => true,
      // 'swiper' => false,
      // 'plyr' => false,
      // 'aos' => false,
	];
   $scss = ['norm', 'main'];
   $sjs = ['norm', 'main'];
   $css = [];
   $js = [];
   $code = rand(1000, 9999);



   // lang
   $lang = 'ru';
   if (isset($_GET['lang'])) if ($_GET['lang'] == 'kz' || $_GET['lang'] == 'ru') $_SESSION['lang'] = $_GET['lang'];
   if (isset($_SESSION['lang'])) $lang = $_SESSION['lang'];

   $view_pr = null;
   if (isset($_GET['view_pr'])) $_SESSION['view_pr'] = $_GET['view_pr'];
   if (isset($_SESSION['view_pr']) && $_SESSION['view_pr'] == 'list') $view_pr = 2; else $view_pr = null;


   // date - time
   $date = date("Y-m-d", time());
   $time = date("H:m:s", time());
   $datetime = date('Y-m-d H:i:s', time());

   // url
	$url = $url_full = $_SERVER['REQUEST_URI'];
	$url = explode('?', $url);
	$url = $url[0];


   // 
   $token = "5542688063:AAGP-xAW5Fy8ZJ0BIbelRRqWI4KFoKn-Igw";
	$chat_id = "-1001761273817";