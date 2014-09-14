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
    * ����� ���������� ��������� �������� ������ processTableRow
    * �� ����� ������, ������������ � �������� ���������.
    * �����, ���� � ���������� ������ ���������� html �������
    * � ������� ����� ��� $contentWidth, �� �� ������� width
    * ��������������� � �������� $contentWidth (550 �� ���������).
    * @param $where �����, � ������� ������ � ������� ������ tr
    * @param $contentWidth ����������� ���������� ������ ��������
    * @return ������������ ����� ��� ������� � �������� ��������� �����.
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
    * ����� ������� � ������, ���������� � �������� ���������,
    * html ��� ������ ������� tr �����, ��� �������� ������
    * ������ � ����� img. � ����������� �� ���������� ������
    * �������� ������������� ��������������� �������� ���������
    * width � height ���� img. ����� ��������� ������� alt, ���� ��
    * ��� ������. ���� � ����� ������ ����� ������ ��� 3, ����� ������
    * ���������� ������������ �����.
    * �����: ����� �� ���� ������ ����������, �� ����, �� �� ������
    * �������� ������ ������� � ������ ������������ ������� � ������
    * ������ ������������ ������� � ��� �����.
    * @param $where �����, � ������� ������ � ������� ������ tr
    * @param $contentWidth ����������� ���������� ������ ��������
    * @return ����� � ���������������� ���������� ��������� width
    * � height ����� img.
    */
    protected function processTableRow($where, $contentWidth = 550) {
        $trPattern = "/<tr>|\s*?<td[^<>]*>\s*?(<img[^<>]+\/>)\s*?<\/td>\s*?|<\/tr>/i";
        $attrPattern = "/((?:alt|src|width|height)=(?:\"|').*?(?:\"|'))/i";
        preg_match_all($trPattern, $where, $matches);

        if (empty($matches[1]))
            return $where; // ������ �� �������;

        $foundImgTags = array();

        foreach($matches[1] as $key => $value) {
            if (!empty($value)) {
                // ���� ��������� �������� �� ������,
                // �� ��������, ��� ��� ��� img,
                // �������� ����������� ���������� �� ��������
                preg_match_all($attrPattern, $value, $attrMatches);

                // ������ $attrMatches[0] �������� ����������� ����������
                // �� ��������� ���������� ���� img. ����� ��������� ����� ���:
                // Array ( [0]=> attr_name0=attr_value0 [1]=> attr_name1=attr_value1 [N]=> attr_nameN=attr_valueN )

                foreach($attrMatches[0] as $attrMatch) {
                    $tempAttrArray = explode("=",$attrMatch);
                    $foundImgTags[$key][$tempAttrArray[0]] = $tempAttrArray[1];
                }
            }
        }
        // �� ������ ����� ������ $foundImgTags � ����� ���� ��������
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
        // M - ���������� ����� img, ������� ���� ������� � ������ �������;

        // ���� � ����� ������ ������� ������ 2� ��������, ��
        // �������������� ������ ����������� � ����������� ��
        // ������ �������

        $numberOfImgs = count($foundImgTags);
        // ���� � ������ ������� ������ ��� ��� ��������
        if ($numberOfImgs > 2) {
            $imgWidthLimit = round($contentWidth/$numberOfImgs)-5; // ������������ ������ �������� � ������� 5 ��������
            // � ������� ������� getimagesize() ������� �������������� ������� �������� � ������� � ������������
            $htmlContentBack = "<tr>";
            foreach($foundImgTags as $k => $foundImgTag) {
                $imgSrc = $foundImgTag['src'];
                // ������� src �������� ������� � ������ � � �����,
                // ������� ��������� �� ���
                preg_match("/(?:\"|')(.*)(?:\"|')/", $imgSrc, $m);
                $imgSrc = $m[1];
                $imgAlt = "";
                if (isset($foundImgTag['alt'])) {
                    preg_match("/(?:\"|')(.*)(?:\"|')/", $foundImgTag['alt'], $n);
                    $imgAlt = $n[1];
                }
                list($width, $height) = getimagesize($imgSrc);
                $w = $width > $imgWidthLimit ? $imgWidthLimit: $width;                 // ���������� ������ �����������
                $h = $width > $imgWidthLimit ? round($height*$imgWidthLimit/$width): $height; // ���������� ������ �����������
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
