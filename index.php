<?php
include 'parsePonto.php';
$file =  file_get_contents('ponto.txt');
$ponto = new Ponto($file);

//var_dump($ponto->getEmployees());
//var_dump($ponto->getEmployeeBalance('013054953938',$startTime,$endTime));
var_dump($ponto->getEmployeeBalance('013165740894',null,null));