<?php

require_once('includes/bootstrap.php');

require_once('templates/admin-header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row">
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h3>Locations</h3>
              <p class="card-text">Manage your locations and toggle queue position for your customers.</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <a href="<?php echo path('admin-locations.php'); ?>" class="btn btn-sm btn-outline-secondary">Manage Locations</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

<?php require_once('templates/admin-footer.php'); ?>