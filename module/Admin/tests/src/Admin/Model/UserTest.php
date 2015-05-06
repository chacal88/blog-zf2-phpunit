<?php
namespace Admin\Model;

use Core\Test\ModelTestCase;
use Admin\Model\User;
use Zend\InputFilter\InputFilterInterface;

/**
 * @group Model
 */
class UserTest extends ModelTestCase
{

    public function testGetInputFilter()
    {
        $user = new User();
        $if = $user->getInputFilter();
        // testa se existem filtros
        $this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);
        return $if;
    }

    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        // testa os filtros
        $this->assertEquals(6, $if->count());
        
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('username'));
        $this->assertTrue($if->has('password'));
        $this->assertTrue($if->has('name'));
        $this->assertTrue($if->has('valid'));
        $this->assertTrue($if->has('role'));
    }

    /**
     * @expectedException Core\Model\EntityException
     */
    public function testInputFilterInvalidoUsername()
    {
        // testa se os filtros estÃ£o funcionando
        $user = new User();
        // username sÃ³ pode ter 50 caracteres
        $user->username = 'Lorem Ipsum Ã© simplesmente uma simulaÃ§Ã£o de texto da indÃºstria 
        tipogrÃ¡fica e de impressos. Lorem Ipsum Ã© simplesmente uma simulaÃ§Ã£o de texto 
        da indÃºstria tipogrÃ¡fica e de impressos';
    }

    /**
     * @expectedException Core\Model\EntityException
     */
    public function testInputFilterInvalidoRole()
    {
        // testa se os filtros estÃ£o funcionando
        $user = new User();
        // role sÃ³ pode ter 20 caracteres
        $user->role = 'Lorem Ipsum Ã© simplesmente uma simulaÃ§Ã£o de texto da indÃºstria 
        tipogrÃ¡fica e de impressos. Lorem Ipsum Ã© simplesmente uma simulaÃ§Ã£o de texto 
        da indÃºstria tipogrÃ¡fica e de impressos';
    }

    /**
     * Teste de inserÃ§Ã£o de um user vÃ¡lido
     */
    public function testInsert()
    {
        $user = $this->addUser();
        
        // testa o filtro de tags e espaÃ§os
        $this->assertEquals('Steve Jobs', $user->name);
        // testa o auto increment da chave primÃ¡ria
        $this->assertEquals(1, $user->id);
    }

    /**
     * @expectedException Core\Model\EntityException
     * @expectedExceptionMessage Input inválido: username =
     */
    public function testInsertInvalido()
    {
        $user = new user();
        $user->name = 'teste';
        $user->username = '';
        
        $saved = $this->getTable('Admin\Model\user')->save($user);
    }

    public function testUpdate()
    {
        $tableGateway = $this->getTable('Admin\Model\User');
        $user = $this->addUser();
        
        $id = $user->id;
        
        $this->assertEquals(1, $id);
        
        $user = $tableGateway->get($id);
        $this->assertEquals('Steve Jobs', $user->name);
        
        $user->name = 'Bill <br>Gates';
        $updated = $tableGateway->save($user);
        
        $user = $tableGateway->get($id);
        $this->assertEquals('Bill Gates', $user->name);
    }

    /**
     * @expectedException Core\Model\EntityException
     * @expectedExceptionMessage Could not find row 1
     */
    public function testDelete()
    {
        $tableGateway = $this->getTable('Admin\Model\User');
        $user = $this->addUser();
        
        $id = $user->id;
        
        $deleted = $tableGateway->delete($id);
        $this->assertEquals(1, $deleted); // numero de linhas excluidas
        
        $user = $tableGateway->get($id);
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