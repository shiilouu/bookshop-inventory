<?php
/* Sample data used while building the interface */
if (!function_exists('e')) { function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); } }
function peso($n){ return '₱' . number_format((float)$n, 2); }

$bookList = [
  ['book_id'=>1,'title'=>'Noli Me Tangere','author'=>'Jose Rizal','category'=>'Fiction','price'=>320,'quantity'=>42,'minimum_stock'=>10],
  ['book_id'=>2,'title'=>'Calculus: Early Transcendentals','author'=>'James Stewart','category'=>'Textbook','price'=>1850,'quantity'=>4,'minimum_stock'=>8],
  ['book_id'=>3,'title'=>'Diary ng Panget','author'=>'HaveYouSeenThisGirL','category'=>'Fiction','price'=>285,'quantity'=>6,'minimum_stock'=>10],
  ['book_id'=>4,'title'=>'One Piece Vol. 100','author'=>'Eiichiro Oda','category'=>'Comics','price'=>420,'quantity'=>18,'minimum_stock'=>6],
  ['book_id'=>5,'title'=>'Java: How to Program','author'=>'Paul Deitel','category'=>'Textbook','price'=>2100,'quantity'=>3,'minimum_stock'=>5],
  ['book_id'=>6,'title'=>'Attack on Titan Vol. 1','author'=>'Hajime Isayama','category'=>'Comics','price'=>390,'quantity'=>25,'minimum_stock'=>6],
];
$saleList = [ // stock_out rows only: date, book_id, quantity, unit_price
  ['date'=>'2026-11-17','book_id'=>1,'quantity'=>6,'unit_price'=>320],
  ['date'=>'2026-11-17','book_id'=>4,'quantity'=>5,'unit_price'=>420],
  ['date'=>'2026-11-18','book_id'=>2,'quantity'=>3,'unit_price'=>1850],
  ['date'=>'2026-11-19','book_id'=>6,'quantity'=>8,'unit_price'=>390],
  ['date'=>'2026-11-20','book_id'=>3,'quantity'=>4,'unit_price'=>285],
  ['date'=>'2026-11-21','book_id'=>1,'quantity'=>9,'unit_price'=>320],
];
function getLowStockBooks($bookList){
  $low = array_values(array_filter($bookList, fn($b)=>$b['quantity'] < $b['minimum_stock']));
  usort($low, fn($a,$b)=>($a['quantity']-$a['minimum_stock']) <=> ($b['quantity']-$b['minimum_stock']));
  return $low;
}
function getBestSellers($saleList,$bookList,$limit=5){
  $t=[]; foreach($saleList as $s){ $t[$s['book_id']]=($t[$s['book_id']]??0)+$s['quantity']; }
  arsort($t); $out=[];
  foreach(array_slice($t,0,$limit,true) as $id=>$q){ foreach($bookList as $b){ if($b['book_id']==$id){ $out[]=['title'=>$b['title'],'sold'=>$q]; } } }
  return $out;
}
function getDailySales($saleList){
  $d=[]; foreach($saleList as $s){ $d[$s['date']]=($d[$s['date']]??0)+$s['quantity']*$s['unit_price']; }
  ksort($d); return $d;
}
