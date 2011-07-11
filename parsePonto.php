<?php

/**
 * Description of parsePonto
 *
 *
 */
class Ponto
{

    private $strFile;
    private $vtFile;
    private $employees = array();

    public function __construct($strFilePonto)
    {
        $this->strFile = $strFilePonto;
        $this->parseFile();
    }

    public function parseFile()
    {
        $vtFile = explode(PHP_EOL, $this->strFile);
        $this->vtFile = $vtFile;
        $this->parseEmployees();
    }

    public function parseEmployees()
    {
        foreach ($this->vtFile as $line)
        {
            $registerType = substr($line, 9, 1);
            if ($registerType == 5)
            {
                $employeeName = trim(substr($line, 35, strlen($line)));
                $employeePis = substr($line, 23, 12);
                $this->employees[$employeePis] = $employeeName;
            }
        }
    }

    public function getEmployees()
    {
        return $this->employees;
    }

    /*
      $lalal = array(
      "10/05/2009" => array('15:30','15:30','15:30','15:30'),
      "12/05/2009" => array('15:30','15:30','15:30','15:30'),
      "13/05/2009" => array('15:30','15:30','15:30','15:30'),
      "14/05/2009" => array('15:30','15:30','15:30','15:30'),
      );
     * 
     */

    public function getEmployeeBalance($employeePis, $startTime, $endTime)
    {
        $balance = array();
        for ($x = 0, $c = count($this->vtFile); $x < $c; $x++)
        {
            $line = $this->vtFile[$x];
            $time = null;

            $registerType = substr($line, 9, 1);
            $registerPis = substr($line, 23, 12);

            if ($registerType == 3 AND $registerPis == $employeePis)
            {
                $time = substr($line, 10, 12);
                $time = DateTime::createFromFormat("dmYHi", $time);
                //$time = mktime(substr($time, 8, 2), substr($time, 10, 2), 00, substr($time, 2, 2), substr($time, 0, 2), substr($time, 4, 4));

                $balance[$time->format("d/m/Y")][] = $time;
            }
        }


        //$balance = array($balance['01/06/2011']);

        $totalWorkedHours = new DateTime();
        foreach ($balance as $day => $register)
        {
            if (isset($register[0]) AND isset($register[1]))
            {
                $morningHours = $register[1]->diff($register[0]);
            }

            if (isset($register[2]) AND isset($register[3]))
            {
                $afternoonHours = $register[3]->diff($register[2]);
            }

            $balance[$day]['workedHours'] = $this->diffDateInterval($morningHours,$afternoonHours);
            //$totalWorkedHours += $balance[$day]['workedHours'];
           // $balance[$day]['balance'] = DateInterval::createFromDateString('P' . $morningTime + $afternoonTime . 'S');
        }
        
        return $balance;
    }

    private function diffDateInterval(DateInterval $interval1, DateInterval $interval2)
    {
        $baseDate = new DateTime();
        $diffDate = new DateTime();

        $diffDate->add($interval1);
        $diffDate->add($interval2);

        return $baseDate->diff($diffDate);
    }

}