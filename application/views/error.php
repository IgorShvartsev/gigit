<!DOCTYPE HTML>
<html lang="en">
<head>
<title>Page Error - <?php echo $type;?></title>                                                  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    *{
        padding:0;
        margin:0;
    }
    body{
        font-family: Helvetica,Arial,sans-serif;
        font-size:12px;
        background:#dedede;
    }
    a:link,
    a:visited
    {
        color: #0398CA;
    }
    h1{
        font-size:24px;  
    }
    h3{
        font-size:18px;
    }

    div#crash
    { 
        width:  600px;
        margin:20% auto;
        padding:10px;
        background: #fff;
        border: 2px solid #87001d;
        position:relative;
        text-align: center;
    }
    div#crash .erimg{
        position:absolute;
        top:-90px;
        left:235px;
    }
    div#crash .info
    {
        color: #FFFFFF;
        background-image: url(<?=base_url('assets/images/default/bg_error.jpg');?>);
        height: 150px;
        border: 2px solid #444444;
        overflow: hidden;
        text-align: center;
        padding-top:80px;
    }
    div#more-information
    {
        background-image: url(<?=base_url('assets/images/default/bg_error_bottom.gif');?>);
        height: 100%;
        margin-top:60px;
        padding-top:10px;
    }
    .shdw-5{box-shadow: 5px 5px 30px rgba(0,0,0,0.5); -moz-box-shadow: 5px 5px 30px rgba(0,0,0,0.5); -webkit-box-shadow: 5px 5px 30px rgba(0,0,0,0.5);}
</style>  
</head>
<body class="body">
    <div id="crash" class="shdw-5">
    <img class="erimg" src="<?=base_url('assets/images/default/' . $type . '.png');?>" alt="error <?php echo $type;?>" /> 
    <div class="info">
        <h3><?=$description;?></h3>
        <div id="more-information">
            <p>
               <a href="<?=base_url();?>">Return to the home page</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>