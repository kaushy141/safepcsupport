<div class="sidebar">
  <div class="version_update"></div>
  <nav class="sidebar-nav">
    <ul class="nav">
      <?php 
	  $navbar = new Navbar();
	  $navbarList = $navbar->getNavBarList();
	  $i=0;
	  foreach($navbarList as $nav)
	  {
		 if(isset($nav['info']) && !empty($nav['info']))
		 {			 			 
			 if(isset($nav['child']) && !empty($nav['child']))
			 {				 
			 	 echo "<li class=\"nav-item nav-dropdown\">";
				 
				 echo "<a class=\"nav-link nav-dropdown-toggle\"><i class=\"fa-w ".$nav['info']['module_icon']."\"></i> ".$nav['info']['module_name']."</a>";
				 echo "<ul class=\"nav-dropdown-items\">";
				 
				 foreach($nav['child'] as $navChild)
				 {
					 echo "<li class=\"nav-item\"><a class=\"nav-link redirect\" data-title=\"".$navChild['module_name']."\" href=\"".$app->siteUrl($navChild['module_key'])."\"><i class=\"fa-w ".$navChild['module_icon']."\" ></i> ".$navChild['module_name']."</a></li>";
				 }
				 
				 echo "</ul>";
				 echo "</li>";
			 }
			 else
			 	echo "<li class=\"nav-item\"> <a class=\"nav-link redirect\" href=\"".$app->siteUrl($nav['info']['module_key'])."\"><i class=\"fa-w ".$nav['info']['module_icon']."\"></i> ".$nav['info']['module_name']." <span class=\"badge gradient_bg\">NEW</span></a> </li>";
			 
			 echo "<li class=\"divider\"></li>";
		 } 
	  }	  
	  ?>
      <li class="nav-item" style="height:50px;"> <a class="nav-link redirect"></a></li>
    </ul>    
  </nav>
</div>