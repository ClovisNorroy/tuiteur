<?php


require_once "../src/mf/utils/ClassLoader.php";

use \mf\auth\Authentification;


class AuthentificationTest extends \PHPUnit\Framework\TestCase {

    public function __construct(){
        (new \mf\utils\ClassLoader('../src'))->register();
        parent::__construct();
    }

    function testAuthentificationSubclass(){
        $this->assertTrue(is_subclass_of('\mf\auth\Authentification', '\mf\auth\AbstractAuthentification'), 
                 "La classe Authentification doit hérité de AbstractAuthentification");
    }
    
    function testAuthentificationNoSession(){
        
        $a = new Authentification();
        
        $msg1 = "Test du constructeur : Lorsque aucun utilisateur n'est connecté la variable de session \$SESSION['user_login'] n'est pas renseignée. ";
      
        $this->assertNull($a->user_login, $msg1.
                       "Du coup l'attribut user_login doit avoir la valeur null.");
        $this->assertEquals($a->access_level, Authentification::ACCESS_LEVEL_NONE, $msg1.
                       "Du coup l'attribut access_level doit valoir ACCESS_LEVEL_NONE.");
        $this->assertFalse($a->logged_in, $msg1.
                        "Du coup l'attribut logged_in doit avoir la valeur false.");

    }

    function testAuthentificationSession(){

        $_SESSION['user_login']   = 'john';
        $_SESSION['access_level'] = 100;

        $a = new Authentification();
         
        $msg1 = "Test du constructeur : Lorsque un utilisateur est connecté la variable de session \$SESSION['user_login'] contient sont identifiant ett \$_SESSION['access_level'] son niveau d'accès. ";

        $this->assertEquals($a->user_login, $_SESSION['user_login'], $msg1.
             "L'attribut user_login doit avoir la valeur de \$_SESSION['user_login']");
        $this->assertEquals($a->access_level, $_SESSION['access_level'],$msg1.
             "L'attribut access_level doit avoir la valeur de \$_SESSION['ccess_level']");
        $this->assertTrue($a->logged_in, $msg1.
             "L'attribut logged_in doit avoir la valeur true");
    }


    function testUpdateSession(){

        $a = new Authentification();
        
        $username = 'john';
        $level = 900;

        $msg1 = "Test de la méthode updateSession : Lorsque un utilisateur se connecte, s'il est correctement authentifié les variables de session doivent être correctement renseignées. ";

        $mth = self::getMethod('updateSession');

        $mth->invokeArgs($a,array($username, $level));
        
        $this->assertEquals($_SESSION['user_login'], $username, $msg1.
                            "La variable de session \$_SESSION['user_login'] doit contenir son identifiant.");

        $this->assertEquals($_SESSION['access_level'], $level, $msg1.
                            "La variable de session \$_SESSION['access_level'] doit contenir son niveau d'accès.");

        $this->assertEquals($a->user_login, $username, $msg1.
             "L'attribut user_login doit contenir son identifiant.");
        
        $this->assertEquals($a->access_level, $level, $msg1.
             "L'attribut user_login doit contenir son niveau d'accès.");
    }


    function testLogout(){

        $msg1 = "Test de la méthode logout : Lorsque un utilisateur se déconnecte, les variables de session sont effacées et les attributs réinitialisés. ";
        
        $_SESSION['user_login']   = 'john';
        $_SESSION['access_level'] = 100;
        
        $a = new Authentification();
         
        $a->logout();

        $this->assertFalse(isset($_SESSION['user_login']), $msg1.
             "La variable de session \$_SESSION['user_login'] doit être effacée.");

        $this->assertFalse(isset($_SESSION['access_level']), $msg1.
             "La variable de session \$_SESSION['access_level'] doit être effacée.");
        
        $this->assertNull($a->user_login, $msg1.
                       "Du coup l'attribut user_login doit avoir la valeur null.");
        $this->assertEquals($a->access_level, Authentification::ACCESS_LEVEL_NONE, $msg1.
                       "Du coup l'attribut access_level doit valoir ACCESS_LEVEL_NONE.");
        $this->assertFalse($a->logged_in, $msg1.
                        "Du coup l'attribut logged_in doit avoir la valeur false.");

    }
    

    function testCheckAccessRightLogged(){
        $_SESSION['user_login']   = 'john';
        $_SESSION['access_level'] = 100;
                   
        $a = new Authentification();

        $msg1 = "Test de la méthode checkAccessRight : elle prend le niveau d'accès nécessaire en paramètre et le compare au niveau de l'utilisateur.";
        
        $this->assertFalse($a->checkAccessRight(200), $msg1."Elle doit retourner false, si le niveau requis est supérieur au niveau d'accès de l'utilisateur." );
        $this->assertTrue($a->checkAccessRight(100), $msg1."Elle doit retourner true, si le niveau requis est inférieur ou égale au niveau d'accès de l'utilisateur." );
        
        unset($_SESSION['user_login']);
        unset($_SESSION['access_level']);

        $a = new Authentification();
        $this->assertFalse($a->checkAccessRight(200), $msg1."Si l'utilisateur n'est pas connectée, elle doit retourner false, si le niveau requis est supérieur au niveau minimum ACCESS_LEVEL_NONE." );
        $this->assertTrue($a->checkAccessRight(Authentification::ACCESS_LEVEL_NONE), $msg1."Si l'utilisateur n'est pas connectée, elle doit retourner true, si le niveau requis est le  niveau minimum ACCESS_LEVEL_NONE." );
        
    }





    protected static function getMethod($name) {
        $class = new \ReflectionClass('\mf\auth\Authentification');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    function testVerifyPassword(){

        $msg1 = "Test de la méthode verifyPassword : elle retourne vrai si le mot de passe et le haché qu'elle reçoit en paramètre correspondent. "; 

        $a = new Authentification();
        $pass = "john";
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $vmth = self::getMethod('verifyPassword');
        
        $this->assertTrue($vmth->invokeArgs($a,array($pass, $hash)), $msg1."La vérification n'est pas correcte. Vérifiez que les paramètres passée sont les bons, et dans les bon ordre.");

    }

    function testHashPassword(){

        $msg1 = "Test de la méthode hashPassword : elle retourne le haché du mot de passe qu'elle reçoit en paramètre. "; 

        $a = new Authentification();
        $pass = "john";
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $hmth = self::getMethod('hashPassword');
        $vmth = self::getMethod('verifyPassword');

        $h = $hmth->invokeArgs($a,array($pass));
        
        $this->assertTrue($vmth->invokeArgs($a,array($pass, $h)), $msg1."Le hachage n'est pas correcte. Vérifiez que l'algorithme est bien renseigné (PASSWORD_DEFAULT), et que le haché est bien retourné par la méthodes.");

    }


    function testLogin(){
    
        $msg1 = "Test de la méthode login : elle effectue l'authentification en vérifiant le mot de passe fourni est celui stocké par l'application et met a jour les variable de session. ";

        $user = "john";
        $pass = "john";
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $level = 300;

        $a = new Authentification();
        
        $a->login($user, $hash, $pass, $level);
        
        $this->assertTrue(isset($_SESSION['user_login']), $msg1."La variable de session \$_SESSION['user_login'] doit être renseignée.");

        $this->assertTrue(isset($_SESSION['access_level']), $msg1."La variable de session \$_SESSION['access_level'] doit être renseigné.");
                        
        $this->assertEquals($_SESSION['user_login'], $user, $msg1."La variable de session \$_SESSION['user_login'] doit contenir son identifiant.");

        $this->assertEquals($_SESSION['access_level'], $level, $msg1."La variable de session \$_SESSION['access_level'] doit contenir son niveau d'accès.");

        $this->assertEquals($a->user_login, $user, $msg1."L'attribut user_login doit contenir son identifiant.");
        
        $this->assertEquals($a->access_level, $level, $msg1."L'attribut user_login doit contenir son niveau d'accès.");
        
        
    }


    function testLoginFail(){
    
        $msg1 = "Test de la méthode login : elle effectue l'authentification en vérifiant le mot de passe fourni est celui stocké par l'application et met a jour les variable de session. ";

        $user = "john";
        $pass = "john";
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $level = 300;
        $pass = "wrong";
        
        $a = new Authentification();
        try{
            $a->login($user, $hash, $pass, $level);
        }
        catch (\Exception $e){
            return;
        }

        $this->fail($msg1."Si le mot de passe fournie est incorrect, elle doit soulever une exception.");
            
            

            
    }
        




    
    
}
