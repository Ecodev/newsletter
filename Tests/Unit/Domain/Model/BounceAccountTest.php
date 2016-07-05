<?php

namespace Ecodev\Newsletter\Tests\Unit\Domain\Model;

/**
 * Test case for class \Ecodev\Newsletter\Domain\Model\BounceAccount.
 */
class BounceAccountTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \Ecodev\Newsletter\Domain\Model\BounceAccount
     */
    protected $subject = null;

    protected function setUp()
    {
        $this->subject = new \Ecodev\Newsletter\Domain\Model\BounceAccount();
    }

    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getEmail());
    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail()
    {
        $this->subject->setEmail('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'email', $this->subject);
    }

    /**
     * @test
     */
    public function getServerReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getServer());
    }

    /**
     * @test
     */
    public function setServerForStringSetsServer()
    {
        $this->subject->setServer('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'server', $this->subject);
    }

    /**
     * @test
     */
    public function getProtocolReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getProtocol());
    }

    /**
     * @test
     */
    public function setProtocolForStringSetsProtocol()
    {
        $this->subject->setProtocol('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'protocol', $this->subject);
    }

    /**
     * @test
     */
    public function getPortReturnsInitialValueForString()
    {
        $this->assertSame(0, $this->subject->getPort());
    }

    /**
     * @test
     */
    public function setPortForIntSetsPort()
    {
        $this->subject->setPort(25);
        $this->assertAttributeSame(25, 'port', $this->subject);
    }

    /**
     * @test
     */
    public function getUsernameReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getUsername());
    }

    /**
     * @test
     */
    public function setUsernameForStringSetsUsername()
    {
        $this->subject->setUsername('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'username', $this->subject);
    }

    /**
     * @test
     */
    public function getPasswordReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getPassword());
    }

    /**
     * @test
     */
    public function setPasswordForStringSetsPassword()
    {
        $this->subject->setPassword('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'password', $this->subject);
    }

    /**
     * @test
     */
    public function getConfigReturnsInitialValueForString()
    {
        $this->assertSame('', $this->subject->getConfig());
    }

    /**
     * @test
     */
    public function setConfigForStringSetsConfig()
    {
        $this->subject->setConfig('Conceived at T3CON10');
        $this->assertAttributeSame('Conceived at T3CON10', 'config', $this->subject);
    }

    /**
     * @test
     */
    public function getSubstitutedConfigDefault()
    {
        $this->subject->setServer('mail.example.com');
        $this->subject->setProtocol('smtp');
        $this->subject->setPort(25);
        $this->subject->setUsername('john');
        $this->subject->setPassword(\Ecodev\Newsletter\Tools::encrypt('hunter2'));
        $expected = 'poll mail.example.com proto smtp username "john" password "hunter2"';
        $this->assertSame($expected, $this->subject->getSubstitutedConfig());
    }

    /**
     * @test
     */
    public function getSubstitutedConfigCustom()
    {
        $this->subject->setServer('pop.example.com');
        $this->subject->setProtocol('pop');
        $this->subject->setPort(123);
        $this->subject->setUsername('connor');
        $this->subject->setPassword(\Ecodev\Newsletter\Tools::encrypt('skynet'));

        $config = 'server  : ###SERVER###
protocol: ###PROTOCOL###
port    : ###PORT###
username: ###USERNAME###
password: ###PASSWORD###';
        $this->subject->setConfig(\Ecodev\Newsletter\Tools::encrypt($config));

        $expected = 'server  : pop.example.com
protocol: pop
port    : 123
username: connor
password: skynet';
        $this->assertSame($expected, $this->subject->getSubstitutedConfig());
    }
}
