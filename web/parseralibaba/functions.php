<?php

function isSuccessDataAlibaba(&$row)
{return true;
    if (!isset($GLOBALS['GLOBALS']['GLOBALS.Categories.Tree'])) {
        $GLOBALS['GLOBALS']['GLOBALS.Categories.Tree'] = array();
        foreach (xls2arr(dirname(__dir__ ) . '/' . 'data/Categories.Tree.xlsx') as $key =>
            $val) {
            $val[0] = trim($val[0]);

            if ($val[0] != '' && $val[0] != 'Categories: Tree') {
                $GLOBALS['GLOBALS']['GLOBALS.Categories.Tree'][$val[0]] = $val[0];
            }
        }
    }

    if (!isset($GLOBALS['GLOBALS']['GLOBALS.config.Categories.Root'])) {
        $GLOBALS['GLOBALS']['GLOBALS.config.Categories.Root'] = array();

        foreach (explode('|', config('listCategoriesRoot')) as $key => $val) {
            $val = trim($val);
            if ($val != '')
                $GLOBALS['GLOBALS']['GLOBALS.config.Categories.Root'][$val] = $val;
        }
    }

    if (!isset($GLOBALS['GLOBALS']['GLOBALS.Categories.Root'])) {
        $GLOBALS['GLOBALS']['GLOBALS.Categories.Root'] = array_map(function ($item)
        {
            return str_getcsv($item, ';'); }
        , file(__dir__ . '/data/Categories Root.csv'));
    }

    $success = true;

    if ($row['Find_Ali'] == 'img') {
        if ($row['PriceMax_Ali'] && $row['Find_PriceMin_Ali']) {
            $PriceMax_Ali = parsefloatstrval($row['PriceMax_Ali']);
            $FindPriceMin_Ali = parsefloatstrval($row['Find_PriceMin_Ali']);

            if ($PriceMax_Ali) {
                if (!($PriceMax_Ali > $FindPriceMin_Ali)) {
                    $success = false;
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                        ' - fail Find_PriceMin_Ali', 'w');
                }
            }
        }
        
        if ($row['PriceMax_Ali'] && $row['Find_PriceMax_Ali']) {
            $PriceMax_Ali = parsefloatstrval($row['PriceMax_Ali']);
            $FindPriceMax_Ali = parsefloatstrval($row['Find_PriceMax_Ali']);

            if ($PriceMax_Ali) {
                if (!($PriceMax_Ali < $FindPriceMax_Ali)) {
                    $success = false;
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                        ' - fail Find_PriceMax_Ali', 'w');
                }
            }
        }

        if (is_array($row['QueryFind']) && isset($row['QueryFind']['moqt']) && $row['QueryFind']['moqt'] && $row['MOQ_Ali']) {
            if (parsefloatstrval($row['MOQ_Ali']) < parsefloatstrval($row['QueryFind']['moqt'])) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail MOQ_Ali', 'w');
            }
        }

        if (config('ali_checking_yrs') && !($row['Find_Ali'] == 'img' && config('ali_search_img_more') ==
            '1')) {
            $row['CheckingFind']['Yrs'] = config('ali_checking_yrs');

            if ($row['Yrs_Ali'] >= config('ali_checking_yrs')) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail Yrs_Ali', 'w');
            }
        }

        if (config('ali_trade_ashurance')) {
            if ($row['Trade$_Ali'] != 'yes') {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail Trade Ashurance', 'w');
            }
        }

        if (config('ali_verified_suplier')) {
            if ($row['Verified_Ali'] != 'yes') {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail Trade Ashurance', 'w');
            }
        }
        
        if (config('ali_find_manufacturer') && $row['Seller_Ali'] && $row['Manufacturer']) {
            if (stripos($row['Seller_Ali'], $row['Manufacturer']) === false) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail to Manufacturer in Seller_Ali', 'w');
            }
        }
    } else {
        $delete_SalesRankCurrent = null;

        if (config('delete_SalesRankCurrent')) {
            $delete_SalesRankCurrent = floatstrval(config('delete_SalesRankCurrent'));
        }

        if ($delete_SalesRankCurrent == '0')
            $delete_SalesRankCurrent = 100000;

        if (config('ali_checking_rating') && !($row['Find_Ali'] == 'img' && config('ali_search_img_more') ==
            '1')) {
            $row['CheckingFind']['Rating'] = config('ali_checking_rating');

            if ($row['Rating_Ali'] >= config('ali_checking_rating')) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail Rating_Ali', 'w');
            }
        }

        if ($row['PriceMax_Ali'] && $row['Find_PriceMin_Ali']) {
            $PriceMax_Ali = parsefloatstrval($row['PriceMax_Ali']);
            $FindPriceMin_Ali = parsefloatstrval($row['Find_PriceMin_Ali']);

            if ($PriceMax_Ali) {
                if (!($PriceMax_Ali > $FindPriceMin_Ali)) {
                    $success = false;
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                        ' - fail Find_PriceMin_Ali', 'w');
                }
            }
        }

        if ($row['PriceMax_Ali'] && $row['Find_PriceMax_Ali']) {
            $PriceMax_Ali = parsefloatstrval($row['PriceMax_Ali']);
            $FindPriceMax_Ali = parsefloatstrval($row['Find_PriceMax_Ali']);

            if ($PriceMax_Ali) {
                if (!($PriceMax_Ali < $FindPriceMax_Ali)) {
                    $success = false;
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                        ' - fail Find_PriceMax_Ali', 'w');
                }
            }
        }

        if (config('ali_checking_reviews') && !($row['Find_Ali'] == 'img' && config('ali_search_img_more') ==
            '1')) {
            $row['CheckingFind']['Reviews'] = config('ali_checking_reviews');

            if ($row['Reviews_Ali'] >= config('ali_checking_reviews')) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail Reviews_Ali', 'w');
            }
        }

        if (config('ali_checking_yrs') && !($row['Find_Ali'] == 'img' && config('ali_search_img_more') ==
            '1')) {
            $row['CheckingFind']['Yrs'] = config('ali_checking_yrs');

            if ($row['Yrs_Ali'] >= config('ali_checking_yrs')) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail Yrs_Ali', 'w');
            }
        }

        if (config('ali_checking_shipping') == '1' && !($row['Find_Ali'] == 'img' &&
            config('ali_search_img_more') == '1')) {
            $row['CheckingFind']['Yrs'] = config('ali_checking_shipping') ? 'yes' : 'no';

            if ($row['Shipping'] != 'yes') {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail Shipping', 'w');
            }
        }

        if (config('ali_checking_image') == '1') {
            $row['CheckingFind']['%Image'] = config('ali_checking_image');
        }

        foreach (explode(',', config('ali_features')) as $feature) {
            $row['CheckingFind']['Features'] = config('ali_features');

            $feature = trim($feature);

            if ($feature && !in_array($feature, $row['Features']) && !($row['Find_Ali'] ==
                'img' && config('ali_search_img_more') == '1')) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail Features', 'w');
            }
        }

        if (config('ali_checking_searсh_ali')) {
            if (!stripos($row['Find_Ali'], '/')) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail Find_Ali', 'w');
            }
        }

        if ($row['Find_Ali'] == 'img' && config('ali_search_img_more') != '1') {
            $row['CheckingFind']['Find_PriceMin_Ali'] = $row['PriceMax_Ali'];

            if (!($row['Find_PriceMin_Ali'] <= $row['PriceMax_Ali'] && $row['PriceMax_Ali'] <=
                $row['Find_PriceMax_Ali'])) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail to Price', 'w');
            }

            if (config('ali_ready_to_ship')) {
                $row['CheckingFind']['Ready To Ship'] = config('ali_ready_to_ship') ? 'yes' :
                    'no';

                if ($row['Ready To Ship'] != 'yes') {
                    $success = false;
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                        ' - fail to Ready To Ship', 'w');
                }
            }

            if (config('ali_verified_suplier')) {
                $row['CheckingFind']['Verified_Ali'] = config('ali_verified_suplier') ? 'yes' :
                    'no';

                if ($row['Verified_Ali'] != 'yes') {
                    $success = false;
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                        ' - fail to Verified_Ali', 'w');
                }
            }

            if (config('ali_trade_ashurance')) {
                $row['CheckingFind']['Trade Ashurance'] = config('ali_trade_ashurance');


            }

            if (config('ali_country')) {
                $row['CheckingFind']['CountryS_Ali'] = config('ali_country');

                if (stripos($row['CountryS_Ali'], config('ali_country')) === false) {
                    $success = false;
                    log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                        ' - fail to CountryS_Ali', 'w');
                }
            }
        }

        if (config('ali_open_page') && config('ali_checking_weight')) {
            $row['CheckingFind']['%Weight'] = config('ali_checking_weight');

            if (abs($row['%Weight']) >= config('ali_checking_weight')) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail to %Weight', 'w');
            }
        }

        if ($row['Model'] == 'no' && config('ali_checking_model_check') == '1') {
            $row['CheckingFind']['Model'] = config('ali_checking_model_check');

            $success = false;
            log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                ' - fail to Model', 'w');
        }

        if (config('ali_open_page') && config('ali_checking_est_time')) {
            $row['CheckingFind']['Est Time'] = config('ali_checking_est_time');

            if ($row['EstTime_Ali'] > config('ali_checking_est_time')) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail to EstTime_Ali', 'w');
            }
        }

        if (config('ali_open_page') && config('ali_checking_color') && $row['Color_Ali']) {
            $row['CheckingFind']['Color'] = config('ali_checking_color');

            if (stripos($row['Color_Ali'], config('ali_checking_color')) === false) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail to Color_Ali', 'w');
            }
        }

        if (config('ali_open_page') && config('ali_checking_package_size') && $row['%Length'] &&
            $row['%Width'] && $row['%Height']) {
            $row['CheckingFind']['%Package'] = config('ali_checking_package_size');

            if (floatstrval($row['%Length']) > 0 && floatstrval($row['%Width']) > 0 &&
                floatstrval($row['%Height']) > 0 && $row['%Length'] < config('ali_checking_package_size') &&
                $row['%Width'] < config('ali_checking_package_size') && $row['%Height'] < config
                ('ali_checking_package_size')) {

            } else {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail to Package Size', 'w');
            }
        }

        if (config('ali_checking_package_size')) {
            $row['CheckingFind']['%Package'] = config('ali_checking_package_size');

            if ($row['%Package'] >= config('ali_checking_package_size')) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail to %Package', 'w');
            }
        }

        if ($row['priceMin'] && $row['PriceMax_Ali']) {
            $PriceMax_Ali = floatstrval(str_replace("#[^\d.,]#", '', $row['PriceMax_Ali']));

            if ($row['priceMin'] > $PriceMax_Ali) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail priceMin > PriceMax_Ali', 'w');
            }
        }

        if ($row['priceMax'] && $row['PriceMax_Ali']) {
            $PriceMax_Ali = floatstrval(str_replace("#[^\d.,]#", '', $row['PriceMax_Ali']));

            if ($row['priceMax'] < $PriceMax_Ali) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail priceMax < PriceMax_Ali', 'w');
            }
        }

        if (config('ali_find_manufacturer') && $row['Seller_Ali'] && $row['Manufacturer']) {
            if (stripos($row['Seller_Ali'], $row['Manufacturer']) === false) {
                $success = false;
                log_write_echo(dirname(__dir__ ) . '/alibaba.log', $row['Url_Search_Ali'] .
                    ' - fail to Manufacturer in Seller_Ali', 'w');
            }
        }
    }

    return $success;
}

function cmdexec($command)
{
    if (substr(php_uname(), 0, 7) == "Windows") {
        //windows
        pclose(popen("start /B " . $command . " 1> " . __dir__ . "/update_log 2>&1 &",
            "r"));
    } else {
        //linux
        shell_exec($command . " > /dev/null 2>&1 &");
    }
}

function str_limit($value, $limit = 100, $end = '...')
{
    if (mb_strwidth($value, 'UTF-8') <= $limit) {
        return $value;
    }

    return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
}

function translitEnRu($s, $del = '-')
{
    $s = (string )$s; // преобразуем in строкоinое значение

    $s = mb_convert_encoding($s, "UTF-8");

    $s = strip_tags($s); // убираем HTML-теги
    $s = str_replace(array("\n", "\r"), " ", $s); // убираем переinод каретки
    $s = trim($s); // убираем пробелы in начале и конце строки
    $s = preg_replace("/[^a-zA-Z0-9А-Яа-я]/sui", $del, $s); // очищаем строку от недопустимых симinолоin

    $s = preg_replace("#" . ($del == '.' ? '\.' : $del) . "{2,}#", $del, $s);

    $s = trim($s, $del);

    return $s; // inозinращаем результат
}

function translateHtml($html, $type = 1)
{
    if (is_array($html)) {
        $arr_text = [];
        $arr_res = [];

        foreach ($html as $key => $text) {
            $response = prepareHtmlTextBefore($text, $type);

            $arr_text[$key] = $response['text'];
            $arr_res[$key] = $response['res'];
        }

        $result = $GLOBALS['GoogleTranslateForFree']->translate('en', 'ru', $arr_text, 5);

        foreach ($result as $key => $text) {
            if (isset($arr_res[$key])) {
                $result[$key] = prepareHtmlTextAfter($result[$key], $arr_res[$key]);
            }
        }

        return $result;
    } else {
        $response = prepareHtmlTextBefore($html, $type);

        $result = $GLOBALS['GoogleTranslateForFree']->translate('en', 'ru', $response['text'],
            5);

        return prepareHtmlTextAfter($response['text'], $response['res']);
    }
}

function prepareHtmlTextBefore($html, $type = 1)
{
    $i_repl = rand(1000000000000, 9999999999999);

    $arr = [];

    $html = preg_replace_callback("#<pre>.*?</pre>#sui", function ($matches)use (&$arr,
        &$i_repl)
    {
        $key = '<' . ++$i_repl . '>'; $arr[$i_repl] = $matches[0]; return $key; }
    , $html);

    $html = preg_replace_callback("#<code>.*?</code>#sui", function ($matches)use (&
        $arr, &$i_repl)
    {
        $key = '<' . ++$i_repl . '>'; $arr[$i_repl] = $matches[0]; return $key; }
    , $html);

    if ($type == 2) {
        $html = preg_replace_callback("#@[a-zA-Z0-9А-Яа-я_]*#sui", function ($matches)
            use (&$arr, &$i_repl)
        {
            $key = '<' . ++$i_repl . '>'; $arr[$i_repl] = $matches[0]; return $key; }
        , $html);
    }

    $tags = ['!DOCTYPE', 'a', 'abbr', 'address', 'area', 'map', 'article', 'aside',
        'audio', 'b', 'base', 'bdi', 'bdo', 'blockquote', 'body', 'br', 'button',
        'canvas', 'caption', 'table', 'cite', 'code', 'col', 'colgroup', 'data',
        'datalist', 'input', 'option', 'dd', 'dt', 'del', 'details', 'summary', 'dfn',
        'dialog', 'div', 'dl', 'em', 'embed', 'fieldset', 'figcaption', 'figure',
        'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'title', 'meta',
        'script', 'link', 'style', 'header', 'hr', 'html', 'i', 'iframe', 'img', 'ins',
        'kbd', 'label', 'legend', 'li', 'main', 'mark', 'meter', 'nav', 'noscript',
        'object', 'param', 'ol', 'optgroup', 'select', 'output', 'p', 'picture',
        'source', 'pre', 'progress', 'q', 'ruby', 'rb', 'rt', 'rtc', 'rp', 's', 'samp',
        'section', 'small', 'video', 'span', 'strong', 'sub', 'sup', 'tbody', 'td',
        'template', 'textarea', 'tfoot', 'th', 'thead', 'time', 'tr', 'track', 'u', 'ul',
        'var', 'wbr', ];

    $html = preg_replace_callback("#</?(" . implode('|', $tags) . ")[^<>]*?>#sui",
        function ($matches)use (&$arr, &$i_repl)
    {
        $key = '<' . ++$i_repl . '>'; $arr[$i_repl] = $matches[0]; return $key; }
    , $html);

    $html = preg_replace_callback("#(&lt;a)#sui", function ($matches)use (&$arr, &$i_repl)
    {
        $key = '<' . ++$i_repl . '>'; $arr[$i_repl] = $matches[0]; return $key; }
    , $html);

    $html = preg_replace_callback("#(&lt;/&gt;>)#sui", function ($matches)use (&$arr,
        &$i_repl)
    {
        $key = '<' . ++$i_repl . '>'; $arr[$i_repl] = $matches[0]; return $key; }
    , $html);

    return ['text' => $html, 'res' => $arr, ];
}

function prepareHtmlTextAfter($text, $response_before)
{
    if ($text) {
        foreach ($response_before as $key => $value) {
            $text = preg_replace('#<\s*' . $key . '\s*>#', $key, $text);
        }

        $text = str_replace(['<', '>'], ['&lt;', '&gt;'], $text);

        foreach ($response_before as $key => $value) {
            $text = str_replace($key, $value, $text);
        }

        $text = preg_replace('#<\s+\d{10,15}\s+>#', '', $text);

        $text = preg_replace('#href\s*=\s*"#', 'href="', $text);

        $text = preg_replace_callback('#(<a[^<>]*?>)(.*?)(</a>)#sui', function ($matches)
        {
            return $matches[1] . trim($matches[2]) . $matches[3]; }
        , $text);

        return close_tags($text);
    }

    return null;
}

function isInItemTitle($items, $title)
{
    foreach ($items as $item) {
        if ($item['title'] == $title) {
            return true;
        }
    }

    return false;
}

function clearHtml($html)
{
    $html = str_replace("<br>", '<br />', $html);
    $html = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/sui", '<$1$2>', $html);
    $html = str_replace(["<h3>", '</h3>'], ['<h3>', '</h3>'], $html);
    $html = str_replace(["<h2>", '</h2>'], ['<h3>', '</h3>'], $html);
    $html = str_replace(["<h1>", '</h1>'], ['<h3>', '</h3>'], $html);

    $html = preg_replace("/<(a)[^>]*?(\/?)>(.*?)<\/a>/isu", '$3', $html);

    $html = preg_replace("#<script[^<>/]*?>.*?</script>#sui", '', $html);
    $html = preg_replace("#<svg[^<>/]*?>.*?</svg>#sui", '', $html);
    $html = preg_replace("#<iframe[^<>/]*?>.*?</iframe>#sui", '', $html);
    $html = preg_replace("#<frame[^<>/]*?>.*?</frame>#sui", '', $html);
    $html = preg_replace("#<img[^<>/]*?/?>#sui", '', $html);
    $html = preg_replace("#<head[^<>/]*?>.*?</head>#sui", '', $html);

    $html = preg_replace("#<body[^<>/]*?>#sui", '', $html);
    $html = preg_replace("#<html[^<>/]*?>#sui", '', $html);

    $html = str_replace("<body>", '', $html);
    $html = str_replace("</body>", '', $html);
    $html = str_replace("<html>", '', $html);
    $html = str_replace("</html>", '', $html);

    $html = str_replace('спраinка | детали | inалюта | карта страны', '', $html);

    $html = str_replace("<div> <br/><br/>
</div>", '', $html);

    $html = close_tags($html);

    return trim($html);
}

function close_tags($content)
{
    $position = 0;
    $open_tags = array();
    //теги для игнорироinания
    $ignored_tags = array(
        'br',
        'hr',
        'img');

    while (($position = strpos($content, '<', $position)) !== false) {
        //забираем inсе теги из контента
        if (preg_match("|^<(/?)([a-z\d]+)\b[^>]*>|i", substr($content, $position), $match)) {
            $tag = strtolower($match[2]);
            //игнорируем inсе одиночные теги
            if (in_array($tag, $ignored_tags) == false) {
                //тег открыт
                if (isset($match[1]) and $match[1] == '') {
                    if (isset($open_tags[$tag]))
                        $open_tags[$tag]++;
                    else
                        $open_tags[$tag] = 1;
                }
                //тег закрыт
                if (isset($match[1]) and $match[1] == '/') {
                    if (isset($open_tags[$tag]))
                        $open_tags[$tag]--;
                }
            }
            $position += strlen($match[0]);
        } else
            $position++;
    }
    //закрыinаем inсе теги
    foreach ($open_tags as $tag => $count_not_closed) {
        if ($count_not_closed > 0)
            $content .= str_repeat("</{$tag}>", $count_not_closed);
    }

    return $content;
}

function html_remove_attributes($text, $allowed = [])
{
    $attributes = implode('|', $allowed);
    $reg = '/(<[\w]+)([^>]*)(>)/i';
    $text = preg_replace_callback($reg, function ($matches)use ($attributes)
    {
        // Если нет разрешенных атрибутоin, inозinращаем пустой тег
        if (!$attributes) {
            return $matches[1] . $matches[3]; }

        $attr = $matches[2]; $reg = '/(' . $attributes . ')="[^"]*"/i'; preg_match_all($reg,
            $attr, $result); $attr = implode(' ', $result[0]); $attr = ($attr ? ' ' : '') .
            $attr; return $matches[1] . $attr . $matches[3]; }
    , $text);

    return $text;
}

function app()
{
    return \workup\App::instance();
}

function config($key, $value = null)
{
    if (is_null($value)) {
        return app()->config($key);
    } else {
        return app()->config($key, $value);
    }
}

function isAdmin()
{
    return value_get($GLOBALS['config'], 'is_admin');
}

function object_get($object, $keys, $default = null)
{
    if (is_null($keys)) {
        return $object;
    }

    if (!is_array($keys)) {
        $keys = explode('.', $keys);
    }

    foreach ($keys as $segment) {
        if (isset($object->{$segment})) {
            $object = $object->{$segment};
        } else {
            return $default;
        }
    }

    return $object;
}

function array_get($array, $keys, $default = null)
{
    if (is_null($keys)) {
        return $array;
    }

    if (!is_array($keys)) {
        $keys = explode('.', $keys);
    }

    foreach ($keys as $segment) {
        if (isset($array[$segment])) {
            $array = $array[$segment];
        } else {
            return $default;
        }
    }

    return $array;
}

function value_get($value, $keys, $default = null)
{
    if (is_null($keys)) {
        return $value;
    }

    if (!is_array($keys)) {
        $keys = explode('.', $keys);
    }

    foreach ($keys as $segment) {
        if ($pos = stripos($segment, '(')) {
            $method = substr($segment, 0, $pos);
            $args = array_map(function ($item)
            {
                return trim($item); }
            , explode(',', trim(rtrim(substr($segment, $pos + 1), ')'))));

            /*
            print_r($value);echo "\n";
            print_r($segment);echo "\n";
            print_r($method);echo "\n";
            print_r($args);echo "\n";
            
            exit();
            */

            if (is_object($value)) {
                switch (count($args)) {
                    case 1:
                        $value = $value->{$method}($args[0]);
                        break;
                    case 2:
                        $value = $value->{$method}($args[0], $args[1]);
                        break;
                    case 3:
                        $value = $value->{$method}($args[0], $args[1], $args[2]);
                        break;
                    case 4:
                        $value = $value->{$method}($args[0], $args[1], $args[2], $args[3]);
                        break;
                    case 5:
                        $value = $value->{$method}($args[0], $args[1], $args[2], $args[3], $args[4]);
                        break;
                    case 6:
                        $value = $value->{$method}($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
                        break;
                    case 7:
                        $value = $value->{$method}($args[0], $args[1], $args[2], $args[3], $args[4], $args[5],
                            $args[6]);
                        break;
                    case 8:
                        $value = $value->{$method}($args[0], $args[1], $args[2], $args[3], $args[4], $args[5],
                            $args[6], $args[7]);
                        break;
                    case 9:
                        $value = $value->{$method}($args[0], $args[1], $args[2], $args[3], $args[4], $args[5],
                            $args[6], $args[7], $args[8]);
                        break;
                    case 10:
                        $value = $value->{$method}($args[0], $args[1], $args[2], $args[3], $args[4], $args[5],
                            $args[6], $args[7], $args[8], $args[9]);
                        break;
                    default:
                        $value = $value->{$method}();
                        break;
                }
            } elseif (is_array($value)) {
                switch (count($args)) {
                    case 1:
                        $value = $value[$method]($args[0]);
                        break;
                    case 2:
                        $value = $value[$method]($args[0], $args[1]);
                        break;
                    case 3:
                        $value = $value[$method]($args[0], $args[1], $args[2]);
                        break;
                    case 4:
                        $value = $value[$method]($args[0], $args[1], $args[2], $args[3]);
                        break;
                    case 5:
                        $value = $value[$method]($args[0], $args[1], $args[2], $args[3], $args[4]);
                        break;
                    case 6:
                        $value = $value[$method]($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
                        break;
                    case 7:
                        $value = $value[$method]($args[0], $args[1], $args[2], $args[3], $args[4], $args[5],
                            $args[6]);
                        break;
                    case 8:
                        $value = $value[$method]($args[0], $args[1], $args[2], $args[3], $args[4], $args[5],
                            $args[6], $args[7]);
                        break;
                    case 9:
                        $value = $value[$method]($args[0], $args[1], $args[2], $args[3], $args[4], $args[5],
                            $args[6], $args[7], $args[8]);
                        break;
                    case 10:
                        $value = $value[$method]($args[0], $args[1], $args[2], $args[3], $args[4], $args[5],
                            $args[6], $args[7], $args[8], $args[9]);
                        break;
                    default:
                        $value = $value[$method]();
                        break;
                }
            } else {
                return $default;
            }
        } else {
            if (is_object($value) && isset($value->{$segment})) {
                $value = $value->{$segment};
            } elseif (is_array($value) && isset($value[$segment])) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }
    }

    return $value;
}

function value_set(&$value, $keys, $var, $recurs = true)
{
    if (is_null($keys)) {
        return $value = $var;
    }

    if (!is_array($keys)) {
        $keys = explode('.', $keys);
    }

    while ($count = count($keys) > 1) {
        $key = array_shift($keys);

        if (is_object($value)) {
            if ($recurs) {
                if (!isset($value->{$key})) {
                    $value->{$key} = new \Sirius\Base\BaseObj;
                }

                if (!is_object($value->{$key})) {
                    $value->{$key} = new \Sirius\Base\BaseObj([$value->{$key}]);
                }
            } else {
                if (!isset($value->{$key}) || !is_object($value->{$key})) {
                    return false;
                }
            }

            $value = $value->{$key};
        } elseif (is_array($value)) {
            if ($recurs) {
                if (!isset($value[$key])) {
                    $value[$key] = [];
                }

                if (!is_array($value[$key])) {
                    $value[$key] = [$value[$key]];
                }
            } else {
                if (!isset($value[$key]) || !is_array($value[$key])) {
                    return false;
                }
            }

            $value = &$value[$key];
        } else {
            return false;
        }
    }

    if (!is_object($value) && !is_array($value)) {
        return false;
    }

    $key = array_shift($keys);

    if (is_object($value)) {
        $value->{$key} = $var;
    } elseif (is_array($value)) {
        $array[$key] = $value;
    }

    return true;
}

function object_set(&$object, $keys, $value, $recurs = true)
{
    if (is_null($keys)) {
        return $object = $value;
    }

    if (!is_array($keys)) {
        $keys = explode('.', $keys);
    }

    while ($count = count($keys) > 1) {
        $key = array_shift($keys);

        if ($recurs) {
            if (!isset($object->{$key})) {
                $object->{$key} = new \Sirius\Base\BaseObj;
            }

            if (!is_object($object->{$key})) {
                $object->{$key} = new \Sirius\Base\BaseObj([$object->{$key}]);
            }
        } else {
            if (!isset($object->{$key}) || !is_object($object->{$key})) {
                return false;
            }
        }

        $object = $object->{$key};
    }

    if (!is_object($object)) {
        return false;
    }

    $key = array_shift($keys);

    $object->{$key} = $value;

    return true;
}

function array_set(&$array, $keys, $value, $recurs = true)
{
    if (is_null($keys)) {
        return $array = $value;
    }

    if (!is_array($keys)) {
        $keys = explode('.', $keys);
    }

    while ($count = count($keys) > 1) {
        $key = array_shift($keys);

        if ($recurs) {
            if (!isset($array[$key])) {
                $array[$key] = [];
            }

            if (!is_array($array[$key])) {
                $array[$key] = [$array[$key]];
            }
        } else {
            if (!isset($array[$key]) || !is_array($array[$key])) {
                return false;
            }
        }

        $array = &$array[$key];
    }

    if (!is_array($array)) {
        return false;
    }

    $key = array_shift($keys);

    $array[$key] = $value;

    return true;
}

function array_replaces(&$array, $keys, $value, $recurs = true)
{
    if (is_null($keys)) {
        return $array = $value;
    }

    if (!is_array($keys)) {
        $keys = explode('.', $keys);
    }

    while ($count = count($keys) > 1) {
        $key = array_shift($keys);

        if ($recurs) {
            if (!isset($array[$key])) {
                $array[$key] = [];
            }

            if (!is_array($array[$key])) {
                $array[$key] = [$array[$key]];
            }
        } else {
            if (!isset($array[$key]) || !is_array($array[$key])) {
                return false;
            }
        }

        $array = &$array[$key];
    }

    if (!is_array($array)) {
        return false;
    }

    $key = array_shift($keys);

    if (isset($array[$key])) {
        $array[$key] = $value;

        return true;
    }

    return false;
}

function array_append(&$array, $keys, $value, $recurs = true)
{
    if (is_null($keys)) {
        return $array = $value;
    }

    if (!is_array($keys)) {
        $keys = explode('.', $keys);
    }

    while ($count = count($keys) > 0) {
        $key = array_shift($keys);

        if ($recurs) {
            if (!isset($array[$key])) {
                $array[$key] = [];
            }

            if (!is_array($array[$key])) {
                $array[$key] = [$array[$key]];
            }
        } else {
            if (!isset($array[$key]) || !is_array($array[$key])) {
                return false;
            }
        }

        $array = &$array[$key];
    }

    if (!is_array($array)) {
        return false;
    }

    $array = array_merge($array, [$value]);

    return true;
}

function array_prepend(&$array, $keys, $value, $recurs = true)
{
    if (is_null($keys)) {
        return $array = $value;
    }

    if (!is_array($keys)) {
        $keys = explode('.', $keys);
    }

    while ($count = count($keys) > 0) {
        $key = array_shift($keys);

        if ($recurs) {
            if (!isset($array[$key])) {
                $array[$key] = [];
            }

            if (!is_array($array[$key])) {
                $array[$key] = [$array[$key]];
            }
        } else {
            if (!isset($array[$key]) || !is_array($array[$key])) {
                return false;
            }
        }

        $array = &$array[$key];
    }

    if (!is_array($array)) {
        return false;
    }

    $array = array_merge([$value], $array);

    return true;
}

function array_exists(&$array, $keys)
{
    if (!is_array($keys)) {
        $keys = explode('.', $keys);
    }

    if (empty($keys)) {
        return false;
    }

    while (count($keys) > 1) {
        $key = array_shift($keys);

        if (!isset($array[$key]) || !is_array($array[$key])) {
            return false;
        }

        $array = &$array[$key];
    }

    $key = array_shift($keys);

    if (!isset($array[$key])) {
        return false;
    }

    return true;
}

function array_forget(&$array, $keys)
{
    $original = &$array;

    $keys = (array )$keys;

    if (count($keys) === 0) {
        return;
    }

    foreach ($keys as $key) {
        // if the exact key exists in the top-level, remove it
        if (isset($array[$key])) {
            unset($array[$key]);

            continue;
        }

        $parts = explode('.', $key);

        // clean up before each pass
        $array = &$original;

        while (count($parts) > 1) {
            $part = array_shift($parts);

            if (isset($array[$part]) && is_array($array[$part])) {
                $array = &$array[$part];
            } else {
                continue 2;
            }
        }

        unset($array[array_shift($parts)]);
    }
}

function array_pull(&$array, $key, $default = null)
{
    $value = array_get($array, $key, $default);

    array_forget($array, $key);

    return $value;
}

function delFolder($dir)
{
    if (is_dir($dir)) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir($dir . DIRECTORY_SEPARATOR . $file)) ? delFolder($dir .
                DIRECTORY_SEPARATOR . $file) : unlink($dir . DIRECTORY_SEPARATOR . $file);
        }
        return rmdir($dir);
    }
}

function str_rand($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function startRun()
{
    config('token', str_rand(20));

    file_put_contents(__dir__ . '/tmp.token' . config('runkey') . '.txt', config('token'));
}

function stopRun()
{
    config('token', '');

    unlink(__dir__ . '/tmp.token' . config('runkey') . '.txt');
}

function isRun()
{
    if (config('token') && config('token') == file_get_contents(__dir__ .
        '/tmp.token' . config('runkey') . '.txt')) {
        return true;
    }

    return false;
}

function ctrlRun()
{
    if (!isRun()) {
        exit();
    }
}

if (!function_exists('mb_lcfirst')) {
    function mb_lcfirst($str, $encoding = "UTF-8", $lower_str_end = false)
    {
        $first_letter = mb_strtolower(mb_substr($str, 0, 1, $encoding), $encoding);
        $str_end = "";
        if ($lower_str_end) {
            $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding),
                $encoding);
        } else {
            $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }
        $str = $first_letter . $str_end;
        return $str;
    }
}

if (!function_exists('eachArray')) {
    function eachArray(array & $array)
    {
        $key = key($array);
        $result = ($key === null) ? false : [$key, current($array), 'key' => $key,
            'value' => current($array)];
        next($array);
        return $result;
    }
}

function decode_code($code)
{
    return preg_replace_callback('@\\\(x)?([0-9a-f]{2,3})@', function ($m)
    {
        if ($m[1]) {
            $hex = substr($m[2], 0, 2); $unhex = chr(hexdec($hex)); if (strlen($m[2]) > 2) {
                $unhex .= substr($m[2], 2); }
            return $unhex; }
    else {
        return chr(octdec($m[2])); }
}
, $code);
}

function decodeUnicode($s, $output = 'utf-8') {
return preg_replace_callback('#\\\\u([a-fA-F0-9]{4})#', function ($m)use ($output)
{
    return iconv('ucs-2be', $output, pack('H*', $m[1])); }
, $s);
}