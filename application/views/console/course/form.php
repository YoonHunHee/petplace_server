<div class="row">
    <div class="col-md-12 col-sm-6 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Course Form</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <form class="form-horizontal form-label-left" novalidate method="post" action="/console/place/action">
                <input type="hidden" name="mode" value="<?=(isset($row)) ? 'edit' : 'regist'?>"/>
                <input type="hidden" name="id" value="<?=(isset($row)) ? $row->id : ''?>"/>
                    
                    <div id="map" style="height:200px;" class="item form-group">
                        Map Loading...
                    </div>
                    
                    <div class="item form-group">
                        <a href="" class="btn btn-info btn-xs">시작 포인트</a>
                        <a href="" class="btn btn-primary btn-xs">종료 포인트</a>
                        <a href="" class="btn btn-danger btn-xs">경유 포인트</a>
                    </div>

                    <script type="text/javascript" src="http://apis.daum.net/maps/maps3.js?apikey=8fa982632b10ed739e3b21f74732b435"></script>
                    <?php if(isset($row) && !empty($row->lat)) {?>
                    <script>
                        var container = document.getElementById('map');
                        var options = {
                            center: new daum.maps.LatLng(<?php echo $row->lat?>, <?php echo $row->lng?>),
                            level: 2
                        };

                        var map = new daum.maps.Map(container, options);
                        map.setZoomable(false);
                        map.setDraggable(false);

                        // 마커가 표시될 위치입니다 
                        var markerPosition  = new daum.maps.LatLng(<?php echo $row->lat?>, <?php echo $row->lng?>); 

                        // 마커를 생성합니다
                        var marker = new daum.maps.Marker({
                            position: markerPosition
                        });

                        // 마커가 지도 위에 표시되도록 설정합니다
                        marker.setMap(map);
                    </script>
                    <?php } else { ?>
                    <script>
                        var container = document.getElementById('map');
                        var options = {
                            center: new daum.maps.LatLng(37.4995504, 127.0283495),
                            level: 2
                        };

                        var map = new daum.maps.Map(container, options);
                    </script>
                    <?php } ?>

                    <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="title" name="title" required="required" class="form-control col-md-7 col-xs-12" value="<?=(isset($row)) ? $row->title : ''?>"/>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="desc" class="control-label col-md-3">간략설명</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea name="desc" id="desc" class="form-control col-md-7 col-xs-12"><?=(isset($row)) ? $row->desc : ''?></textarea>
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-5">
                            <a href="javascript:history.back(-1);" class="btn btn-primary">Cancel</a>
                            <button id="send" type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div
                </form>
            </div>
        </div>
    </div>
</div>


<!-- form validation -->
<script src="/assets/libs/validator/validator.js"></script>
<script>
    $(".marker-btn").on("click", function () {
        var _name = 'addr';
        var _obj = this;
        var _txt = $(_obj).text();
        if($(this).hasClass('road_addr')) {
            _name = 'road_addr';    
        }

        if($('#' + _name).val() == '')return alert('주소를 입력하세요');

        $.ajax({
            type: 'POST',
            url: '/console/common/get_latlng',
            data: { addr : $('#' + _name).val() },
            dataType: 'json',
            beforeSend: function() {
                $(_obj).text('loading...');
            },
            success: function(result) {
                $(_obj).text(_txt);
                var container = document.getElementById('map');
                var options = {
                    center: new daum.maps.LatLng(result['lat'], result['lng']),
                    level: 2
                };

                var map = new daum.maps.Map(container, options);
            
                // 마커가 표시될 위치입니다 
                var markerPosition  = new daum.maps.LatLng(result['lat'], result['lng']); 

                // 마커를 생성합니다
                var marker = new daum.maps.Marker({
                    position: markerPosition
                });

                // 마커가 지도 위에 표시되도록 설정합니다
                marker.setMap(map);
            }
        });
    });
    
    validator.message['date'] = 'not a real date';

    // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);
        
    // bind the validation to the form submit event
    //$('#send').click('submit');//.prop('disabled', true);

    $('form').submit(function (e) {
        e.preventDefault();
        var submit = true;
        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
            submit = false;
        }

        if (submit)
            this.submit();
        return false;
    });

    function markerView(type) {
        if($('#' + type).val() == '')return alert('주소를 입력하세요');

        $.ajax({
            type: 'POST',
            url: '/console/common/get_latlng',
            data: { addr : $('#' + type).val() },
            dataType: 'json',
            beforeSend: function() {

            },
            success: function(result) {
                var container = document.getElementById('map');
                var options = {
                    center: new daum.maps.LatLng(result['lat'], result['lng']),
                    level: 2
                };

                var map = new daum.maps.Map(container, options);
            
                // 마커가 표시될 위치입니다 
                var markerPosition  = new daum.maps.LatLng(result['lat'], result['lng']); 

                // 마커를 생성합니다
                var marker = new daum.maps.Marker({
                    position: markerPosition
                });

                // 마커가 지도 위에 표시되도록 설정합니다
                marker.setMap(map);
            }
        });
    }
</script>