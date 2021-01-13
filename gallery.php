<html>
<?php
   require_once('proc_gallery.php');
   proc_gallery("gallery.csv", "details", "rand");
   proc_gallery("imagetest.csv", "matrix", "date_oldest");
   proc_gallery("imagetest.csv", "list", "size_largest");
   proc_gallery("gallery.csv", "matrix", "rand");
?>
</html>