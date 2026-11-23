<?php
/* SAMPLE DATA ONLY - replace each block with your teammates' DB queries (PDO/mysqli). */
function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function peso($n){ return '₱' . number_format((float)$n, 2); }

$books = [
  ['id'=>1,'title'=>'Noli Me Tangere','author'=>'Jose Rizal','isbn'=>'9789712715','category'=>'Fiction','price'=>320,'quantity'=>42,'minimum_stock'=>10],
  ['id'=>2,'title'=>'Calculus: Early Transcendentals','author'=>'James Stewart','isbn'=>'9781285741550','category'=>'Textbook','price'=>1850,'quantity'=>4,'minimum_stock'=>8],
  ['id'=>3,'title'=>'Diary ng Panget','author'=>'HaveYouSeenThisGirL','isbn'=>'9786214420','category'=>'Fiction','price'=>285,'quantity'=>6,'minimum_stock'=>10],
  ['id'=>4,'title'=>'One Piece Vol. 100','author'=>'Eiichiro Oda','isbn'=>'9781974714','category'=>'Comics','price'=>420,'quantity'=>18,'minimum_stock'=>6],
  ['id'=>5,'title'=>'Java: How to Program','author'=>'Paul Deitel','isbn'=>'9780134800','category'=>'Textbook','price'=>2100,'quantity'=>3,'minimum_stock'=>5],
  ['id'=>6,'title'=>'Attack on Titan Vol. 1','author'=>'Hajime Isayama','isbn'=>'9781612620','category'=>'Comics','price'=>390,'quantity'=>25,'minimum_stock'=>6],
  ['id'=>7,'title'=>'El Filibusterismo','author'=>'Jose Rizal','isbn'=>'9789712716','category'=>'Fiction','price'=>310,'quantity'=>9,'minimum_stock'=>10],
];

// type: in = Stock-In (receiving), out = Stock-Out (sales)
$transactions = [
  ['id'=>1001,'date'=>'2026-11-16','type'=>'in', 'book_id'=>1,'qty'=>30,'unit_price'=>320, 'by'=>'admin','note'=>'Supplier delivery'],
  ['id'=>1002,'date'=>'2026-11-17','type'=>'out','book_id'=>1,'qty'=>6, 'unit_price'=>320, 'by'=>'cashier1','note'=>'Walk-in sale'],
  ['id'=>1003,'date'=>'2026-11-17','type'=>'out','book_id'=>4,'qty'=>5, 'unit_price'=>420, 'by'=>'cashier1','note'=>''],
  ['id'=>1004,'date'=>'2026-11-18','type'=>'out','book_id'=>2,'qty'=>3, 'unit_price'=>1850,'by'=>'cashier2','note'=>'Student order'],
  ['id'=>1005,'date'=>'2026-11-19','type'=>'in', 'book_id'=>6,'qty'=>20,'unit_price'=>390, 'by'=>'admin','note'=>'Restock'],
  ['id'=>1006,'date'=>'2026-11-19','type'=>'out','book_id'=>6,'qty'=>8, 'unit_price'=>390, 'by'=>'cashier1','note'=>''],
  ['id'=>1007,'date'=>'2026-11-20','type'=>'out','book_id'=>3,'qty'=>4, 'unit_price'=>285, 'by'=>'cashier2','note'=>''],
  ['id'=>1008,'date'=>'2026-11-20','type'=>'out','book_id'=>5,'qty'=>2, 'unit_price'=>2100,'by'=>'cashier1','note'=>''],
  ['id'=>1009,'date'=>'2026-11-21','type'=>'out','book_id'=>1,'qty'=>9, 'unit_price'=>320, 'by'=>'cashier1','note'=>'Class set'],
  ['id'=>1010,'date'=>'2026-11-22','type'=>'out','book_id'=>4,'qty'=>4, 'unit_price'=>420, 'by'=>'cashier2','note'=>''],
];

function book_by_id($books,$id){ foreach($books as $b){ if($b['id']==$id) return $b; } return null; }
function low_stock_books($books){
  $low = array_values(array_filter($books, fn($b)=>$b['quantity'] < $b['minimum_stock']));
  usort($low, fn($a,$b)=>($a['quantity']-$a['minimum_stock']) <=> ($b['quantity']-$b['minimum_stock']));
  return $low;
}
function best_sellers($transactions,$books,$limit=5){
  $t=[]; foreach($transactions as $x){ if($x['type']==='out'){ $t[$x['book_id']]=($t[$x['book_id']]??0)+$x['qty']; } }
  arsort($t); $out=[];
  foreach(array_slice($t,0,$limit,true) as $id=>$q){ $b=book_by_id($books,$id); if($b) $out[]=['title'=>$b['title'],'sold'=>$q]; }
  return $out;
}
function sales_by_day($transactions){
  $d=[]; foreach($transactions as $x){ if($x['type']==='out'){ $d[$x['date']]=($d[$x['date']]??0)+$x['qty']*$x['unit_price']; } }
  ksort($d); return $d;
}
