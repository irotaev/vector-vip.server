<?php
require_once 'Abstract.php';
/**
 * Description of MH_HelperDatetime
 *
 * @author marina
 */
class MH_HelperDatetime extends MH_HelperAbstract
{
    protected $_month = array(
        1 => "января", 2 => "февраля", 3 => "марта", 4 => "апреля", 5 => "мая",
        6 => "июня", 7 => "июля", 8 => "августа", 9 => "сентября", 10 => "октября",
        11 => "ноября", 12 => "декабря",
    );

    protected $_monthShort = array(
        1 => "янв", 2 => "фев", 3 => "мар", 4 => "апр", 5 => "мая",
        6 => "июня", 7 => "июля", 8 => "авг", 9 => "сент", 10 => "окт",
        11 => "ноя", 12 => "дек",
    );

    protected $_month_en = array(
        1 => "january", 2 => "february", 3 => "march", 4 => "april", 5 => "may",
        6 => "june", 7 => "july", 8 => "august", 9 => "september", 10 => "october",
        11 => "november", 12 => "december",
    );

    public function getMonthName($month)
    {
        $month = (int)$month;
        return isset($this->_month[$month]) ? $this->_month[$month] : '';
    }

    public function getMonthNameShort($month)
    {
        $month = (int)$month;
        return isset($this->_monthShort[$month]) ? $this->_monthShort[$month] : '';
    }

    public function getMonthEnName($month)
    {
        $month = (int)$month;
        return isset($this->_month_en[$month]) ? $this->_month_en[$month] : '';
    }

    public function formatDate($date, $year = true)
    {
        return date('d ' . ($this->getMonthName((int)date('m', $date))) . ($year ? ' Y' : ''), $date);
    }

    public function formatDateTime($date, $year = true)
    {
        return date('d ' . ($this->getMonthName((int)date('m', $date))) . ($year ? ' Y' : '') . ' G:i:s', $date);
    }

    public function dateDay($date)
    {
        return date('d', $date);
    }

    public function dateMonthShort($date)
    {
        return $this->getMonthNameShort((int)date('m', $date));
    }

    /**
     * Возвращает представление даты в виде "X дней Y часов Z минут назад"
     * Если с указанной в параметре $date даты прошло более $maxDays дней, то возвращает просто отформатированную функцией $this::formatDate дату
     * @param $date
     * @param int $maxDays
     * @return mixed
     */
    public function dateDiffStr($date, $maxDays = 1)
    {
        $now = new DateTime();
        $past = new DateTime();
        $past->setTimestamp($date);
        $dateInterval = $now->diff($past);
        if ($dateInterval->days < $maxDays) {
            $diffStrs = array();
            if ($dateInterval->h > 0) {
                $diffStrs[] = $dateInterval->h . ' ' . \MH\FrontSite::o()->text->plural($dateInterval->h, 'час', 'часа', 'часов');
            }
            if ($dateInterval->i > 0) {
                $diffStrs[] = $dateInterval->i . ' ' . \MH\FrontSite::o()->text->plural($dateInterval->i, 'минута', 'минуты', 'минут');
            }
//            if ($dateInterval->s > 0) {
//                $diffStrs[] = $dateInterval->s . ' ' . \MH\FrontSite::o()->text->plural($dateInterval->s, 'секунда', 'секунды', 'секунд');
//            }

            if (count($diffStrs) > 0) {
                return join(' ', $diffStrs) . ' назад';
            }
            else {
                return $this->formatDate($date);
            }
        }
        else {
            return $this->formatDate($date);
        }
    }
}
