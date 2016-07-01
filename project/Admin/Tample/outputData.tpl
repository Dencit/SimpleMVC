<!-- Created by [SOMA]User: soma Date: 2016/6/3 Time: 17:25 -->

<div class='container'>
    <div class='row'>
        <div class='bs-example'>
            <h4><b class='red'>导出数据<div class='pad'></div></b><caption>输入：奖品类型、开始日期 ( 当日0时 )、结束日期 ( 当日0时 )、数据翻页 ( 65530条/页 )</caption></h4>
            <hr/>
            <div class='input-group col-md-1 ' >
                <button id='downTable'  class='btn btn-success'>导出Excl表单</button>
            </div>

            <div class='input-group col-md-1 ' >
                <button id='viewTable'  class='btn btn-success'>预览数据</button>
            </div>

            <div class='input-group col-md-1' >
                <input class='form-control' id='toPage'  class='form-control' type='text' style='width: 80px' placeholder='翻页' value='1' style='width: 80px'>
            </div>
            <div class='input-group date form_date col-md-3' data-date='' data-date-format='yyyy-mm-dd' data-link-field='endTime' data-link-format='yyyy-mm-dd'>
                <input class='form-control' placeholder='结束日期' size='16' type='text' value='' readonly>
                <span class='input-group-addon'><span class='glyphicon glyphicon-remove'></span></span>
                <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
            </div>
            <input type='hidden' id='endTime' value='' />
            <div class='input-group date form_date col-md-3' data-date='' data-date-format='yyyy-mm-dd' data-link-field='staTime' data-link-format='yyyy-mm-dd'>
                <input class='form-control' placeholder='开始日期' size='16' type='text' value='' readonly>
                <span class='input-group-addon'><span class='glyphicon glyphicon-remove'></span></span>
                <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span>
            </div>
            <input type='hidden' id='staTime' value='' />

            <div class='input-group col-md-1 ' >

                <select id='gift_type' class='form-control' style='width: 150px'>
                    <option value=0>2元现金红包</option>
                    <option value=2>长隆家庭乐票</option>
                    <option value=3>星巴克电子咖啡券</option>
                    <option value=4>10元话费</option>
                    <option value=5>院线通电影票</option>
                </select>
            </div>

            <h4 style='clear:both;margin-top: 80px;'><hr/></h4>

        </div>

    </div><!--//row-->
</div><!--//container-->

