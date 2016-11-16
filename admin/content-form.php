<?php 
            if($row['level']==1){
              switch (@$_REQUEST["page"]) {
                case 'user':
                  include "admin/user_register.php";
                  break;

                case 'user_list':
                  include "admin/user_list.php";
                  break;

                case 'menu_list':
                  include "admin/menu_list.php";
                  break;

                case 'add_menu':
                  include "admin/menu.php";
                  break;

                case 'slideshow':
                  include "admin/slide_show.php";
                  break;
              
                case 'content':
                  include "admin/content.php";
                  break;
               
                case 'content_list':
                  include "admin/content_list.php";
                  break;

                default:
                  include "admin/main.php";
                  break;
              }
            }elseif ($row['level']==2) {
                 switch (@$_REQUEST["page"]) {
                case 'slideshow':
                  include "admin/slide_show.php";
                  break;
              
                case 'content':
                  include "admin/content.php";
                  break;
               
                case 'content_list':
                  include "admin/content_list.php";
                  break;

                default:
                  include "admin/main.php";
                  break; 
                }
            }

             elseif ($row['level']==3) {
                 switch (@$_REQUEST["page"]) {
                case 'content':
                  include "admin/content.php";
                  break;
               
                case 'content_list':
                  include "admin/content_list.php";
                  break;

                default:
                  include "admin/main.php";
                  break; 
                }
            }            
        ?>