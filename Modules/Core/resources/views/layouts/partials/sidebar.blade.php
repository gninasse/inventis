      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="{{ url('/') }}" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="{{ asset('adminlte/assets/img/AdminLTELogo.png') }}"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">CHU-YO</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    KEYSTONE
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @can('cores.dashboard.view')
                  <li class="nav-item">
                    <a href="{{ route('cores.dashboard') }}" class="nav-link {{ request()->routeIs('cores.dashboard') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-speedometer2"></i>
                      <p>Dashboard</p>
                    </a>
                  </li>
                  @endcan
                  @can('cores.users.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.users.index') }}" class="nav-link {{ request()->routeIs('cores.users.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-people"></i>
                      <p>Utilisateurs</p>
                    </a>
                  </li>
                  @endcan
                  @can('cores.roles.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.roles.index') }}" class="nav-link {{ request()->routeIs('cores.roles.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-shield-lock"></i>
                      <p>Rôles</p>
                    </a>
                  </li>
                  @endcan
                  @can('cores.permissions.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.permissions.index') }}" class="nav-link {{ request()->routeIs('cores.permissions.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-key"></i>
                      <p>Permissions</p>
                    </a>
                  </li>
                  @endcan
                  @can('cores.modules.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.modules.index') }}" class="nav-link {{ request()->routeIs('cores.modules.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-box-seam"></i>
                      <p>Modules</p>
                    </a>
                  </li> 
                  @endcan
                  @can('cores.activities.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.activities.index') }}" class="nav-link {{ request()->routeIs('cores.activities.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-clock-history"></i>
                      <p>Activités</p>
                    </a>
                  </li>
                  @endcan
                </ul>
              </li>

              <!-- GROUPE 1 — RÉFÉRENTIEL -->
              <li class="nav-item {{ request()->routeIs('cores.referentiel.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('cores.referentiel.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-database"></i>
                  <p>
                    RÉFÉRENTIEL
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if(Route::has('cores.referentiel.categories.index'))
                  @can('cores.referentiel.categories.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.referentiel.categories.index') }}" class="nav-link {{ request()->routeIs('cores.referentiel.categories.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-folder"></i>
                      <p>Catégories</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.referentiel.sous-categories.index'))
                  @can('cores.referentiel.sous-categories.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.referentiel.sous-categories.index') }}" class="nav-link {{ request()->routeIs('cores.referentiel.sous-categories.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-folder-symlink"></i>
                      <p>Sous-catégories</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.referentiel.familles.index'))
                  @can('cores.referentiel.familles.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.referentiel.familles.index') }}" class="nav-link {{ request()->routeIs('cores.referentiel.familles.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-diagram-3"></i>
                      <p>Familles</p>
                    </a>
                  </li>
                  @endcan
                  @endif


                  @if(Route::has('cores.referentiel.articles.index'))
                  @can('cores.referentiel.articles.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.referentiel.articles.index') }}" class="nav-link {{ request()->routeIs('cores.referentiel.articles.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-box"></i>
                      <p>Articles</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.referentiel.unites.index'))
                  @can('cores.referentiel.unites.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.referentiel.unites.index') }}" class="nav-link {{ request()->routeIs('cores.referentiel.unites.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-rulers"></i>
                      <p>Unités de mesure</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.referentiel.fournisseurs.index'))
                  @can('cores.referentiel.fournisseurs.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.referentiel.fournisseurs.index') }}" class="nav-link {{ request()->routeIs('cores.referentiel.fournisseurs.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-truck"></i>
                      <p>Fournisseurs</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.referentiel.fabricants.index'))
                  @can('cores.referentiel.fabricants.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.referentiel.fabricants.index') }}" class="nav-link {{ request()->routeIs('cores.referentiel.fabricants.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-building"></i>
                      <p>Fabricants</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.referentiel.sources.index'))
                  @can('cores.referentiel.sources.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.referentiel.sources.index') }}" class="nav-link {{ request()->routeIs('cores.referentiel.sources.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-cash-coin"></i>
                      <p>Sources de financement</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.referentiel.modes-acquisition.index'))
                  @can('cores.referentiel.modes-acquisition.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.referentiel.modes-acquisition.index') }}" class="nav-link {{ request()->routeIs('cores.referentiel.modes-acquisition.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-file-earmark-text"></i>
                      <p>Modes d'acquisition</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.referentiel.budgets.index'))
                  @can('cores.referentiel.budgets.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.referentiel.budgets.index') }}" class="nav-link {{ request()->routeIs('cores.referentiel.budgets.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-wallet2"></i>
                      <p>Budgets</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.referentiel.magasins.index'))
                  @can('cores.referentiel.magasins.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.referentiel.magasins.index') }}" class="nav-link {{ request()->routeIs('cores.referentiel.magasins.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-house-door"></i>
                      <p>Magasins</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                </ul>
              </li>

              <!-- GROUPE 2 — ORGANISATION -->
              <li class="nav-item {{ request()->routeIs('cores.organisation.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('cores.organisation.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-diagram-2"></i>
                  <p>
                    ORGANISATION
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if(Route::has('cores.organisation.sites.index'))
                  @can('cores.organisation.sites.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.organisation.sites.index') }}" class="nav-link {{ request()->routeIs('cores.organisation.sites.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-geo-alt"></i>
                      <p>Sites</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.organisation.directions.index'))
                  @can('cores.organisation.directions.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.organisation.directions.index') }}" class="nav-link {{ request()->routeIs('cores.organisation.directions.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-building-fill"></i>
                      <p>Directions</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.organisation.services.index'))
                  @can('cores.organisation.services.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.organisation.services.index') }}" class="nav-link {{ request()->routeIs('cores.organisation.services.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-people-fill"></i>
                      <p>Services</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.organisation.unites.index'))
                  @can('cores.organisation.unites.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.organisation.unites.index') }}" class="nav-link {{ request()->routeIs('cores.organisation.unites.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-person-badge"></i>
                      <p>Unités cliniques</p>
                    </a>
                  </li>
                  @endcan
                  @endif
                </ul>
              </li>

              <!-- GROUPE 3 — PATRIMOINE -->
              <li class="nav-item {{ request()->routeIs('cores.patrimoine.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->routeIs('cores.patrimoine.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-archive"></i>
                  <p>
                    PATRIMOINE
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if(Route::has('cores.patrimoine.statuts.index'))
                  @can('cores.patrimoine.statuts.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.patrimoine.statuts.index') }}" class="nav-link {{ request()->routeIs('cores.patrimoine.statuts.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-toggle-on"></i>
                      <p>Statuts des biens</p>
                    </a>
                  </li>
                  @endcan
                  @endif

                  @if(Route::has('cores.patrimoine.etats.index'))
                  @can('cores.patrimoine.etats.index')
                  <li class="nav-item">
                    <a href="{{ route('cores.patrimoine.etats.index') }}" class="nav-link {{ request()->routeIs('cores.patrimoine.etats.*') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-clipboard-check"></i>
                      <p>États des biens</p>
                    </a>
                  </li>
                  @endcan
                  @endif
                </ul>
              </li>
               
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
