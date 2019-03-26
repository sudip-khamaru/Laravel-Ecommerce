<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link @if( request()->url() == route( 'admin.dashboard' ) ) {{ 'active' }} @endif" href="{{ route( 'admin.dashboard' ) }}">
              <span data-feather="home"></span>
              Dashboard <span class="sr-only">(current)</span>
            </a>
          </li>
          <!-- <li class="nav-item @if( url( '/admin' ) == route( 'admin.dashboard' ) ) {{ 'active' }} @endif">
            <a class="nav-link" href="#">
              <span data-feather="file"></span>
              Orders
            </a>
          </li> -->
          <li class="nav-item dropdown">
            <a id="product-dropdown" class="nav-link @if( request()->url() == route( 'admin.products.index' ) ) {{ 'active' }} @endif  dropdown-toggle" href="javascript:;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span data-feather="shopping-cart"></span>
              Products
            </a>
            <div class="dropdown-menu" aria-labelledby="productDropdown">
              <a class="dropdown-item" href="{{ route( 'admin.products.index' ) }}">
                <span data-feather="list"></span>
                All Products
              </a>
              <a class="dropdown-item" href="{{ route( 'admin.products.create' ) }}">
                <span data-feather="plus"></span>
                Add Product
              </a>
              <a class="dropdown-item" href="{{ route( 'admin.products.trash' ) }}">
                <span data-feather="trash"></span>
                Trashed Products
              </a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a id="category-dropdown" class="nav-link @if( request()->url() == route( 'admin.categories.index' ) ) {{ 'active' }} @else {{ '' }} @endif dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span data-feather="grid"></span>
              Categories
            </a>
            <div class="dropdown-menu" aria-labelledby="categoryDropdown">
              <a class="dropdown-item" href="{{ route( 'admin.categories.index' ) }}">
                <span data-feather="list"></span>
                All Categories
              </a>
              <a class="dropdown-item" href="{{ route( 'admin.categories.create' ) }}">
                <span data-feather="plus"></span>
                Add Category
              </a>
              <a class="dropdown-item" href="{{ route( 'admin.categories.trash' ) }}">
                <span data-feather="trash"></span>
                Trashed Categories
              </a>
            </div>
          </li>
          <!-- <li class="nav-item @if( url( '/admin' ) == route( 'admin.dashboard' ) ) {{ 'active' }} @endif">
            <a class="nav-link" href="#">
              <span data-feather="users"></span>
              Customers
            </a>
          </li>
          <li class="nav-item @if( url( '/admin' ) == route( 'admin.dashboard' ) ) {{ 'active' }} @endif">
            <a class="nav-link" href="#">
              <span data-feather="layers"></span>
              Integrations
            </a>
          </li> -->
        </ul>

      <!--   <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>Saved reports</span>
          <a class="d-flex align-items-center text-muted" href="#">
            <span data-feather="plus-circle"></span>
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              Current month
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              Last quarter
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              Social engagement
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text"></span>
              Year-end sale
            </a>
          </li>
        </ul> -->
      </div>
    </nav>