# Laravel Microsoft Graph Mail

**----- WORKS WITH LARAVEL 9 -----**

Originally forked from wapacro/laravel-msgraph-mail, but added a lot of new features, and updated it to work with changes to Laravel 9.

This package makes it easy to send, read, and manage emails from your personal, work or school account using Microsoft's Graph API,
allowing you to benefit from HTTP instead of SMTP/IMAP/POP with Laravel.

_Tested with different company (Microsoft 365 Business) accounts_

## Installation

Install the package using composer:

```
composer require poseidonphp/laravel-msgraph-mail
```

Add the configuration to your mail.php config file:

```php
'mailers' => [

    'microsoft-graph' => [
        'transport' => 'microsoft-graph',
        'tenant' => env('MAIL_MSGRAPH_TENANT', 'common'),
        'client' => env('MAIL_MSGRAPH_CLIENT'),
        'secret' => env('MAIL_MSGRAPH_SECRET'),
        'saveToSentItems' => env('MAIL_MSGRAPH_SAVE_TO_SENT_ITEMS', true)
    ]

    // ...

]
```

Valid values for `tenant` are your tenant identifier (work & school accounts) or `common` for personal accounts.

**Note:** This package relies on [Laravel's Cache](https://laravel.com/docs/cache) interface for caching access tokens.
Make sure to configure it properly, too!

### Version

The latest version is only compatible with Laravel 9.x.

| Package Version | Laravel Version |
|-----------------|-----------------|
| ^1.0            | 7.x             |
| ^2.0            | 8.x             |
| ^3.0            | 9.x             |


### Getting the credentials

To get the necessary client ID and secret you'll need to register your application and grant it the required
permissions. Head over to [the Azure Portal to do so](https://docs.microsoft.com/en-us/graph/auth-register-app-v2)
(you don't need to be an Azure user).

Make sure to grant the _Mail.Send_ permission and to generate a secret afterwards (may be hidden during app registration).

**Permissions:**
* MailboxSettings.Read - needed to read outlook/mail categories
* Mail.Read - Needed if reading mail from mailboxes
* Mail.ReadWrite - Needed if Reading/writing/managing mail in mailboxes
* Mail.Send - Send mail using GraphAPI


**Work & School accounts:** Granting your app the _Mail.Send_ permission allows you by default to send emails with every
valid email address within your company/school. Use an [Exchange Online Application Access Policy](https://docs.microsoft.com/en-us/graph/auth-limit-mailbox-access)
to restrict which email addresses are valid senders for your application.


### Usage
Set the mail driver to be "microsoft-graph"
