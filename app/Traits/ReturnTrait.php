<?php
/**
 * Created by PhpStorm.
 * User: Ludovic
 * Date: 09-11-16
 * Time: 18:02
 */

namespace App\Traits;

trait ReturnTrait
{
    public function beautifyReturn($code, $extra = '')
    {
        switch ($code)
        {
            case 200:
                return $this->beautifyReturnMessage($code, $this->className . ' Successfully ' . $extra);
                break;
            case 400:
                return $this->beautifyReturnMessage($code, $this->className . ' Bad Request', $extra);
                break;
            case 404:
                return $this->beautifyReturnMessage($code, $this->className . ' Not Found', $extra);
                break;
            case 406:
                return $this->beautifyReturnMessage($code, $this->className . ' Not Acceptable', $extra);
                break;
            default:
                return $this->beautifyReturnMessage($code, $this->className . ' Unspecified Error', $extra);
                break;
        }
    }

    public function beautifyReturnMessage($code, $message, $error = '')
    {
        $return = [
            'StatusCode' => $code,
            'StatusMessage' => $message
        ];

        if($error != '')
            $return['Error'] = $error;

        return Response()->json($return);
    }
}