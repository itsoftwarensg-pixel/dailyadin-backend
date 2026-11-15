<style>
.main-header {text-align: center;}
.custom-link {padding-top: 15px;text-align: center;display: inline-block;position: relative;}
.custom-link a {background: #fff;color:#000;padding: 5px 10px;border-radius: 5px;font-size: 14px;font-weight: 400;}
</style>
      <header class="main-header">
        <!-- Logo -->
        <a href="<?= $sAdminURL; ?>dashboard.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>D</b>J</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b style="font-size: 18px;">Daily Adin</b>&nbsp;<i class="fa fa-spinner fa-spin fa-lg ajaxLoader" style="display:none;"></i></span>
        </a>
        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
           <div class="custom-link">
            <a href="https://dailyadin.com/" target="_blank">View Website</a>
           </div>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
                  <?php //if($_SESSION['userPic'] && file_exists($userFilepath.'/'.$_SESSION['userPic'])): 
                  ?>
                  <?php if ($_SESSION['userPic']) : ?>
                    <img src="<?= $userFilepath . $_SESSION['userPic'] ?>" class="user-image" alt="<?= ucfirst($_SESSION['userName']); ?>">
                  <?php else : ?>
                    <img src="<?= $userFilepath; ?>no_image.png" class="user-image" alt="User Image">
                  <?php endif; ?>
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs"><?= (!empty($_SESSION['userName'])) ? ucfirst($_SESSION['userName']) : ''; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <li class="user-header">
                    <?php //if($_SESSION['userPic'] && file_exists($userFilepath.'/'.$_SESSION['userPic'])): 
                    ?>
                    <?php if ($_SESSION['userPic']) : ?>
                      <img src="<?= $userFilepath . '/' . $_SESSION['userPic'] ?>" class="img-circle" alt="<?= $_SESSION['userName']; ?>">
                    <?php else : ?>
                      <img src="<?= $userFilepath; ?>no_image.png" class="img-circle" alt="User Image">
                    <?php endif; ?>
                    <p>
                      <?= ucfirst($_SESSION['userName']); ?> - <?= ($sAdmin) ? 'Admin' : ''; ?>
                      <small>Member since Nov. 2012</small>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?= $sAdminURL; ?>admin/editUser.php?uEditID=<?= $_SESSION['userID']; ?>" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?= $sAdminURL; ?>sessionOut.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>