<?php
    include_once 'phpQuery.php';
    function getBetween($string, $start = "", $end = ""){
        if (strpos($string, $start)) { // required if $start not exist in $string
            $startCharCount = strpos($string, $start) + strlen($start);
            $firstSubStr = substr($string, $startCharCount, strlen($string));
            $endCharCount = strpos($firstSubStr, $end);
            if ($endCharCount == 0) {
                $endCharCount = strlen($firstSubStr);
            }
            return substr($firstSubStr, 0, $endCharCount);
        } else {
            return '';
        }
    }

    $fp = fopen('result.txt', 'w+');
    $html = file_get_contents('https://www.italska8.cz/byty');
    $document = phpQuery::newDocument($html);
    $mytext=$document->find('.clickable-row');
    $result='';
    foreach ($mytext as $tr) {

        $trLink = pq($tr); //pq делает объект phpQuery
        $id=$trLink->find('td:nth-child(1)')->html();
        $Dispozice=$trLink->find('td:nth-child(2)')->html();
        $Patro=explode('.',$trLink->find('td:nth-child(3)')->html())[0];
        $Plocha=explode('m',$trLink->find('td:nth-child(5)')->html())[0];
        $Stav=$trLink->find('td:nth-child(6)')->html();
        $Price=$trLink->find('td:nth-child(7)')->html();
        $Typ=$trLink->find('td:nth-child(4)')->html();
        $href = $trLink->attr('data-href');
        $cart = phpQuery::newDocument(file_get_contents($href));
        $Terrace=$cart->find('div.block-inline > p:nth-child(2) > strong')->html();
        $Terrace=trim(getBetween($Terrace,'Podlaží:','.'));
        $result=$result.'{'.$id.';'.$Dispozice.';'.$Patro.';'.$Plocha.';'.$Stav.';'.$Price.';'.$Typ.';'.$Terrace.'}';
    }
    echo $result;
    $test = fwrite($fp, $result); // Запись в файл
    if ($test) echo 'Данные в файл успешно занесены.';
    else echo 'Ошибка при записи в файл.';
    fclose($fp);
?>