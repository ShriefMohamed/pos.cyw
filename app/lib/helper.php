<?php


namespace Framework\lib;


trait Helper
{
    public static function Hash($string): string
    {
        if ($string) {
            $cipher = new Cipher();
            return $cipher->Hash($string);
        } else {
            return false;
        }
    }

    public static function AppendLoggedin($arr = []): array
    {
        return $arr += ["Admin" => Session::Get('loggedin')->username];
    }

    public static function TimeElapsed($datetime, $full = false): string
    {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public static function DateDiff($date, $date1, $minutes = false)
    {
        $date = new \DateTime($date);
        $date1 = new \DateTime($date1);
        $diff = $date->diff($date1);

        if ($minutes != false) {
            $minutes = $diff->days * 24 * 60;
            $minutes += $diff->h * 60;
            $minutes += $diff->i;

            $diff = $minutes;
        }

        return $diff;
    }

    public static function ConvertDateFormat($date, $date_time = false)
    {
        if ($date) {
            $date = new \DateTime($date);
            return ($date_time != false) ? $date->format(DATE_TIME_FORMAT) : $date->format(DATE_FORMAT);
        } else {
            return false;
        }
    }

    public static function ReArrayFiles(&$file_post): array
    {
        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

    public static function getArrayKey($array, $input) {
        $output = array_keys($array, $input);
        return $output[0];
    }

    public static function sortArrayByArray(array $array, array $orderArray): array
    {
        $ordered = array();
        foreach ($orderArray as $key) {
            if (in_array($key, $array)) {
                $ordered[] = $key;
                unset($array[array_search($key, $array)]);
            }
        }
        return $ordered + $array;
    }

    public static function mergeAlternativeCategories(array &$categories, array $alternative_categories): array
    {
        foreach ($alternative_categories as $alternative_category) {
            $pos = array_search($alternative_category, array_values($categories));
            if ($pos !== false) {
                $categories = array_merge(
                    array_slice($categories, 0, $pos + 1),
                    ['Alternative '.$alternative_category],
                    array_slice($categories, $pos + 1)
                );
            }
        }
        return $categories;
    }

    public static function CompressImage($source, $destination, $quality = '75')
    {
        if ($source && $destination) {
            $info = getimagesize($source);

            if ($info['mime'] == 'image/jpeg')
                $image = imagecreatefromjpeg($source);
            elseif ($info['mime'] == 'image/gif')
                $image = imagecreatefromgif($source);
            elseif ($info['mime'] == 'image/png')
                $image = imagecreatefrompng($source);

            return (imagejpeg($image, $destination, $quality)) ? true : false;
        }
    }

    public static function CalculateUpcCheckDigit($upc_code): int
    {
        $checkDigit = -1; // -1 == failure
        $upc = substr($upc_code,0,11);
        // send in a 11 or 12 digit upc code only
        if (strlen($upc) == 11 && strlen($upc_code) <= 12) {
            $oddPositions = $upc[0] + $upc[2] + $upc[4] + $upc[6] + $upc[8] + $upc[10];
            $oddPositions *= 3;
            $evenPositions= $upc[1] + $upc[3] + $upc[5] + $upc[7] + $upc[9];
            $sumEvenOdd = $oddPositions + $evenPositions;
            $checkDigit = (10 - ($sumEvenOdd % 10)) % 10;
        }
        return $checkDigit;
    }

    public static function GenerateTemplate($template_file, $variables)
    {
//        $input = file_get_contents(EMAIL_TEMPLATES_PATH . $template_file);
//        preg_match_all("~{{(.*?)}}~", $input, $output);
//        print_r($output[1]);
//        array_combine($output[1], $variables);

        // check if the file exists, if not then create it.
        if (!file_exists(EMAIL_TEMPLATES_PATH . $template_file . '.html')) {
            touch(EMAIL_TEMPLATES_PATH . $template_file . '.html');
        }
        // get the content of the file.
        $template = file_get_contents(EMAIL_TEMPLATES_PATH . $template_file . '.html');
        // replace the variables from the file with the actual data.
        foreach ($variables as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }

    public static function SetFeedback($type, $feedback)
    {
        Session::Append('messages', ['type' => $type, 'message' => $feedback]);
    }

    public static function CustomerLogger($id): \Monolog\Logger
    {
        return LoggerModel::Instance($id, 'customers')->InitializeLogger();
    }

    public static function GetUserIpAddress()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    public static function getLicenseExpirationPeriod($years, $months): string
    {
        $expiration_period = $years > 0 ? $years." Year".($years > 1 ? 's' : '') : "";
        $expiration_period .= $months > 0
            ? $expiration_period
                ? " and ".$months." Month". ($months > 1 ? 's' : '')
                : $months." Month".($months > 1 ? 's' : '')
            : "";
        return $expiration_period;
    }

    public static function ReturnOnlyNonFalse($args)
    {
        foreach ($args as $variable) {
            if ($variable !== false) {
                return $variable;
            }
        }
        return false;
    }
}