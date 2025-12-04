  <a href="/">Home</a>
  <a href="/people/">People</a>
  <a href="/organizations/">Organizations</a>
  <a href="/subscriptions/">Subscriptions</a>
  <?php
  echo ("    <div class=\"dropdown\">\n");
  echo ("      <button class=\"dropbtn\">Devices\n");
  echo ("        <i class=\"fa fa-caret-down\"></i>\n");
  echo ("      </button>\n");
  echo ("      <div class=\"dropdown-content\">\n");
  echo ("        <a href=\"/devices/\">All Devices</a>\n");
  echo ("        <a href=\"/devices/summary/\">Device Summary</a>\n");
  echo ("        <a href=\"/devices/deprecated/\">Deprecated Devices</a>\n");
  echo ("      </div>\n");
  echo ("    </div>\n");
  if ($isadmin) {
    echo ("    <div class=\"dropdown\">\n");
    echo ("      <button class=\"dropbtn\">Administration\n");
    echo ("        <i class=\"fa fa-caret-down\"></i>\n");
    echo ("      </button>\n");
    echo ("      <div class=\"dropdown-content\">\n");
    echo ("        <a href=\"/admin/users/\">Users</a>\n");
    echo ("        <a href=\"/admin/roles/\">Roles</a>\n");
    echo ("        <a href=\"/admin/settings/\">Settings</a>\n");
    echo ("        <a href=\"/admin/sync/\">Sync Status</a>\n");
    echo ("      </div>\n");
    echo ("    </div>\n");
  }
  ?>
  <a href="/logout">Logout</a>