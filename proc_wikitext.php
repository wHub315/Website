<html>
<style>
    heading1 {
        font-family: Georgia;
        font-size: 19px;
    }

    heading2 {
        font-family: Georgia;
        font-size: 16px;
    }

    heading3 {
        font-family: Georgia;
        font-size: 13px;
        font-weight: bold; 
    }

    heading4 {
        font-family: Georgia;
        font-size: 10.5px;
        font-weight: bold;
    }

    term {
        font-weight: bold;
    }
</style>
<?php
#Function that handles links
function links(&$line, &$length)
{
    #If line contains a named link, it goes into this if statement
    if (preg_match("!\[http:!", $line, $beginning, PREG_OFFSET_CAPTURE))
    {
        $beginarr = $beginning[0];
        $startind = $beginarr[1];
        preg_match("!\]!", $line, $ending, PREG_OFFSET_CAPTURE);
        $endarr = $ending[0];
        $endind = $endarr[1];
        preg_match("! !", $line, $spaces, PREG_OFFSET_CAPTURE, $startind);
        $spacearr = $spaces[0];
        $spaceind = $spacearr[1];
        #isolate link by finding location of http, location of space after http, and subtracting location of first http from location of space
        $link = substr($line, $startind + 1, $spaceind - $startind - 1);
        $word = substr($line, $spaceind + 1, $endind - $spaceind - 1);
        $final = "<a href=\"{$link}\">{$word}</a>";
        $p1 = substr($line, 0, $startind);
        $p2 = substr($line, $endind + 1, $length - $endind - 1);
        $line = "{$p1}{$final}{$p2}";
        $length = strlen($line);
    }
    #If line contains a link but it is not named
    else if (preg_match("!http!", $line, $beginning2, PREG_OFFSET_CAPTURE))
    {
        $beginarr = $beginning2[0];
        $startind = $beginarr[1];
        #searches for whitespace at end of link
        $hmm = preg_match("#( |\n)#", $line, $endingsp, PREG_OFFSET_CAPTURE, $startind);
        $endsparr = $endingsp[0];
        $endlink = $endsparr[1];
        $link = substr($line, $startind, $endlink - $startind);
        $p1 = substr($line, 0, $startind);
        $p2 = substr($line, $endlink, $length - $endlink);
        $final = "<a href=\"{$link}\">{$link}</a>";
        $line = "{$p1}{$final}{$p2}";
        $length = strlen($line);
    }
}
#Function that handles bold and italics. Searches given line for the apostrophes with preg_match
function boldanditalics(&$line, &$length)
{
    #If line contains text that is bold and italic
    if (preg_match_all("!\'\'\'\'\'!", $line, $matches, PREG_OFFSET_CAPTURE))
    {
        #location of first '''''
        $firstmatcharr = $matches[0];
        $firstind = $firstmatcharr[0];
        #location of second '''''
        $secondmatcharr = $matches[0];
        $secondind = $secondmatcharr[1];
        $before = substr($line, 0, $firstind[1]);
        $italicsandbold = substr($line, $firstind[1] + 5, $secondind[1] - $firstind[1] - 5);
        $after = substr($line, $secondind[1] + 5, $length - $secondind[1] - 5);
        $line = "{$before}<term><i>{$italicsandbold}</i></term>{$after}";
        $length = strlen($line);
    }
    #If line contains text that is bold
    else if (preg_match_all("!\'\'\'!", $line, $matches, PREG_OFFSET_CAPTURE))
    {
        #location of first '''
        $firstmatcharr = $matches[0];
        $firstind = $firstmatcharr[0];
        #location of second '''
        $secondmatcharr = $matches[0];
        $secondind = $secondmatcharr[1];
        $before = substr($line, 0, $firstind[1]);
        $bold = substr($line, $firstind[1] + 3, $secondind[1] - $firstind[1] - 3);
        $after = substr($line, $secondind[1] + 3, $length - $secondind[1] - 3);
        $line = "{$before}<term>{$bold}</term>{$after}";
        $length = strlen($line);
    }
    #If line contains text that is italic
    else if (preg_match_all("!\'\'!", $line, $matches, PREG_OFFSET_CAPTURE))
    {
        #location of first ''
        $firstmatcharr = $matches[0];
        $firstind = $firstmatcharr[0];
        #location of second ''
        $secondmatcharr = $matches[0];
        $secondind = $secondmatcharr[1];
        $before = substr($line, 0, $firstind[1]);
        $italics = substr($line, $firstind[1] + 2, $secondind[1] - $firstind[1] - 2);
        $after = substr($line, $secondind[1] + 2, $length - $secondind[1] - 2);
        $line = "{$before}<i>{$italics}</i>{$after}";
        $length = strlen($line);
    }
}
#Function handles calls to color or highlight parts of text
function colororhighlight(&$line, &$length)
{
    #If given line contains text to highlight
    if (preg_match("!color\|\|!", $line, $colorm, PREG_OFFSET_CAPTURE))
    {
        $colorarr = $colorm[0];
        $colorind = $colorarr[1] + 7;
        preg_match("!\|!", $line, $colorem, PREG_OFFSET_CAPTURE, $colorind);
        $colorearr = $colorem[0];
        $coloreind = $colorearr[1];
        #isolate name of color by finding index of ||, adding 2, finding index of |, adding 1, and subtracting index of | from index of ||
        $colorname = substr($line, $colorind, $coloreind - $colorind);
        $p1 = substr($line, 0, $colorind - 14);
        preg_match("!\}!", $line, $endm, PREG_OFFSET_CAPTURE);
        $endarr = $endm[0];
        $endind = $endarr[1];
        $tohighlight = substr($line, $coloreind + 1, $endind - $coloreind - 1);
        $p2 = substr($line, $endind + 2, $length - $endind - 2);
        $line = "{$p1}<rfont style=\"background-color:{$colorname}\">{$tohighlight}</rfont>{$p2}";
        $length = strlen($line);
    }
    #If given line contains text to color
    else if (preg_match("!color\|!", $line, $colorm2, PREG_OFFSET_CAPTURE))
    {
        $colorarr = $colorm2[0];
        $colorind = $colorarr[1] + 6;
        preg_match("!\|!", $line, $colorem, PREG_OFFSET_CAPTURE, $colorind);
        $colorearr = $colorem[0];
        $coloreind = $colorearr[1];
        #isolate name of color by finding index of first |, adding 1, finding index of second |, adding 1, and subtracting the former from the latter
        $colorname = substr($line, $colorind, $coloreind - $colorind);
        $p1 = substr($line, 0, $colorind - 8);
        preg_match("!\}!", $line, $endm, PREG_OFFSET_CAPTURE);
        $endarr = $endm[0];
        $endind = $endarr[1];
        $tocolor = substr($line, $coloreind + 1, $endind - $coloreind - 1);
        $p2 = substr($line, $endind + 2, $length - $endind - 2);
        $line = "{$p1}<rfont style=\"color:{$colorname}\">{$tocolor}</rfont>{$p2}";
        $length = strlen($line);
    }
}
#Handles calls to headings
function heading($line, $length, &$prevline)
{
    $prevline = 0;
    $line = substr($line, 0, -1);
    $newlen = strlen($line);
    $length = $newlen;
    if ($line[$length - 2] === "=")
    {
        $length -= 1;
    }
    $headingnumber = 0;
    $newline = $line;
    #Counts number of = sign in text, goes to switch statement to decide which to use
    while (true)
    {
        if (($newline[0] === "=") && ($newline[$length - 1] === "="))
        {
            ++$headingnumber;
            $length -= 2;
            $newline = substr($newline, 1, $length);
        }
        else
        {
            break;
        }
    }
    if (($newline[0] === " ") && ($newline[$length - 1] === " "))
    {
        $line = substr($newline, 1, $length);
        #Headings 4-infinity are the exact same so I covered them in default
        switch ($headingnumber)
        {
        case 1:
            echo "<heading1> {$line} </heading1>";
            echo "<hr />";
            break;
        case 2:
            echo "<heading2> {$line} </heading2>";
            echo "<hr />";
            break;
        case 3:
            echo "<p> <heading3> {$line} </heading3> </p>";
            break;
        default:
            echo "<p> <heading4> {$line} </heading4> </p>";
            break;
        }
    }
}
#Function that handles indents
function indents($line, $length, &$prevline)
{
    #Variable that marks whether the previous line was plaintext without a <br> or </p> called
    if ($prevline === 1)
    {
        echo "<br>";
    }
    #Counts number of indents to use in loop
    while(true)
    {
        if ($line[0] == ":")
        {
            $length -= 1;
            $line = substr($line, 1, $length);
            echo "&nbsp&nbsp&nbsp";
        }
        else
        {
            break;
        }
    }
    #The text can contain a link, be colored or highlighted, or bold or italicized
    links($line,$length);
    colororhighlight($line,$length);
    boldanditalics($line,$length);
    echo "{$line}<br>";
    $prevline = 0;
}
#Function to handle blockquotes
function blockquote($line, $length, &$prevline)
{
    $pipes = preg_match_all("!\|!", $line);
    #If quote has an author listed
    if ($pipes > 1)
    {
        $authorindex = strpos($line, "author=") + 7;
        $textindex = strpos($line, "text=") + 5;
        $text = substr($line, $textindex, $authorindex - $textindex - 8);
        echo "<p>&nbsp&nbsp {$text} </p>";
        $endindex = strrpos($line, " ");
        $removeend = $length - $endindex;
        $author = substr($line, $authorindex, $length - $authorindex - $removeend);
        echo "&nbsp&nbsp&nbsp - {$author} <br>";
    }
    #If quote does not have an author listed
    else
    {
        $textindex = strpos($line, "text=") + 5;
        $endindex = strrpos($line, " ");
        $removeend = $length - $endindex;
        $text = substr($line, $textindex, $length - $textindex - $removeend);
        echo "<p>&nbsp&nbsp {$text} </p>";
    }
    $prevline = 0;
}
#Handles unordered lists
function unorderedlist(&$line, &$length, &$sourcefile, &$prevline)
{
    $prevline = 0;
    $newspaces = 0;
    $oldspaces = 0;
    $length = strlen($line);
    while (true)
    {
        #Counts number of * in line to see if it is a sub-item or not
        for ($i = 0; $i < $length; ++$i)
        {
            if ($line[$i] === "*")
            {
                #newspaces denotes number of * contained in line
                ++$newspaces;
            }
            else
            {
                break;
            }
        }
        $length -= $newspaces;
        $line = substr($line, $newspaces, $length);
        #If there no * in line, end list and break
        if ($newspaces === 0)
        {
            echo "</ul>";
            break;
        }
        #If there are more * than the previous line, create a new list as it is a sub-item
        else if ($newspaces > $oldspaces)
        {
            echo "<ul>";
            links($line,$length);
            colororhighlight($line,$length);
            boldanditalics($line,$length);
            echo "<li> {$line} </li>";
        }
        #If there are the same amount of * as the previous item, simply continue printing the list
        else if ($newspaces === $oldspaces)
        {
            links($line,$length);
            colororhighlight($line,$length);
            boldanditalics($line,$length);
            echo "<li> {$line} </li>";
        }
        #There are less * than the previous item, end the sublist of the previous item and print current
        else
        {
            $difference = $oldspaces - $newspaces;
            for ($i = 0; $i < $difference; ++$i)
            {
                echo "</ul>";
            }
            links($line,$length);
            colororhighlight($line,$length);
            boldanditalics($line,$length);
            echo "<li> {$line} </li>";
        }
        $oldspaces = $newspaces;
        $newspaces = 0;
        $line = fgets($sourcefile) or die ("<br>");
        $length = strlen($line);
        if ($length < 1)
        {
            break;
        }
    }
}
#Function handles ordered lists
function orderedlist(&$line, &$length, &$sourcefile, &$prevline)
{
    $prevline = 0;
    $newspaces = 0;
    $oldspaces = 0;
    $length = strlen($line);
    while (true)
    {
        #Counts number of # in line to see if it is a sub-item or not
        for ($i = 0; $i < $length; ++$i)
        {
            if ($line[$i] === "#")
            {
                #newspaces denotes number of # contained in line
                ++$newspaces;
            }
            else
            {
                break;
            }
        }
        $length -= $newspaces;
        $line = substr($line, $newspaces, $length);
        #If there no # in line, end list and end loop
        if ($newspaces === 0)
        {
            echo "</ol>";
            break;
        }
        #If there are more # than the previous line, create a new list as it is a sub-item
        else if ($newspaces > $oldspaces)
        {
            echo "<ol>";
            links($line,$length);
            colororhighlight($line,$length);
            boldanditalics($line,$length);
            echo "<li> {$line} </li>";
        }
        #If there are the same amount of # as the previous item, simply continue printing the list
        else if ($newspaces === $oldspaces)
        {
            links($line,$length);
            colororhighlight($line,$length);
            boldanditalics($line,$length);
            echo "<li> {$line} </li>";
        }
        #There are less # than the previous item, end the sublist of the previous item and print current
        else
        {
            $difference = $oldspaces - $newspaces;
            for ($i = 0; $i < $difference; ++$i)
            {
                echo "</ol>";
            }
            links($line,$length);
            colororhighlight($line,$length);
            boldanditalics($line,$length);
            echo "<li> {$line} </li>";
        }
        $oldspaces = $newspaces;
        $newspaces = 0;
        $line = fgets($sourcefile) or die ("<br>");
        $length = strlen($line);
        if ($length < 1)
        {
            break;
        }
    }
}
#Function handles description lists
function definitionlist($line, $length, &$prevline)
{
    #If the line containing the term also contains a definition
    if (preg_match("!:!", $line, $matches, PREG_OFFSET_CAPTURE))
    {
        $matcharr = $matches[0];
        $colonind = $matcharr[1];
        $term = substr($line, 2, $colonind - 3);
        $definition = substr($line, $colonind + 1, $length - $colonind - 1);
        echo "<term> {$term} </term> <br>";
        echo "&nbsp&nbsp&nbsp{$definition}";
    }
    #If the line only contains the term
    else
    {
        $term = substr($line, 2, $length - 2);
        echo "<term> {$term} </term> <br>";
    }
    $prevline = 0;
}
#Function handles images
function image($line, $length, &$prevline)
{
    $prevline = 0;
    #If there is a |, that means size is given
    if (preg_match("!\|!",$line, $pipem, PREG_OFFSET_CAPTURE))
    {
        #px=??? format
        if (preg_match("!=!",$line, $equalsm, PREG_OFFSET_CAPTURE))
        {
            $eqarr = $equalsm[0];
            $eqind = $eqarr[1];
            #isolate size by finding the = and subtracting it from the total length
            $size = substr($line, $eqind+1, $length - $eqind - 1);
            preg_match("!File!", $line, $namem, PREG_OFFSET_CAPTURE);
            $namearr = $namem[0];
            $nameind = $namearr[1] + 5;
            preg_match("!\.!", $line, $endm, PREG_OFFSET_CAPTURE, $nameind);
            $endarr = $endm[0];
            $endind = $endarr[1] + 4;
            #isolate name by finding File: location and subtracting from location of .png + 3 to get length
            $filename = substr($line, $nameind, $endind-$nameind);
            $p1 = substr($line, 0, $nameind - 7);
            echo "{$p1}";
            echo "<img src=\"{$filename}\" alt = \"Wikipedia\" width=\"{$size}\" height=\"{$size}\">";
        }
        #???px format
        else
        {
            preg_match("!px!", $line, $pxm, PREG_OFFSET_CAPTURE);
            $pxarr = $pxm[0];
            $pxind = $pxarr[1];
            $pipearr = $pipem[0];
            $pipeind = $pipearr[1];
            #isolate size by finding where px is located and subtracting known location of | in line
            $size = substr($line, $pipeind + 1, $pxind - $pipeind - 1);
            preg_match("!File!", $line, $namem, PREG_OFFSET_CAPTURE);
            $namearr = $namem[0];
            $nameind = $namearr[1] + 5;
            preg_match("!\.!", $line, $endm, PREG_OFFSET_CAPTURE, $nameind);
            $endarr = $endm[0];
            $endind = $endarr[1] + 4;
            #isolate name by finding File: location and subtracting from location of .png + 3 to get length
            $filename = substr($line, $nameind, $endind-$nameind);
            $p1 = substr($line, 0, $nameind - 7);
            echo "{$p1}";
            echo "<img src=\"{$filename}\" alt = \"Wikipedia\" width=\"{$size}\" height=\"{$size}\">";
        }
    }
    else
    {
        preg_match("!File!", $line, $namem, PREG_OFFSET_CAPTURE);
        $namearr = $namem[0];
        $nameind = $namearr[1] + 5;
        preg_match("!\.!", $line, $endm, PREG_OFFSET_CAPTURE, $nameind);
        $endarr = $endm[0];
        $endind = $endarr[1] + 4;
        #isolate name by finding File: location and subtracting from location of .png + 3 to get length
        $filename = substr($line, $nameind, $endind-$nameind);
        $p1 = substr($line, 0, $nameind - 7);
        echo "{$p1}";
        echo "{$filename}<br>";
        echo "<img src=\"{$filename}\" alt = \"Wikipedia\">";
    }
}
#main function with calls to many others
function proc_wikitext($filename)
    {
        $prevline = 0;
        $sourcefile = fopen($filename, "r") or die("Unable to open {$filename}\n");
        while($line = fgets($sourcefile))
        {
            $length = strlen($line);
            if($line[0] === "=")
            {
                heading($line, $length, $prevline);
            }
            else if (strpos($line, "----") === 0)
            {
                $prevline = 0;
                echo "<hr />";
            }
            else if ($line[0] === ":")
            {
                indents($line, $length, $prevline);
            }
            else if (preg_match("!{{Quote!", $line))
            {
                blockquote($line, $length, $prevline);
            }
            else if ($line[0] === "*")
            {
                unorderedlist($line, $length, $sourcefile, $prevline);
            }
            else if ($line[0] === "#")
            {
                orderedlist($line, $length, $sourcefile, $prevline);
            }
            else if ($line[0] === ";")
            {
                definitionlist($line, $length, $prevline);
            }
            else if (preg_match("!\[File!", $line))
            {
                image($line, $length, $prevline);
            }
            else if ($length > 2)
            {
                #handles plaintext
                $prevline = 1;
                boldanditalics($line,$length);
                links($line,$length);
                colororhighlight($line,$length);
                echo "{$line}";
            }
            else if ($length === 2 || $length === 0)
            {
                #Empty lines
                $prevline = 0;
                echo "<p></p>";
            }
        }
        fclose($sourcefile);
    }
    ?>
</html>