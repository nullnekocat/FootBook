<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">      
        <a class="navbar-brand" href="index.php">FootBook</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="wiki.php">Wiki</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Profile
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="login.php">Login</a>
                        <a class="dropdown-item" href="signup.php">Sign up</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="profile.php">Page</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="admin.php">Admin</a>
                </li>
            </ul>
            <form class="form-inline d-flex ms-auto">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-secondary">Filter</button>
                    <button class="btn btn-outline-success my-sm-0" type="submit">Icon</button>
                </div>
            </form>
        </div>
    </div>
</nav>