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
                        User
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="login.php">Login</a>
                        <a class="dropdown-item" href="signup.php">Sign up</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="profile.php">Profile</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Admin</a>
                </li>
            </ul>
            <form class="form-inline d-flex ms-auto" method="get" action="results.php" id="mainSearchForm" autocomplete="off">
                <div class="input-group position-relative">
                    <input class="form-control" type="search" placeholder="Buscar publicaciones" aria-label="Buscar" name="q" id="main-search-input">
                    <button class="btn btn-secondary" type="button" id="openFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-funnel"></i> Filtros
                    </button>
                    <ul class="dropdown-menu p-3" style="min-width:300px;" id="filterDropdownMenu">
                        <li>
                            <div class="mb-2">
                                <label for="category" class="form-label mb-0">Categoría</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Todas</option>
                                    <option value="Jugadas">Jugadas</option>
                                    <option value="Entrevistas">Entrevistas</option>
                                    <option value="Partidos">Partidos</option>
                                    <!-- ... -->
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="mb-2">
                                <label for="year" class="form-label mb-0">Año de mundial</label>
                                <input type="number" class="form-control" id="year" name="year" min="1930" max="2026" placeholder="Ej: 2014">
                            </div>
                        </li>
                        <li>
                            <div class="mb-2">
                                <label for="country" class="form-label mb-0">País sede</label>
                                <input type="text" class="form-control" id="country" name="country" placeholder="Ej: Brasil">
                            </div>
                        </li>
                        <li>
                            <div class="mb-2">
                                <label for="user" class="form-label mb-0">Usuario</label>
                                <input type="text" class="form-control" id="user" name="user" placeholder="Nombre de usuario">
                            </div>
                        </li>
                        <li>
                            <div class="mb-2">
                                <label for="order" class="form-label mb-0">Ordenar por</label>
                                <select class="form-select" id="order" name="order">
                                    <option value="recent">Más reciente</option>
                                    <option value="likes">Más likes</option>
                                    <option value="comments">Más comentarios</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <button class="btn btn-primary w-100" type="submit" form="mainSearchForm">Aplicar filtros</button>
                        </li>
                    </ul>
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>