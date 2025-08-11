# EasyEmailValidator

A lightweight PHP package to validate email addresses against allowed and denied domain lists.

## Installation

Add the package to your project with Composer:

```bash
composer require auvernhat/easyemailvalidator
```

## Usage

```php
use Auvernhat\EasyEmailValidator\EasyValidator;

$validator = new EasyValidator();

// Validate a single email address
$isValid = $validator->validate('test@gmail.com'); // true or false

// Validate multiple email addresses
$allValid = $validator->validateMultiple([
    'test@gmail.com',
    'test@outlook.com'
]); // true if all are valid, false otherwise
```

## Providers

EasyEmailValidator supports multiple providers to fetch lists of allowed or denied email domains.  
A **provider** is a PHP class that extends the abstract class `ProviderAbstract` and implements two methods:

- `getAllowDomains(): array` — returns an array of allowed domains (can be empty if not used).
- `getDenyDomains(): array` — returns an array of denied domains.

### Available Providers

Several providers are included by default:

- **AmieiroProvider**  
  Uses the public lists from [amieiro/disposable-email-domains](https://github.com/amieiro/disposable-email-domains).

- **AdamLovingProvider**  
  Uses the list from [Adam Loving's gist](https://gist.github.com/adamloving/4401361).

- **DisposableProvider**  
  Uses the list from [disposable/disposable-email-domains](https://github.com/disposable/disposable-email-domains).

### How to Use a Provider

You can specify the provider to use when creating the validator:

```php
use Auvernhat\EasyEmailValidator\EasyValidator;
use Auvernhat\EasyEmailValidator\Providers\AdamLovingProvider;

$validator = new EasyValidator(new AdamLovingProvider());
$isValid = $validator->validate('test@gmail.com');
```

If you don't specify a provider, the default is `DisposableProvider`:

```php
$validator = new EasyValidator(); // Uses DisposableProvider by default
```

### How to Create Your Own Provider

To add your own provider, create a new class in `src/Providers/` that extends `ProviderAbstract` and implements the required methods:

```php
use Auvernhat\EasyEmailValidator\Providers\ProviderAbstract;

class MyCustomProvider extends ProviderAbstract
{
    public function getAllowDomains(): array
    {
        // Return your list of allowed domains
        return ['example.com'];
        // Return nothing if you only want a deny list
        return [];
    }

    public function getDenyDomains(): array
    {
        // Return your list of denied domains
        return ['tempmail.com', 'mailinator.com'];
        // Return nothing if you only want an allow list
        return [];
    }
}
```


## Example: Allow Only Your Company's Emails

You can create a custom provider that only allows emails from your own company domain. For example, to only accept `@mycompany.com` addresses:

```php
use Auvernhat\EasyEmailValidator\Providers\ProviderAbstract;

class MyCustomProvider extends ProviderAbstract
{
    public function getAllowDomains(): array
    {
        return ['mycompany.com'];
    }

    public function getDenyDomains(): array
    {
        return [];
    }
}

$validator = new EasyValidator(new MyCustomProvider);

$res = $validator->validate("test@gmail.com");
// returns false
$res = $validator->validate("test@mycompany.com");
// returns true
```

This is useful if you want to restrict access or registration to your own organization only.

Simple, fast, and effective for filtering disposable emails!
