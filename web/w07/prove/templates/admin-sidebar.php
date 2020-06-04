<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link <?php echo ( isCurrentPage('admin.php') ) ? 'active' : ''; ?>" href="<?php echo path('admin.php'); ?>">
              <span data-feather="home"></span>
              Dashboard <?php echo ( isCurrentPage('admin.php') ) ? '<span class="sr-only">(current)</span>' : ''; ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ( isCurrentPage('admin-locations.php') ) ? 'active' : ''; ?>" href="<?php echo path('admin-locations.php'); ?>">
              <span data-feather="map-pin"></span>
              Locations <?php echo ( isCurrentPage('admin-locations.php') ) ? '<span class="sr-only">(current)</span>' : ''; ?>
            </a>
          </li>
        </ul>
      </div>
    </nav>