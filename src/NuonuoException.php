<?php
/**
 * 凯拓软件 [临渊羡鱼不如退而结网,凯拓与你一同成长]
 * @package topphp-nuonuo-invoice
 * @date 2020/7/31 10:25
 * @author sleep <sleep@kaituocn.com>
 */

namespace Topphp\TopphpNuonuoInvoice;

use Exception;

class NuonuoException extends Exception
{
    public function errorMessage()
    {
        return $this->getMessage();
    }
}
