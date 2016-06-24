<?php
/** Created by [SOMA]User:陈鸿扬 Date: 2016/5/17 Time: 15:04 **/


function tableTamp($data,$thead='',$caption=''){
echo "
<div class='container'>
    <div class='row'>
        <div class='bs-example'>
            <h4><b class='red'>".$thead."&nbsp;&nbsp;&nbsp;&nbsp;</b><caption>".$caption."</caption></h4>
            <hr/>
            <table class='table table-striped clearfix'>
                <thead>
                    <tr class='active'><th id='uid'>用户id</th><th id='nick'>用户昵称</th><th id='logtime'>参与时间</th><th id='gift'>奖品</th><th id='mobile'>手机</th></tr>
                </thead>
                <tbody>
            ";
                foreach($data as $k=>$v){
                    $uid=$data[$k]->uid;
                    $nick=$data[$k]->nick;
                    $gift=$data[$k]->gift;
                    switch($gift){
                        case 0 :$gift="2元现金红包";break;
                        case 2 :$gift="长隆家庭乐票";break;
                        case 3 :$gift="星巴克电子咖啡券";break;
                        case 4 :$gift="10元话费";break;
                        case 5 :$gift="院线通电影票";break;
                    }
                    $time=$data[$k]->time;//$users_get


                    if(isset($data[$k]->mobile)){$mobile=$data[$k]->mobile;}
                    else{ $mobile=''; }

                    echo'<tr><td>'.$uid.'</td><td>'.$nick.'</td><td>'.date('Y-m-d h:s:i',$time).'</td><td>'.$gift.'</td><td>'.$mobile.'</td></tr>';
                }
        echo "
                </tbody>
            </table>
        <hr/>
        </div>
    </div><!--//row-->
</div><!--//container-->
";
}

