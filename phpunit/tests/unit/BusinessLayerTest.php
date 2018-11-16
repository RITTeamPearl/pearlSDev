<?php

use PHPUnit\Framework\TestCase;

class BusinessLayerTest extends TestCase{

    public function testSendEmail(){

        $email = new \pearlApp\business\business_layer;

        $email->sendEmail("dh2603@rit.edu", "Toya Test", "Hey gurl hey");

        $expected = 'dh2603@rit.edu Toya Test Hey gurl hey';

        $this->assertEquals($expected, $email);
    }

    public function testSendText(){

        $text = new \pearlApp\business\business_layer;

        $text->sendText("Hi this is Toya");

        $expected = "Hi this is Toya";

        $this->assertEquals($expected, $text);
    }

    public function testUploadFile(){}

    public function testCreateNewsTable(){}

    public function testCreateUserTable(){}

    public function testCreatePendingUserTable(){}

    public function testCreateLandingNewsTable(){}

    public function testCreateIndividualNotification(){}

}