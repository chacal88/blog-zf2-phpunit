<?php
namespace Admin\Service;

use DateTime;
use Core\Test\ServiceTestCase;
use Admin\Model\User;
use Core\Model\EntityException;
use Zend\Authentication\AuthenticationService;

/**
 * Testes do serviÃ§o Auth
 * 
 * @category Admin
 * @package Service
 * @author Elton Minetto<eminetto@coderockr.com>
 */

/**
 * @group Service
 */
class AuthTest extends ServiceTestCase
{

//     /**
//      * AuthenticaÃ§Ã£o sem parÃ¢metros
//      * @expectedException \Exception
//      * 
//      * @return void
//      */
//     public function testAuthenticateWithoutParams()
//     {
//         $authService = $this->serviceManager->get('Admin\Service\Auth');
        
//         $authService->authenticate();
//     }

    /**
     * AuthenticaÃ§Ã£o sem parÃ¢metros
     * @expectedException \Exception
     * @expectedExceptionMessage Parâmetros inválidos
     * 
     * @return void
     */
    public function testAuthenticateEmptyParams()
    {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        
        $authService->authenticate(array());
    }

    /**
     * Teste da autenticaÃ§Ã£o invÃ¡lida
     * @expectedException \Exception
     * @expectedExceptionMessage Login ou senha inválidos
     * 
     * @return void
     */
    public function testAuthenticateInvalidParameters()
    {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        
        $authService->authenticate(array(
            'username' => 'invalid',
            'password' => 'invalid'
        ));
    }

    /**
     * Teste da autenticaÃ§Ã£o InvÃ¡lida
     * @expectedException \Exception
     * @expectedExceptionMessage Login ou senha inválidos
     * 
     * @return void
     */
    public function testAuthenticateInvalidPassord()
    {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $user = $this->addUser();
        
        $authService->authenticate(array(
            'username' => $user->username,
            'password' => 'invalida'
        ));
    }

    /**
     * Teste da autenticaÃ§Ã£o VÃ¡lida
     * 
     * @return void
     */
    public function testAuthenticateValidParams()
    {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $user = $this->addUser();
        
        $result = $authService->authenticate(array(
            'username' => $user->username,
            'password' => 'apple'
        ));
        $this->assertTrue($result);
        
        // testar a se a authenticaÃ§Ã£o foi criada
        $auth = new AuthenticationService();
        $this->assertEquals($auth->getIdentity(), $user->username);
        
        // verica se o usuÃ¡rio foi salvo na sessÃ£o
        $session = $this->serviceManager->get('Session');
        $savedUser = $session->offsetGet('user');
        $this->assertEquals($user->id, $savedUser->id);
    }

    /**
     * Limpa a autenticaÃ§Ã£o depois de cada teste
     * 
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        $auth = new AuthenticationService();
        $auth->clearIdentity();
    }

    /**
     * Teste do logout
     * 
     * @return void
     */
    public function testLogout()
    {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $user = $this->addUser();
        
        $result = $authService->authenticate(array(
            'username' => $user->username,
            'password' => 'apple'
        ));
        $this->assertTrue($result);
        
        $result = $authService->logout();
        $this->assertTrue($result);
        
        // verifica se removeu a identidade da autenticaÃ§Ã£o
        $auth = new AuthenticationService();
        $this->assertNull($auth->getIdentity());
        
        // verifica se o usuÃ¡rio foi removido da sessÃ£o
        $session = $this->serviceManager->get('Session');
        $savedUser = $session->offsetGet('user');
        $this->assertNull($savedUser);
    }

    private function addUser()
    {
        $user = new User();
        $user->username = 'steve';
        $user->password = md5('apple');
        $user->name = 'Steve <b>Jobs</b>';
        $user->valid = 1;
        $user->role = 'admin';
        
        $saved = $this->getTable('Admin\Model\User')->save($user);
        return $saved;
    }
}