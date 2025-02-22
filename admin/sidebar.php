<!-- sidebar -->

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebar-brand brand-logo" href="index"><img src="../assets/img/logo.png" alt="logo" /></a>
    <a class="sidebar-brand brand-logo-mini" href="index"><img src="../assets/img/logosmall3.png" alt="logo" /></a>
  </div>
  <ul class="nav">

    <li class="nav-item nav-category">
      <span class="nav-link">Navigation</span>
    </li>

    <li class="nav-item menu-items">
      <a class="nav-link" href="home">
        <span class="menu-icon">
          <i class="mdi mdi-speedometer"></i>
        </span>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#ui-shop" aria-expanded="false" aria-controls="ui-shop">
        <span class="menu-icon">
        <i class="mdi mdi-store"></i>
        </span>
        <span class="menu-title">Shop</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-shop">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="new-item">Add Item</a></li>
          <li class="nav-item"> <a class="nav-link" href="inventory">Inventory</a></li>
        </ul>
      </div>
    </li>

    <li class="nav-item menu-items">
      <a class="nav-link" data-toggle="collapse" href="#ui-orders" aria-expanded="false" aria-controls="ui-orders">
        <span class="menu-icon">
        <i class="mdi mdi-store"></i>
        </span>
        <span class="menu-title">Orders</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-orders">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="orders-pending">Pending</a></li>
          <li class="nav-item"> <a class="nav-link" href="orders-confirmed">Confirmed</a></li>
          <li class="nav-item"> <a class="nav-link" href="orders-completed">Completed</a></li>
          <li class="nav-item"> <a class="nav-link" href="orders-cancelled">Cancelled</a></li>
        </ul>
      </div>
    </li>


    <li class="nav-item menu-items">
      <a class="nav-link" href="users.php">
        <span class="menu-icon">
          <i class="mdi mdi-account-multiple"></i>
        </span>
        <span class="menu-title">Users</span>
      </a>
    </li>



    
    <li class="nav-item menu-items">
      <a class="nav-link" href="reports">
        <span class="menu-icon">
          <i class="mdi mdi-file-chart"></i>
        </span>
        <span class="menu-title">Reports</span>
      </a>
    </li>

  </ul>
</nav>

<!-- navbar -->