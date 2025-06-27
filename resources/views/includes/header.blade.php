<!-- =======================Header START -->
<header class="navbar-light navbar-sticky header-static">
	<!-- Logo Nav START -->
	<nav class="navbar navbar-expand-lg">
		<div class="container">
			<!-- Logo START -->
			<a class="navbar-brand" href="/">
				<img class="navbar-brand-item light-mode-item" src="/assets/images/logo.svg" alt="logo">			
				<img class="navbar-brand-item dark-mode-item" src="/assets/images/logo-light.svg" alt="logo">			
			</a>
			<!-- Logo END -->

			<!-- Responsive navbar toggler -->
			<button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			  <span class="text-body h6 d-none d-sm-inline-block">Menu</span>
			  <span class="navbar-toggler-icon"></span>
		  </button>

			<!-- Main navbar START -->
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="navbar-nav navbar-nav-scroll ms-auto">
					
					<!-- Nav item 1 Demos -->
					<li class="nav-item dropdown">
						<a class="nav-link active" href="/" id="homeMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Accueil</a>
					</li>

					<!-- Nav item 2 Pages -->
					<li class="nav-item dropdown dropdown-fullwidth">
						<a class="nav-link dropdown-toggle" href="#" id="pagesMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Economie</a>
						<ul class="dropdown-menu" aria-labelledby="pagesMenu">
                            <economie-header></economie-header>
						</ul>
					</li>

                    <li class="nav-item dropdown dropdown-fullwidth">
						<a class="nav-link dropdown-toggle" href="#" id="pagesMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">International</a>
						<ul class="dropdown-menu" aria-labelledby="pagesMenu">
                            <international-header></international-header>
						</ul>
					</li>

                    <li class="nav-item dropdown dropdown-fullwidth">
						<a class="nav-link dropdown-toggle" href="#" id="pagesMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Politique</a>
						<ul class="dropdown-menu" aria-labelledby="pagesMenu">
                            <politique-header></politique-header>
						</ul>
					</li>
					
				</ul>
			</div>
			<!-- Main navbar END -->

			<!-- Nav right START -->
			<div class="nav ms-sm-3 flex-nowrap align-items-center">
				<!-- Dark mode switch -->
				<div class="nav-item">
					<div class="modeswitch" id="darkModeSwitch">
						<div class="switch"></div>
					</div>
				</div>
				<!-- Nav additional link -->
				<div class="nav-item dropdown dropdown-toggle-icon-none d-sm-block">
					<a class="nav-link dropdown-toggle" role="button" href="#" id="navAdditionalLink" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="bi bi-three-dots fs-4"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-end min-w-auto shadow rounded text-end" aria-labelledby="navAdditionalLink">
						<li><a class="dropdown-item fw-normal" href="/about">A propos</a></li>
						<li><a class="dropdown-item fw-normal" href="#newsletter">Newsletter</a></li>
						<li><a class="dropdown-item fw-normal" href="/contact">Contactez nous</a></li>
						<li><a class="dropdown-item fw-normal" href="/privacy">Confidentialité</a></li> 
						<li><a class="dropdown-item fw-normal" href="/cookies">Cookies</a></li>
						<li><off-canvas></off-canvas></li>
					</ul>
				</div>
				<!-- Nav Button -->
				<div class="nav-item d-none d-md-block">
					<in-first-menu></in-first-menu>
				</div>
				<!-- Nav Search -->
				<div class="nav-item dropdown nav-search dropdown-toggle-icon-none">
					<a class="nav-link pe-0 dropdown-toggle" role="button" href="#" id="navSearch" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="bi bi-search fs-4"> </i>
					</a>
					<div class="dropdown-menu dropdown-menu-end shadow rounded p-2" aria-labelledby="navSearch">
					  <form class="input-group" action="/search-posts" method="GET">
					    <input class="form-control border-success" name="query" type="search" placeholder="Recherche..." aria-label="Search">
					    <button class="btn btn-success m-0" type="submit"><i class="bi bi-search"> </i></button>
					  </form>
					</div>
				</div>
			</div>
			<!-- Nav right END -->
		</div>
	</nav>
	<!-- Logo Nav END -->
</header>
<!-- =======================
Header END -->