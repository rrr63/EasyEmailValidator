<?php

use PHPUnit\Framework\TestCase;
use Auv\EasyEmailValidator\EasyValidator;
use Auv\EasyEmailValidator\Providers\ProviderAbstract;
use Auv\EasyEmailValidator\Providers\AdamLovingProvider;

class EasyValidatorTest extends TestCase
{
    public function testValidateReturnsFalseForInvalidEmailFormat(): void
    {
        $validator = new EasyValidator();
        $this->assertFalse($validator->validate('invalid-email'));
    }

    public function testValidateReturnsTrueForValidEmailFormat(): void
    {
        $validator = new EasyValidator();
        $this->assertTrue($validator->validate('test@gmail.com'));
    }

    public function testValidateReturnsFalseForDeniedDomain(): void
    {
        $validator = new EasyValidator();
        $this->assertFalse($validator->validate('test@yopmail.fr'));
    }

    public function testValidateFalseReturnsMultipleEmails(): void
    {
        $validator = new EasyValidator();
        $this->assertFalse($validator->validateMultiple([
            'test@gmail.com',
            'test@yopmail.fr'
        ]));
    }

    public function testValidateTrueReturnsMultipleEmails(): void
    {
        $validator = new EasyValidator();
        $this->assertTrue($validator->validateMultiple([
            'test@gmail.com',
            'test@outlook.com'
        ]));
    }

    // providers

    public function testValidateReturnsFalseForDeniedDomainWithAdamLovingProvider(): void
    {
        $validator = new EasyValidator(new AdamLovingProvider());
        $this->assertFalse($validator->validate('test@zehnminutenmail.de'));
    }

    public function testValidateReturnsTrueForAllowedDomainWithAdamLovingProvider(): void
    {
        $validator = new EasyValidator(new AdamLovingProvider());
        $this->assertTrue($validator->validate('test@gmail.com'));
    }

    // custom provider for company domain only
    private static function getCustomProvider(): ProviderAbstract
    {
        return new class () extends ProviderAbstract {
            public function getAllowDomains(): array
            {
                return ['mycompany.com'];
            }
            public function getDenyDomains(): array
            {
                return [];
            }
        };
    }

    public function testCustomProviderValidDomain(): void
    {
        $validator = new EasyValidator(self::getCustomProvider());
        $this->assertTrue($validator->validate('user@mycompany.com'));
    }

    public function testCustomProviderInvalidDomain(): void
    {
        $validator = new EasyValidator(self::getCustomProvider());
        $this->assertFalse($validator->validate('user@gmail.com'));
    }
}
