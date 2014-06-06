<?php
namespace AMP;

class MailTest extends \PHPUnit_Framework_TestCase
{
    private $email;

    public function setUp()
    {
        $this->email = new Mail();
    }

    /**
     * @test
     */
    public function settingMessageFormat()
    {
        $message = "The message";
        $name = "name";
        $this->email->setMessage($message, $name);
        $testMessage = "From: " . $name . PHP_EOL . PHP_EOL . $message;
        $this->assertEquals($testMessage, $this->email->getMessage());
    }

    /**
     * @test
     */
    public function checkingEmptyObjectShouldBeInvalid()
    {
        $this->assertEquals(false, $this->email->isValid());
    }

    /**
     * @test
     */
    public function settingEmailWithoutRecipientShouldGetInvalid()
    {
        $this->email->setMessage("The message", "Chris");
        $this->email->setSubject("Hot Topic");
        $this->email->setSender("chris@agilemusicproject.com");
        $this->assertEquals(false, $this->email->isValid());
    }

    /**
     * @test
     */
    public function settingEmailWithoutSubjectShouldGetInvalid()
    {
        $this->email->setMessage("The message", "Chris");
        $this->email->setRecipient("info@agilemusicproject.com");
        $this->email->setSender("chris@agilemusicproject.com");
        $this->assertEquals(false, $this->email->isValid());
    }

    /**
     * @test
     */
    public function settingEmailWithoutSenderShouldGetInvalid()
    {
        $this->email->setMessage("The message", "Chris");
        $this->email->setRecipient("info@agilemusicproject.com");
        $this->email->setSubject("Hot Topic");
        $this->assertEquals(false, $this->email->isValid());
    }

    /**
     * @test
     */
    public function settingEmailWithoutMessageShouldGetInvalid()
    {
        $this->email->setMessage(null, "Chris");
        $this->email->setRecipient("info@agilemusicproject.com");
        $this->email->setSubject("Hot Topic");
        $this->email->setSender("chris@agilemusicproject.com");
        $this->assertEquals(false, $this->email->isValid());
    }

    /**
     * @test
     */
    public function settingEmailWithoutNameShouldGetInvalid()
    {
        $this->email->setMessage("The message", null);
        $this->email->setRecipient("info@agilemusicproject.com");
        $this->email->setSubject("Hot Topic");
        $this->email->setSender("chris@agilemusicproject.com");
        $this->assertEquals(false, $this->email->isValid());
    }

    /**
     * @test
     */
    public function fillingOutEveryArgumentShouldBeValid()
    {
        $this->email->setMessage("The message", "Chris");
        $this->email->setRecipient("info@agilemusicproject.com");
        $this->email->setSubject("Hot Topic");
        $this->email->setSender("chris@agilemusicproject.com");
        $this->assertEquals(true, $this->email->isValid());
    }
}
