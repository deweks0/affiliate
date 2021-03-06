<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="" class="simple-text logo-normal">
      {{ auth()->user()->company == null ?'Affiliate':auth()->user()->company->name  }}
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route($routeDashboard) }}">
          <i class="material-icons">dashboard</i>
          <p>{{ __('Dashboard') }}</p>
        </a>
      </li>
      
      @if(
      auth()->user()->hasPermissionTo('company.view')|| 
      auth()->user()->hasPermissionTo('admin.view') || 
      auth()->user()->hasPermissionTo('reseller.view') 
      )

      <li class="nav-item {{ ($activePage == 'reseller' || $activePage == 'admin') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#adminService" aria-expanded="true">
          <i><span class="material-icons">perm_identity</span></i>
          <p>{{ __('User Management') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'reseller' || $activePage == 'admin' || $activePage=='company') ? ' show' : '' }}" id="adminService">
          <ul class="nav">
            @can('company.view')
            <li class="nav-item{{ $activePage == 'company' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('admin.company.index') }}">
                <span class="sidebar-mini"> CP </span>
                <span class="sidebar-normal"> {{ __('Company') }} </span>
              </a>
            </li>
            @endcan
            @can('admin.view')
            <li class="nav-item{{ $activePage == 'admin' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('admin.user.index') }}">
                <span class="sidebar-mini"> AD </span>
                <span class="sidebar-normal"> {{ __('Admin') }} </span>
              </a>
            </li>
            @endcan
      @can('reseller.view')
            <li class="nav-item{{ $activePage == 'reseller' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('admin.reseller.index') }}">
                <span class="sidebar-mini"> RS </span>
                <span class="sidebar-normal">{{ __('Reseller') }} </span>
              </a>
            </li>
            @endcan
          </ul>
        </div>
      </li>
      @endif

      @can('role.view')
        
      
      <li class="nav-item{{ $activePage == 'role' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('admin.role.index') }}">
          <i class="material-icons">admin_panel_settings</i>
          <p>{{ __('Role Management') }}</p>
        </a>
      </li>
      @endcan
      @if(auth()->user()->hasPermissionTo('product.view'))
      <li class="nav-item{{ $activePage == 'product' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('admin.product.index') }}">
          <i class="material-icons">content_paste</i>
            <p>{{ __('Product') }}</p>
        </a>
      </li>
      @endif
     @can('setting.view')

      <li class="nav-item{{ $activePage == 'setting' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('admin.setting.index')}}">
          <i class="material-icons">settings_applications</i>
            <p>{{ __('Settings') }}</p>
        </a>
      </li> 
      @endcan
      @can('log.view')

      <li class="nav-item{{ $activePage == 'log' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('admin.log.index')}}">
          <i class="material-icons">timeline</i>
            <p>{{ __('Log Activity') }}</p>
        </a>
      </li>
      @endcan
      @role('reseller')
      <li class="nav-item{{ $activePage == 'client' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('reseller.client.index')}}">
          <i class="material-icons">supervisor_account</i>
            <p>{{ __('Clients') }}</p>
        </a>
      </li>
      <li class="nav-item{{ $activePage == 'transaction' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('reseller.client.transaction')}}">
          <i class="material-icons">receipt_long</i>
            <p>{{ __('Transaction') }}</p>
        </a>
      </li>
    
      @endrole
      <li class="nav-item{{ $activePage == 'commission' ? ' active' : '' }}">
        <a class="nav-link" 
        @role('reseller')
          href="{{ route('reseller.commission.index')}}"
          @else
          href="{{ route('admin.commissions.index')}}"
        @endrole
        >
          <i class="material-icons">payments</i>
            <p>{{ __('commission') }}</p>
        </a>
      </li>
    </ul>
  </div>
</div>
