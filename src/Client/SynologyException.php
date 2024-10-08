<?php

namespace Kazio73\SynologyApiClient\Client;

use Exception;

/**
 * Class SynologyException.
 */
class SynologyException extends Exception
{
    public function __construct($message = null, $code = null)
    {
        if (empty($message)) {
            switch ($code) {
                case 101:
                    $message = 'Invalid parameter';
                    break;
                case 102:
                    $message = 'The requested API does not exist';
                    break;
                case 103:
                    $message = 'The requested method does not exist';
                    break;
                case 104:
                    $message = 'The requested version does not support the functionality';
                    break;
                case 105:
                    $message = 'The logged in session does not have permission';
                    break;
                case 106:
                    $message = 'Session timeout';
                    break;
                case 107:
                    $message = 'Session interrupted by duplicate login';
                    break;
                case 400:
                    $message = 'No such account or incorrect password';
                    break;
                case 401:
                    $message = 'Guest account disabled';
                    break;
                case 402:
                    $message = 'Account disabled';
                    break;
                case 403:
                    $message = 'Wrong password';
                    break;
                case 404:
                    $message = 'Permission denied';
                    break;
                case 407:
                    $message = 'Operation not permitted';
                    break;
                case 408:
                    $message = 'No such file or directory';
                    break;
                case 1003;
                    $message = 'Cannot overwrite or skip the existing file because no overwrite parameter is given';
                    break;
                default:
                    $message = 'Unknown';
                    break;
            }
        }
        parent::__construct($message, $code);
    }
}
