<?php /* STUB - replace with Kimberly's sidebar.php. Keep the Low-Stock badge and these 5 links. */
$current = basename($_SERVER['PHP_SELF']);
$lowCount = count(low_stock_books($books));
function nav($file,$label,$current,$badge=0){
  $cls = $current===$file ? ' class="active" aria-current="page"' : '';
  $b = $badge>0 ? '<span class="badge" aria-label="'.$badge.' low stock">'.$badge.'</span>' : '';
  echo "<li><a href=\"$file\"$cls>$label$b</a></li>";
}
?>
<aside class="sidebar" id="sidebar">
  <div class="brand">Book Inventory</div>
  <ul>
    <?php nav('dashboard.php','Dashboard',$current);
          nav('stock_in.php','Stock-In',$current);
          nav('stock_out.php','Stock-Out',$current);
          nav('transactions.php','Transaction History',$current);
          nav('low_stock.php','Low-Stock Alerts',$current,$lowCount); ?>
  </ul>
</aside>
<main class="content">
