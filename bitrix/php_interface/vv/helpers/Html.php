<?php
require_once 'Abstract.php';

class MH_HelperHtml extends MH_HelperAbstract {

    public function setWidthTo550($matches, $src, $maxWidth = 550) {
        $imgString = '<' . $matches[1] . 'img' . $matches[2] . 'src="' . $src . '"' . $matches[4] . '>';

        if ( preg_match( '/(.*?)width=\"(.*?)\"(.*)/is', $imgString, $m ) ) {
            $width = intval( $m[2] );

            if ( $width > $maxWidth ) {
                $imgString = $m[1] . 'width="'.$maxWidth.'"' . $m[3];
                if ( preg_match( '/(.*?)height=\"(.*?)\"(.*)/is', $imgString, $m ) ) {
                    $height = intval( $m[2] );
                    $height = floor( $height * $maxWidth / $width );
                    $imgString = $m[1] . 'height="' . $height . '"' . $m[3];
                }
            }
        }
        return $imgString;
    }

    /**
    * Метод рекурсивно применяет алгоритм метода processTableRow
    * ко всему тексту, принимаемому в качестве аргумента.
    * Также, если в переданном тексте содержится html таблица
    * с шириной более чем $contentWidth, то ее атрибут width
    * устанавливается в значение $contentWidth (550 по умолчанию).
    * @param $where текст, в котором искать и парсить строки tr
    * @param $contentWidth максимально допустимая ширина контента
    * @return распарсенный текст для вставки в основной контейнер сайта.
    */
    public function tableAndImages550Process_old($where, $contentWidth = 550) {
        $processedText = "";
        $trOpenTagPos = strpos($where, "<tr");
        $trCloseTagPos = strpos($where, "</tr>");
        if (!$trOpenTagPos && !$trCloseTagPos)
            return $where;

        $partBeforeOpenTrTag = substr($where, 0, $trOpenTagPos);
        $partAfterCloseTrTag = substr($where, $trCloseTagPos+5);

        $tableRowContent = substr($where, $trOpenTagPos, $trCloseTagPos-$trOpenTagPos);

        $processedText .= preg_replace_callback( "/(.*<table[^<>]*?width=(?:\"|')?)(\d+)((?:\"|')?[^<>]*?>.*)/" , function($matches) use ($contentWidth){
            if (empty($matches))
                return $partBeforeOpenTrTag;
            $tableWidth = (int)$matches[2];
            $tableWidth = $tableWidth > $contentWidth ? $contentWidth: $tableWidth;
            return $matches[1] . $tableWidth . $matches[3];
        }, $partBeforeOpenTrTag);

        $processedText .= $this->processTableRow($tableRowContent);
        $processedText .= $this->tableAndImages550Process($partAfterCloseTrTag);
        return $processedText;
    }

    public function tableAndImages550Process($where, $contentWidth = 550) {
        $processedText = "";
        while ( 1 ) {
            $trOpenTagPos = strpos($where, "<tr");
            $trCloseTagPos = strpos($where, "</tr>");
            if (!$trOpenTagPos && !$trCloseTagPos){
                $processedText .= $where;
                break;
            }
            $partBeforeOpenTrTag = substr($where, 0, $trOpenTagPos);
            $partAfterCloseTrTag = substr($where, $trCloseTagPos+5);

            $tableRowContent = substr($where, $trOpenTagPos, $trCloseTagPos-$trOpenTagPos);

            $processedText .= preg_replace_callback( "/(.*<table[^<>]*?width=(?:\"|')?)(\d+)((?:\"|')?[^<>]*?>.*)/" , function($matches) use ($contentWidth){
                if (empty($matches))
                    return $partBeforeOpenTrTag;
                $tableWidth = (int)$matches[2];
                $tableWidth = $tableWidth > $contentWidth ? $contentWidth: $tableWidth;
                return $matches[1] . $tableWidth . $matches[3];
            }, $partBeforeOpenTrTag);

            $processedText .= $this->processTableRow($tableRowContent);
            $where = $partAfterCloseTrTag;

        }

        return $processedText;
    }

    /**
    * Метод находит в тексте, переданном в качестве аргумента,
    * html код строки таблицы tr такой, что содержит только
    * ячейки с тегом img. В зависимости от допустимой ширины
    * контента устанавливает соответствующие значения атрибутам
    * width и height тега img. Также сохраняет атрибут alt, если он
    * был указан. Если в одной строке ячеек меньше чем 3, метод просто
    * возвращает первональный текст.
    * Важно: метод не ищет строки рекурсивно, то есть, он не сможет
    * отыскать строку таблицы в ячейке родительской таблицы в ячейке
    * другой родительской таблицы и так далее.
    * @param $where текст, в котором искать и парсить строки tr
    * @param $contentWidth максимально допустимая ширина контента
    * @return текст с соответствующими значениями атрибутов width
    * и height тегов img.
    */
    protected function processTableRow($where, $contentWidth = 550) {
        $trPattern = "/<tr>|\s*?<td[^<>]*>\s*?(<img[^<>]+\/>)\s*?<\/td>\s*?|<\/tr>/i";
        $attrPattern = "/((?:alt|src|width|height)=(?:\"|').*?(?:\"|'))/i";
        preg_match_all($trPattern, $where, $matches);

        if (empty($matches[1]))
            return $where; // ничего не найдено;

        $foundImgTags = array();

        foreach($matches[1] as $key => $value) {
            if (!empty($value)) {
                // если найденное значение не пустое,
                // то полагаем, что это тег img,
                // извлечём необходимую информацию по картинке
                preg_match_all($attrPattern, $value, $attrMatches);

                // массив $attrMatches[0] содержит необходимую информацию
                // по атрибутам найденного тега img. Имеет следующий общий вид:
                // Array ( [0]=> attr_name0=attr_value0 [1]=> attr_name1=attr_value1 [N]=> attr_nameN=attr_valueN )

                foreach($attrMatches[0] as $attrMatch) {
                    $tempAttrArray = explode("=",$attrMatch);
                    $foundImgTags[$key][$tempAttrArray[0]] = $tempAttrArray[1];
                }
            }
        }
        // на данном этапе массив $foundImgTags в общем виде выглядит
        // Array (
        //    [1]=> Array(
        //             [attr1] => value1,
        //
        //             [attrN] => valueN
        //              )
        //    [2]=> Array(
        //             [attr1] => value1,
        //
        //             [attrN1] => valueN1
        //              )
        //    [M]=> Array(
        //             [attr1] => value1,
        //
        //             [attrN2] => valueN2
        //              )
        // )
        // M - количество тегов img, которые были найдены в строке таблицы;

        // Если в одной строке таблицы больше 2х картинок, то
        // откорректируем размер изображений в зависимости от
        // ширины таблицы

        $numberOfImgs = count($foundImgTags);
        // если в строке таблицы больше чем две картинки
        if ($numberOfImgs > 2) {
            $imgWidthLimit = round($contentWidth/$numberOfImgs)-5; // максимальная ширина картинки с запасом 5 пикселов
            // с помощью функции getimagesize() получим действительные размеры картинок и сравним с максимальным
            $htmlContentBack = "<tr>";
            foreach($foundImgTags as $k => $foundImgTag) {
                $imgSrc = $foundImgTag['src'];
                // атрибут src содержит ковычки в начале и в конце,
                // поэтому избавимся от них
                preg_match("/(?:\"|')(.*)(?:\"|')/", $imgSrc, $m);
                $imgSrc = $m[1];
                $imgAlt = "";
                if (isset($foundImgTag['alt'])) {
                    preg_match("/(?:\"|')(.*)(?:\"|')/", $foundImgTag['alt'], $n);
                    $imgAlt = $n[1];
                }
                list($width, $height) = getimagesize($imgSrc);
                $w = $width > $imgWidthLimit ? $imgWidthLimit: $width;                 // корректная ширина изображения
                $h = $width > $imgWidthLimit ? round($height*$imgWidthLimit/$width): $height; // корректная высота изображения
                $foundImgTags[$k]['width'] = $w;
                $foundImgTags[$k]['height'] = $h;
                $htmlContentBack .= "<td><img src='".$imgSrc."' alt='".$imgAlt."' width='".$w."' height='".$h."' /></td>";
            }
            $htmlContentBack .= "</tr>";
            return $htmlContentBack;
        }
        return $where;
    }
}
?>
