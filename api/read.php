<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    	<!-- Bootstrap -->
    	<link type="text/css" rel="stylesheet" href="../layout/css/bootstrap.min.css" />

  </head>
  <body>

   <div id="read-data"></div>

   <script type="text/javascript" src="../layout/js/jquery.min.js"></script>
   <script type="text/javascript" src="../layout/js/bootstrap.min.js"></script>

   <script>

        //get Api
        fetch("http://localhost/task/api/get_data.php").then(response=>response.json()).then(data=>{
          console.log(data[0].id);
          for(var i = 0;i<data.length;i++)
          {
                $("#read-data").html(JSON.stringify(data));

          }
       });


      //Post data
        var data = {Name:'hawaa', phone:'000'};
        console.log(data);
        fetch("http://localhost/task/api/post_data.php",{
           body:JSON.stringify(data),
           method:"post",

        }).then(response=>response.json()).then(api_data=>{;

          console.log(api_data);


      });
    </script>
  </body>
</html>
