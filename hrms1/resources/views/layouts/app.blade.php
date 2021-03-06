<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">

   <title>Human Resource Management System</title>
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
   <link rel="stylesheet" href="<?php echo asset('css/style.css')?>" type="text/css">
   <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
   <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
   <link rel="stylesheet" href="//cdn.datatables.net/1.11.1/css/jquery.dataTables.min.css">
   @yield('css')

</head>

<body>
   <nav class="navbar navbar-expand-lg navbar-light bg-light">

      <div class="container-fluid">
         <a class="navbar-brand" href="#">
            <button type="button" id="sidebarCollapse" class="btn btn-info">
               <i class="fas fa-bars"></i>
            </button>
            HRMS
         </a>

         <ul class="navbar-nav">
            @guest
            <li class="nav-item">
               <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @if (Route::has('register'))
            <li class="nav-item">
               <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @endif
            @else
            <li class="nav-item">
               <a class="nav-link" href="#">
                  <i class="fas fa-bell fa-lg"></i>
               </a>
            </li>
            <li class="nav-item dropdown">
               <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                  {{ Auth::user()->name }} <span class="caret"></span>
               </a>

               <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
               </a>

               <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
               </form>
            </div>
         </li>
         @endguest
      </ul>
   </div>
</nav>

<div class="wrapper">
   <nav id="sidebar">
      <ul class="list-unstyled components">
         <li class="active">
            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Dashboard</a>
            <ul class="collapse list-unstyled" id="homeSubmenu">
               <li>
                  <a href="#">Page1</a>
               </li>
               <li>
                  <a href="#">Page2</a>
               </li>
            </ul>
         </li>
         <li>
            <a href="#">About</a>
         </li>
         <li>
            <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Pages</a>
            <ul class="collapse list-unstyled" id="pageSubmenu">
               <li>
                  <a href="#">Page 1</a>
               </li>
            </ul>
         </li>
         <li>
            <a href="#">Portfolio</a>
         </li>
         <li>
            <a href="#">Contact</a>
         </li>
      </ul>
   </nav>

   <div id="content">
      <main class="py-4">
         @yield('content')
      </main>
   </div>

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

<script type="text/javascript">
   $(document).ready(function () {
      $('#sidebarCollapse').on('click', function () {
         $('#sidebar').toggleClass('active');
      });
   });
</script>

<script src="//cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js" type="text/javascript"></script>

<script type="text/javascript">
   $(document).ready( function () {
      $('#leaveTypesTable').DataTable();
   } );

   $(document).ready( function () {
      $('#adminLeaveApplicationListTable').DataTable();
   } );

   $(document).ready( function () {
      $('#allAdminsLeaveGradeTable').DataTable();
   } );

   $(document).ready( function () {
      $('#allEmployeesLeaveGradeTable').DataTable();
   } );

   $(document).ready( function () {
      $('#employeesLeaveGradeTable').DataTable();
   } );

   $(document).ready( function () {
      $('#employeeLeaveApplicationListAdminView').DataTable();
   } );

   $(document).ready( function () {
      $('#leaveEntitlementTable').DataTable();
   } );

   $(document).ready( function () {
      $('#adminOwnLeaveApplicationList').DataTable();
   } );

   $(document).ready( function () {
      $('#adminOwnLeaveGradeTable').DataTable();
   } );

   $(document).ready( function () {
      $('#leaveGradeTable').DataTable();
   } );

   $(document).ready( function () {
      $('#employeeLeaveApplicationList').DataTable();
   } );
</script>
@yield('script')

</body>
</html>
