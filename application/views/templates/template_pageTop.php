<div id="pageTop">
  <div id="pageTopWrap">
    <div id="pageTopLogo">
      <div id="pageTopLogoTop"></div>
      <a href="http://www.timecords.com">
        <img src="http://www.timecords.com/images/logo2.png" alt="logo" title="timecords">
      </a>
    </div>
    <div id="pageTopRest">
      <div id="menu1">
        <div>
          <!--<a href="http://www.timecords.com/change_pass">Change Password</a>-->
          <a href="http://www.timecords.com/notification">Notification</a>
          <a href="http://www.timecords.com/logout">Log Out</a>
        </div>
      </div>
      <div id="menu2">
        <div>
          <a href="http://www.timecords.com/user/<?php echo $otherProfile['username']; ?>"><?php echo $whose; ?> Wall</a>
          <a href="http://www.timecords.com/photos/<?php echo $otherProfile['username']; ?>"><?php echo $whose; ?> Photos</a>
          <a href="http://www.timecords.com/friends/<?php echo $otherProfile['username']; ?>"><?php echo $whose; ?> Friends</a>
          <div id="memSearch">
            <div id="memSearchInput">
              <input id="searchUsername" type="text" autocomplete="off" onKeyUp="getNames(this.value)" placeholder="Search Username" >
            </div>
            <div id="memSearchResults"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>