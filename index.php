<html>

<!-- HEAD section ............................................................................ -->
<head>
  <!-- style -->
 
  <style>
    div.defaultFont {
        font-family: Georgia;
    }
    
    div.secondaryFont {
        font-family: Georgia;
    }

    div.homeButtons {
        display: inline-block;
        border: 1px solid black;
        width: 19%;
    }

    div.aboutSection {
        display: inline-block;
        border: 1px solid black;
        width: 100%;
    }

    h3 {
        color: blue;
    }

    rfont {
        font-family: Georgia;
    }
    <!-- link href="default.css" rel="stylesheet" type="text/css -->
  </style>

  

</head>

<!-- BODY section ............................................................................. -->
<body>
<div class="defaultFont">

<!-- PHP testing area ................................ --> 
<?php
   require_once('proc_csv.php');
   require_once('proc_wikitext.php');

   echo "<h1> Wyatt Masterson's 315 Site\n </h1>";

   echo "<div class=\"homebuttons\"> <a href=\"http://masterson315.infinityfreeapp.com/gallery.php\"target=\"_blank\">Click here to view gallery page!</a> </div>";
   echo "<div class=\"homebuttons\"> <a href=\"http://masterson315.infinityfreeapp.com/search.php\">Click here to enter search page!</a> </div>";
   echo "<div class=\"homebuttons\"> <a href=\"http://masterson315.infinityfreeapp.com/blog.php\"target=\"_blank\">Click here to view blog page!</a> </div>";
   echo "<div class=\"homebuttons\"> <a href=\"http://masterson315.infinityfreeapp.com/resources.php\"target=\"_blank\">Click here to enter resources page!</a> </div>";
   echo "<div class=\"homebuttons\"> <a href=\"http://masterson315.infinityfreeapp.com/tips.php\"target=\"_blank\">Click here to enter tips page!</a> </div><br>";
   echo "<h2> About Me </h2>";
   echo "<p><div class=\"aboutSection\">My name is Wyatt Masterson and I am a Junior Computer Science major at Texas A&M. I have a few years experience of programming, and I am most proficient in C++ and Java. Creating this website was part of a project I had to do for my CSCE 315: Programming Studio class. I learned alot creating this website, and I hope that I can apply the lessons I learned in my future career as well. </div></p>";

   #For whatever reason, when calling proc_csv, the quotes used in the arguments must be of the same type as the quotes given in the file. e.g. when calling a file that uses double quotes, all arguments must be given in doublequotes
   echo "<h2> Testing Proc_Csv </h2>";
   call_proc_csv();
   echo "<h2> Testing Proc_wikitext </h2>";
   proc_wikitext('wikitext.wiki');
?>

</body>

</html>

