<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    //接口返回异常
    public function render($request, Exception $exception)
    {
        return response()->json(['code'=>$exception->getCode(),'msg'=>$exception->getMessage()]);
    }

}
