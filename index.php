<?php
include "header.php";
include "menu.php";
?>

<div style="background:green; overflow:hidden; white-space:nowrap; padding:10px; color:white; font-weight:bold; font-size:18px;">
  <div style="display:inline-block; white-space:nowrap; animation: scroll 8s linear infinite;">
    🎄 10% DISCOUNT FOR CHRISTMAS SALE 🎄 Order now for best offers !!! Limited time deal. Get the best offers right now! Dépêchez-vous &nbsp;&nbsp;&nbsp;
    🎄 10% DISCOUNT FOR CHRISTMAS SALE 🎄 Order now for best offers !!! Limited time deal. Get the best offers right now! Dépêchez-vous &nbsp;&nbsp;&nbsp;
  </div>
</div>

<style>
@keyframes scroll {
  0%   { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
</style>


<div class="container">
  <div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
      <h1 class="display-5 fw-bold">Welcome to the M & N Electronics</h1>
      <p class="col-md-8 fs-4">
        Shop now for the latest Electronics
      </p>
      <a class="btn btn-primary btn-lg me-2" href="products.php">Shop Electronics</a>
    </div>
  </div>
</div>
<?php
include 'carousel.php';
include "footer.php";
?>
