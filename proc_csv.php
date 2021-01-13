<?php
function proc_csv ($filename, $delimiter, $quote, $columns_to_show) {
    $sourcefile = fopen($filename, "r") or die("Unable to open {$filename}\n");
    echo "<table border=\"2\">\n";
    if ($columns_to_show == "ALL")
    {
        while ($line = fgets($sourcefile)) 
        {
           #call preg_quote on $line to remove possible errors 
           $line = preg_quote($line);
           $occurences = preg_match_all("#{$quote}#", $line);
           echo "<tr>\n";
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
                   echo " <td> ".$data_cols[$i]." </td>\n";
               }
               echo "</tr>\n";
           }
           else
           {
                $data_cols = preg_split("#{$delimiter}#", $line);
                for ($i=0; $i<count($data_cols); ++$i) 
                {
                     echo " <td> ".$data_cols[$i]." </td>\n";
                }
                echo "</tr>\n";
           }
        }
    }
    else
    {
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
                echo "<tr>\n";
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
                        echo " <td> ".$data_cols[$i]." </td>\n";
                    }
                }
                echo "</tr>\n";
            }
            else
            {
                #if no quotes appear in the line, simply split the line with the given delimeter and print each cell
                echo "<tr>\n";
                $data_cols = preg_split("#{$delimiter}#", $line);
                for ($i=0; $i<count($data_cols); ++$i) 
                {
                    #ensures nothing out of bounds is printed
                    $valid = strpos($columns_to_show, strval($i));
                    if ($valid !== FALSE)
                    {
                    echo " <td> ".$data_cols[$i]." </td>\n";
                    }
                }
                echo "</tr>\n";
            }
            
        }
    }
    fclose($sourcefile);
    echo "</table>\n<p/>";
    }
    function call_proc_csv()
    {
       proc_csv("dat2-doublequote-tab.csv", "\t", "\"", "1:3");
       proc_csv('dat-singlequote-colon.csv', ':', '\'', 'ALL');
    }
    ?>