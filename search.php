<html>
<body>
<?php
function displaypage($page, $word)
{
    #Had to hardcode this part of index.php as there is no extension in the domain for it
    if (preg_match("#index.php#", $page))
    {
        $str = file_get_contents("http://masterson315.infinityfreeapp.com");
        #regular expression to gather all instances of $word outside html tags, as not to mess up formatting
        preg_match_all("#>[^<>]*{$word}[^>]*<#", $str, $matches);
        $matcharr = $matches[0];
        #Loops through all matches, replacing them with highlighted keyword 
        foreach($matcharr as $text)
        {
            #Arbitrary value used to ensure current text is not in CSS
            if (preg_match("#;#", $text) == 0)
            {
                preg_match("#{$word}#", $text, $indexarr, PREG_OFFSET_CAPTURE);
                $index = $indexarr[0][1];
                #Three parts to string: before keyword, keyword, after keyword. Does this to ensure the replacment does not mess anything up
                $before = substr($text, 0, $index);
                $after = substr($text, $index + strlen($word), strlen($text) - $index);
                $replacement = "{$before}<mark>{$word}</mark>{$after}";
                $text = preg_quote($text);
                #replaces matching text with same text but with a highlighted keyword
                $str = preg_replace("#{$text}#", $replacement, $str, 1);
            }
        }
        echo $str;
    }
    else
    {
        $str = file_get_contents("http://masterson315.infinityfreeapp.com/{$page}");
        preg_match_all("#>[^<>]*{$word}[^>]*<#", $str, $matches);
        $matcharr = $matches[0];
        foreach($matcharr as $text)
        {
            if (preg_match("#;#", $text) == 0)
            {
                preg_match("#{$word}#", $text, $indexarr, PREG_OFFSET_CAPTURE);
                $index = $indexarr[0][1];
                $before = substr($text, 0, $index);
                $after = substr($text, $index + strlen($word), strlen($text) - $index);
                $replacement = "{$before}<mark>{$word}</mark>{$after}";
                $text = preg_quote($text);
                $str = preg_replace("#{$text}#", $replacement, $str, 1);
            }
        }
        echo $str;
    }
}
function receiveword() 
{
    #Gets name of page and keyword from url
    $page = $_GET["page"];
    $word = $_GET["keyword"];
    echo "<a href=\"http://masterson315.infinityfreeapp.com\">Click here to return to main page!</a><br>";
    echo "<form action=\"search.php\" method=\"post\">";
    echo "<label for=\"search\">Enter keyword to search:</label><br>";
    echo "<input type=\"text\" name=\"search\"><br>";
    echo "<input type=\"submit\">";
    echo "</form>";
    #If a search has been conducted and a link has been clicked
    if ($page !== NULL && $word !== NULL)
    {
        displaypage($page, $word);
    }
    else
    {
        search($_POST["search"]);
    }
}
function search($keyword)
{
    if ($keyword !== NULL)
    {
        #Retrieves html sourcecode of every page and checks if keyword appears in it. If it does, displays the link to the user
        $index = file_get_contents("http://masterson315.infinityfreeapp.com");
        $gallery = file_get_contents("http://masterson315.infinityfreeapp.com/gallery.php");
        $blog = file_get_contents("http://masterson315.infinityfreeapp.com/blog.php");
        $resources = file_get_contents("http://masterson315.infinityfreeapp.com/resources.php");
        $tips = file_get_contents("http://masterson315.infinityfreeapp.com/tips.php");
        #creating the fake link
        $fakeword = preg_replace("# #", "%02", $keyword);
        if (preg_match("#{$keyword}#", $index) || preg_match("#{$keyword}#", $gallery))
        {
            echo "Pages containing the text {$keyword}:<br>";
        }
        if (preg_match("#{$keyword}#", $index))
        {
            echo "<a href=\"search.php?page=index.php&keyword={$keyword}\">http://masterson315.infinityfreeapp.com?{$fakeword}</a>";
            echo "<br>";
        }
        if (preg_match("#{$keyword}#", $gallery))
        {
            echo "<a href=\"search.php?page=gallery.php&keyword={$keyword}\">http://masterson315.infinityfreeapp.com/gallery.php?{$fakeword}</a>";
            echo "<br>";
        }
        if (preg_match("#{$keyword}#", $blog))
        {
            echo "<a href=\"search.php?page=blog.php&keyword={$keyword}\">http://masterson315.infinityfreeapp.com/blog.php?{$fakeword}</a>";
            echo "<br>";
        }
        if (preg_match("#{$keyword}#", $resources))
        {
            echo "<a href=\"search.php?page=resources.php&keyword={$keyword}\">http://masterson315.infinityfreeapp.com/resources.php?{$fakeword}</a>";
            echo "<br>";
        }
        if (preg_match("#{$keyword}#", $tips))
        {
            echo "<a href=\"search.php?page=tips.php&keyword={$keyword}\">http://masterson315.infinityfreeapp.com/tips.php?{$fakeword}</a>";
            echo "<br>";
        }
    }
}
//search.php - contains search functions
receiveword();
?>
</body>
</html>