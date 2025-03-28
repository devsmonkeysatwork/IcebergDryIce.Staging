@extends('website.layouts.main')

@section('content')
    <style>
        th {
            background-color: #006;
            color: #FFF;
            font-weight: bold;
        }
    </style>

    <body onload="document.getElementById('weight').focus();">

    <br>

    <center>

            <table width='90%'>
                <tr>
                    <td class='forsale_menu selected'>ORDER</td>
                    <td class='forsale_menu'><a href='{{ url('/location') }}'>Location</a></td>
                    <td class='forsale_menu'><a href='{{ url('/review') }}'>Review</a></td>
                </tr>
            </table>

        <br>



        <form action='' method="post" id='order_form'>
            @csrf
            <table>
                <tr>
                    <th colspan='4'>ORDER</th>
                </tr>
                <tr>
                    <td>Dry Ice (lbs.)</td>
                    <td>
                        <input class='textbox_data' style='text-align: center;' size='10' type='text' name='weight' id='weight' value='0' onblur="calc_weight();">
                    </td>
                    <td width='130px' align='right'>
                        $ <input class='textbox_data' style='text-align: right;' readonly size='7' type='text' name='weight_cost' id='weight_cost' value='0.00'>
                    </td>
                    <td align='right' width='300px'>
                        <div id='weight_notes'><i>$1.95 / lbs., minimum 10 lbs.</i></div>
                    </td>
                </tr>
                <tr>
                    <td>Styrofoam Box</td>
                    <td>
                        <input style='text-align: center;' class='textbox_data' size='10' type='text' name='box' id='box' value='0' onblur="calc_boxes();">
                    </td>
                    <td align='right'>
                        $ <input class='textbox_data' style='text-align: right;' readonly size='7' type='text' name='box_cost' id='box_cost' value='0.00'>
                    </td>
                    <td align='right'>
                        <div id='box_notes'><i>$30 / empty box, holds up to 50 lbs.</i></div>
                    </td>
                </tr>
            </table>
            <br>
            <br>
                <input style='width: 100px; text-align: center; color: #090; border: outset 2px #090;' class='is_button' name='next' value='NEXT' onclick='next_page();'>

        </form>

    </center>

    <script>
        var cost_per_pound = 1.95;
        var cost_per_box = 30;
        var minimum_pounds = 10;

        function next_page() {
            if (calc_weight() && calc_boxes()) {
                if (document.getElementById('weight').value == 0 && document.getElementById('box').value == 0)
                    set_msg('weight_notes', 'Please add something to your order', true);
                else
                    document.getElementById('order_form').submit();
            }
            return false;
        }

        function calc_weight() {
            set_msg('weight_notes', '<center><b>VALID</b></center>', false);
            var weight = document.getElementById('weight').value;
            if (isNaN(weight) || weight < minimum_pounds) {
                set_msg('weight_notes', 'Minimum order weight is 10 pounds.', true);
                return false;
            }
            document.getElementById('weight_cost').value = (weight * cost_per_pound).toFixed(2);
            return true;
        }

        function calc_boxes() {
            set_msg('box_notes', '<center><b>VALID</b></center>', false);
            var boxes = document.getElementById('box').value;
            if (isNaN(boxes)) {
                set_msg('box_notes', 'Boxes must be a number.', true);
                return false;
            }
            document.getElementById('box_cost').value = (boxes * cost_per_box).toFixed(2);
            return true;
        }
    </script>
    </body>
@endsection
