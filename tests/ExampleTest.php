<?php

declare(strict_types=1);

namespace Topphp\Test;

use PHPUnit\Framework\TestCase;
use Topphp\TopphpNuonuoInvoice\NuoNuo;
use Topphp\TopphpNuonuoInvoice\NuonuoException;

class ExampleTest extends TestCase
{

    /**
     * @author sleep
     * ==============================================================================测试环境==============================================================================
     */
    public function testGetMerchantToken()
    {
        try {
            $res = NuoNuo::instance()
                ->setAppKey("SD15125971")
                ->setAppSecret("SD354602BB0B48D0")
                ->getMerchantToken();
            $res = json_decode($res, true);
            var_dump($res);
            $this->assertIsString($res['access_token']);
        } catch (NuonuoException $e) {
            var_dump($e->errorMessage());
        }
    }

    public function testQueryInvoiceQuantity()
    {
        try {
            $senId = md5(uniqid());
            $res   = NuoNuo::instance()
                ->setAppKey("SD15125971")
                ->setAppSecret("SD354602BB0B48D0")
                ->sendPostSyncRequest(
                    $senId,
                    '8001a531020bc360ebdb7b0bggcwg8xs',
                    '91120111MA05K2XKX9',
                    'nuonuo.electronInvoice.queryInvoiceQuantity',
                    json_encode([
                        'taxnum'           => '91120111MA05K2XKX9',
                        'invoiceTimeStart' => '2018-03-19 00:00:00',
                        'invoiceTimeEnd'   => '2019-04-23 23:59:59',
                    ])
                );
            var_dump($res);
        } catch (NuonuoException $e) {
            var_dump($e->errorMessage());
        }
    }


    /**
     * ==============================================================================正式环境============================================================================================================================================================
     * @author sleep
     */
    public function testProdGetMerchantToken()
    {
        try {
            $res = NuoNuo::instance()
                ->setEnv('prod')
                ->setAppKey("15125971")
                ->setAppSecret("FB354602BB0B48D0")
                ->getMerchantToken();
            $res = json_decode($res, true);
            var_dump($res);
            $this->assertIsString($res['access_token']);
        } catch (NuonuoException $e) {
            var_dump($e->errorMessage());
        }
    }

    /**
     * 企业开票量查询
     * @author sleep
     */
    public function testProdQueryInvoiceQuantity()
    {
        try {
            $senId = md5(uniqid());
            $res   = NuoNuo::instance()
                ->setEnv('prod')
                ->setAppKey("15125971")
                ->setAppSecret("FB354602BB0B48D0")
                ->sendPostSyncRequest(
                    $senId,
                    '8001a531020bc360ebdb7b0nktvjmkts',
                    '',
                    'nuonuo.electronInvoice.queryInvoiceQuantity',
                    json_encode([
                        'taxnum'           => '91120111MA05K2XKX9',
                        'invoiceTimeStart' => '2020-01-01 00:00:00',
                        'invoiceTimeEnd'   => '2020-07-31 23:59:59',
                    ])
                );
            var_dump($res);
        } catch (NuonuoException $e) {
            var_dump($e->errorMessage());
        }
    }

    /**
     * @author sleep
     * nuonuo.ElectronInvoice.queryInvoiceResult
     */
    public function testProdQueryInvoiceResult()
    {
        $res = NuoNuo::instance()
            ->setEnv('prod')
            ->setAppKey("15125971")
            ->setAppSecret("FB354602BB0B48D0")
            ->sendPostSyncRequest(
                md5(uniqid()),
                '8001a531020bc360ebdb7b0nktvjmkts',
                '',
                'nuonuo.ElectronInvoice.queryInvoiceResult',
                json_encode([
                    'serialNos'            => ['01200190010407781284'],
                    'isOfferInvoiceDetail' => '1',
                ])
            );
        var_dump(json_decode($res, true));
    }

    /**
     * 发票列表查询
     * @author sleep
     */
    public function testProdQueryInvoiceList()
    {
        try {
            $res = NuoNuo::instance()
                ->setEnv('prod')
                ->setAppKey("15125971")
                ->setAppSecret("FB354602BB0B48D0")
                ->sendPostSyncRequest(
                    uniqid(),
                    "8001a531020bc360ebdb7b0nktvjmkts",
                    '',
                    'nuonuo.ElectronInvoice.queryInvoiceList',
                    json_encode([
                        'taxnum'    => '91120111MA05K2XKX9',
                        'startTime' => '2020-01-06 00:00:00',
                        'endTime'   => '2020-01-10 23:59:59',
                        'pageNo'    => "1",
                        "pageSize"  => "20"
                    ])
                );
            var_dump($res);
        } catch (NuonuoException $e) {
            var_dump($e->getMessage());
        }
    }

    public function testProdGetPDF()
    {
        try {
            $res = NuoNuo::instance()
                ->setEnv('prod')
                ->setAppKey("15125971")
                ->setAppSecret("FB354602BB0B48D0")
                ->sendPostSyncRequest(
                    uniqid(),
                    "8001a531020bc360ebdb7b0nktvjmkts",
                    '',
                    'nuonuo.ElectronInvoice.getPDF',
                    json_encode([
                        'invoiceCode' => '1200192130',
                        'invoiceNo'   => '01465694',
                        "exTaxAmount" => "3883.50"
                    ])
                );
            var_dump($res);
        } catch (NuonuoException $e) {
            var_dump($e->getMessage());
        }
    }

    public function testDump()
    {
        $a = "{\"code\":\"E0000\",\"describe\":\"调用成功\",\"result\":{\"totalCount\":1,\"invoices\":[{\"serialNo\":\"01200190010407781284\",\"sellerTaxNo\":\"91120111MA05K2XKX9\",\"orderNo\":\"5925527515117781284\",\"status\":\"2\",\"invoiceCode\":\"012001900104\",\"invoiceNo\":\"07781284\",\"exTaxAmount\":1980.20,\"taxAmount\":19.80,\"payerName\":\"中共天津市委支部生活社\",\"invoiceKind\":\"c\",\"addTime\":\"2020-06-19 15:45:46\",\"invoiceTime\":\"2020-06-19 15:56:07\"}]}}";
        var_dump(json_decode($a, true)['result']);
    }
}
