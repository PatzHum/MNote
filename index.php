<?php
    require_once "back/db.php";
    $data = "";
    if (isset($_GET["r"])){
        $data = get_instance_raw($_GET["r"]);
    }
?>
<html>
    <head>
        <!-- Bootstrap Includes -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!--Roboto-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300|Roboto+Condensed" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <script src="js/index.js"></script>
    <script src="https://www.desmos.com/api/v0.7/calculator.js?apiKey=dcb31709b452b1cf9dc26972add0fda6"></script> 
    <script src='https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML'></script>
    
    <title>Note</title> 
    </head>
    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
    </script>

    <body>  
        <div class="container-fluid">
            <div class="col-md-6">
                <div class="row">
                    <button class="glyphicon glyphicon-play fab fab1 fab-green" onclick="compile()"></button> 
                    <a href="view.php?r=<?php if (isset($_GET["r"])){echo $_GET["r"];}?>" class="glyphicon glyphicon-new-window fab fab3 fab-blue"></a>

                    <a href="index.php" class="glyphicon glyphicon-file fab fab4 fab-blue"></a>
                    
                    <form method="post" style="margin:0;" action="back/post.php">
                    <?php
                        if (isset($_GET["r"])){
                    ?>
                    <input type="hidden" name="rid" value="<?php echo $_GET["r"];?>"/>
                    <?php
                        }
                    ?>
                    <button id="fab-save" type="submit" class="glyphicon glyphicon-floppy-disk fab fab2 fab-green"></button>
                    <textarea name="raw" onkeyup="textChanged()" id="raw_in" class="fill">
<?php
if ($data == ""){    
?>(title){(b){Note}}
<em>A note-taking tool using the powerful tools of Desmos, LaTeX and HTML, all in one place.</em>
Last updated: 11/15/2016
<i>By: Patrick Hum</i>
(graph){f(x)=\frac{1}{\sqrt{x}}}
Write some $m^at_h$ inline.
Or an <b>important</b> equations like this:
$$y=mx+b$$
$$\sum_{i=1}^{n}x^*_i$$
$$\int_{0}^{x^2}f(x)dx$$
How about fractions?
$$\frac{d}{dx} x^2+1 = 2x$$
How about lining things up?
$$
\begin{align}
y&=mx+b\\
(3)&=(2)(3)+b\\
b&=-3
\end{align}
$$
(b){Listing Things 101}
(begin){list}
You can even write math in here!
$x^2+1$
$\int{x^3dx}$
(end){list}
More new things to come!
<?php
}
else{
    echo $data;
}
?></textarea>
                </form>
                </div>
            </div>
            <div class="col-md-6 no-pad">
                <div id="out">
                    &nbsp;
                </div>
            </div>  
        </div>
    </body>
    <script>
        compile();
    </script>

    <script src="eqn/eqn.js"></script>
    <script>
        

    </script>
</html>
