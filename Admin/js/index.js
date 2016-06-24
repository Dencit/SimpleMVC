
function clickFunc(){

    $("#downTable").bind("click",function(){

        $staTime=$("#staTime").val();
        $endTime=$("#endTime").val();
        $toPage=$("#toPage").val();
        $type=$("#gift_type").val();

        if($staTime!=''&&$endTime!=''&&$toPage!=''){

            if($staTime==$endTime){
                alert('日期不能是同一天');
            }else{

                if($type==0){
                    $uri="./usrXsl.php?u=Admin888&ty='"+$type+"'&ds="+$staTime+"&de="+$endTime+"&p="+$toPage;
                }else{
                    $uri="./usrXsl.php?u=Admin888&ty="+$type+"&ds="+$staTime+"&de="+$endTime+"&p="+$toPage;
                }
                //console.log($uri);
                window.open($uri);
            }

        }else{
            alert("导出设置不全，请填写完整");
        }

    });
//
    $("#viewTable").bind("click",function(){

        $staTime=$("#staTime").val();
        $endTime=$("#endTime").val();
        $toPage=$("#toPage").val();
        $type=$("#gift_type").val();

        if($staTime!=''&&$endTime!=''&&$toPage!=''){

            if($staTime==$endTime){
                alert('日期不能是同一天');
            }else{

                if($type==0){
                    $uri="./usrXsl.php?u=Admin888&ty='"+$type+"'&ds="+$staTime+"&de="+$endTime+"&p="+$toPage+"&sta=view";
                }else{
                    $uri="./usrXsl.php?u=Admin888&ty="+$type+"&ds="+$staTime+"&de="+$endTime+"&p="+$toPage+"&sta=view";
                }

                //console.log($uri);
                window.open($uri);
            }

        }else{
            alert("导出设置不全，请填写完整");
        }

    });



//

    $("#refresh").bind("click",function(){

        var $array=new Array();
        var $pbtInp=$('.pbtInp');
        var $length=$pbtInp.length;

        $arr=pbtInp($pbtInp,$array,$length);
        $vsum=pbtInpSUM($pbtInp,$length);

        iObjCon($arr);
        iObjCon($vsum);

        if($vsum=='1000'){
            pAlert('1000');
           __post({"page":'index',"state":'pbtRefresh',"postData":$arr},$indexUrl);

        }else{
            pAlert($vsum);
            alert("当前概率总和是"+$vsum+"\n不等于1000,请调整！");
        }

    });


}
//
function pbtInpSUM ($pbtInp,$length){
    var $valueSum='';
    for(var i= 0;i<$length;i++){
        var $val =$pbtInp.eq(i).val();
        $valueSum = Number($valueSum);
        $valueSum += Number($val);
    }
    return $valueSum;
}

function pbtInp ($pbtInp,$array,$length){
    for(var i= 0;i<$length;i++){
        $array[i]=$pbtInp.eq(i).val();
    }
    return $array;
}
