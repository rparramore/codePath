<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<html>
    <head>
        <meta charset="UTF-8">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title></title>
    </head>
    <body>
        <div class="container">
            <div cass="row">
                <br>
                <br>
            </div>
            <div class="row">
            
                <div class="col-md-4 col-md-offset-4">
                    <form id="form" data-toggle="validator" role="form">
                        <!-- sub total -->
                        <div class="form-group has-feedback">
                            <div class="input-group">
                                <span class="input-group-addon">Bill subtotal $</span>
                                <input id="subtotalInput" type="number" step="any" min="0" class="form-control" required>
                            </div>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>

                        <!-- tip percentage -->
                        <div class="form-group center-block">
                            <div class="btn-group" data-toggle="buttons">
                                <?php 
                                    for($i = 10; $i <= 20; $i+=5) {
                                        echo "<label class=\"btn btn-primary active\">";
                                        echo "<input type=\"radio\" name=\"tip\" id=\"tip".$i."\" value=\"".$i."\" autocomplete=\"off\">".$i."%";
                                        echo "</label>";
                                    }
                                ?>
                            </div>
                        </div>
                                                
                        <!-- input for number of people -->
                        <div class="form-group has-feedback center">
                            <div class="input-group">
                                <span class="input-group-addon">Number of people</span>
                                <input id="numPeopleInput" type="number" min="1" placeholder="1" class="form-control" required>
                            </div>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>

                        <div class="form-group center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                    
                    
                    <div id="resultOutput" class="panel panel-primary" hidden>
                        <div class="panel-heading">
                            Cost Results
                        </div>
                        <div class="panel-body">                            
                            <!-- Tip -->
                            <label id="tipOutput"></label>
                            <br>
                            <!-- Total -->
                            <label id="totalOutput"></label>
                            <br>
                            <!-- Split Tip -->
                            <label id="splitTipOutput"></label>
                            <br>
                            <!-- Split Total -->
                            <label id="splitTotalOutput"></label>
                        </div>
                    </div>
                </div>
                
            </div>            
        </div>
        
    </body>
</html>

<script>
    var mm;
    
    $(document).ready(function() {
        $('#tip10').attr('required', true);
    });
    
    $('#form').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        calculateCost();
    });
    
    $('#tipCustomInput').focus(function() {
        $('#customTip').attr('checked',true);
    });
    
    // ajax call
    var calculateCost = function() {
        //var subtotal = parseFloat($('#subtotalInput').val());
        var tipChoice = $('input[name=tip]:checked');
        var tip = tipChoice.val();
        var subtotal = parseFloat($('#subtotalInput').val());
        var numPeople = parseInt($('#numPeopleInput').val());
        // check if tip choice
        if(tipChoice.attr('id') === "customTip") {
            tip = $('#tipCustomInput').val();
        }
        console.log('numPeople: ' + numPeople + '; subTotal: ' + subtotal + '; tip: ' + tip);
        $.ajax({
            url: 'actions.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'splitTotal',
                subtotal: subtotal,
                tip: tip,
                numPeople: numPeople
            },
            success: function(response) {
                // load everything to the DOM
                $('#tipOutput').html('Tip: $' + response['tipAmount']);
                $('#totalOutput').html('Total: $' + response['total']);
                
                if(numPeople !== 1) {
                    $('#splitTipOutput').html('Tip each: $' + response['tipAmountSplit']);
                    $('#splitTotalOutput').html('Total each: $' + response['totalSplit']);
                } else {
                    $('#splitTipOutput').html('');
                    $('#splitTotalOutput').html('');
                }
                $('#resultOutput').show()
            },
            error: function(response) {
                alert('ERROR:' + JSON.stringify(response));
            }
        });
    };
</script>