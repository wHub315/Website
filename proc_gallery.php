<html>
<head>
<style>
img.list 
{
   height: 32%;
   width: 100%;
}
img.matrix 
{
   height: 32%;
   width: 32%;
}
div.mtext
{
    display: inline-block;
    border: 1px hidden black;
    width: 32%;
}
div.dtext
{
    border: 1px hidden black;
    width: 100%;
}
</style>
</head>
<body>
<?php
function proc_csv_array ($filename, $delimiter, $quote, $columns_to_show) 
{
    $sourcefile = fopen($filename, "r") or die("Unable to open {$filename}\n");
	$output_array = array();
    if ($columns_to_show == "ALL")
    {
        while ($line = fgets($sourcefile)) 
        {
           #call preg_quote on $line to remove possible errors 
           $line = preg_quote($line);
           $occurences = preg_match_all("#{$quote}#", $line);
           #If quotes appear in the line
           if ($occurences > 0)
           {
               $index = 0;
               $offset = 0;
               $positions = array();
               #Puts positions of quotes in an array
               preg_match_all("#{$quote}#", $line, $matches, PREG_OFFSET_CAPTURE);
               $arr = $matches[0];
               #Replaces delimiters in within quotations with an arbitrary character 5
               for($i = 0; $i < $occurences; $i+=2)
               {
                   $length = $arr[$i+1][1] - $arr[$i][1] + 1;
                   $index = $arr[$i][1];
                   $og_string = substr($line, $index, $length);
                   $new_string = preg_replace("#{$delimiter}#", "5", $og_string);
                   $p2 = substr($line, $index + $length, strlen($line) - $index - $length);
                   $p1 = substr($line, 0, $index);
                   $line = "{$p1}{$new_string}{$p2}";
               }
               $data_cols = preg_split("#{$delimiter}#", $line);
               #Once preg_split has been performed, replaces 5 with the delimiter once again
               for ($i=0; $i<count($data_cols); ++$i)
               {
                   if (strpos($data_cols[$i], "5") !== FALSE)
                   {
                       $data_cols[$i] = preg_replace("#5#", "{$delimiter}", $data_cols[$i]);
                   }
                   $data_cols[$i] = preg_replace("#{$quote}#", "", $data_cols[$i]);
                   $data_cols[$i] = preg_replace("#\\\#", "", $data_cols[$i]);
               }
               $output_array[] = $data_cols;
           }
           else
           {
                $data_cols = preg_split("#{$delimiter}#", $line);
				$output_array[] = $data_cols;
           }
        }
    }
    else
    {
		$columns_array = array();
        while ($line = fgets($sourcefile)) 
        {
            $occurences = preg_match_all("#{$quote}#", $line);
            #If quotes appear in the line
            if ($occurences > 0)
            {
                $index = 0;
                $offset = 0;
                #Puts positions of quotes in an array
                preg_match_all("#{$quote}#", $line, $matches, PREG_OFFSET_CAPTURE);
                $arr = $matches[0];
                #Replaces delimiters in within quotations with an arbitrary character 5
                for($i = 0; $i < $occurences; $i+=2)
                {
                    $length = $arr[$i+1][1] - $arr[$i][1] + 1;
                    $index = $arr[$i][1];
                    $og_string = substr($line, $index, $length);
                    $new_string = preg_replace("#{$delimiter}#", "5", $og_string);
                    $p2 = substr($line, $index + $length, strlen($line) - $index - $length);
                    $p1 = substr($line, 0, $index);
                    $line = "{$p1}{$new_string}{$p2}";
                }
                $data_cols = preg_split("#{$delimiter}#", $line);
                #Once preg_split has been performed, replaces 5 with the delimiter once again
                for ($i=0; $i<count($data_cols); ++$i)
                {
                    if (strpos($data_cols[$i], "5") !== FALSE)
                    {
                        $data_cols[$i] = preg_replace("#5#", "{$delimiter}", $data_cols[$i]);
                    }
                    $valid = strpos($columns_to_show, strval($i + 1));
					
                    if ($valid !== FALSE)
                    {
                        $data_cols[$i] = preg_replace("#{$quote}#", "", $data_cols[$i]);
						$columns_array[] = $data_cols[$i];
						
                    }
                }
				$output_array[] = $columns_array;
            }
            else
            {
                #if no quotes appear in the line, simply split the line with the given delimeter and print each cell
                $data_cols = preg_split("#{$delimiter}#", $line);
                for ($i=0; $i<count($data_cols); ++$i) 
                {
                    #ensures nothing out of bounds is printed
                    $valid = strpos($columns_to_show, strval($i));
                    if ($valid !== FALSE)
                    {
						$columns_array[] = $data_cols[$i];
                    }
                }
				$output_array[] = $columns_array;
            }
            
        }
    }
    fclose($sourcefile);
	return $output_array;
}
//Given the data and sort mode, this function calls several built-in php functions to sort data array
function image_sort($data, $sort_mode)
{
    if (strcmp($sort_mode, "orig") === 0)
    {
        return $data;
    }
    else if (strcmp($sort_mode, "date_newest") === 0)
    {
        $date_array = array();
        //loops through array of data and calls filemtime on each image, appends to $date_array
        foreach($data as &$file)
        {
            //assumes file is located in same directory as gallery.php
            $date_array[] = filemtime($file[0]);
        }
        //sorts both arrays in descending order because filetime values are larger for newer images
        array_multisort($date_array, SORT_DESC, $data, SORT_DESC);
        return $data;
    }
    else if (strcmp($sort_mode, "date_oldest") === 0)
    {
        $date_array = array();
        //loops through array of data and calls filemtime on each image, appends to $date_array
        foreach($data as &$file)
        {
            //assumes file is located in same directory as gallery.php
            $date_array[] = filemtime($file[0]);
        }
        //sorts both arrays in ascending order because filetime values are smaller for older images
        array_multisort($date_array, $data);
        return $data;
    }
    else if (strcmp($sort_mode, "size_largest") === 0)
    {
        $size_array = array();
        foreach($data as &$file)
        {
            //assumes file is located in same directory as gallery.php
            $size_array[] = filesize($file[0]);
        }
        //sorts both arrays in descending order because the larger files will have larger values in $size_array
        array_multisort($size_array, SORT_DESC, $data, SORT_DESC);
        return $data;
    }
    else if (strcmp($sort_mode, "size_smallest") === 0)
    {
        $size_array = array();
        foreach($data as &$file)
        {
            //assumes file is located in same directory as gallery.php
            $size_array[] = filesize($file[0]);
        }
        //sorts both arrays in descending order because the larger files will have larger values in $size_array
        array_multisort($size_array, $data);
        return $data;
    }
    else if (strcmp($sort_mode, "rand") === 0)
    {
        //call built-in function to shuffle values in data array
        shuffle($data);
        return $data;
    }
}
function gallery_matrix($data)
{
    for ($i = 0; $i < count($data); $i+=3)
    {
        echo "<img class=\"matrix\" src=\"{$data[$i][0]}\">";
        echo "&nbsp&nbsp";
        if ($i + 1 < count($data))
        {
            echo "<img class=\"matrix\" src=\"{$data[$i + 1][0]}\">";
            echo "&nbsp&nbsp";
            if ($i + 2 < count($data))
            {
                //outputs image 3 and descriptions for images 1-3 below
                echo "<img class=\"matrix\" src=\"{$data[$i + 2][0]}\">";
                echo "<br>";
                echo "<div class=\"mtext\"> {$data[$i][1]} </div>";
                echo "&nbsp&nbsp";
                echo "<div class=\"mtext\"> {$data[$i+1][1]} </div>";
                echo "&nbsp&nbsp";
                echo "<div class=\"mtext\"> {$data[$i+2][1]} </div>";
                echo "<br>";
            }
            else
            {
                //Adds linebreak, outputs descriptions for images 1&2
                echo "<br>";
                echo "<div class=\"mtext\"> {$data[$i][1]} </div>";
                echo "&nbsp&nbsp";
                echo "<div class=\"mtext\"> {$data[$i+1][1]} </div>";
                echo "<br>";
                break;
            }
        }
        else
        {
            //Adds linebreak, outputs descriptions for image 1
            echo "<br>";
            echo "<div class=\"mtext\"> {$data[$i][1]} </div>";
            echo "<br>";
            break;
        }
    }
}
function gallery_list($data)
{
    foreach($data as &$row)
    {
        //Outputs image to fill entire width of browser, then description underneath
        echo "<img class=\"list\" src=\"{$row[0]}\">";
        echo "<br>";
        echo "{$row[1]}<br>";
    }
}
function gallery_details($data)
{
    date_default_timezone_set("America/Mexico_City");
    $d;
    $s;
    foreach($data as &$row)
    {
        //Outputs filename, description, time last modified, and size separated by tabs
        echo "<p>";
        echo "{$row[0]}&nbsp&nbsp&nbsp";
        echo "{$row[1]}&nbsp&nbsp&nbsp";
        $d = filemtime($row[0]);
        echo date("m-d-y T", $d);
        echo "&nbsp&nbsp&nbsp";
        $s = filesize($row[0]);
        echo "{$s} bytes</p>";
    }
}

function proc_gallery($filename, $mode, $sort_mode)
{
    //calls modified csv function to  gather data from file into an array, assumes doublequotes and , as delimiter
    $data = proc_csv_array("{$filename}", ",", "\"", "ALL");
    //calls sort function to sort data based off of given sort_mode
    $data = image_sort($data, $sort_mode);
    if (strcmp($mode, "list") === 0)
    {
        gallery_list($data, $sort_mode);
    }
    else if (strcmp($mode, "matrix") === 0)
    {
        gallery_matrix($data, $sort_mode);
    }
    else if (strcmp($mode, "details") === 0)
    {
        gallery_details($data, $sort_mode);
    }
}
    ?>
</body>
</html>