<h1 align="center">Welcome to Silverstripe Mailchimp Sync 👋</h1>
<p>
</p>

> Sync Silverstripe Member to Mailchimp

## Install

```sh
composer require jinjie/silverstripe-mailchimp
```

## How to use

- Setup [Mailchimp API](https://mailchimp.com/help/about-api-keys/) by including variables to .env
    - MAILCHIMP_API_KEY (API KEY)
    - MAILCHIMP_SERVER (Server prefix. Eg, us20)
- Run `{BaseHref}/dev/build`
- Go to `Settings > Mailchimp` and select the list to be synced. If the there are no lists in the
dropdown, very likely the API is not set up properly.
- Run "Sync Mailchimp" task from `{BaseHref}/dev/tasks` and check result
    - Setup cron to run `dev/tasks/SwiftDevLabs-SSMailchimp-Dev-MailchimpSyncTask` periodically

## Author

👤 **Kong Jin Jie**

* Github: [@jinjie](https://github.com/jinjie)
* Website: [Swift DevLabs](https://www.swiftdev.sg/)

## Show your support

Give a ⭐️ if this project helped you!

***
_This README was generated with ❤️ by [readme-md-generator](https://github.com/kefranabg/readme-md-generator)_
