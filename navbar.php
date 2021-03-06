<nav class="navbar navbar-dark navbar-fixed-top bg-warning">
  <div class="container">
    <a class="navbar-brand hidden-sm-down" href="index.php"><span class="fa fa-forumbee"></span> The Buzz</a>
    <ul class="nav navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="about.php">About</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Discover</a>
        <div class="dropdown-menu">
          <a class="dropdown-item"><span class="fa fa-fire" style="color:red;"></span> Hot</a>
          <span></span>
          <a class="dropdown-item" href="top.php"><span class="fa fa-trophy" style="color:gold;"></span> Top</a>
          <span></span>
          <a class="dropdown-item" href="new.php"><span class="fa fa-newspaper-o"></span> New</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="search.php"><span class="fa fa-search"></span> Search</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="team.php">Team</a>
      </li>
      <?php if(empty($_SESSION['user'])){ ?>
      <div class="full-nav">
        <li class="nav-item dropdown pull-xs-right">
          <a class="dropdown-toggle btn btn-secondary" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-sign-in"></span> Login to your Beehive</a>
          <div class="dropdown-menu dropdown-menu-right" style="padding:5px;">
            <form action="login.php" method="post">
              Email: <input type="text" class="form-control" name="email" value="" required=""/>
              </br>
              Password: <input type="password" class="form-control" name="password" value="" required=""/>
              </br>
              <input class="btn btn-primary-outline" type="submit" value="Login" required=""/>
            </form>
          </div>
        </li>
      </div>
      <?php } else{ ?>
        <li class="nav-item dropdown pull-xs-right">
          <a class="dropdown-toggle btn btn-secondary" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            Hey @<?php echo $_SESSION['user']['username']; ?>!
          </a>
          <div class="dropdown-menu dropdown-menu-right" style="padding:5px;">
            <a class="dropdown-item" href="user.php?username=<?php echo $_SESSION['user']['username']; ?>"><img class="img-fluid center-block" src="https://api.adorable.io/avatars/8/<?php echo $_SESSION['user']['username']; ?>.png" alt="The drones bees are almost done their work!"></a>
            <a class="dropdown-item" href="home.php"><span class="fa fa-home"></span> Home</a>
            <span></span>
            <a class="dropdown-item" href="user.php?username=<?php echo $_SESSION['user']['username']; ?>"><span class="fa fa-user"></span> Your Profile</a>
            <span></span>
            <a class="dropdown-item" href="logout.php"><span class="fa fa-sign-out"></span> Logout</a>
          </div>
        </li>
      <?php } ?>
    </ul>
  </div>
</nav>
