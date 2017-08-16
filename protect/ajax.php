<!DOCTYPE html>
<html>
<head>
    <!------------------------------- head_default ------------------------------->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/img/Logo_database.svg" />
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Hammock Helicopter</title>

    <!------------------------- CSS ------------------------->
    <link href="/css/bootstrap.min.css" rel="stylesheet">                                <!-- Bootstrap Core CSS -->
    <link href="/css/common_stylesheet.css" rel="stylesheet">                             <!-- Custom CSS -->
    <link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"> <!-- Custom Fonts -->

    <!------------------------- Scripts ------------------------->
    <script src="/js/jquery.js"></script>       <!-- jQuery -->
    <script src="/js/bootstrap.min.js"></script> <!-- Bootstrap Core JavaScript -->
    <script src="/js/functions.js"></script>
</head>

<body>
    <p>On va tester du jQuery</p>
    <input type="text" id="FilterInput" placeholder="Search.." title="Type in a name"/>
    <button onclick="this.disabled = true;myFunction(this);">GO</button>
    <div id="Content" style="margin-top: 15px;">
        Mon Content
    </div>
</body>

<script>
    function myFunction(button) {

        var pn = document.getElementById('FilterInput').value;
        pn = pn.toUpperCase().replace(' ','').replace('-','');
        var V=1;


        var callback = function(content) {

            var part_numbers = [];
            var mcrd = content.getElementsByTagName('html')[0].querySelector('[name=mcrd]');

            if(mcrd === null && V < 2) {
                V++;
                xmlhttpGet(<?php echo '\'http://'.$_SERVER['HTTP_HOST'].'/protect/ajax-server.php?V=\'';?> + V + '&PN=' + pn, callback);

            } else if(mcrd !== null) {

                var tr_list = mcrd.nextElementSibling.getElementsByTagName('table')[2].getElementsByTagName('tr');
                var div = document.getElementById('Content');
                var i, new_pn;

                console.log('tr_list got');
                for(i=3; i<tr_list.length; i++) {

                    new_pn = tr_list[i].getElementsByTagName('td')[0].innerHTML;
                    if(part_numbers.indexOf(new_pn) === -1) {
                        part_numbers.push(new_pn);
                    }
                }

                div.innerHTML = pn + ' : alt PN list<br/>';

                for(i=0; i<part_numbers.length; i++) {
                    div.innerHTML += '- PN' + i + ': ' + part_numbers[i] + '<br/>';
                }

                button.disabled = false;
            } else {

                document.getElementById('Content').innerHTML = 'N/A';
                button.disabled = false;
            }
        };

        try {
            document.getElementById('Content').innerHTML = '<img src=\'/img/wait_rot.gif\'/>';
            xmlhttpGet(<?php echo '\'http://'.$_SERVER['HTTP_HOST'].'/protect/ajax-server.php?V=1&PN=\'';?> + pn, callback);

        } catch (error) {
            console.log('error');
            button.disabled = false;
            document.getElementById('Content').innerHTML = 'N/A';
        }

    }
</script>

</html>