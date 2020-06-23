<?php

public function getContent($url, $data)
    {
        $result = CookieModel::find()->where(['app_id' => 'appKItilB1z3297'])->one();

        $cookie = $result->cookie;
        if (is_array($data)) {
            ksort($data);
            $data = http_build_query($data);

        }

        if(is_array($cookie)) {
            $str = '';
            foreach ($cookie as $key => $value) {
                $str .= $key.'='.$value.'; ';
            }

            $cookie = substr($str,0,-1);
        }

        $opts = array(
            'http'=>array(
                'method'=>"POST",
                'header'=>"Content-type: application/x-www-form-urlencoded\r\n".
                    "Content-length:".strlen($data)."\r\n" .
                    "Cookie: ".$cookie."\r\n" .
                    "\r\n",
                'content' => $data,
            )

        );

        $context = stream_context_create($opts);
        $ret = file_get_contents($url, false, $context);

        return $ret;
    }
