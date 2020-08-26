# topphp-nuonuo-invoice

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

# 诺诺发票 api

### 代码演示
```php
<?php
//获取token
function testGetMerchantToken()
{
    try {
        $res = NuoNuo::instance()
            ->setAppKey("xxx")
            ->setAppSecret("xxx")
            ->getMerchantToken();
        $res = json_decode($res, true);
        var_dump($res);

    } catch (NuonuoException $e) {
        var_dump($e->errorMessage());
    }
}

//查询开票记录
function testQueryInvoiceQuantity()
{
    try {
        $senId = md5(uniqid());
        $res   = NuoNuo::instance()
            ->setAppKey("SD15125971")
            ->setAppSecret("SD354602BB0B48D0")
            ->sendPostSyncRequest(
                $senId,
                'xxx',
                'xxxx',
                'nuonuo.electronInvoice.queryInvoiceQuantity',
                json_encode([
                    'taxnum'           => 'xxxx',
                    'invoiceTimeStart' => '2018-03-19 00:00:00',
                    'invoiceTimeEnd'   => '2019-04-23 23:59:59',
                ])
            );
        var_dump($res);
    } catch (NuonuoException $e) {
        var_dump($e->errorMessage());
    }
}
```

## Structure
> 组件结构

```
bin/        
build/
docs/
config/
src/
tests/
vendor/
```


## Install

Via Composer

``` bash
$ composer require topphp/topphp-nuonuo-invoice
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email sleep@kaituocn.com instead of using the issue tracker.

## Credits

- [topphp][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/topphp/topphp-nuonuo-invoice.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/topphp/topphp-nuonuo-invoice/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/topphp/topphp-nuonuo-invoice.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/topphp/topphp-nuonuo-invoice.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/topphp/topphp-nuonuo-invoice.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/topphp/topphp-nuonuo-invoice
[link-travis]: https://travis-ci.org/topphp/topphp-nuonuo-invoice
[link-scrutinizer]: https://scrutinizer-ci.com/g/topphp/topphp-nuonuo-invoice/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/topphp/topphp-nuonuo-invoice
[link-downloads]: https://packagist.org/packages/topphp/topphp-nuonuo-invoice
[link-author]: https://github.com/topphp
[link-contributors]: ../../contributors
