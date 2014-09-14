<?php

/**
 * Description of Text
 *
 * @author dmitriy
 */
class MH_HelperText extends MH_HelperAbstract
{

    public function plural($num, $str1, $str2, $str3)
    {
        return $num % 10 == 1 && $num % 100 != 11
            ? $str1
            : (
            $num % 10 >= 2 && $num % 10 <= 4 && ($num % 100 < 10 || $num % 100 >= 20)
                ? $str2
                : $str3
            );
    }

    /**
     * Обрезает тест до длинны не более $maxLength символов, не разрезая
     * слова посередине. Из текста вырезаются все теги
     *
     * @param string $text  входной текст
     * @param integer $maxLength  длинна
     * @return string
     */
    public function cutUpToWords($text, $maxLength, $tail = false)
    {
        $text = trim(html_entity_decode(strip_tags($text), ENT_QUOTES, 'cp1251'));

        if (strlen($text) <= $maxLength) {
            return $text;
        }
        // находим все границы слов
        if (preg_match_all('/[ .!?]/', $text, $m, PREG_OFFSET_CAPTURE)) {
            // перебираем все границы слов в обратном порядке, находим первое
            // подходящее по длинне
            foreach (array_reverse($m[0]) as $key => $pos) {
                if ($pos[1] + 1 <= $maxLength) {
                    return trim(substr($text, 0, $pos[1])) . ($tail ? $tail : '');
                }
            }
        }
        return '';
    }

    public function dateFormat($timestamp)
    {
        return date('d/m/Y H:i', $timestamp);
    }

    public function dateFormatShort($timestamp)
    {
        return date('d/m/Y', $timestamp);
    }

    public function decodeHtmlEntity($text)
    {
        $text = str_replace(array('&#8213;'), array('&mdash;'), $text);
        $text = html_entity_decode(strip_tags($text, '<br><b><i>'), ENT_QUOTES, 'cp1251');
        return $text;
    }

    /**
     * Метод удаляет все html теги из
     * входной строки. Также удаляет пустые
     * пробелы с конца и начала строки,
     * экранирует специальные символы SQL.
     * @param $value входная строка
     * @return экранированная строка
     */
    public function escapeText($value)
    {
        $value = trim(strip_tags($value));
        if (function_exists("mysql_real_escape_string")) {
            if (get_magic_quotes_gpc()) {
                $value = stripslashes($value);
            }
            $value = mysql_real_escape_string($value);

        } else {
            $value = addslashes($value);
        }
        return $value;
    }

}
