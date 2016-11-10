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
    public function beautifyReturn($code, $extra = array())
    {
        switch ($code) {
            case 200:
                return $this->beautifyReturnMessage($code, $this->className . ' Successfully ' . $extra['Extra'], (isset($extra[$this->className . 'ID'])) ? $extra[$this->className . 'ID'] : '');
                break;
            case 400:
                return $this->beautifyReturnMessage($code, $this->className . ' Bad Request', $extra['Error']);
                break;
            case 404:
                return $this->beautifyReturnMessage($code, $this->className . ' Not Found', $extra['Error']);
                break;
            case 406:
                return $this->beautifyReturnMessage($code, $this->className . ' Not Acceptable', $extra['Error']);
                break;
            default:
                return $this->beautifyReturnMessage($code, $this->className . ' Unspecified Error', $extra['Error']);
                break;
        }
    }

    public function beautifyReturnMessage($code, $message, $extra = '')
    {
        $return = [
            'StatusCode' => $code,
            'StatusMessage' => $message
        ];

        if ($extra != '') {
            if ($code == 200)
                $return[$this->className . 'ID'] = $extra;
            else
                $return['Error'] = $extra;
        }


        return Response()->json($return);
    }
}