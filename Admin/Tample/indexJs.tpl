<link rel='stylesheet' type='text/css' href='./plug-in/css/bootstrap-datetimepicker.min.css'/>
<script src='./plug-in/js/bootstrap-datetimepicker.min.js'></script>
<script src='./plug-in/js/locales/bootstrap-datetimepicker.zh-CN.js'></script>

<script type='text/javascript'>
  $('.form_date').datetimepicker({
    language:  'zh-CN',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0,
    pickerPosition: "top-left"
  });
</script>

<script src="./js/common.js"></script>
<script src="./js/jsonAct.js"></script>
<script src="./js/index.js"></script>

<script>


  $(document).ready(function(){

    __get({"page":'index',"state":'giftCount'},$indexUrl);
    __get({"page":'index',"state":'pbtGet'},$indexUrl);
    clickFunc();

  });


  $(window).load(function(){



  });



</script>