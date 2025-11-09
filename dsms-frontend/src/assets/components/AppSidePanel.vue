<template>
  <div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
      <div id="sidebar-menu" class="sidebar-menu">
        <ul>
          <li class="menu-title">Main</li>
          <li class="active">
            <router-link :to="{ name: 'dashboard' }">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </router-link>
          </li>
          <li>
            <a href="doctors.html"><i class="fa fa-user"></i> <span>Instructors</span></a>
          </li>
          <li>
            <a href="patients.html"><i class="fa fa-users"></i> <span>Students</span></a>
          </li>
          <li>
            <a href="appointments.html"><i class="fa fa-book"></i> <span>Courses</span></a>
          </li>
          <li>
            <router-link :to="{ name: 'branches' }">
              <i class="fa fa-calendar-check-o"></i>
              <span>Branches</span>
            </router-link>
          </li>
          <li>
            <a href="departments.html"><i class="fa fa-credit-card-alt"></i> <span>Payments</span></a>
          </li>
          <li class="submenu">
            <a href="#"
              ><i class="fa fa-cogs"></i> <span> Settings </span> <span class="menu-arrow"></span
            ></a>
            <ul style="display: none">
              <li><a href="employees.html">Employees List</a></li>
              <li><a href="leaves.html">Leaves</a></li>
              <li><a href="holidays.html">Holidays</a></li>
              <li><a href="attendance.html">Attendance</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
