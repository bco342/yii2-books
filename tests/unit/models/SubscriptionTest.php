<?php

namespace tests\unit\models;

use app\models\Subscription;
use Codeception\Test\Unit;

class SubscriptionTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var Subscription
     */
    private $subscription;

    protected function _before()
    {
        $this->subscription = new Subscription();
    }

    /**
     * @dataProvider phoneNumberProvider
     */
    public function testFormatPhoneNumber($input, $expected)
    {
        $result = $this->subscription->formatPhoneNumber($input);
        $this->assertEquals($expected, $result, "Failed formatting phone number: '$input'");
    }

    public function phoneNumberProvider()
    {
        return [
            'standard format' => ['89991234567', '+79991234567'],
            'formatted with parentheses' => ['8 (999) 123-45-67', '+79991234567'],
            'with dashes' => ['999-123-45-67', '+79991234567'],
            'with international code' => ['+7 999 123 45 67', '+79991234567'],
            'without prefix' => ['9991234567', '+79991234567'],
            'with letters' => ['8 (999) ABC-DE-FG', '+7999'],
            'complex format' => ['+7(965)123-45-67', '+79651234567'],
            'russian format' => ['8-965-123-45-67', '+79651234567'],
            'empty string' => ['', ''],
            'only letters' => ['ABC', ''],
            'single digit 8' => ['8', '+7'],
            'single digit 9' => ['9', '+79'],
            'spaces only' => ['   ', ''],
            'international format' => ['+79991234567', '+79991234567'],
            'with extra spaces' => [' 8 999 123 45 67 ', '+79991234567'],
            'with dots' => ['8.999.123.45.67', '+79991234567'],
            'mixed separators' => ['8-999.123 45/67', '+79991234567'],
        ];
    }

    public function testValidationWithFormattedNumber()
    {
        $this->subscription->guest_phone = '8 (999) 123-45-67';
        
        $this->subscription->validate(['guest_phone']);
        $this->assertEquals('+79991234567', $this->subscription->guest_phone);
    }

    public function testRequiredFields()
    {
        $this->assertFalse($this->subscription->validate());
        $this->assertArrayHasKey('guest_phone', $this->subscription->errors);
        $this->assertArrayHasKey('author_id', $this->subscription->errors);
    }

    public function testPhoneNumberPersistence()
    {
        $phones = [
            '89991234567',
            '8 (999) 123-45-67',
            '+7-999-123-45-67'
        ];

        foreach ($phones as $phone) {
            $subscription = new Subscription([
                'guest_phone' => $phone,
                'author_id' => 1
            ]);

            $subscription->validate();
            $this->assertEquals('+79991234567', $subscription->guest_phone);
        }
    }
    
    public function testPhoneLengthValidation()
    {
        $subscription = new Subscription();

        // Test with invalid phone number length
        $subscription->guest_phone = '+79876543';
        $this->assertFalse($subscription->validate(['guest_phone']));

        $subscription->guest_phone = '+79876543210123';
        $this->assertFalse($subscription->validate(['guest_phone']));

        // Test with valid phone number
        $subscription->guest_phone = '+79876543210';
        
        $subscription->validate(['guest_phone']);
        $this->assertEquals('+79876543210', $subscription->guest_phone);
    }
}