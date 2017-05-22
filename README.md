# Aliyun MNS Queue Driver For Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

## Install

Via Composer

``` bash
$ composer require chenzi/laravel-mns-driver
```

## Config

Add following service providers into your providers array in `config/app.php`

``` php
Chenzi\LaravelMNS\LaravelMNSServiceProvider::class
```

Edit your `config/queue.php`, add `mns` connection

```php
'mns'        => [
    'sms'=> [
        'driver'       => 'mns',
        'key'          => env('QUEUE_MNS_ACCESS_KEY'),
        'secret'       => env('QUEUE_MNS_SECRET_KEY'),
        'endpoint'     => env('QUEUE_MNS_ENDPOINT'),
        'queue'        => env('QUEUE_NAME'),
        'wait_seconds' => 30,
    ],
    'email'=> [
        'driver'       => 'mns',
        'key'          => env('QUEUE_MNS_ACCESS_KEY'),
        'secret'       => env('QUEUE_MNS_SECRET_KEY'),
        'endpoint'     => env('QUEUE_MNS_ENDPOINT'),
        'queue'        => env('QUEUE_NAME'),
        'wait_seconds' => 30,
    ]
]
```
About [wait_seconds](https://help.aliyun.com/document_detail/35136.html)

Edit your `.env` file

```bash
QUEUE_DRIVER=mns
QUEUE_NAME=foobar-local
QUEUE_MNS_ACCESS_KEY=your_acccess_key
QUEUE_MNS_SECRET_KEY=your_secret_key
QUEUE_MNS_ENDPOINT=http://12345678910.mns.cn-hangzhou.aliyuncs.com/
```
You should update `QUEUE_MNS_ENDPOINT` to `internal endpoint` in production mode

## Usage

First create a queue and get queue endpoint at [Aliyun MNS Console](https://mns.console.aliyun.com/)

Then update `MNS_ENDPOINT` in `.env`

Push a test message to queue

```php
Queue::push(function($job){
	/**
	 * Your statments go here
	 */
	$job->delete();
});
```

Create queue listener, run command in terminal

```bash
$ php artisan queue:mns:work sms
```
## Commands
Flush MNS messages on Aliyun

```bash
$ php artisan queue:mns:flush
```

## Security

Create RAM access control at [Aliyun RAM Console](https://ram.console.aliyun.com)

1. Create a custom policy such as `AliyunMNSFullAccessFoobar`

	```
	{
	  "Version": "1",
	  "Statement": [
		{
		  "Action": "mns:*",
		  "Resource": [
			"acs:mns:*:*:*/foobar-local",
			"acs:mns:*:*:*/foobar-sandbox",
			"acs:mns:*:*:*/foobar-production"
		  ],
		  "Effect": "Allow"
		}
	  ]
	}
	```

2. Create a user for you app such as `foobar`

3. Assign the policy `AliyunMNSFullAccessFoobar` to the user `foobar`

4. Create and get the `AccessKeyId` and `AccessKeySecret` for user `foorbar`

5. update `QUEUE_MNS_ACCESS_KEY` and `QUEUE_MNS_ACCESS_SECRET` in `.env`

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Credits

- [Loki Else][link-author]
- [abrahamgreyson](https://github.com/abrahamgreyson/laravel-mns)
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/chenzi/laravel-mns-driver.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/chenzi/laravel-mns-driver/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/chenzi/laravel-mns-driver.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/chenzi/laravel-mns-driver.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/chenzi/laravel-mns-driver.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/chenzi/laravel-mns-driver
[link-travis]: https://travis-ci.org/chenzi/laravel-mns-driver
[link-scrutinizer]: https://scrutinizer-ci.com/g/chenzi/laravel-mns-driver/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/chenzi/laravel-mns-driver
[link-downloads]: https://packagist.org/packages/chenzi/laravel-mns-driver
[link-author]: https://github.com/chenzi
[link-contributors]: ../../contributors
