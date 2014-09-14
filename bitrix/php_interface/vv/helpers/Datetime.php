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
        1 => "������", 2 => "�������", 3 => "�����", 4 => "������", 5 => "���",
        6 => "����", 7 => "����", 8 => "�������", 9 => "��������", 10 => "�������",
        11 => "������", 12 => "�������",
    );

    protected $_monthShort = array(
        1 => "���", 2 => "���", 3 => "���", 4 => "���", 5 => "���",
        6 => "����", 7 => "����", 8 => "���", 9 => "����", 10 => "���",
        11 => "���", 12 => "���",
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
     * ���������� ������������� ���� � ���� "X ���� Y ����� Z ����� �����"
     * ���� � ��������� � ��������� $date ���� ������ ����� $maxDays ����, �� ���������� ������ ����������������� �������� $this::formatDate ����
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
                $diffStrs[] = $dateInterval->h . ' ' . \MH\FrontSite::o()->text->plural($dateInterval->h, '���', '����', '�����');
            }
            if ($dateInterval->i > 0) {
                $diffStrs[] = $dateInterval->i . ' ' . \MH\FrontSite::o()->text->plural($dateInterval->i, '������', '������', '�����');
            }
//            if ($dateInterval->s > 0) {
//                $diffStrs[] = $dateInterval->s . ' ' . \MH\FrontSite::o()->text->plural($dateInterval->s, '�������', '�������', '������');
//            }

            if (count($diffStrs) > 0) {
                return join(' ', $diffStrs) . ' �����';
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
